<x-guest-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">{{ $tenant->name }}</span>
                    <span class="block text-indigo-600">{{ __('Upcoming Events') }}</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    {{ __('Choose an event and book your tickets now.') }}
                </p>
            </div>

            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                            {{-- Placeholder Image - Could be dynamic --}}
                            <div class="h-48 bg-gray-200 w-full relative">
                                <img src="https://placehold.co/600x400?text={{ urlencode($event->name) }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                                <div class="absolute top-0 right-0 bg-indigo-600 text-white px-3 py-1 rounded-bl-lg text-sm font-bold">
                                    {{ $event->start_date->format('d M') }}
                                </div>
                            </div>
                            
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->name }}</h3>
                                    <div class="text-gray-600 text-sm mb-4">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $event->start_date->format('H:i') }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $event->venue->name ?? 'Venue' }}
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('public.shop.show', $event->slug) }}" class="mt-4 w-full block text-center bg-gray-900 border border-transparent rounded-md py-2 px-4 text-white font-medium hover:bg-gray-800">
                                    {{ __('View Details') }} &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500 text-xl">{{ __('No upcoming events found.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
