<h1>Account Dashboard</h1>

<p>Name: {{ auth()->user()->name }}</p>
<p>Email: {{ auth()->user()->email }}</p>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<a href="{{ route('update-account') }}">Update Account Information</a>
<a href="{{ route('transactions') }}">View Transactions</a>

<form method="POST" action="{{ route('upload-document') }}" enctype="multipart/form-data">
    @csrf

    <input type="file" name="document" required>
    <button type="submit">Upload Document</button>
</form>
