@extends('layouts.error')

@section('main_content')
    {{ $exception->getMessage() ?: 'Invalid link.' }}
@endsection
