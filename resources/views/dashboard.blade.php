<!-- resources/views/dashboard.blade.php -->
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
                    <p>Balance: {{ $account->balance }} {{ $account->currency }}</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Recent Transactions</h3>
                    @foreach ($transactions as $transaction)
                        <div class="mb-2">
                            <p>{{ $transaction->created_at->format('Y-m-d H:i') }} -
                                {{ $transaction->amount }} {{ $transaction->currency }}
                                ({{ $transaction->from_account_id == $account->id ? 'Sent' : 'Received' }})
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Add this to resources/views/dashboard.blade.php -->
    <div class="mt-4">
        <h4 class="text-lg font-semibold mb-2">Switch Currency</h4>
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
</x-app-layout>
