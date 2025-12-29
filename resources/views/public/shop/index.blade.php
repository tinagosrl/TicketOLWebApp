<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->name }} - Ticket Shop</title>
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
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $tenant->name }}
                </h1>
                <p class="text-sm text-gray-600">Official Ticket Shop</p>
            </div>
            <!-- Simple Cart Placeholder -->
            <button class="btn-primary px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
                Cart (0)
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold mb-6">Upcoming Events</h2>
            
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                            @if($event->image_path)
                                <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->name }}</h3>
                                <p class="text-gray-500 text-sm mb-4">
                                    {{ $event->start_date->format('l, F j, Y') }}<br>
                                    {{ $event->venue->name }}, {{ $event->venue->city }}
                                </p>
                                <a href="{{ route('public.shop.show', ['domain' => $tenant->domain, 'slug' => $event->slug]) }}" class="block w-full text-center text-primary bg-gray-50 py-2 rounded hover:bg-indigo-100 font-semibold transition">
                                    View Tickets
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <p class="text-gray-500">No upcoming events found.</p>
                </div>
            @endif
        </div>
    </main>
    
    <footer class="bg-white border-t mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            <div class="mb-4 space-x-4">
                <a href="{{ route("language.switch", "it") }}" class="text-gray-500 hover:text-gray-900">IT</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route("language.switch", "en") }}" class="text-gray-500 hover:text-gray-900">EN</a>
            </div>            &copy; {{ date('Y') }} {{ $tenant->name }}. Powered by TicketOL.
        </div>
    </footer>
</body>
</html>
