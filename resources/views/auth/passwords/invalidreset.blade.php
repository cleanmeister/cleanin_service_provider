@extends('layouts.auth')

@section('content')

<div class="login-box" style="margin-left: auto; margin-right: auto;">
    <div class="login-logo" style="margin-bottom: 0;">
        <a href="{{ route('home') }}"><span style="font-weight: 1000;">Clean</span> Meister</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <h5><p class="login-box-msg" style="padding: 0;">Invalid Request</p></h5>
            <p class="alert alert-danger">The password reset token is invalid.</p>
        <div class="row" style="margin: 0;">
          <div class="col-lg-4 text-left" style="padding: 0;">
            <strong><a href="{{ url('/') }}/login">Login</a></strong>
          </div>
          <div class="col-lg-4 text-center" style="padding: 0;">
            <strong><a href="{{ url('/') }}/password/reset">Reset Password</a></strong>
          </div>
          <div class="col-lg-4 text-right" style="padding: 0;">
            <strong><a href="{{ url('/') }}/register">Register</a></strong>
          </div>
        </div>
        </div>
    </div>
</div>
@endsection