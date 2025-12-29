<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function export(Request $request)
    {
         $tenant = auth()->user()->tenant;
        // Simplified export based on Ticket model
        $query = Ticket::whereHas("order", function ($q) use ($tenant) {
            $q->where("tenant_id", $tenant->id)->where("status", "paid");
        })->with(["order", "ticketType", "ticketType.event"]);
        
        $tickets = $query->get();
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=tickets.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use ($tickets) {
            $file = fopen("php://output", "w");
            fputcsv($file, ["Ticket ID", "Order Ref", "Event", "Ticket Type", "Customer Name", "Customer Email", "Status", "Date"]);
            
             foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->order->reference_no,
                    $ticket->ticketType->event->name,
                    $ticket->ticketType->name,
                    $ticket->order->customer_name,
                    $ticket->order->customer_email,
                    $ticket->validated_at ? "Validated" : "Active",
                    $ticket->created_at
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $tenant = auth()->user()->tenant;
        $tab = $request->get('tab', 'active');
        $search = $request->get('search');

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
            case 'logs':
                // Logs are handled separately, but we might show general logs here? 
                // Creating a separate variable for logs if tab is logs
                break;
            case 'active':
            default:
                $query->whereNull('validated_at')
                      ->where('created_at', '>=', now()->subYear())
                      ->whereHas('ticketType.event', function($eq) {
                          $eq->where('end_date', '>=', now());
                      });
                break;
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        $logs = null;
        if ($tab === 'logs') {
            $logs = TicketLog::whereHas('ticket.order', function($q) use ($tenant) {
                 $q->where('tenant_id', $tenant->id);
            })->with(['ticket.ticketType', 'user', 'ticket.order'])->orderBy('created_at', 'desc')->paginate(20);
        }

        return view('tenant.tickets.index', compact('tickets', 'tab', 'search', 'logs'));
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
        
        // Decrement sold count?
        $ticket->ticketType->decrement('sold'); // Decrement by 1 since it's a single ticket
        
        $ticket->delete();

         // Log (if we kept soft deletes, but here it's hard delete so maybe log before?)
         // Ideally logs are kept. 

        return back()->with('success', __('Ticket cancelled.'));
    }
}
