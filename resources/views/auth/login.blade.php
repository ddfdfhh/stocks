@extends('layouts.auth.auth_app')
@section('title')
    Login
@endsection
@section('content')
    <h4 class="mb-2  text-center">Welcome</h4>
    <p class="mb-4 text-center">Please sign-in to your account</p>
    <div class="login_error_msg" style="color:red"></div>
    <div id="validation_errors"></div>
    <form data-module="Login" class="mb-3" action="{{ route('login') }}" id="login_form" method="POST">
        @csrf
        <div class="mb-3 form-group">

            <label for="email" class="form-label">Email </label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email "
                autofocus>
        </div>
        <div class="mb-3 form-password-toggle form-group">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
                {{-- <a href="auth-forgot-password-basic.html">
                  <small>Forgot Password?</small>
                </a> --}}
            </div>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit" id="login_btn">Sign in</button>
        </div>
    </form>
@endsection
