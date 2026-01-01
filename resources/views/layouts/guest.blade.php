<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if($seoDesc = \App\Models\GlobalSetting::get('seo_meta_description'))
            <meta name="description" content="{{ $seoDesc }}">
        @endif
        @if($seoKeys = \App\Models\GlobalSetting::get('seo_keywords'))
            <meta name="keywords" content="{{ $seoKeys }}">
        @endif
        @if($llmContext = \App\Models\GlobalSetting::get('llm_context'))
            <!-- LLM_CONTEXT: {{ $llmContext }} -->
            <meta name="ai-directives" content="{{ $llmContext }}">
        @endif


                <title>{{ \App\Models\GlobalSetting::get('app_name', config('app.name', 'TicketOL')) }}</title>
        @if($favicon = \App\Models\GlobalSetting::get('favicon_path'))
            <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
        @else
             <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-auto h-24 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full {{ $width ?? 'sm:max-w-md' }} mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
