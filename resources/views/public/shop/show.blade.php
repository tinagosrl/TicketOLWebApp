<x-guest-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('public.shop.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                    &larr; {{ __('Back to Events') }}
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:flex">
                <!-- Left: Image & Info -->
                <div class="md:w-1/2 lg:w-2/5 relative">
                    <img src="https://placehold.co/800x1200?text={{ urlencode($event->name) }}" alt="{{ $event->name }}" class="w-full h-64 md:h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end md:hidden">
                        <div class="p-6 text-white">
                            <h1 class="text-3xl font-bold">{{ $event->name }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Right: Details & Tickets -->
                <div class="p-8 md:w-1/2 lg:w-3/5 flex flex-col">
                    <div class="hidden md:block mb-6">
                        <h1 class="text-4xl font-extrabold text-gray-900">{{ $event->name }}</h1>
                    </div>

                    <div class="flex flex-col space-y-4 text-gray-600 mb-8">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 mr-3 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <span class="block font-semibold text-gray-900">{{ $event->start_date->format('l, d F Y') }}</span>
                                <span class="block text-sm">{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                             <svg class="w-6 h-6 mr-3 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <span class="block font-semibold text-gray-900">{{ $event->venue->name ?? 'Venue' }}</span>
                                <span class="block text-sm">{{ $event->venue->address ?? '' }}</span>
                                <span class="block text-sm">{{ $event->venue->city ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-8 mt-auto">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Select Tickets') }}</h3>
                        
                        <div class="space-y-4">
                            @foreach($event->ticketTypes as $ticketType)
                                <div class="border rounded-lg p-4 {{ $ticketType->quantity > 0 ? 'hover:border-indigo-300' : 'opacity-60 bg-gray-50' }} transition-colors">
                                    <form action="{{ route('public.cart.store') }}" method="POST" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                        @csrf
                                        <input type="hidden" name="ticket_type_id" value="{{ $ticketType->id }}">
                                        
                                        <div class="flex-grow">
                                            <div class="font-medium text-gray-900">{{ $ticketType->name }}</div>
                                            <div class="text-indigo-600 font-bold text-lg">€ {{ number_format($ticketType->price, 2) }}</div>
                                            @if($ticketType->quantity <= 0)
                                                <div class="text-red-500 text-xs font-bold uppercase mt-1">Sold Out</div>
                                            @endif
                                        </div>

                                        @if($ticketType->quantity > 0)
                                            <div class="flex items-center space-x-3">
                                                <select name="quantity" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                                    @for($i = 1; $i <= min(10, $ticketType->quantity); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium">
                                                    {{ __('Add') }}
                                                </button>
                                            </div>
                                        @else
                                            <button disabled class="bg-gray-300 text-white px-4 py-2 rounded-md cursor-not-allowed text-sm font-medium">
                                                {{ __('Unavailable') }}
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
