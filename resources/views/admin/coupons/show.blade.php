<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Coupon Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold mb-1">{{ $coupon->code }}</h3>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-right">
                             <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                                Edit Coupon
                            </a>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gray-50 px-4 py-3 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ ucfirst($coupon->type) }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">Value</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                @if($coupon->type == 'percent')
                                    {{ $coupon->value }}%
                                @elseif($coupon->type == 'fixed')
                                    €{{ number_format($coupon->value, 2) }}
                                @else
                                    {{ number_format($coupon->value, 0) }} Months
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">Usage</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $coupon->times_used }} / {{ $coupon->usage_limit ?? '∞' }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">Expires At</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Never' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Usage History Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Usage History</h3>
                    
                    @if($coupon->usages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used At</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($coupon->usages as $usage)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $usage->tenant->id ?? 'N/A' }} 
                                            <!-- Optimally we would add 'name' to tenants table, but we use domain/id for now or fetch admin user -->
                                            (ID: {{ $usage->tenant->id }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $usage->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $usage->tenant->domain ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No usage history found for this coupon.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
