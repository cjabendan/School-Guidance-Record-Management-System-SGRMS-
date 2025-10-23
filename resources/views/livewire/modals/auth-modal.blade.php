@if ($showAuthModal)
    <div class="qr-modal">
        <div class="qr-modal-content">
            <button wire:click="$set('showAuthModal', false)" class="qr-close">&times;</button>
            <div class="qr-icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="qr-icon">
                    <path fill-rule="evenodd"
                        d="M3 4.875C3 3.839 3.84 3 4.875 3h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5A1.875 1.875 0 0 1 3 9.375v-4.5ZM4.875 4.5a.375.375 0 0 0-.375.375v4.5c0 .207.168.375.375.375h4.5a.375.375 0 0 0 .375-.375v-4.5a.375.375 0 0 0-.375-.375h-4.5Zm7.875.375c0-1.036.84-1.875 1.875-1.875h4.5C20.16 3 21 3.84 21 4.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5a1.875 1.875 0 0 1-1.875-1.875v-4.5Zm1.875-.375a.375.375 0 0 0-.375.375v4.5c0 .207.168.375.375.375h4.5a.375.375 0 0 0 .375-.375v-4.5a.375.375 0 0 0-.375-.375h-4.5ZM6 6.75A.75.75 0 0 1 6.75 6h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75A.75.75 0 0 1 6 7.5v-.75Zm9.75 0A.75.75 0 0 1 16.5 6h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM3 14.625c0-1.036.84-1.875 1.875-1.875h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.035-.84 1.875-1.875 1.875h-4.5A1.875 1.875 0 0 1 3 19.125v-4.5Zm1.875-.375a.375.375 0 0 0-.375.375v4.5c0 .207.168.375.375.375h4.5a.375.375 0 0 0 .375-.375v-4.5a.375.375 0 0 0-.375-.375h-4.5Zm7.875-.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm6 0a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM6 16.5a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm9.75 0a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm-3 3a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Zm6 0a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75Z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            <div style="text-align:center;">
                <p class="settings-form-heading">Enter Authentication Code</p>
                <p class="settings-form-subheading">Enter the 6-digit code from your authenticator app.</p>
            </div>

            <div style="display:flex;gap:6px;justify-content:center;margin-top:.5rem;">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" wire:model.defer="verifyCodeDigits.{{ $i }}"
                        oninput="moveNext(this)"
                        style="width:40px;height:50px;text-align:center;font-size:20px;border:1px solid #ccc;border-radius:8px;">
                @endfor
            </div>

            <button wire:click="verifyTwoFactor" class="settings-form-button full-width" style="margin-top:1rem;">
                Verify Code
            </button>

            @if (session()->has('error'))
                <p style="color:#f87171;margin-top:0.5rem;">{{ session('error') }}</p>
            @endif
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const firstInput = document.querySelector('input[type="text"]');
        if (firstInput) firstInput.focus();
    });

    function moveNext(el) {
        if (el.value.length === 1) {
            const next = el.nextElementSibling;
            if (next && next.tagName === 'INPUT') next.focus();
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.tagName === 'INPUT' && e.target.value === '') {
            const prev = e.target.previousElementSibling;
            if (prev && prev.tagName === 'INPUT') prev.focus();
        }
    });
</script>
