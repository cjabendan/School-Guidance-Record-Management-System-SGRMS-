<div class="auth-container">
    <div class="auth-content">

        <div class="auth-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="auth-icon">
                <path fill-rule="evenodd"
                    d="M11.484 2.17a.75.75 0 0 1 1.032 0 11.209 11.209 0 0 0 7.877 3.08.75.75 0 0 1 .722.515 12.74 12.74 0 0 1 .635 3.985c0 5.942-4.064 10.933-9.563 12.348a.749.749 0 0 1-.374 0C6.314 20.683 2.25 15.692 2.25 9.75c0-1.39.223-2.73.635-3.985a.75.75 0 0 1 .722-.516l.143.001c2.996 0 5.718-1.17 7.734-3.08ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75ZM12 15a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75H12Z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <h2 class="auth-header">Confirm password</h2>

        <div class="auth-subheader">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        @if (session('error'))
            <div class="auth-error">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="confirm" class="auth-flex">
            <div class="auth-label-row">
                <label for="password" class="auth-label">Password</label>
                @error('password')
                    <span class="auth-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="password-input-wrapper" style="position: relative; width: 100%;">
                <input id="password" type="{{ $showPassword ? 'text' : 'password' }}" wire:model.defer="password"
                    autocomplete="current-password" class="auth-input" />

                <button type="button" wire:click="$toggle('showPassword')"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
                           background: none; border: none; cursor: pointer; color: #555;">
                    @if ($showPassword)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12
                                     C3.226 16.338 7.244 19.5 12 19.5
                                     c.993 0 1.953-.138 2.863-.395M6.228 6.228
                                     A10.451 10.451 0 0 1 12 4.5
                                     c4.756 0 8.773 3.162 10.065 7.498
                                     a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228
                                     L3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21
                                     m-3.228-3.228-3.65-3.65m0 0
                                     a3 3 0 1 0-4.243-4.243
                                     m4.242 4.242L9.88 9.88" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639
                                     C3.423 7.51 7.36 4.5 12 4.5
                                     c4.638 0 8.573 3.007 9.963 7.178
                                     .07.207.07.431 0 .639
                                     C20.577 16.49 16.64 19.5 12 19.5
                                     c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0
                                     3 3 0 0 1 6 0Z" />
                        </svg>
                    @endif
                </button>
            </div>

            <button type="submit" class="auth-button" style="margin-top: .5rem;">
                Confirm
            </button>
        </form>
    </div>
</div>
