<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmed - {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8 space-y-8">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900">Order Confirmed!</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Thank you, {{ $order->customer_name }}.
                </p>
                <p class="text-sm text-gray-500">
                    One email with the tickets has been sent to {{ $order->customer_email }}
                </p>
            </div>

            <div class="border-t border-b border-gray-200 py-6">
                <!-- Download All Button -->
                <div class="mb-6 flex justify-center">
                    <a href="{{ URL::signedRoute('orders.download.tickets', $order) }}" target="_blank" class="w-full text-center px-4 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-md transition-all">
                        📄 Download All Tickets (PDF)
                    </a>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-medium text-gray-500">Order Ref:</span>
                    <span class="text-lg font-bold text-gray-900">{{ $order->reference_no }}</span>
                </div>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm items-start">
                            <div>
                                <span class="font-bold text-gray-900">{{ $item->quantity }}x {{ $item->ticketType->name }}</span>
                                <div class="text-xs text-gray-500">{{ $item->ticketType->event->name }}</div>
                            </div>
                            <div class="text-right">
                                <span class="font-medium text-gray-900">€ {{ number_format($item->price * $item->quantity, 2) }}</span>
                                <div class="mt-2">
                                     <!-- Optional: Individual download links if needed, but primary is the big button above -->
                                     <span class="text-xs text-gray-400">Includes {{ $item->quantity }} ticket(s)</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-base font-medium text-gray-900">Total Paid</span>
                <span class="text-2xl font-bold text-gray-900">€ {{ number_format($order->total_amount, 2) }}</span>
            </div>

            <div>
                <a href="{{ route('public.shop.index', $tenant->domain) }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-gray-50 border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to Shop
                </a>
            </div>
        </div>
    </div>
</body>
</html>
