<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Dynamic Title: Tenant Name or App Name --}}
        @php
            $tenant = app()->bound('tenant') ? app('tenant') : null;
            $appName = \App\Models\GlobalSetting::get('app_name', config('app.name', 'TicketOL'));
            $title = $tenant ? $tenant->name : $appName;
        @endphp
        <title>{{ $title }}</title>

        {{-- Dynamic Favicon: Tenant or Global --}}
        @php
            $favicon = null;
            if ($tenant && $tenant->favicon) {
                $favicon = asset('storage/' . $tenant->favicon);
            } else {
                $favicon = \App\Models\GlobalSetting::get('favicon_path');
                // If global setting is a path, asset() it? Or is it full URL?
                // GlobalSetting logic usually stores path.
                // Assuming $favicon here is direct URL or needs asset()
                // Check existing usage: <link ... href="{{ $favicon }}">
            }
        @endphp

        @if($favicon)
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
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            {{-- Optional Header/Nav for Shop --}}
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="flex items-center">
                         @if($tenant && $tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="h-10 w-auto">
                         @else
                            <h1 class="text-2xl font-bold text-gray-900">{{ $tenant->name ?? $appName }}</h1>
                         @endif
                    </div>
                    
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-500">
                            <a href="{{ route('language.switch', 'it') }}" class="{{ app()->getLocale() == 'it' ? 'font-bold text-indigo-600' : 'hover:text-gray-700' }}">IT</a>
                            |
                            <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'font-bold text-indigo-600' : 'hover:text-gray-700' }}">EN</a>
                        </div>
                    </div>
    
                </div>
            </header>

            <main class="flex-grow">
                {{ $slot }}
            </main>
            
            <footer class="bg-gray-800 text-white py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                    &copy; {{ date('Y') }} {{ $tenant->name ?? $appName }}. Powered by TicketOL.
                </div>
            </footer>
        </div>
    </body>
</html>
