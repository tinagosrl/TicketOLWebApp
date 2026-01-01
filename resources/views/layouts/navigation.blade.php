<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                     @if(auth()->check() && auth()->user()->isSuperAdmin())
                        {{-- SUPER ADMIN MENU --}}
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')">
                            {{ __('Tenants') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                            {{ __('Plans') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.coupons.index')" :active="request()->routeIs('admin.coupons.*')">
                            {{ __('Discount Codes') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.logs.impersonation')" :active="request()->routeIs('admin.logs.*')">
                            {{ __('Activity Logs') }}
                        </x-nav-link>
                         {{-- Global Settings Dropdown --}}
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                        <div>{{ __('Settings') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.branding.edit')">
                                        {{ __('Branding') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.email_templates.index')">
                                        {{ __('Email Templates') }}
                                    </x-dropdown-link>
                                     {{-- System Config --}}
                                     <x-dropdown-link :href="route('admin.system_config.edit')">
                                        {{ __('System Config') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                    @else
                        {{-- TENANT MENU --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        @if(auth()->check() && auth()->user()->role === 'tenant_admin')
                        <x-nav-link :href="route('tenant.team.index')" :active="request()->routeIs('tenant.team.*')">
                            {{ __('Team') }}
                        </x-nav-link>
                        @endif

                         <!-- Ticketing Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                        <div>{{ __('Ticketing') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('tenant.venues.index')">
                                        {{ __('Venues') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('tenant.events.index')">
                                        {{ __('Events') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('tenant.tickets.index') }}">
                                        {{ __('Orders & Tickets') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Language Switcher (Desktop) -->
                <div class="flex items-center space-x-2 mr-4">
                    <a href="{{ route('language.switch', 'it') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 {{ app()->getLocale() == 'it' ? 'underline decoration-indigo-500 decoration-2' : '' }}">IT</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('language.switch', 'en') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 {{ app()->getLocale() == 'en' ? 'underline decoration-indigo-500 decoration-2' : '' }}">EN</a>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        @if(auth()->check() && !auth()->user()->isSuperAdmin())
                        <x-dropdown-link :href="route('tenant.shop.settings.edit')">
                            {{ __('Shop Settings') }}
                        </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
             @if(auth()->check() && auth()->user()->isSuperAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')">
                    {{ __('Tenants') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                    {{ __('Plans') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.branding.edit')" :active="request()->routeIs('admin.branding.*')">
                    {{ __('Branding') }}
                </x-responsive-nav-link>
             @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                 @if(auth()->user()->role === 'tenant_admin')
                    <x-responsive-nav-link :href="route('tenant.team.index')" :active="request()->routeIs('tenant.team.*')">
                        {{ __('Team') }}
                    </x-responsive-nav-link>
                @endif
                 <x-responsive-nav-link :href="route('tenant.tickets.index')" :active="request()->routeIs('tenant.tickets.index')">
                    {{ __('Orders & Tickets') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Language Switcher (Mobile) -->
                <div class="px-4 py-2 flex gap-4">
                     <a href="{{ route('language.switch', 'it') }}" class="text-base font-medium text-gray-600 {{ app()->getLocale() == 'it' ? 'text-indigo-600' : '' }}">Italiano</a>
                     <a href="{{ route('language.switch', 'en') }}" class="text-base font-medium text-gray-600 {{ app()->getLocale() == 'en' ? 'text-indigo-600' : '' }}">English</a>
                </div>
                
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                 @if(auth()->check() && !auth()->user()->isSuperAdmin())
                 <x-responsive-nav-link :href="route('tenant.shop.settings.edit')">
                    {{ __('Shop Settings') }}
                </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
