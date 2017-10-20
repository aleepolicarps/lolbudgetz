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
            <form>
            <div class="form-group">
                <label for="first_name">First name </label>
                <input type="text" class="form-control" id="first_name" placeholder="Enter first name">
            </div>
            <div class="form-group">
                <label for="last_name">First name </label>
                <input type="text" class="form-control" id="last_name" placeholder="Enter last name">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input"> I agree to the terms and agreements.
                </label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Submit</button>
            </form>
        </div>
      </div>
    </div>
@endsection
