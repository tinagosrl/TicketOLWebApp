<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $tenant = auth()->user()->tenant;

        if (!$tenant) {
            abort(403, 'No tenant assigned.');
        }

        // Stats Query
        // We need to count items sold, not just orders.
        // Orders are linked to tenant. Items are linked to Order.
        
        $ordersQuery = Order::where('tenant_id', $tenant->id)->where('status', 'paid');
        
        $totalTicketsSold = OrderItem::whereHas('order', function ($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)->where('status', 'paid');
        })->sum('quantity');
        
        $totalTicketsSoldToday = OrderItem::whereHas('order', function ($q) use ($tenant) {
             $q->where('tenant_id', $tenant->id)
               ->where('status', 'paid')
               ->whereDate('created_at', today());
        })->sum('quantity');

         $totalTicketsSoldMonth = OrderItem::whereHas('order', function ($q) use ($tenant) {
             $q->where('tenant_id', $tenant->id)
               ->where('status', 'paid')
               ->whereMonth('created_at', now()->month)
               ->whereYear('created_at', now()->year);
        })->sum('quantity');

        // Recent Orders
        $recentOrders = Order::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->withCount('items') // or sum quantity
            ->latest()
            ->take(5)
            ->get();
            
        // For recent orders, we might want to show total items quantity
        $recentOrders->each(function($order) {
            $order->total_items = $order->items()->sum('quantity');
        });

        return view('dashboard', [
            'tenant' => $tenant,
            'totalTicketsSold' => $totalTicketsSold,
            'totalTicketsSoldToday' => $totalTicketsSoldToday,
            'totalTicketsSoldMonth' => $totalTicketsSoldMonth,
            'recentOrders' => $recentOrders,
            'isArchive' => false // For the tab logic if implemented later
        ]);
    }
}
