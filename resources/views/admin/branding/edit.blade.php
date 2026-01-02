<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Global Branding') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- App Name -->
                            <div class="col-span-2">
                                <x-input-label for="app_name" :value="__('Application Name')" />
                                <x-text-input id="app_name" class="block mt-1 w-full" type="text" name="app_name" :value="$settings['app_name'] ?? config('app.name')" />
                                <x-input-error :messages="$errors->get('app_name')" class="mt-2" />
                            </div>

                            <!-- Logo -->
                            <div>
                                <x-input-label for="logo" :value="__('Application Logo')" />
                                @if(isset($settings['logo_path']))
                                    <div class="mt-2 mb-2">
                                        <img src="{{ $settings['logo_path'] }}" alt="Current Logo" class="h-20 w-auto object-contain border rounded p-1">
                                    </div>
                                @endif
                                <input id="logo" type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-xs text-gray-500 mt-1">Consigliato: PNG, Sfondo Trasparente. Max 2MB. Dimensioni: ~200x60px.</p>
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            </div>

                            <!-- Favicon -->
                            <div>
                                <x-input-label for="favicon" :value="__('Favicon')" />
                                @if(isset($settings['favicon_path']))
                                    <div class="mt-2 mb-2">
                                        <img src="{{ $settings['favicon_path'] }}" alt="Current Favicon" class="h-8 w-8 object-contain border rounded p-1">
                                    </div>
                                @endif
                                <input id="favicon" type="file" name="favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-xs text-gray-500 mt-1">Consigliato: PNG/ICO. Max 1MB. Dimensioni: 32x32px.</p>
                                <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
                            </div>

                            <!-- Primary Color -->
                            <div>
                                <x-input-label for="primary_color" :value="__('Primary Brand Color')" />
                                <div class="flex items-center mt-1">
                                    <input type="color" id="primary_color_picker" value="{{ $settings['primary_color'] ?? '#4f46e5' }}" class="h-10 w-10 border rounded shadow-sm mr-2 p-0.5" onchange="document.getElementById('primary_color').value = this.value">
                                    <x-text-input id="primary_color" class="block w-full" type="text" name="primary_color" :value="$settings['primary_color'] ?? '#4f46e5'" placeholder="#4f46e5" />
                                </div>
                                <x-input-error :messages="$errors->get('primary_color')" class="mt-2" />
                            </div>
                            
                            <!-- Pricing Theme (New) -->
                            <div class="col-span-2 md:col-span-1 bg-gray-50 p-4 rounded-lg">
                                <x-input-label for="pricing_theme" :value="__('Pricing Page Theme')" />
                                <div class="mt-3 space-y-3">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="pricing_theme" value="modern" class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ ($settings['pricing_theme'] ?? 'modern') === 'modern' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 font-medium">Classic Modern (Light)</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="pricing_theme" value="premium" class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500" {{ ($settings['pricing_theme'] ?? 'modern') === 'premium' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-white bg-black px-2 py-0.5 rounded font-bold border border-yellow-500">Premium Dark (Gold/Silver)</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Scegli il layout della home page.</p>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
             <div class="mt-6 text-sm text-gray-500">
                <p>Nota: Questo branding influenzerà la dashboard Super Admin e il layout generale. I tenant possono personalizzare Logo e Favicon per il loro Shop Pubblico dedicato.</p>
            </div>
        </div>
    </div>
</x-app-layout>
