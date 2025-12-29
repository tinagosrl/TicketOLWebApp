<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Complete your registration by paying for your chosen plan.') }}
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <h2 class="text-xl font-bold mb-2">{{ $plan->name }} Plan</h2>
        <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
        
        <div class="text-3xl font-bold text-indigo-600 mb-6">
            €{{ number_format($plan->price_monthly, 2) }} <span class="text-sm text-gray-400 font-normal">/ month</span>
        </div>

        <div class="border-t pt-4">
            <h3 class="font-medium mb-2">Order Summary</h3>
            <div class="flex justify-between mb-1">
                <span>Subscription (Monthly)</span>
                <span>€{{ number_format($plan->price_monthly, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg mt-2 border-t pt-2">
                <span>Total Due</span>
                <span>€{{ number_format($plan->price_monthly, 2) }}</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('billing.process') }}" class="mt-6">
        @csrf
        <!-- Fake Payment Button -->
        <x-primary-button class="w-full justify-center py-3 text-lg">
            {{ __('Pay Now (Fake Card)') }}
        </x-primary-button>
        
        <p class="text-center text-xs text-gray-400 mt-4">
            {{ __('This is a simulation. No actual money will be charged.') }}
        </p>
    </form>
</x-guest-layout>
