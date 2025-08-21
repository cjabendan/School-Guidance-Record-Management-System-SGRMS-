@extends('layouts.reg')
@section('content')
    <div class="container" role="main">
        <div class="left">
            <div>
                <h1 class="welcome-text">
                    Welcome!<br />
                    <span class="welcome-subtext">First things first...</span>
                </h1>
                <p class="description">
                    Create a profile to get updated with your child's progress.
                </p>
            </div>
            <div>
                <img src="{{ asset('images/img/reg.png') }}" class="reg-image" />
            </div>
        </div>

        <div class="right">
            <form class="form" action="/register" method="POST">
                @csrf
                <div>
                    <p class="reg-title">Create your account</p>
                 
                </div>

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input id="first_name" name="first_name" placeholder="Enter your first name" type="text"
                        class="input">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" placeholder="Enter your username" type="text" class="input">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input id="last_name" name="last_name" placeholder="Enter your last name" type="text" class="input">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" placeholder="Enter your email" type="email" class="input">
                </div>

                <div class="form-group" style="position:relative;">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Create a password" class="input">
                    <span class="toggle-password" onclick="togglePassword('password', 'togglePasswordIcon1')"
                        tabindex="0">
                        <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                    </span>
                </div>

                <div class="form-group" style="position:relative;">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="Re-enter your password" class="input">
                    <span class="toggle-password" onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')"
                        tabindex="0">
                        <i class="fas fa-eye" id="togglePasswordIcon2"></i>
                    </span>
                </div>

                <div class="form-group">
                    <label for="guardian_relationship">Relationship to Student</label>
                    <input id="guardian_relationship" name="guardian_relationship" placeholder="e.g. Mother, Father, Guardian" type="text" class="input">
                </div>

                <button type="submit" class="button-submit">Register Account</button>

                <p class="p">Already have an account?
                    <a href="{{ url('/?login=true') }}"><span class="span">Log in instead</span></a>
                </p>
            </form>
        </div>
    </div>
    
@endsection
