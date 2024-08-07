<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exchange Rates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Current Exchange Rate</h3>
                    <div id="exchange-rate">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/exchange-rate?from=USD&to=EUR')
                .then(response => response.json())
                .then(data => {
                    if (data.rate) {
                        document.getElementById('exchange-rate').textContent = `1 USD = ${data.rate} EUR`;
                    } else {
                        document.getElementById('exchange-rate').textContent = 'Failed to load exchange rate';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('exchange-rate').textContent = 'Failed to load exchange rate';
                });
        });
    </script>
</x-app-layout>
