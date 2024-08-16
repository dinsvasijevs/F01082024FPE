<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cryptocurrencies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-semibold mb-6">Top 10 Cryptocurrencies</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($cryptocurrencies as $crypto)
                            <div class="bg-gray-100 rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-xl font-semibold">{{ $crypto['name'] }} ({{ $crypto['symbol'] }})</h4>
                                    <span class="text-sm font-medium px-2 py-1 rounded {{ $crypto['quote']['USD']['percent_change_24h'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ number_format($crypto['quote']['USD']['percent_change_24h'], 2) }}%
                                    </span>
                                </div>
                                <p class="text-3xl font-bold mb-4">${{ number_format($crypto['quote']['USD']['price'], 2) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">{{ $crypto['symbol'] }}</span>
                                    <button onclick="openBuyModal('{{ $crypto['symbol'] }}', {{ $crypto['quote']['USD']['price'] }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                        Buy
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBuyModal(symbol, price) {
            document.getElementById('buySymbol').value = symbol;
            document.getElementById('buyModal').classList.remove('hidden');
            document.getElementById('buyModalTitle').textContent = `Buy ${symbol}`;
            document.getElementById('buyTotal').textContent = `Current price: $${price.toFixed(2)}`;
        }

        function closeBuyModal() {
            document.getElementById('buyModal').classList.add('hidden');
        }

        function openSellModal(symbol, price) {
            document.getElementById('sellSymbol').value = symbol;
            document.getElementById('sellModal').classList.remove('hidden');
            document.getElementById('sellModalTitle').textContent = `Sell ${symbol}`;
            document.getElementById('sellTotal').textContent = `Current price: $${price.toFixed(2)}`;
        }

        function closeSellModal() {
            document.getElementById('sellModal').classList.add('hidden');
        }

        // Add event listeners for amount inputs to update total in real-time
        document.getElementById('buyAmount').addEventListener('input', updateBuyTotal);
        document.getElementById('sellAmount').addEventListener('input', updateSellTotal);

        function updateBuyTotal() {
            const amount = document.getElementById('buyAmount').value;
            const price = parseFloat(document.getElementById('buyTotal').textContent.split('$')[1]);
            document.getElementById('buyTotal').textContent = `Total: $${(amount * price).toFixed(2)}`;
        }

        function updateSellTotal() {
            const amount = document.getElementById('sellAmount').value;
            const price = parseFloat(document.getElementById('sellTotal').textContent.split('$')[1]);
            document.getElementById('sellTotal').textContent = `Total: $${(amount * price).toFixed(2)}`;
        }
    </script>
</x-app-layout>
