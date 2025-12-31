<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Tenants') }}
            </h2>
            <div class="flex space-x-2">
                 <a href="{{ route('admin.tenants.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Export Excel
                </a>
                <a href="{{ route('admin.tenants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create Tenant
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                     <!-- Search Form -->
                     <form method="GET" action="{{ route('admin.tenants.index') }}" class="mb-6 flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Name, Domain, or Email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.tenants.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 flex items-center">Reset</a>
                        @endif
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tenants as $tenant)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $tenant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('public.shop.index', ['domain' => $tenant->domain]) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                {{ $tenant->domain }} (Link)
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tenant->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tenant->currentPlan && $tenant->currentPlan->plan)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $tenant->currentPlan->plan->getTranslation('name') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">No active plan</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.tenants.impersonate', $tenant->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3 font-bold" title="Login as Admin">Impersonate</a>
                                            <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tenant? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tenants->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
