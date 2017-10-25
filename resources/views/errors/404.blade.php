@extends('layouts.error')

@section('main_content')
    {{ $exception->getMessage() ?: 'Sorry, the page your are looking for cannot be found.' }}
@endsection
