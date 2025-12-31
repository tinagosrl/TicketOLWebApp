<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tenant') }}: {{ $tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Company Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $tenant->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="domain" :value="__('Domain')" />
                                <x-text-input id="domain" class="block mt-1 w-full" type="text" name="domain" :value="old('domain', $tenant->domain)" required />
                                <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Admin Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $tenant->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                             <div class="flex items-center">
                                <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $tenant->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">{{ __('Active') }}</label>
                            </div>
                        </div>
                        
                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Current Plan</h3>
                             @if($tenant->currentPlan && $tenant->currentPlan->plan)
                                <p class="text-gray-600">Currently on <span class="font-semibold">{{ $tenant->currentPlan->plan->getTranslation('name') }}</span> (Started: {{ $tenant->currentPlan->starts_at->format('d/m/Y') }})</p>
                            @else
                                <p class="text-gray-400">No active plan found.</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">Plan changes should be handled via the Billing/Subscription module (Future).</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.tenants.index') }}" class="text-gray-600 underline text-sm mr-4">Cancel</a>
                            <x-primary-button class="ml-4">
                                {{ __('Update Tenant') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
