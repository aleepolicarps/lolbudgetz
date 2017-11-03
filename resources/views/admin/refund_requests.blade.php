@extends('layouts.admin')

@section('title', 'Refund Requests')

@section('body_content')
    <div class="container">
        <h1>Refund Requests</h1>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date submitted</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($refund_requests as $refund_request)
                    @php($user = $refund_request->user()->first())
                    <tr @if($refund_request->is_pending())class="success"@endif>
                        <td>{{ $refund_request->created_at }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $refund_request->details }}</td>
                        <td>
                            @if($refund_request->is_pending())
                                <button class="btn btn-default">Resolve</button>
                                <button class="btn btn-default">Decline</button>
                                <button class="btn btn-default">Delete</button>
                            @else
                                [ {{ $refund_request->get_status_as_string() }} ]
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No Refund Requests.</td>
                    <tr>
                @endforelse
            </tbody>
        <table>
    </div>
@endsection
