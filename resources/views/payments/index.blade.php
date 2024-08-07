<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Your Payments</h3>
                    @if($payments->count() > 0)
                        <ul>
                            @foreach($payments as $payment)
                                <li class="mb-2">
                                    Amount: {{ $payment->amount }} {{ $payment->currency }} -
                                    Status: {{ $payment->status }} -
                                    Date: {{ $payment->created_at->format('Y-m-d H:i') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No payments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
