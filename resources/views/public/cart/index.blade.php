<x-tenant-shop-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <h1 class="text-3xl font-bold mb-8 text-gray-900">{{ __('Your Cart') }}</h1>
            
            @if(count($items) > 0)
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <!-- Left Column: Items -->
                    <div class="w-full lg:w-2/3">
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                            <!-- Desktop Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Event') }}</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Ticket') }}</th>
                                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Qty') }}</th>
                                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
                                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['event_name'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['name'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $item['quantity'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">€ {{ number_format($item['price'], 2) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">€ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form action="{{ route('public.cart.destroy', ['domain' => request()->route('domain'), 'id' => $item['id']]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile List (Cards) -->
                            <div class="md:hidden divide-y divide-gray-200">
                                 @foreach($items as $item)
                                    <div class="p-4 flex flex-col space-y-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold text-gray-900">{{ $item['event_name'] }}</h3>
                                                <p class="text-sm text-gray-500">{{ $item['name'] }}</p>
                                            </div>
                                            <form action="{{ route('public.cart.destroy', ['domain' => request()->route('domain'), 'id' => $item['id']]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <div class="bg-gray-100 rounded-lg px-2 py-1">
                                                Qty: <span class="font-semibold">{{ $item['quantity'] }}</span>
                                            </div>
                                            <div class="text-gray-500">
                                                € {{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}
                                            </div>
                                            <div class="font-bold text-lg text-gray-900">
                                                € {{ number_format($item['price'] * $item['quantity'], 2) }}
                                            </div>
                                        </div>
                                    </div>
                                 @endforeach
                            </div>
                        </div>

                         <div class="mt-6">
                            <a href="{{ route('public.shop.index', ['domain' => request()->route('domain')]) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center"
                               style="color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                                &larr; {{ __('Continue Shopping') }}
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Summary -->
                    <div class="w-full lg:w-1/3">
                        <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Summary') }}</h2>
                            
                            <div class="flex justify-between items-center mb-2 text-gray-600">
                                <span>{{ __('Subtotal') }}</span>
                                <span>€ {{ number_format($total, 2) }}</span>
                            </div>
                            <!-- Future: Taxes/Fees -->
                            
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <div class="flex justify-between items-end mb-6">
                                <span class="text-xl font-bold text-gray-900">{{ __('Total') }}</span>
                                <span class="text-2xl font-bold text-gray-900" style="color: {{ $tenant->primary_color ?? '#111827' }}">€ {{ number_format($total, 2) }}</span>
                            </div>

                            <a href="{{ route('public.shop.checkout.index', ['domain' => request()->route('domain')]) }}" 
                               class="w-full block text-center py-4 rounded-lg text-white font-bold text-lg hover:opacity-90 transition-opacity shadow-lg"
                               style="background-color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                                {{ __('Proceed to Checkout') }}
                            </a>
                             
                            <div class="mt-4 text-center text-xs text-gray-400">
                                {{ __('Secure Checkout') }}
                            </div>
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ __('Your cart is empty.') }}</h2>
                    <p class="text-gray-500 mb-8">{{ __('Looks like you haven\'t added any tickets yet.') }}</p>
                    <a href="{{ route('public.shop.index', ['domain' => request()->route('domain')]) }}" 
                       class="inline-block px-6 py-3 rounded-lg text-white font-medium hover:opacity-90 transition-opacity"
                       style="background-color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                        {{ __('Browse Events') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-tenant-shop-layout>
