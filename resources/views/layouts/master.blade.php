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
        @yield('body_content')
        <script src="/js/app.js"></script>
        @yield('custom_script')
    </body>
</html>
