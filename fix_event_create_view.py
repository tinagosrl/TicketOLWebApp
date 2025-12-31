import os 

path = 'resources/views/tenant/events/create.blade.php'
if os.path.exists(path):
    with open(path, 'r') as f:
        content = f.read()

    # Need to add Type selection.
    # Where? Maybe after Name/Description?
    # Before Venue?
    
    # We also need to check allowed types from the authenticated user's tenant plan in the view.
    # @php $allowedTypes = auth()->user()->tenant->currentPlan->plan->allowed_event_types ?? ['scheduled', 'open']; @endphp
    
    # Selection UI:
    # If both allowed: Toggle/Radio/Select.
    # If only one: Hidden input + display text? Or just Select with 1 option.
    
    type_field = """
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
    """
    
    # Insert after Name input block.
    # <div class="md:col-span-2"> ... <x-text-input id="name" ...> ... </div>
    
    # Finding end of name block is hard without parser.
    # Let's insert before Venue block.
    # <div> <x-input-label for="venue_id" ...
    
    if 'x-input-label for="venue_id"' in content and 'name="type"' not in content:
        content = content.replace('<div>\n                            <x-input-label for="venue_id"', type_field + '\n                        <div>\n                            <x-input-label for="venue_id"')
        with open(path, 'w') as f:
            f.write(content)
        print("Updated create.blade.php with Type selector.")

