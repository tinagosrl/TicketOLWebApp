<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tenant Details') }}: {{ $tenant->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.tenants.impersonate', $tenant) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Impersonate') }}
                </a>
                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Main Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Organization Info') }}</h3>
                            <dl class="divide-y divide-gray-100">
                                <div class="px-0 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Name</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $tenant->name }}</dd>
                                </div>
                                <div class="px-0 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Domain</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $tenant->domain }}.ticketol.eu</dd>
                                </div>
                                <div class="px-0 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Contact Email</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $tenant->email }}</dd>
                                </div>
                                <div class="px-0 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Registered</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $tenant->created_at->format('d M Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Subscription') }}</h3>
                             <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">Current Plan</span>
                                    <span class="font-bold text-indigo-600">{{ $tenant->currentPlan?->plan->getTranslation('name') ?? 'None' }}</span>
                                </div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">Status</span>
                                    @if($tenant->is_active)
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Suspended</span>
                                    @endif
                                </div>
                                 <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Expires/Renews</span>
                                    <span class="text-sm text-gray-900">{{ $tenant->currentPlan?->ends_at?->format('d M Y') ?? 'N/A' }}</span>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users List (Optional, but useful) -->
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Tenant Users') }}</h3>
                    <ul role="list" class="divide-y divide-gray-100">
                        @foreach($tenant->users as $user)
                            <li class="flex justify-between gap-x-6 py-5">
                                <div class="flex min-w-0 gap-x-4">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $user->name }}</p>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                    <p class="text-sm leading-6 text-gray-900">{{ ucfirst($user->role) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
