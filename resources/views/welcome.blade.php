<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @php
            $theme = \App\Models\GlobalSetting::get('pricing_theme', 'modern');
        @endphp

        @if($theme === 'premium')
            @include('welcome_premium')
        @else
            @include('welcome_modern')
        @endif
    </body>
</html>
