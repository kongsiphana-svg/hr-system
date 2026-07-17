@extends('layouts.dashboard')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0">Employees Registry</h3>
    <small class="text-muted">View and manage staff accounts</small>
</div>
<div class="card border-0 shadow-sm p-4 bg-white">
    <table class="table align-middle">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Registered On</th></tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td class="fw-bold">{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection