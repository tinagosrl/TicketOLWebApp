<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Template') }}: {{ $template->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.email_templates.update', $template) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- English -->
                            <div class="space-y-4">
                                <h3 class="font-semibold text-lg">English (Default)</h3>
                                <div>
                                    <x-input-label for="subject_en" :value="__('Subject')" />
                                    <x-text-input id="subject_en" class="block mt-1 w-full" type="text" name="subject_en" :value="$template->subject_en" required />
                                </div>
                                <div>
                                    <x-input-label for="body_en" :value="__('Body (HTML Supported)')" />
                                    <textarea id="body_en" name="body_en" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-64" required>{{ $template->body_en }}</textarea>
                                </div>
                            </div>

                            <!-- Italian -->
                            <div class="space-y-4">
                                <h3 class="font-semibold text-lg">Italian</h3>
                                <div>
                                    <x-input-label for="subject_it" :value="__('Subject')" />
                                    <x-text-input id="subject_it" class="block mt-1 w-full" type="text" name="subject_it" :value="$template->subject_it" />
                                </div>
                                <div>
                                    <x-input-label for="body_it" :value="__('Body (HTML Supported)')" />
                                    <textarea id="body_it" name="body_it" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-64">{{ $template->body_it }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
