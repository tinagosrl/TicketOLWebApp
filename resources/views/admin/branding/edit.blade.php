<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Branding') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- System Name -->
                        <div class="mb-6">
                            <x-input-label for="system_name" :value="__('System Name')" />
                            <x-text-input id="system_name" class="block mt-1 w-full" type="text" name="system_name" :value="$settings['system_name'] ?? config('app.name')" />
                        </div>

                        <!-- System Logo -->
                        <div class="mb-6">
                            <x-input-label for="system_logo" :value="__('System Logo')" />
                            <div class="mt-2 flex items-center gap-4">
                                @if(isset($settings['system_logo']))
                                    <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                        <img src="{{ Storage::url($settings['system_logo']) }}" alt="Logo" class="max-w-full max-h-full">
                                    </div>
                                @endif
                                <input type="file" id="system_logo" name="system_logo" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100" accept="image/*" />
                            </div>
                        </div>

                        <!-- System Favicon -->
                        <div class="mb-6">
                            <x-input-label for="system_favicon" :value="__('System Favicon (ICO/PNG)')" />
                            <div class="mt-2 flex items-center gap-4">
                                @if(isset($settings['system_favicon']))
                                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                        <img src="{{ Storage::url($settings['system_favicon']) }}" alt="Favicon" class="max-w-full max-h-full">
                                    </div>
                                @endif
                                <input type="file" id="system_favicon" name="system_favicon" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100" accept="image/*" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Update Branding') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
