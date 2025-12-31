<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use Illuminate\Http\Request;
use App\Exports\ImpersonationLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class ImpersonationLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ImpersonationLog::with(['impersonator', 'impersonated']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
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

        $logs = $query->latest()->paginate(30);

        return view('admin.logs.impersonation', compact('logs'));
    }

    public function export(Request $request)
    {
        return Excel::download(new ImpersonationLogsExport($request), 'impersonation_logs.xlsx');
    }
}
