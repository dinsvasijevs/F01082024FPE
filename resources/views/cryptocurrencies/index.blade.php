<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cryptocurrencies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-semibold mb-6">Top 10 Cryptocurrencies</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($cryptocurrencies as $crypto)
                            <div class="bg-gray-100 rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-xl font-semibold">{{ $crypto['name'] }}</h4>
                                    <span class="text-sm font-medium px-2 py-1 rounded {{ $crypto['quote']['USD']['percent_change_24h'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ number_format($crypto['quote']['USD']['percent_change_24h'], 2) }}%
                                    </span>
                                </div>
                                <p class="text-3xl font-bold mb-4">${{ number_format($crypto['quote']['USD']['price'], 2) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">{{ $crypto['symbol'] }}</span>
                                    <div>
                                        <button onclick="openBuyModal('{{ $crypto['symbol'] }}', {{ $crypto['quote']['USD']['price'] }})" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2">
                                            Buy
                                        </button>
                                        <button onclick="openSellModal('{{ $crypto['symbol'] }}', {{ $crypto['quote']['USD']['price'] }})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                            Sell
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buy Modal -->
    <div id="buyModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="buyModalTitle">Buy Cryptocurrency</h3>
                    <div class="mt-2">
                        <form id="buyForm" action="{{ route('crypto.buy') }}" method="POST">
                            @csrf
                            <input type="hidden" id="buySymbol" name="symbol">
                            <div class="mb-4">
                                <label for="buyAmount" class="block text-gray-700 text-sm font-bold mb-2">Amount to buy:</label>
                                <input type="number" id="buyAmount" name="amount" step="0.00000001" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <p id="buyTotal" class="mb-4"></p>
                        </form>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="buyForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Buy
                    </button>
                    <button type="button" onclick="closeBuyModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sell Modal -->
    <div id="sellModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="sellModalTitle">Sell Cryptocurrency</h3>
                    <div class="mt-2">
                        <form id="sellForm" action="{{ route('crypto.sell') }}" method="POST">
                            @csrf
                            <input type="hidden" id="sellSymbol" name="symbol">
                            <div class="mb-4">
                                <label for="sellAmount" class="block text-gray-700 text-sm font-bold mb-2">Amount to sell:</label>
                                <input type="number" id="sellAmount" name="amount" step="0.00000001" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <p id="sellTotal" class="mb-4"></p>
                        </form>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="sellForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Sell
                    </button>
                    <button type="button" onclick="closeSellModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
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
