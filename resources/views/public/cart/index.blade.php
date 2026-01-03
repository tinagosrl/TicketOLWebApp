<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-900">{{ __('Your Cart') }}</h1>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(count($items) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Event') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Ticket Type') }}</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['event_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $item['quantity'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">€ {{ number_format($item['price'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">€ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('public.cart.destroy', ['domain' => request()->route('domain'), 'id' => $item['id']]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Remove') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900">{{ __('Grand Total') }}:</td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">€ {{ number_format($total, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-8 flex justify-between items-center">
                            <a href="{{ route('public.shop.index', ['domain' => request()->route('domain')]) }}" class="text-indigo-600 hover:text-indigo-900">&larr; {{ __('Continue Shopping') }}</a>
                            <form action="{{ route('public.shop.checkout.index', ['domain' => request()->route('domain')]) }}" method="GET" class="w-full md:w-auto">
                                <!-- Checkout button as link -->
                                 <a href="{{ route('public.shop.checkout.index', ['domain' => request()->route('domain')]) }}" class="bg-gray-800 text-white px-6 py-3 rounded-md hover:bg-gray-700 ml-4">
                                    {{ __('Proceed to Checkout') }}
                                </a>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg mb-4">{{ __('Your cart is empty.') }}</p>
                            <a href="{{ route('public.shop.index', ['domain' => request()->route('domain')]) }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
                                {{ __('Browse Events') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
