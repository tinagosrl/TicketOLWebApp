<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tabs & Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex space-x-1 bg-gray-200 p-1 rounded-lg">
                    <a href="{{ route('tenant.tickets.index', ['tab' => 'active']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ $tab == 'active' ? 'bg-white text-gray-900 shadow' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ __('Active') }}
                    </a>
                    <a href="{{ route('tenant.tickets.index', ['tab' => 'validated']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ $tab == 'validated' ? 'bg-white text-gray-900 shadow' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ __('Validated') }}
                    </a>
                    <a href="{{ route('tenant.tickets.index', ['tab' => 'archive']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ $tab == 'archive' ? 'bg-white text-gray-900 shadow' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ __('Archive') }}
                    </a>
                </div>

                <a href="{{ route("tenant.tickets.export", request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 mr-2">
                    Export CSV
                </a>                <form action="{{ route('tenant.tickets.index') }}" method="GET" class="w-full md:w-auto">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search (Ref, Name, Email)..." 
                               class="w-full md:w-64 pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Ticket List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($tickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Ref</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Type</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Holder</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $ticket->order->reference_no }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->ticketType->event->name }}
                                                <div class="text-xs text-gray-400">{{ $ticket->ticketType->event->start_date->format('d M Y H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->quantity }}x {{ $ticket->ticketType->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->order->customer_name }}
                                                <div class="text-xs text-gray-400">{{ $ticket->order->customer_email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($ticket->validated_at)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Validated {{ $ticket->validated_at->format('d/m/Y') }}
                                                    </span>
                                                @elseif($ticket->ticketType->event->end_date->isPast())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500">
                                                        {{ __("Archived") }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ __("Active") }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if(!$ticket->validated_at && $tab === 'active')
                                                    <div class="flex justify-end space-x-2">
                                                        <form action="{{ route('tenant.tickets.validate', $ticket->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-3 py-1 rounded hover:bg-indigo-50 transition">
                                                                Validate
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('tenant.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <!-- Actions for checked-in or archived tickets? -->
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            No tickets found in this section.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
