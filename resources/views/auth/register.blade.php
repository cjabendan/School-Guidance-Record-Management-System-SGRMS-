@extends('layouts.main')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="container" role="main">
        <section class="left-panel">
            <div>

                <h1 class="heading" lang="en"> Let’s setup your Parent <br /> Account </h1>

            </div>
        </section>
        <section class="right-panel" aria-label="Setup form">
            <h2 class="title">Register account</h2>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $e) {
                    echo "<div class='error'>$e</div>";
                }
            }
            if (!empty($success)) {
                echo "<div class='message'>$success</div>";
            }
            ?>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter your first name"
                            value="<?php echo isset($first_name) ? htmlspecialchars($first_name) : ''; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter your last name"
                            value="<?php echo isset($last_name) ? htmlspecialchars($last_name) : ''; ?>" />
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address"
                        value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" />
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_num">Phone number</label>
                        <input type="tel" id="contact_num" name="contact_num" placeholder="Enter your phone number"
                            value="<?php echo isset($contact_num) ? htmlspecialchars($contact_num) : ''; ?>" aria-describedby="phone" />
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Choose a username"
                            value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" />
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Confirm your password" />
                    </div>
                </div>
                <button type="submit" aria-label="Get started">
                    <span>Register Now</span>
                </button>
                <div class="switch-auth">
                    <p>Already have an account or a student?</p>
                    <a class="back-btn" href="{{ url('login') }}">Log in instead</a>
                </div>
            </form>
        </section>
    </div>
@endsection
