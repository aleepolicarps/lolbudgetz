<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - Piggybudget</title>

        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="/css/app.css" rel="stylesheet" type="text/css">
        <script>window.BASE_URL = "{{ url('/') }}";</script>
        @yield('custom_style')
    <head>
    <body>
        <header>
            <nav class="navbar navbar-default">
              <div class="container-fluid">
                <div class="navbar-header">
                  <a class="navbar-brand" href="{{ route('admin_index') }}">Piggybudget Admin</a>
                </div>
                <ul class="nav navbar-nav">
                  <li><a href="{{ route('admin_index') }}">Home</a></li>
                  <li><a href="#">Page 1</a></li>
                </ul>
              </div>
            </nav>
        </header>
        @yield('body_content')
        <script src="/js/app.js"></script>
        @yield('custom_script')
    </body>
</html>
