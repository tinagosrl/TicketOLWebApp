<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->name }} - {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: {{ $tenant->primary_color ?? "#4f46e5" }};
        }
        .text-primary { color: var(--primary-color); }
        .text-primary:hover { opacity: 0.8; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { opacity: 0.9; }
        .focus-ring-primary:focus { --tw-ring-color: var(--primary-color); }
    </style></head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('public.shop.index', $tenant->domain) }}" class="text-primary text-sm font-semibold mb-2 inline-block">&larr; Back to Events</a>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $tenant->name }}
            </h1>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row">
                <!-- Event Image & Info -->
                <div class="md:w-1/2 p-0 bg-gray-50 relative">
                     @if($event->image_path)
                        <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->name }}" class="w-full h-64 md:h-full object-cover">
                    @else
                        <div class="w-full h-64 md:h-full flex items-center justify-center text-gray-400">No Image</div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 text-white">
                         <h2 class="text-3xl font-bold">{{ $event->name }}</h2>
                         <p class="text-lg opacity-90">{{ $event->start_date->format('F j, Y - H:i') }}</p>
                    </div>
                </div>

                <!-- Ticket Selection -->
                <div class="md:w-1/2 p-8">
                    <div class="mb-6">
                        <h3 class="text-gray-900 font-bold text-xl mb-2">Event Details</h3>
                        <p class="text-gray-600 mb-2">{{ $event->description ?: 'No description available.' }}</p>
                        <p class="text-gray-500 text-sm">
                            <strong>Venue:</strong> {{ $event->venue->name }}<br>
                            {{ $event->venue->address }}, {{ $event->venue->city }}
                        </p>
                    </div>

                    <hr class="my-6">

                    <h3 class="text-gray-900 font-bold text-xl mb-4">Select Tickets</h3>
                    
                    @if($event->ticketTypes->count() > 0)
                        <form action="{{ route('public.shop.checkout.store', $tenant->domain) }}" method="POST"> 
                            @csrf
                            <div class="space-y-4 mb-6">
                                @foreach($event->ticketTypes as $ticket)
                                    <div class="flex justify-between items-center border p-4 rounded-lg hover:border-indigo-500 transition cursor-pointer">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $ticket->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $ticket->quantity - $ticket->sold }} available
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">€ {{ number_format($ticket->price, 2) }}</div>
                                            <select name="tickets[{{ $ticket->id }}]" class="mt-1 block rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <h3 class="text-gray-900 font-bold text-xl mb-4">Your Details</h3>
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" name="customer_name" id="customer_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="customer_email" id="customer_email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            
                            @if($errors->any())
                                <div class="mb-4 text-red-600 text-sm">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <button type="submit" class="w-full mt-2 btn-primary font-bold py-3 px-4 rounded hover:bg-indigo-700 transition">
                                Buy Tickets Now
                            </button>
                        </form>
                    @else
                        <p class="text-red-500">No tickets available for this event.</p>
                    @endif

                </div>
            </div>
        </div>
    </main>
</body>
</html>
