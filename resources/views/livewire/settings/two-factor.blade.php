<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">Two-Factor Authentication</p>
        <p class="settings-form-subheading">Add an extra layer of security to your account.</p>
    </div>

    <div class="settings-flex-row">
        <p><span
                class="twoFactor-status {{ $enabled ? 'Enabled' : 'Disabled' }}">{{ $enabled ? 'Enabled' : 'Disabled' }}</span>
        </p>
    </div>

    <div>
        @if ($enabled)
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <p class="settings-form-subheading">
                        When two-factor authentication is enabled, you will be prompted for a secure, <br>
                        random pin during login, which you can retrieve from the TOTP-supported <br>
                        application on your phone.
                    </p>

                </div>

                <div class="recoverycodes-container">
                    <div class="recoverycodes-header">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="icons">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <p class="settings-form-heading">2FA Recovery Codes</p>
                    </div>

                    <div>
                        <p class="settings-form-subheading">Recovery codes lets you regain access if you lose your 2FA
                            device. Store them in a secure password manager.</p>
                    </div>

                    <div>
                        <button wire:click="toggle" class="settings-form-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="icons">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            View Recovery Codes
                        </button>
                    </div>


                </div>
            </div>
        @else
            <p class="settings-form-subheading">
                When you enable two-factor authentication, you will be prompted <br>
                to scan a QR code or set up an account using a Google Authenticator <br> application.
            </p>
        @endif

    </div>

    <div>
        <button wire:click="toggle" class="settings-form-button{{ $enabled ? ' Disabled' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="icons">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6
          11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623
          5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152
          c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
            <span>{{ $enabled ? 'Disable' : 'Enable' }} 2FA</span>
        </button>

    </div>

    @if (session()->has('success'))
        <p class="text-green-400 mt-2">{{ session('success') }}</p>
    @endif
</div>
