<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6" x-data="{ lang: 'it' }">
                    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                        @csrf
                        @method('PUT')

                        <!-- Language Tabs -->
                        <div class="mb-6 border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button type="button" @click="lang = 'it'" :class="{'border-indigo-500 text-indigo-600': lang === 'it', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': lang !== 'it'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    🇮🇹 Italian (Default)
                                </button>
                                <button type="button" @click="lang = 'en'" :class="{'border-indigo-500 text-indigo-600': lang === 'en', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': lang !== 'en'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    🇬🇧 English
                                </button>
                            </nav>
                        </div>

                        <!-- Translatable Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Name -->
                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="name" :value="__('Plan Name')" />
                                
                                <div x-show="lang === 'it'">
                                    <x-text-input id="name_it" class="block mt-1 w-full" type="text" name="name[it]" :value="$plan->name['it'] ?? ''" required />
                                </div>
                                <div x-show="lang === 'en'" style="display: none;">
                                    <x-text-input id="name_en" class="block mt-1 w-full" type="text" name="name[en]" :value="$plan->name['en'] ?? ''" />
                                </div>
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Description -->
                            <div class="col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <div x-show="lang === 'it'">
                                    <textarea id="description_it" name="description[it]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ $plan->description['it'] ?? '' }}</textarea>
                                </div>
                                <div x-show="lang === 'en'" style="display: none;">
                                    <textarea id="description_en" name="description[en]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ $plan->description['en'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Features HTML -->
                             <div class="col-span-2">
                                <x-input-label for="features_html" :value="__('Features List (HTML)')" />
                                <p class="text-xs text-gray-500 mb-1">Use &lt;li&gt; items.</p>
                                
                                <div x-show="lang === 'it'">
                                    <textarea id="features_html_it" name="features_html[it]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-xs" rows="5">{{ $plan->features_html['it'] ?? '' }}</textarea>
                                </div>
                                <div x-show="lang === 'en'" style="display: none;">
                                    <textarea id="features_html_en" name="features_html[en]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-xs" rows="5">{{ $plan->features_html['en'] ?? '' }}</textarea>
                                </div>
                            </div>
                         </div>

                        <!-- Common Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <x-input-label for="price_monthly" :value="__('Monthly Price (€)')" />
                                <x-text-input id="price_monthly" class="block mt-1 w-full" type="number" step="0.01" name="price_monthly" :value="$plan->price_monthly" required />
                            </div>
                             <div>
                                <x-input-label for="price_yearly" :value="__('Yearly Price (€)')" />
                                <x-text-input id="price_yearly" class="block mt-1 w-full" type="number" step="0.01" name="price_yearly" :value="$plan->price_yearly" required />
                            </div>
                             <div>
                                <x-input-label for="ticket_limit" :value="__('Ticket Limit (0 = Unlimited)')" />
                                <x-text-input id="ticket_limit" class="block mt-1 w-full" type="number" name="ticket_limit" :value="$plan->ticket_limit" required />
                            </div>
                             <div>
                                <x-input-label for="max_subadmins" :value="__('Max SubAdmins')" />
                                <x-text-input id="max_subadmins" class="block mt-1 w-full" type="number" name="max_subadmins" :value="$plan->max_subadmins" required />
                            </div>

                            <!-- Application Fee -->
                            <div class="col-span-2 md:col-span-1 bg-yellow-50 p-3 rounded border border-yellow-200">
                                <x-input-label for="application_fee_percent" :value="__('Application Fee (%)')" />
                                <div class="flex items-center">
                                    <x-text-input id="application_fee_percent" class="block mt-1 w-full" type="number" step="0.01" min="0" max="100" name="application_fee_percent" :value="$plan->application_fee_percent" required />
                                    <span class="ml-2 text-gray-600">%</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ __('Percentuale trattenuta dalla piattaforma su ogni biglietto venduto.') }}
                                </p>
                            </div>

                             <div class="md:col-span-1">
                                <x-input-label for="position" :value="__('Display Position')" />
                                <x-text-input id="position" class="block mt-1 w-full" type="number" name="position" :value="$plan->position" />
                            </div>
                                                        <div class="flex items-center justify-between mt-6 col-span-1 md:col-span-2">
                                <label for="is_recommended" class="inline-flex items-center">
                                    <input id="is_recommended" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_recommended" value="1" {{ $plan->is_recommended ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Consigliato') }}</span>
                                </label>

                                <label for="is_active" class="inline-flex items-center ms-6">
                                    <input id="is_active" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-900 font-bold">{{ __('Attivo') }}</span>
                                </label>
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
