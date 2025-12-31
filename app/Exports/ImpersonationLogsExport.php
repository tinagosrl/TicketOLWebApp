<?php

namespace App\Exports;

use App\Models\ImpersonationLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ImpersonationLogsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = ImpersonationLog::with(['impersonator', 'impersonated']);

        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $query->where(function ($q) use ($search) {
                // Search in relationships or log fields
                $q->whereHas('impersonator', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('impersonated', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('ip_address', 'like', "%{$search}%")
                ->orWhere('action', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Impersonator',
            'Impersonated User',
            'Action',
            'IP Address',
            'User Agent',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->created_at->format('Y-m-d H:i:s'),
            $log->impersonator ? $log->impersonator->name . ' (' . $log->impersonator->email . ')' : 'System/Unknown',
            $log->impersonated ? $log->impersonated->name . ' (' . $log->impersonated->email . ')' : 'Unknown',
            ucfirst($log->action),
            $log->ip_address,
            $log->user_agent,
        ];
    }
}
