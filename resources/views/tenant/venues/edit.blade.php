<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Venue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tenant.venues.update', $venue) }}">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Venue Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$venue->name" required />
                        </div>
                        <div>
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="$venue->city" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="$venue->address" required />
                        </div>
                        <div>
                            <x-input-label for="capacity" :value="__('Capacity')" />
                            <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="$venue->capacity" required />
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Opening Hours') }}</h3>
                        <div class="space-y-4">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <div class="flex items-center space-x-4">
                                    <div class="w-24 font-medium text-gray-700 capitalize">{{ __($day) }}</div>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="opening_hours[{{ $day }}][open]" 
                                            value="{{ $venue->opening_hours[$day]['open'] ?? '' }}"
                                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <span>-</span>
                                        <input type="time" name="opening_hours[{{ $day }}][close]" 
                                            value="{{ $venue->opening_hours[$day]['close'] ?? '' }}"
                                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="opening_hours[{{ $day }}][closed]" value="1" 
                                            {{ ($venue->opening_hours[$day]['closed'] ?? false) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Closed') }}</span>
                                    </div>
                                    @if($day !== 'monday')
                                        <div class="flex items-center ml-4">
                                            <button type="button" onclick="copyPrevious('{{ $day }}')" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                                {{ __('Copia') }}
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
    
<div class="mt-6 flex justify-end">
                        <x-primary-button>{{ __('Update Venue') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function copyPrevious(currentDay) {
            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            const currentIndex = days.indexOf(currentDay);
            if (currentIndex <= 0) return;

            const prevDay = days[currentIndex - 1];

            const prevOpen = document.querySelector(`input[name="opening_hours[${prevDay}][open]"]`).value;
            const prevClose = document.querySelector(`input[name="opening_hours[${prevDay}][close]"]`).value;
            const prevClosed = document.querySelector(`input[name="opening_hours[${prevDay}][closed]"]`).checked;

            document.querySelector(`input[name="opening_hours[${currentDay}][open]"]`).value = prevOpen;
            document.querySelector(`input[name="opening_hours[${currentDay}][close]"]`).value = prevClose;
            document.querySelector(`input[name="opening_hours[${currentDay}][closed]"]`).checked = prevClosed;
        }
    </script>
    
</x-app-layout>
