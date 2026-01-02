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

                        
                        @php
                            $allowedTypes = auth()->user()->tenant->currentPlan->plan->allowed_event_types ?? ['scheduled', 'open'];
                        @endphp
                        
                        <div class="md:col-span-2">
                             <x-input-label for="type" :value="__('Event Type')" />
                             <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @if(in_array('scheduled', $allowedTypes))
                                    <option value="scheduled">{{ __('Scheduled Event (Concert, Show)') }}</option>
                                @endif
                                @if(in_array('open', $allowedTypes))
                                    <option value="open">{{ __('Open Access (Museum, Park)') }}</option>
                                @endif
                             </select>
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

                        <div class="mt-4">
                             <x-input-label for="vertical_image" :value="__('Vertical Image (Event Details)')" />
                             <p class="text-xs text-gray-500 mb-1">Recommended: 600x900px</p>
                             <input id="vertical_image" type="file" name="vertical_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                        </div>
    

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date & Time')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date & Time')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>{{ __('Create & Manage Tickets') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        function updateInputs() {
            const type = typeSelect.value;
            // Capture current values
            let startVal = startDateInput.value;
            let endVal = endDateInput.value;

            if (type === 'open') {
                startDateInput.type = 'date';
                endDateInput.type = 'date';
                
                // If it has 'T', strip it
                if(startVal.includes('T')) startDateInput.value = startVal.split('T')[0];
                if(endVal.includes('T')) endDateInput.value = endVal.split('T')[0];
            } else {
                startDateInput.type = 'datetime-local';
                endDateInput.type = 'datetime-local';
                
                // If it doesn't have 'T' and has value, add time
                if(startVal && !startVal.includes('T')) startDateInput.value = startVal + 'T09:00';
                if(endVal && !endVal.includes('T')) endDateInput.value = endVal + 'T18:00';
            }
        }
        
        if(typeSelect) {
            typeSelect.addEventListener('change', updateInputs);
            updateInputs(); // Init
        }
    });
</script>

</x-app-layout>
