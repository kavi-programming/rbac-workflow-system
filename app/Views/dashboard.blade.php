@extends('layouts.app')

@section('content')

<h2>My Requests</h2>

<form method="GET" action="{{ route('dashboard') }}">
    <select name="status" onchange="this.form.submit()">
        <option value="">-- Select Status --</option>
        <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
    </select>
</form>

<table>
    ...
</table>

{{ $requests->appends(request()->query())->links() }}

@endsection
