@extends('layouts.master')

@section('title', 'Sign up failed')

@section('body_content')
    <div class="jumbotron text-center">
      <h1>Sign up failed :(</h1>
      <p>Redirecting you to the signup page ...</p>
    </div>
@endsection


@section('custom_script')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                window.location.href = BASE_URL + '/signup';
            }, 3000);
        });
    </script>
@endsection
