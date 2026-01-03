<x-tenant-shop-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl" style="color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                    {{ $tenant->name }}
                </h1>
                <h2 class="mt-4 text-2xl text-gray-700 font-semibold">{{ __('Upcoming Events') }}</h2>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    {{ __('Choose an event and book your tickets now.') }}
                </p>
            </div>

            @if($events->count() > 0)
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach($events as $event)
                        <div class="w-full md:w-[24rem] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                            {{-- Image Logic --}}
                            <div class="h-48 bg-gray-200 w-full relative">
                                <img src="{{ $event->image_path ? Storage::url($event->image_path) : 'https://placehold.co/600x400?text=' . urlencode($event->name) }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                                <div class="absolute top-0 right-0 text-white px-3 py-1 rounded-bl-lg text-sm font-bold" style="background-color: {{ $tenant->primary_color ?? '#4f46e5' }}">
                                    @if($event->type == 'scheduled')
                                        {{ $event->start_date->format('d M') }}
                                    @else
                                        {{ __('Open') }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->name }}</h3>
                                    <div class="text-gray-600 text-sm mb-4">
                                        
                                        <!-- Date Logic Simplified for Grid -->
                                        @if($event->type == 'scheduled')
                                            <div class="flex items-center mb-1">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class='mr-1'>{{ __('Start:') }}</span> {{ $event->start_date->format('H:i') }}
                                            </div>
                                        @else
                                             <div class="flex items-center mb-1">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                @if($event->end_date)
                                                    {{ __('Until:') }} {{ $event->end_date->format('d M Y') }}
                                                @else
                                                    {{ __('Always Open') }}
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $event->venue->name ?? __('Venue') }}
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('public.shop.show', ['domain' => $tenant->domain, 'slug' => $event->slug]) }}" 
                                   style="background-color: {{ $tenant->secondary_color ?? '#111827' }}; color: {{ $tenant->text_color ?? '#ffffff' }}"
                                   class="mt-4 w-full block text-center border border-transparent rounded-md py-2 px-4 font-medium hover:opacity-90 transition-opacity">
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

            <div class="mt-16 border-t border-gray-200 pt-12">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden md:flex">
                    <div class="p-8 md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('Visit Us') }}</h2>
                        <div class="space-y-4">
                            @if(isset($tenant->venues) && $tenant->venues->count() > 0)
                                @foreach($tenant->venues as $v)
                                    <div>
                                        <h3 class="text-xl font-semibold mb-2" style="color: {{ $tenant->primary_color ?? '#4f46e5' }}">{{ $v->name }}</h3>
                                        <p class="text-gray-600">{{ $v->address }}, {{ $v->city }}</p>
                                        
                                        @if($v->opening_hours)
                                            <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                                @if(isset($v->opening_hours[$day]))
                                                    <div class="capitalize text-gray-500">{{ __($day) }}</div>
                                                    <div class="text-gray-900 font-medium">
                                                        @if(($v->opening_hours[$day]['closed'] ?? false))
                                                            {{ __('Closed') }}
                                                        @else
                                                            {{ $v->opening_hours[$day]['open'] ?? '-' }} - {{ $v->opening_hours[$day]['close'] ?? '-' }}
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
</x-tenant-shop-layout>
