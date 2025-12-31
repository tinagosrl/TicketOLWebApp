<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Tenant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.tenants.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Company Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="domain" :value="__('Domain (e.g. mype-ticket.ticketol.eu)')" />
                                <x-text-input id="domain" class="block mt-1 w-full" type="text" name="domain" :value="old('domain')" required />
                                <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Admin Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="plan_id" :value="__('Initial Plan')" />
                                <select id="plan_id" name="plan_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->getTranslation('name') }} (Up to {{ $plan->max_events }} events)</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('plan_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Admin Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.tenants.index') }}" class="text-gray-600 underline text-sm mr-4">Cancel</a>
                            <x-primary-button class="ml-4">
                                {{ __('Create Tenant') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
