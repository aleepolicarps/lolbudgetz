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
    </script>
@endsection
