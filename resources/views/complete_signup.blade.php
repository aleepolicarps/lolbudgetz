@extends('layouts.master')

@section('title', 'Complete Signup')

@section('body_content')
    <div class="jumbotron text-center">
      <h1>Complete your signup</h1>
      <p>Set your password and you can use Piggy Budget as you please</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
                <form id="signupform">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" minlength="5" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm your password</label>
                        <input type="password" class="form-control" id="confirmPassword" minlength="5" name="confirmPassword" required>
                    </div>
                    <input type="hidden" value="{{ $register_attempt->uuid }}" id="uuid" name="uuid">
                    <button type="submit" class="btn btn-primary" style="width:100%;">Submit</button>
                </form>
            </div>
        </div>
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

        $('#signupform').submit(function(event) {
            event.preventDefault();

            var password = $('#password').val();
            var confirmPassword = $('#confirmPassword').val();

            if(password !== confirmPassword) {
                alert('Password and confimation do not match');
                return false;
            }

            showLoader();
            $.ajax({
                url: BASE_URL + '/api/complete-signup',
                method: 'POST',
                aync: true,
                data: {
                    password: password,
                    uuid: $('#uuid').val()
                },
                success: function() {
                    window.location.href = BASE_URL;
                    hideLoader();
                },
                 error: function(response) {
                    alert(response.message);
                    hideLoader();
                }
            });
        });
    </script>
@endsection
