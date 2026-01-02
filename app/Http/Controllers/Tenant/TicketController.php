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
                if ($tab !== 'logs') { // Only apply active filter if not logs
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
             return Excel::download(new TicketLogsExport($logs), 'logs.csv', \Maatwebsite\Excel\Excel::CSV);
        } else {
            $query = $this->getTicketsQuery($tenant, $tab, $search, $dateFrom, $dateTo);
            $tickets = $query->get();
            
            if ($request->get('format') === 'excel') {
                return Excel::download(new TicketsExport($tickets), 'tickets.xlsx');
            }
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

        if ($ticket->validated_at && $ticket->remaining_uses <= 0) {
            return back()->with('error', __('Ticket fully used and validated.'));
        }

        // Logic for Multi-Use Tickets
        if ($ticket->remaining_uses > 1) {
            $ticket->decrement('remaining_uses');
            $action = 'validated_partial';
            $msg = __('Entry Validated. Remaining uses: ') . $ticket->remaining_uses;
        } else {
            // Last Use
            $ticket->update([
                'remaining_uses' => 0,
                'validated_at' => now()
            ]);
            $action = 'validated';
            $msg = __('Ticket validated successfully.');
        }

        // Log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', $msg);
    }

    public function unvalidateTicket(Ticket $ticket)
    {
         if ($ticket->order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // For simplicity, unvalidate restores 1 use or resets?
        // Let's say it resets "validated_at" and ensures at least 1 use if it was 0.
        // Complex scenario: if partial, unvalidate adds 1? 
        // For MVP: Unvalidate restores fully if cancelled? Or allow Increment?
        // Let's keep existing logic: Reset timestamp. If 0 uses, set to 1?
        // Safe bet: If validated_at is set, it means it was exhausted.
        
        if (!$ticket->validated_at) {
             // Maybe it was partially valid? 
             // Allow 'Undoing' a partial scan is tricky without history tracking of exact decrement.
             return back()->with('error', __('Ticket is still valid/active.'));
        }

        $ticket->update([
            'validated_at' => null,
            'remaining_uses' => 1 // Reset to at least 1? Or restore original? 
                                  // We don't store original quantity on Ticket, only order item.
                                  // Fetch from Order Item to be safe?
        ]);
        
        // Restore fully? Or just 1? 
        // Let's grab original quantity from OrderItem if possible, otherwise 1.
        $originalQty = $ticket->orderItem ? $ticket->orderItem->quantity : 1;
        // Wait, orderItem->quantity is total bought.
        // If consolidated, ticket->remaining_uses should be orderItem->quantity.
        // Check if other tickets exist for this item?
        // If query count(tickets for order_item) == 1, then it was consolidated.
        $count = Ticket::where('order_item_id', $ticket->order_item_id)->count();
        if ($count == 1) {
             $ticket->update(['remaining_uses' => $ticket->orderItem->quantity]);
        }


        // Log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'unvalidated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

         return back()->with('success', __('Ticket validation reverted (Reset to original uses).'));
    }

    public function destroy(Ticket $ticket)
    {
         if ($ticket->order->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
        
        // Decrement sold count?
        // If consolidated, decrement by remaining uses? Or total original?
        // sold count on TicketType tracks total SOLD, not valid.
        // Cancellation should decrement sold count.
        
        // If consolidated, how many sold were represented? 
        // Ideally we check orderItem->quantity if $count == 1.
        $count = Ticket::where('order_item_id', $ticket->order_item_id)->count();
        $qtyToDecrement = 1;
        if($count == 1 && $ticket->remaining_uses > 1) {
             // It's a group ticket (or partially used).
             // We should decrement by its original value? Or current?
             // Usually cancellation refunds the whole thing.
             $qtyToDecrement = $ticket->orderItem->quantity;
        }
        
        $ticket->ticketType->decrement('sold', $qtyToDecrement);
        
        $ticket->delete();

        return back()->with('success', __('Ticket cancelled.'));
    }
}
