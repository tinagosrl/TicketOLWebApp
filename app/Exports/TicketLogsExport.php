<?php

namespace App\Exports;

use App\Models\TicketLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketLogsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function collection()
    {
        return $this->logs;
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('d/m/Y H:i:s'),
            $log->ticket_id,
            $log->ticket->ticketType->name ?? 'N/A',
            $log->user->name ?? 'System',
            ucfirst($log->action),
            $log->ip_address,
        ];
    }

    public function headings(): array
    {
        return [
            "Date",
            "Ticket ID",
            "Ticket Type",
            "User",
            "Action",
            "IP Address",
        ];
    }
}
