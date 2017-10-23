@extends('layouts.master')

@section('title', 'Sign Up')

@section('body_content')
    <div class="jumbotron text-center">
      <h1>Piggy Budget</h1>
      <p>Sign the fuck up to budget your shit!</p>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
            <form id="signupForm">
            <div class="form-group">
                <label for="first_name">First name </label>
                <input type="text" class="form-control" id="firstName" placeholder="Enter first name" required>
            </div>
            <div class="form-group">
                <label for="last_name">First name </label>
                <input type="text" class="form-control" id="lastName" placeholder="Enter last name" required>
            </div>
            <div class="form-group">
                <label for="emailAddress">Email address</label>
                <input type="email" class="form-control" id="emailAddress" aria-describedby="emailHelp" placeholder="Enter email" required>
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" required> I agree to the terms and agreements.
                </label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Submit</button>
            </form>
            <form class="pspPaymentForm" id="pspPaymentForm"></form>
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

    var checkIfMaxpayLoaded = function() {
        if(window.PaymentPage) {
            hideLoader();
            PaymentPage.sellProduct(maxpay.productId);
        } else {
            setTimeout(checkIfMaxpayLoaded, 500);
        }
    }

    var loadMaxpay = function() {
        showLoader();

        var maxpayScript = $('#pspScript');
        if(maxpayScript.length) {
            maxpayScript.remove();
        }

        var maxpayScript = $(
            '<script>',
            {
                'id': 'pspScript',
                'class': 'pspScript',
                'src': 'https://hpp.maxpay.com/paymentPage.js',
                'data-iframesrc': 'https://hpp.maxpay.com/hpp',
                'data-name': 'Payment page',
                'data-key': maxpay.publicKey,
                'data-signature': maxpay.signature,
                'data-uniqueuserid': maxpay.unique_user_id,
                'data-locale': maxpay.locale,
                'data-email': $('#emailAddress').val(),
                'data-firstname': $('#firstName').val(),
                'data-lastname': $('#lastName').val(),
                'data-displaybuybutton': false,
                'data-productPublicId': maxpay.productId,
                'data-success_url': BASE_URL + '/callback/payment/success',
                'data-decline_url': BASE_URL + '/callback/payment/failed',
                'data-type': 'popup',
                'data-widgetid': "23482",
                'data-custom_webid': window.WEB_ID
            }
        );

        $('#pspPaymentForm').append(maxpayScript);

        setTimeout(function() {
            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", false, true);
            window.dispatchEvent(evt);
            checkIfMaxpayLoaded();
        }, 5000);
    };

    var isEmailAvailable = function(email) {
        var emailAvailable = false;
        $.ajax({
            url: BASE_URL + '/api/attempt-register',
            async: false,
            method: 'POST',
            data: {
                first_name: $('#firstName').val(),
                last_name: $('#lastName').val(),
                email_address: $('#emailAddress').val()
            },
            success: function(data) {
                maxpay.unique_user_id = data.unique_user_id;
                emailAvailable = true;
            },
            error: function(response) {
                data = response.responseJSON;
                alert(data.message);
                emailAvailable = false;
            }
        });

        return emailAvailable;
    }

    $('#signupForm').submit(function(event) {
        event.preventDefault();

        if(!isEmailAvailable($('#emailAddress').val())) {
            return false;
        }

        loadMaxpay();
    });

    var getWebId = function() {
        $.get(BASE_URL + '/api/webid/' + WEB_ID, function(data) {
            maxpay.publicKey = data.public_key;
            maxpay.productId = data.product_id;
            maxpay.locale = data.locale;
        });
    }

    $(document).ready(function() {
        window.maxpay = {
            'signature': '7bf1c227be104c6334de1bbd764a97c514178e4dca27663f2380e776c0ed6731'
        };
        window.WEB_ID = 'web_id_1';
        getWebId();
    });
    </script>
@endsection
