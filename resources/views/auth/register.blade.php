<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Company/Tenant Info -->
        <h3 class="text-lg font-medium text-gray-900 mb-4 divider">{{ __('Organization Details') }}</h3>

        <!-- Tenant Name -->
        <div>
            <x-input-label for="tenant_name" :value="__('Organization Name')" />
            <x-text-input id="tenant_name" class="block mt-1 w-full" type="text" name="tenant_name" :value="old('tenant_name')" required autofocus />
            <x-input-error :messages="$errors->get('tenant_name')" class="mt-2" />
        </div>

        <!-- Domain -->
        <div class="mt-4">
            <x-input-label for="domain" :value="__('Custom Domain (e.g. art-gallery)')" />
            <x-text-input id="domain" class="block mt-1 w-full" type="text" name="domain" :value="old('domain')" required />
            <p class="text-xs text-gray-500 mt-1">{{ __('Your shop will be at: domain.ticketol.com') }}</p>
            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
        </div>

         <!-- Plan Selection -->
         <div class="mt-4">
            <x-input-label for="plan_id" :value="__('Select Subscription Plan')" />
            <select id="plan_id" name="plan_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} - €{{ number_format($plan->price_monthly, 2) }}/mo
                        ({{ $plan->ticket_limit > 0 ? $plan->ticket_limit . ' tickets' : 'Unlimited' }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('plan_id')" class="mt-2" />
        </div>

        <!-- Discount Code -->
        <div class="mt-4">
            <x-input-label for="discount_code" :value="__('Discount Code (Optional)')" />
            <x-text-input id="discount_code" class="block mt-1 w-full" type="text" name="discount_code" :value="old('discount_code')" />
            <x-input-error :messages="$errors->get('discount_code')" class="mt-2" />
        </div>

        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4 divider">{{ __('Account Details') }}</h3>

        <div class="grid grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register & Pay') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
