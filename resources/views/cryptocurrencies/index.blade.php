<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cryptocurrency Market') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($cryptocurrencies as $crypto)
                            <div class="bg-white shadow-md rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-2">{{ $crypto['name'] }} ({{ $crypto['symbol'] }})</h3>
                                <p class="mb-2">
                                    <strong>Price:</strong> ${{ number_format($crypto['quote']['USD']['price'], 2) }}
                                </p>
                                <p class="mb-4">
                                    <strong>24h Change:</strong>
                                    <span class="{{ $crypto['quote']['USD']['percent_change_24h'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($crypto['quote']['USD']['percent_change_24h'], 2) }}%
                                    </span>
                                </p>
                                <form action="{{ route('cryptocurrencies.buy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="symbol" value="{{ $crypto['symbol'] }}">
                                    <div class="mb-4">
                                        <label for="amount_{{ $crypto['symbol'] }}" class="block text-sm font-medium text-gray-700">Amount to buy:</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="number" class="form-input block w-full pr-12 sm:text-sm sm:leading-5" id="amount_{{ $crypto['symbol'] }}" name="amount" step="0.00000001" min="0.00000001" required>
                                            <div class="absolute inset-y-0 right-0 flex items-center">
                                                <span class="text-gray-500 sm:text-sm sm:leading-5 pr-2">
                                                    {{ $crypto['symbol'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Buy
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
