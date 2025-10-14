<div class="settings-form">
    <div class="settings-form-header">
        <p class="settings-form-heading">Profile</p>
        <p class="settings-form-subheading">Update your profile, and email address.</p>
    </div>

    <div class="flex-1">
        <div class="settings-profile-img-wrapper">
            <img src="{{ asset('images/user/' . Auth::user()->profile_image) }}" alt="User-Profile"
                class="settings-user-profile">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="icons editPhoto">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>
        </div>
    </div>

    <div class="settings-flex-row">
        @if (Auth::user()->role === 'admin')
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">First name</label>
                <input type="text" value="{{ Auth::user()->first_name }}" wire:model="name"
                    class="settings-form-input">
            </div>
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">Middle name</label>
                <input type="text" value="{{ Auth::user()->middle_name }}" wire:model="middle_name"
                    class="settings-form-input">
            </div>
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">Last name</label>
                <input type="text" value="{{ Auth::user()->last_name }}" wire:model="last_name"
                    class="settings-form-input">
            </div>
        @else
            <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
                <label class="settings-form-label">Name</label>
                <div class="settings-locked-field" style="position: relative;">
                    <input type="text"
                        value="{{ Auth::user()->first_name . ' ' . Auth::user()->middle_name . ' ' . Auth::user()->last_name }}"
                        class="settings-form-input" disabled title="Only administrators can modify names.">
                    <span class="settings-lock-icon"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); opacity: 0.6;"
                        title="Editing restricted to administrators."><svg xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icons">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </span>
                </div>
                <p class="settings-form-note">Only administrators can update their name information.</p>
            </div>
            <div class="flex-1"></div>
            <div class="flex-1"></div>
        @endif
    </div>

    <div class="settings-flex-row">
        <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
            <label class="settings-form-label">Email</label>
            <input type="email" value="{{ Auth::user()->email }}" wire:model="email" class="settings-form-input">
        </div>
        <div class="flex-1" style="display:flex; flex-direction: column; gap: .3rem;">
            <label class="settings-form-label">Phone</label>

            <div class="input-wrapper" style="align-items:center; gap:8px;">
                <span class="ph-flag">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Flag_of_the_Philippines.svg"
                        alt="PH">
                </span>
                <span class="prefix">+63</span>

                <input type="tel" wire:model.defer="num" class="settings-form-input" placeholder="9123456789"
                    pattern="\d{10}" maxlength="10" oninput="this.value = this.value.replace(/\D/g,'').slice(0,10)"
                    style="padding-left: 4.8rem; flex:1;">
            </div>

            @error('num')
                <div class="text-red-500 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex-1"></div>
    </div>

    <div>
        <button wire:click="save" class="settings-form-button">Save</button>
    </div>

    @if (Auth::user()->role === 'admin')
        <div class="settings-form-footer">
            <p class="settings-form-heading">Transfer privilege</p>
            <p class="settings-form-subheading">Transfer your admin role or delete this account.</p>
        </div>

        <div>
            <button wire:click="transferPrivilege" class="settings-form-button delete">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="icons">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                Transfer role
            </button>
        </div>
    @endif

    @if (session()->has('success'))
        <p class="text-green-400 mt-2">{{ session('success') }}</p>
    @endif
</div>
