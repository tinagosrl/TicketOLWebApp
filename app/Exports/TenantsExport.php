<?php

namespace App\Exports;

use App\Models\Tenant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class TenantsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Tenant::query()->with('currentPlan.plan');

        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Domain',
            'Admin Email',
            'Current Plan',
            'Status',
            'Created At',
        ];
    }

    public function map($tenant): array
    {
        return [
            $tenant->id,
            $tenant->name,
            $tenant->domain,
            $tenant->email,
            $tenant->currentPlan && $tenant->currentPlan->plan ? $tenant->currentPlan->plan->name : 'No Plan',
            $tenant->is_active ? 'Active' : 'Inactive',
            $tenant->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
