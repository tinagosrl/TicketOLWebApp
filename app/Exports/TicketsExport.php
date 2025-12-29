<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tenant;
    protected $request;

    public function __construct($tenant, $request)
    {
        $this->tenant = $tenant;
        $this->request = $request;
    }

    public function collection()
    {
        // Re-use logic from Controller (simplified duplicate for now, ideally in Service)
        $query = Ticket::whereHas("order", function ($q) {
            $q->where("tenant_id", $this->tenant->id)->where("status", "paid");
        })->with(["order", "ticketType", "ticketType.event"]);

        // Apply filters if needed, based on request (simplified for now to match basic export)
        
        return $query->get();
    }

    public function map($ticket): array
    {
        return [
            $ticket->id,
            $ticket->order->reference_no,
            $ticket->ticketType->event->name,
            $ticket->ticketType->name,
            $ticket->order->customer_name,
            $ticket->order->customer_email,
            $ticket->validated_at ? "Validated" : "Active",
            $ticket->created_at->format('d/m/Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            "Ticket ID",
            "Order Ref",
            "Event",
            "Ticket Type",
            "Customer Name",
            "Customer Email",
            "Status",
            "Date",
        ];
    }
}
