@extends('layouts.error')

@section('main_content')
    {{ $exception->getMessage() ?: 'forbidden.' }}
@endsection
