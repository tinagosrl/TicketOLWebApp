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
    <body class="bg-gray-50 font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="flex items-center">
                        <x-application-logo class="h-10 w-auto fill-current text-indigo-600" />
                        
                    </div>
                    <nav class="flex gap-4 items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline mr-4">{{ __('Dashboard') }}</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">{{ __('Log in') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ __('Register') }}</a>
                            @endif
                        @endauth
                        
                        <!-- Language Switcher -->
                        <div class="flex items-center space-x-2 border-l pl-4 ml-2 border-gray-300">
                             <a href="{{ route('language.switch', 'it') }}" class="text-sm font-medium {{ app()->getLocale() == 'it' ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-gray-900' }}">IT</a>
                             <span class="text-gray-300">|</span>
                             <a href="{{ route('language.switch', 'en') }}" class="text-sm font-medium {{ app()->getLocale() == 'en' ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-gray-900' }}">EN</a>
                        </div>
                    </nav>
                </div>
            </header>

            <!-- Hero / Pricing -->
            <main class="flex-grow">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                            {{ __('Choose Your Plan') }}
                        </h1>
                        <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                            {{ __('Flexible pricing for museums, events, and cultural venues.') }}
                        </p>
                    </div>

                    <!-- Pricing Table -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                        @foreach($plans as $plan)
                            <div class="relative bg-white rounded-2xl shadow-xl flex flex-col border border-gray-200 hover:border-indigo-500 transition-colors duration-300 {{ $plan->is_recommended ? 'ring-2 ring-orange-500' : '' }}">
                                
                                @if($plan->is_recommended)
                                    <div class="absolute top-0 right-0 -mt-3 -mr-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-500 text-white uppercase tracking-wide shadow-sm">
                                            {{ __('Recommended') }}
                                        </span>
                                    </div>
                                @endif

                                <div class="p-6 flex-grow">
                                    <h3 class="text-xl font-bold text-gray-900 text-center">{{ $plan->getTranslation('name') }}</h3>
                                    <div class="mt-4 text-center">
                                        <span class="text-4xl font-extrabold text-gray-900">€{{ number_format($plan->price_monthly, 0) }}</span>
                                        <span class="text-base font-medium text-gray-500">/{{ __('mo') }}</span>
                                    </div>
                                    <p class="mt-2 text-center text-sm text-gray-500">
                                        {{ __('Billed monthly') }}
                                    </p>

                                    <!-- Description -->
                                    <p class="mt-6 text-gray-600 text-sm text-center">
                                        {{ $plan->getTranslation('description') }}
                                    </p>

                                    <!-- Features HTML List -->
                                    @if($plan->features_html)
                                        <ul class="mt-6 space-y-4 text-sm text-gray-600 list-disc pl-5">
                                            {!! $plan->getTranslation('features_html') !!}
                                        </ul>
                                    @endif
                                </div>

                                <div class="p-6 bg-gray-50 rounded-b-2xl border-t border-gray-100">
                                    <a href="{{ route('plan.select', ['planId' => $plan->id]) }}" class="block w-full py-3 px-6 text-center rounded-md shadow bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition duration-200">
                                        {{ __('Select Plan') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        &copy; {{ date('Y') }} TicketOL. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
