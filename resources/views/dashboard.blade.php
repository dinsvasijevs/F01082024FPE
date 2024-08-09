<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                    <p>IBAN: {{ $account->iban }}</p>
                    <p>Balance: {{ number_format($account->balance, 2) }} {{ $account->currency }}</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Recent Transactions</h3>
                    @foreach ($transactions as $transaction)
                        <div class="mb-2">
                            <p>{{ $transaction->created_at->format('Y-m-d H:i') }} -
                                {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                ({{ $transaction->from_account_id == $account->id ? 'Sent' : 'Received' }})
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Transfer Money</h3>
                    <form action="{{ route('transfer') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="to_account_id" class="block text-sm font-medium text-gray-700">To Account ID:</label>
                            <input type="text" name="to_account_id" id="to_account_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                            <input type="number" name="amount" id="amount" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency:</label>
                            <select name="currency" id="currency" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach (\App\Models\Currency::all() as $currency)
                                    <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Transfer
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Switch Currency</h3>
                    <form action="{{ route('switch-currency') }}" method="POST">
                        @csrf
                        <select name="currency" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach (\App\Models\Currency::all() as $currency)
                                <option value="{{ $currency->code }}" {{ $account->currency == $currency->code ? 'selected' : '' }}>
                                    {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Switch
                        </button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
