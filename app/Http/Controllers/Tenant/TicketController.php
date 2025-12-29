<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use App\Exports\TicketsExport;
use App\Exports\TicketLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    private function getTicketsQuery($tenant, $tab, $search, $dateFrom, $dateTo)
    {
         // Refactored to query Ticket model directly
        $query = Ticket::whereHas('order', function ($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)
              ->where('status', 'paid');
        })->with(['order', 'ticketType.event']);

        // Search Scope
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('unique_code', 'like', "%{$search}%")
                  ->orWhereHas('order', function($oq) use ($search) {
                    $oq->where('reference_no', 'like', "%{$search}%")
                       ->orWhere('customer_name', 'like', "%{$search}%")
                       ->orWhere('customer_email', 'like', "%{$search}%");
                })->orWhereHas('ticketType', function($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%")
                       ->orWhereHas('event', function($eq) use ($search) {
                            $eq->where('name', 'like', "%{$search}%");
                       });
                });
            });
        }

        // Date Scope
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
             $query->whereDate('created_at', '<=', $dateTo);
        }

        // Tab Logic
        switch ($tab) {
            case 'validated':
                $query->whereNotNull('validated_at');
                break;
            case 'archive':
                 $query->where(function($q) {
                    $q->where('created_at', '<', now()->subYear())
                      ->orWhereHas('ticketType.event', function($eq) {
                          $eq->where('end_date', '<', now());
                      });
                 });
                break;
            case 'active':
            default:
                if ($tab !== 'logs') { // Only apply active filter if not logs, though usually logs is handled separately
                     $query->whereNull('validated_at')
                      ->where('created_at', '>=', now()->subYear())
                      ->whereHas('ticketType.event', function($eq) {
                          $eq->where('end_date', '>=', now());
                      });
                }
                break;
        }
        
        return $query;
    }
    
     private function getLogsQuery($tenant, $search, $dateFrom, $dateTo)
    {
        $query = TicketLog::whereHas('ticket.order', function($q) use ($tenant) {
                 $q->where('tenant_id', $tenant->id);
            })->with(['ticket.ticketType', 'user', 'ticket.order']);

        if ($search) {
             $query->where(function($q) use ($search) {
                $q->whereHas('ticket', function($tq) use ($search) {
                     $tq->where('unique_code', 'like', "%{$search}%")
                       ->orWhereHas('order', function($oq) use ($search) {
                            $oq->where('reference_no', 'like', "%{$search}%");
                       });
                })->orWhereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
             $query->whereDate('created_at', '<=', $dateTo);
        }
        
        return $query;
    }


    public function export(Request $request)
    {
        $tenant = auth()->user()->tenant;
        $tab = $request->get('tab', 'active');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if ($tab === 'logs') {
            $query = $this->getLogsQuery($tenant, $search, $dateFrom, $dateTo);
            $logs = $query->get();
            
            if ($request->get('format') === 'excel') {
                return Excel::download(new TicketLogsExport($logs), 'logs.xlsx');
            }
             // CSV fallback for logs? or implement CSV manually like tickets?
        } else {
            $query = $this->getTicketsQuery($tenant, $tab, $search, $dateFrom, $dateTo);
            $tickets = $query->get();
            
            if ($request->get('format') === 'excel') {
                return Excel::download(new TicketsExport($tickets), 'tickets.xlsx');
            }
        }
        
        // Manual CSV Export (Fallback or if format not excel)
        // Note: For Logs CSV logic is missing in previous implementation, assume user prefers Excel now for everything.
        // But let's keep the existing CSV for tickets if requested.
        
        // Let's force Excel for simplicity if format is not explicitly CSV, OR reimplement CSV using the collection.
        // Actually, we can use Excel::download for CSV too by passing \Maatwebsite\Excel\Excel::CSV
        
        if ($tab === 'logs') {
             return Excel::download(new TicketLogsExport($logs), 'logs.csv', \Maatwebsite\Excel\Excel::CSV);
        } else {
             return Excel::download(new TicketsExport($tickets), 'tickets.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }

    public function index(Request $request)
    {
        $tenant = auth()->user()->tenant;
        $tab = $request->get('tab', 'active');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $tickets = null;
        $logs = null;

        if ($tab === 'logs') {
            $logs = $this->getLogsQuery($tenant, $search, $dateFrom, $dateTo)
                         ->orderBy('created_at', 'desc')
                         ->paginate(20)
                         ->withQueryString();
        } else {
            $tickets = $this->getTicketsQuery($tenant, $tab, $search, $dateFrom, $dateTo)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->withQueryString();
        }

        return view('tenant.tickets.index', compact('tickets', 'logs', 'tab', 'search', 'dateFrom', 'dateTo'));
    }

    public function validateTicket(Ticket $ticket)
    {
        if ($ticket->order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($ticket->validated_at) {
            return back()->with('error', __('Ticket already validated.'));
        }

        $ticket->update(['validated_at' => now()]);

        // Log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'validated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', __('Ticket validated successfully.'));
    }

    public function unvalidateTicket(Ticket $ticket)
    {
         if ($ticket->order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if (!$ticket->validated_at) {
             return back()->with('error', __('Ticket is not validated.'));
        }

        $ticket->update(['validated_at' => null]);

        // Log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'unvalidated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

         return back()->with('success', __('Ticket validation reverted.'));
    }

    public function destroy(Ticket $ticket)
    {
         if ($ticket->order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        
        $ticket->ticketType->decrement('sold'); // Decrement by 1 since it's a single ticket
        
        $ticket->delete();

        return back()->with('success', __('Ticket cancelled.'));
    }
}
