<h1>Transactions</h1>

<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($transactions as $transaction)
        <tr>
            <td>{{ $transaction->date }}</td>
            <td>{{ $transaction->description }}</td>
            <td>{{ $transaction->amount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<a href="{{ route('dashboard') }}">Back to Dashboard</a>
