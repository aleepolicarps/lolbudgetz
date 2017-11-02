@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <br>
                    @if ($user_subscription->active)
                        <button class="btn btn-danger" id="unsubscibeBtn">Unsubscribe</button>
                    @endif
                    <button class="btn" data-toggle="modal" data-target="#refundModal">Request refund</button>
                    <!-- Modal -->
                    <div id="refundModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Please enter details for requesting a for a refund</h4>
                          </div>
                          <div class="modal-body">
                            <textarea id="refundDetails" style="width: 100%;height: 100px;border-radius: 2%;"></textarea>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" id="requestRefundBtn">Submit</button>
                          </div>
                        </div>

                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom_script')
    <script>
        $('#unsubscibeBtn').click(function() {
            $.ajax({
                url: BASE_URL + '/api/unsubscribe',
                method: 'POST',
                success: function() {
                    alert('You have unsubscribed!');
                    window.location.reload();
                }, error: function(response) {
                    console.log(response);
                    alert(response.responseJSON.message);
                }
            })
        });

        $('#requestRefundBtn').click(function() {
            var refundDetails = $('#refundDetails').val();
            if(!refundDetails) {
                alert('Please enter details');
            }
            $.ajax({
                url: BASE_URL + '/api/request-refund',
                method: 'POST',
                data: {
                    details: $('#refundDetails').val()
                },
                success: function() {
                    alert('Thanks for reaching out. Your request will be processed within 24 hours');
                    $('#refundModal').modal('hide');
                },
                error: function() {
                    alert('Error encountered!');
                }
            });
        });

    </script>
@endsection
