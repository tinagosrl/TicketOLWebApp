<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome & Shop Link Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8 border-l-4 border-indigo-500">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('Welcome') }}, {{ $tenant->name }}!</h3>
                        <p class="text-gray-500 mt-1">{{ __('Manage your events and customize your shop.') }}</p>
                        
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('tenant.shop.settings.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Customize Shop & Logo') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-auto bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">{{ __('Your Public Shop URL') }}</p>
                        <div class="flex items-center gap-2">
                             <a href="{{ route('public.shop.index', $tenant->domain) }}" target="_blank" class="text-indigo-600 font-medium hover:underline text-sm break-all">
                                {{ route('public.shop.index', $tenant->domain) }}
                            </a>
                            <a href="{{ route('public.shop.index', $tenant->domain) }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-6 mb-8">
                <!-- Sold Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-2">{{ __('Sold Today') }}</p>
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-5xl font-extrabold text-gray-900">{{ $totalTicketsSoldToday }}</p>
                    </div>
                </div>

                <!-- Sold Month -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-2">{{ __('Sold This Month') }}</p>
                     <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-full">
                             <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-5xl font-extrabold text-gray-900">{{ $totalTicketsSoldMonth }}</p>
                    </div>
                </div>

                 <!-- Total Sold -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-2">{{ __('Total Tickets Sold') }}</p>
                     <p class="text-xs text-gray-400 mb-1">({{ date('Y') }})</p>
                     <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <p class="text-5xl font-extrabold text-indigo-600">{{ $totalTicketsSold }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Recent Orders') }}</h3>
                        <a href="{{ route('tenant.tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __("View All Orders") }} &rarr;</a>
                    </div>
                    @if($recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Ref') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Customer') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Items') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $order->reference_no }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->customer_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->total_items }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                € {{ number_format($order->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('d M Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            {{ __('No orders yet.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
