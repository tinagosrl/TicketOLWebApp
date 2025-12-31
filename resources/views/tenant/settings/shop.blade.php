<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shop Customization') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">


                <form method="POST" action="{{ route('tenant.shop.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Logo -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="logo">
                            {{ __('Logo (Optional)') }}
                        </label>
                        @if($tenant->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Current Logo" class="h-16 w-auto object-contain border rounded p-1">
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-xs text-gray-500 mt-1">{{ __('Recommended size: 200x50px. Max 2MB.') }}</p>
                    </div>

                    <!-- Favicon -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="favicon">
                            {{ __('Favicon (Optional)') }}
                        </label>
                         @if($tenant->favicon)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $tenant->favicon) }}" alt="Current Favicon" class="h-8 w-8 object-contain border rounded p-1">
                            </div>
                        @endif
                        <input type="file" name="favicon" id="favicon" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-xs text-gray-500 mt-1">{{ __('Recommended size: 32x32px. Max 1MB.') }}</p>
                    </div>

                    <!-- Colors -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="primary_color">
                                {{ __('Primary Color') }}
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="primary_color" id="primary_color" value="{{ $tenant->primary_color ?? '#4f46e5' }}" class="h-10 w-10 p-0 border-0 rounded cursor-pointer">
                                <input type="text" name="primary_color_text" value="{{ $tenant->primary_color ?? '#4f46e5' }}" 
                                       onchange="document.getElementById('primary_color').value = this.value"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="secondary_color">
                                {{ __('Secondary Color') }}
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="secondary_color" id="secondary_color" value="{{ $tenant->secondary_color ?? '#1f2937' }}" class="h-10 w-10 p-0 border-0 rounded cursor-pointer">
                                <input type="text" name="secondary_color_text" value="{{ $tenant->secondary_color ?? '#1f2937' }}" 
                                       onchange="document.getElementById('secondary_color').value = this.value"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
