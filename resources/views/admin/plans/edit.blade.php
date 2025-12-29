<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Plan') }}: {{ $plan->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Plan Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$plan->name" required />
                            </div>

                             <!-- Slug (Readonly) -->
                             <div>
                                <x-input-label for="slug" :value="__('Slug (Cannot Change)')" />
                                <x-text-input id="slug" class="block mt-1 w-full bg-gray-100" type="text" :value="$plan->slug" readonly />
                            </div>
                            
                            <!-- Description -->
                            <div class="col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ $plan->description }}</textarea>
                            </div>

                             <!-- Price Monthly -->
                             <div>
                                <x-input-label for="price_monthly" :value="__('Monthly Price (€)')" />
                                <x-text-input id="price_monthly" class="block mt-1 w-full" type="number" step="0.01" name="price_monthly" :value="$plan->price_monthly" required />
                            </div>

                             <!-- Price Yearly -->
                             <div>
                                <x-input-label for="price_yearly" :value="__('Yearly Price (€)')" />
                                <x-text-input id="price_yearly" class="block mt-1 w-full" type="number" step="0.01" name="price_yearly" :value="$plan->price_yearly" required />
                            </div>

                            <!-- Ticket Limit -->
                             <div>
                                <x-input-label for="ticket_limit" :value="__('Ticket Limit (0 = Unlimited)')" />
                                <x-text-input id="ticket_limit" class="block mt-1 w-full" type="number" name="ticket_limit" :value="$plan->ticket_limit" required />
                            </div>

                            <!-- Max SubAdmins -->
                             <div>
                                <x-input-label for="max_subadmins" :value="__('Max SubAdmins')" />
                                <x-text-input id="max_subadmins" class="block mt-1 w-full" type="number" name="max_subadmins" :value="$plan->max_subadmins" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Update Plan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
