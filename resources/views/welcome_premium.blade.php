<div class="bg-gray-50 min-h-screen font-sans text-gray-900">
    <!-- Header (Light - Same as Standard) -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                 @if($logo = \App\Models\GlobalSetting::get('logo_path'))
                    <img src="{{ $logo }}" alt="App Logo" class="h-12 w-auto">
                @else
                    <x-application-logo class="h-10 w-auto fill-current text-indigo-600" />
                @endif
            </div>
            <nav class="flex gap-4 items-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline mr-4">{{ __('Dashboard') }}</a>
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

    <main class="flex-grow py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 sm:text-6xl sm:tracking-tight lg:text-7xl mb-4">
                {{ __('Choose Your Plan') }}
            </h1>
            <p class="mt-5 max-w-2xl mx-auto text-xl text-gray-500">
                {{ __('Exclusive solutions for premium events and prestigious venues.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4 lg:gap-10 mt-12">
            @foreach($plans as $index => $plan)
                @php
                    // Card Styles (Premium Gradients)
                    $styles = [
                        'bronze' => 'bg-gradient-to-b from-yellow-700 to-yellow-900 border-yellow-800 text-yellow-100', // Basic
                        'silver' => 'bg-gradient-to-b from-gray-300 to-gray-500 border-gray-400 text-gray-900', // Silver
                        'gold'   => 'bg-gradient-to-b from-yellow-300 via-yellow-500 to-yellow-600 border-yellow-400 text-yellow-900 shadow-[0_0_20px_rgba(234,179,8,0.3)]', // Gold
                        'platinum' => 'bg-gradient-to-b from-purple-300 to-purple-500 border-purple-400 text-purple-900', // Special
                    ];

                    $styleKey = 'bronze';
                    $lowerName = strtolower($plan->getTranslation('name'));
                    if(str_contains($lowerName, 'gold') || str_contains($lowerName, 'oro')) $styleKey = 'gold';
                    elseif(str_contains($lowerName, 'silver') || str_contains($lowerName, 'argento')) $styleKey = 'silver';
                    elseif(str_contains($lowerName, 'platinum') || str_contains($lowerName, 'special') || $plan->is_recommended) $styleKey = 'platinum';
                    
                    if ($styleKey === 'bronze' && $index > 0) {
                         $keys = array_keys($styles);
                         $styleKey = $keys[$index % count($keys)];
                    }

                    $currentStyle = $styles[$styleKey];
                    $textColor = ($styleKey === 'bronze') ? 'text-yellow-100' : 'text-gray-900';
                    
                    // Button: Black with White Text
                    $btnStyle = 'bg-black text-white hover:bg-gray-800 border border-transparent';

                @endphp

                <!-- Card Container - Overflow Visible for Star -->
                <div class="relative rounded-xl p-1 {{ $currentStyle }} transform hover:scale-105 transition-transform duration-300 shadow-2xl flex flex-col mt-6">
                    
                    <!-- Star Icon (Half In / Half Out) -->
                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 z-20">
                        <div class="bg-white p-2 rounded-full shadow-lg"> <!-- Optional white circle backing if needed, or remove -->
                             <svg class="w-16 h-16 text-yellow-400 drop-shadow-md" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>

                    <!-- Ribbon -->
                    @if($plan->is_recommended)
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 z-10">
                            <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold bg-white text-black border-2 border-black uppercase tracking-wide shadow-lg transform rotate-12">
                                {{ __('Recommended') }}
                            </span>
                        </div>
                    @endif

                    <div class="h-full bg-opacity-90 rounded-lg p-6 flex flex-col relative overflow-hidden flex-grow pt-10"> <!-- Added pt-10 for Star space -->
                        
                        <div class="z-10 text-center flex-grow">
                            <h3 class="text-2xl font-black uppercase tracking-wider mb-2 {{ $textColor }}">{{ $plan->getTranslation('name') }}</h3>
                            
                            <div class="mt-4 mb-6">
                                <span class="text-4xl font-extrabold {{ $textColor }}">€{{ number_format($plan->price_monthly, 0) }}</span>
                                <span class="text-sm font-medium opacity-75 {{ $textColor }}">/{{ __('mo') }}</span>
                            </div>

                            <p class="text-sm opacity-90 mb-6 font-medium {{ $textColor }}">
                                {{ $plan->getTranslation('description') }}
                            </p>

                            <!-- Features -->
                            @if($plan->features_html)
                                <div class="text-left text-sm space-y-2 {{ $textColor }} opacity-90 font-medium">
                                    <ul class="list-disc pl-5">
                                        {!! $plan->getTranslation('features_html') !!}
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="mt-8 z-10 w-full">
                            <a href="{{ route('plan.select', ['planId' => $plan->id]) }}" class="block w-full py-3 px-6 text-center rounded-lg shadow-md font-bold uppercase tracking-wider transition duration-200 {{ $btnStyle }}">
                                {{ __('Select Plan') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer (Light) -->
    <footer class="bg-white border-t border-gray-200 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} TicketOL. All rights reserved.
            </p>
        </div>
    </footer>
</div>
