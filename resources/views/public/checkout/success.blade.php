<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Order Confirmed!</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Reference: <span class="font-mono font-bold text-gray-700">{{ $order->reference_no }}</span>
                </p>

                <div class="border-t border-b border-gray-200 py-4 mb-6 text-left">
                    <p class="text-sm text-gray-600 mb-2">Thank you, <strong>{{ $order->customer_name }}</strong>.</p>
                    <p class="text-sm text-gray-600">A confirmation email has been sent to {{ $order->customer_email }}.</p>
                </div>

                <div class="space-y-3">
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('orders.download.tickets', $order->id) }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Download Tickets (PDF)
                    </a>
                    
                    <a href="{{ route('public.shop.index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Return to Events
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
