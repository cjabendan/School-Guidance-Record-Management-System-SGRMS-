<!-- Loader Overlay -->
@include('components.loader')

<div class="login-modal">
    <div class="modal">
        <div class="modal-left">
            <img class="modal-image" alt="Image" src="{{ asset('images/img/2.png') }}" />
        </div>
        <div class="modal-right">
            <!-- Close Button -->
            <button type="button" class="close-modal-btn" onclick="closeLoginModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
            <div class="logo">
                <img src="{{ asset('images/logo/logo.svg') }}" class="brand-logo" alt="SGRMS Logo">
            </div>
            <h2>Log in to your Account</h2>
            <p>Welcome! Please enter your credentials to log in.</p>
            <form action="{{ url('login') }}" method="POST">
                @csrf
                <div class="input-box" style="position:relative;">
                    <input type="text" name="username" class="input-box" placeholder="Email or Student ID"
                        value="{{ old('username') }}">
                    <span class="input-icon">
                        <i class="fas fa-user"></i>
                    </span>
                    @error('username')
                        <span class='error'>{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-box password-box" style="position:relative;">
                    <input type="password" name="password" class="input-box" id="login-password" placeholder="Password">
                    <span class="toggle-password" onclick="togglePassword()" tabindex="0">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </span>
                    @error('password')
                        <span class='error'>{{ $message }}</span>
                    @enderror
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="log-btn">Login</button>
                <div class="signup-link">
                    <p>No account or a parent?<a href="{{ url('register') }}">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
