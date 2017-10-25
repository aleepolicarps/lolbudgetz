@extends('layouts.email')

@section('main_content')
    <p>Hi {{ $register_attempt->first_name }},</p>
    <p>Thanks for signing up to Piggybudget! One last final step, is to complete your registration. Click the button below or copy the link below to your browser's address bar.</p>
    <p><a href="{{ route('complete_signup', ['uuid'=>$register_attempt->uuid]) }}" class="btn">Complete Signup</a></p>
    <p>{{ route('complete_signup', ['uuid'=>$register_attempt->uuid]) }}</p>
@endsection
