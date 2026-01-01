<x-guest-layout width="sm:max-w-2xl">
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
            <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                <input type="text" name="domain" id="domain" class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="my-shop" value="{{ old('domain') }}" required>
                <span class="flex select-none items-center pr-3 pl-1 text-gray-500 sm:text-sm">.ticketol.eu</span>
            </div>
            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
        </div>

         <!-- Plan Selection -->
         <div class="mt-4">
            <x-input-label for="plan_id" :value="__('Select Subscription Plan')" />
            <select id="plan_id" name="plan_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ (old('plan_id') ?? request()->get('plan_id')) == $plan->id ? 'selected' : '' }}>
                        {{ $plan->getTranslation('name') }} - €{{ number_format($plan->price_monthly, 2) }}/mo
                        ({{ $plan->ticket_limit > 0 ? $plan->ticket_limit . ' tickets' : 'Unlimited' }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('plan_id')" class="mt-2" />
        </div>

        <!-- Billing Address -->
        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4 divider">{{ __('Billing Address') }}</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Address -->
            <div class="md:col-span-2">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- City -->
            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <!-- Province & Zip -->
            <div class="flex gap-4">
                <div class="w-1/2">
                    <x-input-label for="province" :value="__('Prov (e.g. MI)')" />
                    <x-text-input id="province" class="block mt-1 w-full uppercase" type="text" name="province" :value="old('province')" required maxlength="2" />
                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                </div>
                <div class="w-1/2">
                     <x-input-label for="zip_code" :value="__('ZIP Code')" />
                    <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code')" required />
                    <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                </div>
            </div>

            <!-- VAT Number -->
            <div>
                <x-input-label for="vat_number" :value="__('VAT Number (P.IVA)')" />
                <x-text-input id="vat_number" class="block mt-1 w-full" type="text" name="vat_number" :value="old('vat_number')" required />
                <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
            </div>

             <!-- SDI & PEC -->
             <div class="flex gap-4">
                <div class="w-1/3">
                    <x-input-label for="sdi_code" :value="__('SDI Code')" />
                    <x-text-input id="sdi_code" class="block mt-1 w-full uppercase" type="text" name="sdi_code" :value="old('sdi_code')" required maxlength="7" />
                    <x-input-error :messages="$errors->get('sdi_code')" class="mt-2" />
                </div>
                <div class="w-2/3">
                     <x-input-label for="pec" :value="__('PEC Email')" />
                    <x-text-input id="pec" class="block mt-1 w-full" type="email" name="pec" :value="old('pec')" required />
                    <x-input-error :messages="$errors->get('pec')" class="mt-2" />
                </div>
            </div>
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
            
            <!-- Password Rules Hint -->
            <ul class="text-xs text-gray-500 mt-2 list-disc list-inside space-y-1">
                <li>{{ __('At least 8 characters') }}</li>
                <li>{{ __('Must contain at least one uppercase letter') }}</li>
                <li>{{ __('Must contain at least one number') }}</li>
                <li>{{ __('Must contain at least one symbol (@$!%*#?&)') }}</li>
            </ul>
            
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Discount Code -->
        <div class="mt-4">
            <x-input-label for="discount_code" :value="__('Discount Code (Optional)')" />
            <x-text-input id="discount_code" class="block mt-1 w-full" type="text" name="discount_code" :value="old('discount_code')" />
            <x-input-error :messages="$errors->get('discount_code')" class="mt-2" />
        </div>

        <!-- Security Check (Honeypot + Math) -->
        <div class="mt-8 p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-inner">
            <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center uppercase tracking-wide">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                {{ __('Security Check') }}
            </h4>
            
            <!-- Honeypot (Hidden) -->
            <div style="display: none!important; opacity: 0; position: absolute; left: -9999px;">
                <label for="website_url">Website</label>
                <input id="website_url" type="text" name="website_url" value="" tabindex="-1" autocomplete="off" />
            </div>

            <!-- Math Captcha -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <label for="math_answer" class="text-sm text-gray-600">{{ __('Please prove you are human:') }}</label>
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm font-mono text-lg font-bold text-indigo-600 tracking-wider">
                        {{ $num1 ?? '?' }} + {{ $num2 ?? '?' }}
                    </div>
                    <span class="text-gray-400">=</span>
                    <x-text-input id="math_answer" class="block w-20 text-center font-bold text-lg p-2" type="tel" name="math_answer" required placeholder="?" />
                </div>
            </div>
            <x-input-error :messages="$errors->get('math_answer')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-8">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="ms-4 inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                {{ __('Register & Pay') }}
            </button>
        </div>
    </form>
</x-guest-layout>
