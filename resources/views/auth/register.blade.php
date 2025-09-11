@extends('layouts.reg')
@section('title', 'SGRMS - School Guidance Records Management System')
@section('content')

    <!-- Loader Overlay -->
    @include('components.loader')

    <div class="register-modal">
        <div class="register-container">
            <div class="register-left">
                <div class="overlay-wrapper">
                    <img src="{{ asset('images/img/reg.jpg') }}" alt="">
                    <div class="gradient-overlay"></div>
                </div>
            </div>
            <div class="register-right">
                <form id="signupForm" class="register-form" action="/register" method="POST">
                    @csrf
                    <div>
                        <h2 class="register-title">Create your parent account</h2>
                    </div>
                    <div class="form-group-row">
                        <div class="form-group">
                            <label for="first_name">First Name
                                @error('first_name')
                                    <span class="text-danger">- {{ $message }}</span>
                                @enderror
                            </label>
                            <input id="first_name" name="first_name" placeholder="Enter your first name" type="text"
                                class="input" value="{{ old('first_name') }}">
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name
                                @error('last_name')
                                    <span class="text-danger">- {{ $message }}</span>
                                @enderror
                            </label>
                            <input id="last_name" name="last_name" placeholder="Enter your last name" type="text"
                                class="input" value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address
                            @error('email')
                                <span class="text-danger">- {{ $message }}</span>
                            @enderror
                        </label>
                        <input id="email" name="email" placeholder="Enter your email" type="email" class="input"
                            value="{{ old('email') }}">
                    </div>


                    <div class="form-group">
                        <label for="phonenumber">Phone Number</label>
                        <div class="input-wrapper">
                            <span class="ph-flag">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Flag_of_the_Philippines.svg"
                                    alt="PH">
                            </span>
                            <span class="prefix">+63</span>
                            <input id="phonenumber" name="phonenumber" type="text" pattern="\d{10}" maxlength="10"
                                placeholder="9XXXXXXXXX" required style="padding-left: 4.8rem">
                        </div>
                    </div>


                    <div class="form-group">
                        <label>Sex</label>
                        <div class="gender-radio-group"
                            style="display:flex; gap:2rem; align-items:center; margin-top:0.5rem;">
                            <label style="display:flex; align-items:center; gap:0.8rem;">
                                <input type="radio" name="gender" value="Male" required>
                                <span> Male</span>
                            </label>
                            <label style="display:flex; align-items:center; gap:.8rem;">
                                <input type="radio" name="gender" value="Female" required>
                                <span> Female</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" style="position:relative;">
                        <label for="password">Password
                            @error('password')
                                <span class="text-danger">- {{ $message }}</span>
                            @enderror
                        </label>
                        <div class="input-wrapper">
                            <input id="password" name="password" type="password" placeholder="Create a password"
                                class="input">
                            <span class="toggle-password" onclick="togglePassword('password', 'togglePasswordIcon1')"
                                tabindex="0">
                                <i class="fas fa-eye" id="togglePasswordIcon1"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group" style="position:relative;">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-wrapper">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                placeholder="Re-enter your password" class="input">
                            <span class="toggle-password"
                                onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')" tabindex="0">
                                <i class="fas fa-eye" id="togglePasswordIcon2"></i>
                            </span>
                        </div>

                    </div>

                    <button type="submit" class="register-btn">
                        <span>Sign Up</span>
                    </button>
                    <div>
                        <p class="message">Already have an account?<a href="{{ url('/?login=true')  }}" class="link">Log
                                in instead</a></p>
                    </div>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var signupForm = document.getElementById('signupForm');
                    if (signupForm) {
                        signupForm.addEventListener('submit', function() {
                            if (typeof showLoader === 'function') showLoader();
                        });
                    }
                });
                </script>
            </div>
        </div>
    </div>
@endsection
