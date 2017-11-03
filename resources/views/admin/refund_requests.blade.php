@extends('layouts.admin')

@section('title', 'Refund Requests')

@section('body_content')
    <div class="container">
        <div class="row">
            <h1 class="col-lg-5">Refund Requests</h1>
            <span class="col-lg-7" style="display:flex;">
                <input type="text" placeholder="maxpay transaction id" class="form-control" id="maxpayTransactionId">
                <button class="btn btn-primary" id="refundBtn">refund</refund>
            </span>
        </div>
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
    <div id='loader' style='visibility:hidden; position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url(https://payment.fastbudgeting.com/img/ajax-loader.gif) 50% 50% no-repeat rgba(0,0,0,0.4);'></div>
@endsection

@section('custom_script')
    <script>
        var showLoader = function() {
            $('#loader').css('visibility', 'visible');
        }

        var hideLoader = function() {
            $('#loader').css('visibility', 'hidden');
        }

        window.changeStatus = function(refundRequestId, status) {
            showLoader();
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
                },
                complete: hideLoader
            })
        };

        $('#refundBtn').click(function() {
            var maxpayTransactionId = $('#maxpayTransactionId').val();
            if(!maxpayTransactionId) {
                alert('Please provide the transaction id.');
                return;
            }

            showLoader();
            $.ajax({
                url: BASE_URL + '/api/refund',
                method: 'POST',
                data: {
                    transaction_id: maxpayTransactionId
                },
                success: function() {
                    alert('Transaction refunded successfully!');
                    $('#maxpayTransactionId').val('');
                },
                error: function(response) {
                    alert(response.responseJSON.message);
                },
                complete: hideLoader
            });
        });
    </script>
@endsection
