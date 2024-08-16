<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Your Investments</h3>
                    <table class="min-w-full">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-500 uppercase tracking-wider">Symbol</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-500 uppercase tracking-wider">Current Value</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-500 uppercase tracking-wider">Return</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($investments as $investment)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $investment['name'] }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $investment['symbol'] }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $investment['amount'] }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">${{ number_format($investment['current_value'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500 {{ $investment['return'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($investment['return'], 2) }}%
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
