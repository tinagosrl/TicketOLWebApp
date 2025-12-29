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
                    <div class="mt-6 flex justify-end">
                        <x-primary-button>{{ __('Update Venue') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
