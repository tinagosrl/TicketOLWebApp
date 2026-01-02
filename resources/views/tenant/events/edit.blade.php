<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Event') }}: {{ $event->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Event Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Event Details') }}</h3>
                <form method="POST" action="{{ route('tenant.events.update', $event) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                             <x-input-label for="name" :value="__('Event Name')" />
                             <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$event->name" required />
                        </div>
                        
                        <div class="md:col-span-2">
                             <x-input-label for="description" :value="__('Description')" />
                             <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-32">{{ $event->description }}</textarea>
                        </div>

                        
                        @php
                            $allowedTypes = auth()->user()->tenant->currentPlan->plan->allowed_event_types ?? ['scheduled', 'open'];
                            $isScheduled = $event->type === 'scheduled';
                            // Initial Format Logic
                            $startDateFormat = $isScheduled ? 'Y-m-d\TH:i' : 'Y-m-d';
                            $endDateFormat = $isScheduled ? 'Y-m-d\TH:i' : 'Y-m-d';
                        @endphp
                        
                        <div class="md:col-span-2">
                             <x-input-label for="type" :value="__('Event Type')" />
                             <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @if(in_array('scheduled', $allowedTypes))
                                    <option value="scheduled" {{ $isScheduled ? 'selected' : '' }}>{{ __('Scheduled Event (Concert, Show)') }}</option>
                                @endif
                                @if(in_array('open', $allowedTypes))
                                    <option value="open" {{ !$isScheduled ? 'selected' : '' }}>{{ __('Open Access (Museum, Park)') }}</option>
                                @endif
                             </select>
                        </div>
    
                        <div>
                            <x-input-label for="venue_id" :value="__('Venue')" />
                            <select id="venue_id" name="venue_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ $venue->id == $event->venue_id ? 'selected' : '' }}>
                                        {{ $venue->name }} ({{ $venue->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                             <x-input-label for="image" :value="__('Event Image')" />
                             <p class="text-xs text-gray-500 mb-1">Recommended: 800x600px</p>
                             @if($event->image_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($event->image_path) }}" class="h-20 w-auto rounded" />
                                </div>
                             @endif
                             <input id="image" type="file" name="image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                        </div>

                        <div class="mt-4">
                             <x-input-label for="vertical_image" :value="__('Vertical Image (Event Details)')" />
                             <p class="text-xs text-gray-500 mb-1">Recommended: 600x900px</p>
                             @if($event->vertical_image_path)
                                <img src="{{ asset('storage/' . $event->vertical_image_path) }}" alt="Vertical Image" class="h-20 w-auto mb-2 rounded">
                             @endif
                             <input id="vertical_image" type="file" name="vertical_image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                        </div>
    

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <!-- Note: type is initially set by PHP to avoid mismatch, JS will handle updates -->
                            <x-text-input id="start_date" class="block mt-1 w-full" 
                                          type="{{ $isScheduled ? 'datetime-local' : 'date' }}" 
                                          name="start_date" 
                                          :value="$event->start_date ? $event->start_date->format($startDateFormat) : ''" required />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" 
                                          type="{{ $isScheduled ? 'datetime-local' : 'date' }}" 
                                          name="end_date" 
                                          :value="$event->end_date ? $event->end_date->format($endDateFormat) : ''" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>{{ __('Update Details') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Ticket Types -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Ticket Types') }}</h3>
                </div>

                <!-- Add New Ticket Type Form (Inline) -->
                <form method="POST" action="{{ route('tenant.ticket_types.store') }}" class="mb-6 p-4 bg-gray-50 rounded-lg flex flex-wrap gap-4 items-end">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <div class="flex-grow min-w-[200px]">
                        <x-input-label for="ticket_name" :value="__('Name (e.g. VIP)')" />
                        <x-text-input id="ticket_name" class="block mt-1 w-full" type="text" name="name" required placeholder="Standard" />
                    </div>
                    <div class="w-32">
                        <x-input-label for="price" :value="__('Price (€)')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" required placeholder="10.00" />
                    </div>
                    <div class="w-24">
                        <x-input-label for="min_purchase" :value="__('Min Qty')" />
                        <x-text-input id="min_purchase" class="block mt-1 w-full" type="number" name="min_purchase" value="1" min="1" required />
                    </div>
                    <div class="w-32">
                        <x-input-label for="quantity" :value="__('Total Qty')" />
                        <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" required placeholder="100" />
                        <p class="text-xs text-gray-500 mt-1">0 = Unlimited</p>
                    </div>
                    <x-secondary-button type="submit" class="mb-0.5">
                        {{ __('Add') }}
                    </x-secondary-button>
                </form>

                <!-- List Ticket Types -->
                @if($ticketTypes->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Min Purchase</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Available</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sold</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($ticketTypes as $ticket)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $ticket->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">€ {{ number_format($ticket->price, 2) }}</td>
                                     <td class="px-4 py-2 text-sm text-gray-500">{{ $ticket->min_purchase }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">
                                        @if($ticket->quantity == -1)
                                            <span class="text-green-600 font-bold">Unlimited</span>
                                        @else
                                            {{ $ticket->quantity }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $ticket->sold }}</td>
                                    <td class="px-4 py-2 text-sm text-right">
                                        <form action="{{ route('tenant.ticket_types.destroy', $ticket) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this ticket type?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-sm">No ticket types defined yet.</p>
                @endif
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
             // Init check unnecessary if PHP handled it, but harmless
        }
    });
</script>

</x-app-layout>
