<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('tenant.events.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                             <x-input-label for="name" :value="__('Event Name')" />
                             <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                        </div>
                        
                        <div class="md:col-span-2">
                             <x-input-label for="description" :value="__('Description')" />
                             <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-32"></textarea>
                        </div>

                        <div>
                            <x-input-label for="venue_id" :value="__('Venue')" />
                            <select id="venue_id" name="venue_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }} ({{ $venue->city }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                             <x-input-label for="image" :value="__('Event Image')" />
                             <input id="image" type="file" name="image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                        </div>

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date & Time')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" required />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date & Time')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" required />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>{{ __('Create & Manage Tickets') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
