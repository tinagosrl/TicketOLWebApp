<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
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
