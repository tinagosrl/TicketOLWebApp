<x-guest-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-900">{{ __('Checkout') }}</h1>

            <form action="{{ route('public.shop.checkout.store', ['domain' => request()->route('domain')]) }}" method="POST">
                @csrf
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Contact Information') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('Where should we send your tickets?') }}</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
                                <input type="text" name="customer_name" id="customer_name" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="customer_email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                                <input type="email" name="customer_email" id="customer_email" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                         <!-- Group Ticket Option -->
                        <div class="mt-4">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="consolidate_tickets" name="consolidate_tickets" type="checkbox" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="consolidate_tickets" class="font-medium text-gray-700">{{ __("Single Group Ticket") }}</label>
                                    <p class="text-gray-500">{{ __("Generate one QR code per ticket type for the entire group (e.g., 1 ticket valid for 5 entries).") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('public.cart.index', ['domain' => request()->route('domain')]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        {{ __('Back to Cart') }}
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Complete Order') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
