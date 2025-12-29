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
                    <a href="{{ route('tenant.tickets.index', ['tab' => 'logs']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium {{ $tab == 'logs' ? 'bg-white text-gray-900 shadow' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ __('Logs') }}
                    </a>
                </div>

                @if($tab !== 'logs')
                <a href="{{ route("tenant.tickets.export", request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 mr-2">
                    {{ __('Export CSV') }}
                </a>                
                <form action="{{ route('tenant.tickets.index') }}" method="GET" class="w-full md:w-auto">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search') }}..." 
                               class="w-full md:w-64 pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
                @endif
            </div>

            <!-- Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if($tab === 'logs')
                        @if($logs && $logs->count() > 0)
                             <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Ticket ID') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Action') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($logs as $log)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    #{{ $log->ticket_id }} 
                                                    <span class="text-xs text-gray-400">({{ $log->ticket->ticketType->name ?? 'N/A' }})</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->user->name ?? 'System' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ ucfirst($log->action) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->ip_address }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $logs->links() }}
                            </div>
                        @else
                             <div class="text-center py-8 text-gray-500">
                                {{ __('No logs found.') }}
                            </div>
                        @endif
                    @else
                        @if($tickets->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / Code</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Event') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Ticket Type') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Customer') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <div class="text-indigo-600">#{{ $ticket->id }}</div>
                                                    <div class="text-xs text-gray-500">{{ $ticket->unique_code }}</div>
                                                    <div class="text-xs text-gray-400">Ref: {{ $ticket->order->reference_no }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->ticketType->event->name }}
                                                    <div class="text-xs text-gray-400">{{ $ticket->ticketType->event->start_date->format('d/m/Y H:i') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->ticketType->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->order->customer_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($ticket->validated_at)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            {{ __('Validated') }} {{ $ticket->validated_at->format('d/m/Y H:i') }}
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
                                                                    {{ __('Validate') }}
                                                                </button>
                                                            </form>
                                                            
                                                            <form action="{{ route('tenant.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                                    {{ __('Cancel') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($ticket->validated_at && $tab === 'validated')
                                                        <div class="flex justify-end space-x-2">
                                                            <form action="{{ route('tenant.tickets.unvalidate', $ticket->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                                @csrf
                                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 border border-yellow-600 px-3 py-1 rounded hover:bg-yellow-50 transition">
                                                                    {{ __('Revert Validation') }}
                                                                </button>
                                                            </form>
                                                        </div>
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
                                {{ __('No tickets found in this section.') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
