<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Complete your subscription with a secure payment.') }}
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            {{ __('Payment Details') }}
        </h2>
        
        <div class="mb-6">
            <p class="text-gray-700 font-medium">{{ __('Plan') }}: <span class="font-bold text-indigo-600">{{ $plan_name }}</span></p>
            <p class="text-gray-700 font-medium">{{ __('Amount') }}: <span class="font-bold text-indigo-600">€{{ $amount }}</span></p>
        </div>

        <!-- Fake Card Form -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('Card Holder Name') }}</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="John Doe" value="Test User">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('Card Number') }}</label>
                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0000 0000 0000 0000" value="4242 4242 4242 4242">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                     <label class="block text-sm font-medium text-gray-700">{{ __('Expiration Date') }}</label>
                     <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="MM/YY" value="12/30">
                </div>
                <div>
                     <label class="block text-sm font-medium text-gray-700">{{ __('CVC') }}</label>
                     <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="123" value="123">
                </div>
            </div>
        </div>

        <div class="mt-6">
            <form method="POST" action="{{ route('payment.process') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Pay €') }}{{ $amount }}
                </button>
            </form>
            <p class="mt-2 text-xs text-center text-gray-400 uppercase">{{ __('Secure Payment (Simulated)') }}</p>
        </div>
    </div>
</x-guest-layout>