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
                                <button class="btn btn-default" onclick="window.changeStatus({{$refund_request->id}}, 'resolved')">Resolve</button>
                                <button class="btn btn-default" onclick="window.changeStatus({{$refund_request->id}}, 'declined')">Decline</button>
                                <button class="btn btn-default" onclick="window.changeStatus({{$refund_request->id}}, 'deleted')">Delete</button>
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

@section('custom_script')
    <script>
        window.changeStatus = function(refundRequestId, status) {
            $.ajax({
                url: BASE_URL + '/api/refund-request-status',
                method: 'POST',
                data: {
                    refund_request_id: refundRequestId,
                    status: status,
                },
                success: function() {
                    alert('Request marked as '+ status + ' successfully!');
                    window.location.reload();
                },
                error: function() {
                    alert('Error encountered!');
                }
            });
        };
    </script>
@endsection
