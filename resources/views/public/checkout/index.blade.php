<x-tenant-shop-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <h1 class="text-3xl font-bold mb-8 text-gray-900">{{ __('Checkout') }}</h1>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Left Column: Form -->
                <div class="w-full lg:w-2/3">
                    <form action="{{ route('public.shop.checkout.store', ['domain' => request()->route('domain')]) }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <!-- Contact Info Card -->
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl mb-6">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('Contact Information') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('Where should we send your tickets?') }}</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
                                        <input type="text" name="customer_name" id="customer_name" required 
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                               placeholder="Mario Rossi">
                                    </div>

                                    <div>
                                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                                        <input type="email" name="customer_email" id="customer_email" required 
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                               placeholder="mario@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Options Card (Eco-Friendly Highlight) -->
                        <div class="bg-gradient-to-r from-green-50 to-white border border-green-100 overflow-hidden shadow-sm rounded-xl mb-6 relative">
                            <div class="absolute top-0 right-0 p-2">
                                <svg class="w-12 h-12 text-green-100 transform rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="p-6">
                                <label class="flex items-start space-x-3 cursor-pointer group relative z-10">
                                    <div class="flex items-center h-5">
                                        <input id="consolidate_tickets" name="consolidate_tickets" type="checkbox" value="1" checked 
                                               class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500 transition-colors">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="block font-bold text-gray-900 group-hover:text-green-700 transition-colors">{{ __("Single Group Ticket") }}</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                {{ __('Eco Choice 🌿') }}
                                            </span>
                                        </div>
                                        <span class="block text-sm text-gray-600 mt-1">
                                            {{ __("Save paper and hassle! Generate one single QR code for the entire group.") }}
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Mobile Actions (Hidden on Desktop, shown at bottom of form) -->
                        <div class="lg:hidden mt-6">
                            <button type="submit" 
                                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all"
                                    style="background-color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                                {{ __('Complete Order') }} • € {{ number_format($total, 2) }}
                            </button>
                             <div class="mt-4 text-center">
                                <a href="{{ route('public.cart.index', ['domain' => request()->route('domain')]) }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                    &larr; {{ __('Back to Cart') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Order Summary (Sticky) -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-100">{{ __('Order Summary') }}</h2>
                        
                        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($items as $item)
                                <div class="flex justify-between items-start text-sm">
                                    <div class="flex-1 pr-4">
                                        <div class="font-medium text-gray-900">{{ $item['event_name'] }}</div>
                                        <div class="text-gray-500">{{ $item['name'] }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Qty: {{ $item['quantity'] }}</div>
                                    </div>
                                    <div class="font-medium text-gray-900 whitespace-nowrap">
                                        € {{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 mb-6">
                            <div class="flex justify-between items-center text-gray-600 mb-2">
                                <span>{{ __('Subtotal') }}</span>
                                <span>€ {{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-end mt-4">
                                <span class="text-xl font-bold text-gray-900">{{ __('Total') }}</span>
                                <div class="text-right">
                                    <span class="block text-2xl font-bold" style="color: {{ $tenant->primary_color ?? '#111827' }}">€ {{ number_format($total, 2) }}</span>
                                    <span class="text-xs text-gray-400 font-normal">EUR</span>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Actions -->
                        <div class="hidden lg:block">
                            <button type="submit" form="checkout-form"
                                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-lg"
                                    style="background-color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                                {{ __('Complete Order') }}
                            </button>
                            
                            <div class="mt-4 text-center">
                                <a href="{{ route('public.cart.index', ['domain' => request()->route('domain')]) }}" class="text-gray-400 hover:text-gray-600 text-sm transition-colors">
                                    &larr; {{ __('Back to Cart') }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-center space-x-2 text-gray-300">
                             <svg class="h-6 w-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                             <span class="text-xs">{{ __('Secure encrypted payment') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-tenant-shop-layout>
