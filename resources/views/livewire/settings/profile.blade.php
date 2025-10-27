<div class="settings-form">
    
    <div class="settings-form-header">
        <p class="settings-form-heading">Profile</p>
        <p class="settings-form-subheading">Update your profile, and email address.</p>
    </div>

    <div class="flex-1">
        <div class="settings-profile-img-wrapper">
            <img id="visibleProfileImage" src="{{ asset('images/user/' . Auth::user()->profile_image) }}" alt="User-Profile"
                class="settings-user-profile">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="icons editPhoto" id="openEditPhotoBtn" title="Change photo">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>
            <!-- Hidden input to receive cropped image data for Livewire -->
            <input type="hidden" id="cropped_image_data_input" wire:model="cropped_image_data">
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
        <button id="profileSaveBtn" wire:click="save" class="settings-form-button">Save</button>
    </div>

    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        // Listen for Livewire emitted toast events (works across Livewire versions)
        if (typeof Livewire !== 'undefined' && Livewire.on) {
            Livewire.on('toast', function (payload) {
                // If a persistent saving toast was shown, remove it first
                if (window._savingToastEl) {
                    try { removeToast(window._savingToastEl); } catch(e){}
                    window._savingToastEl = null;
                }
                // Remove any explicitly flagged saving toasts
                try {
                    document.querySelectorAll('.notifications .toast[data-saving-toast]')
                        .forEach(function(el){ try { removeToast(el); } catch(e){} });
                } catch(e){}

                if (typeof createToast === 'function') {
                    createToast(payload.type || 'success', payload.message || 'Done');
                    // re-enable button when toast arrives
                    const btn = document.getElementById('profileSaveBtn');
                    if (btn) btn.disabled = false;
                } else {
                    alert(payload.message || 'Profile updated');
                }
            });
        }

        // Backwards-compatible window event listener (in case other code dispatches it)
        window.addEventListener('toast', function (e) {
            if (typeof createToast === 'function') {
                if (window._savingToastEl) {
                    try { removeToast(window._savingToastEl); } catch(e){}
                    window._savingToastEl = null;
                }
                try {
                    document.querySelectorAll('.notifications .toast').forEach(function(el){
                        if (el.textContent && /Saving/i.test(el.textContent)) {
                            try { removeToast(el); } catch(e){}
                        }
                    });
                } catch(e){}
                createToast(e.detail.type || 'success', e.detail.message || 'Done');
                const btn = document.getElementById('profileSaveBtn');
                if (btn) btn.disabled = false;
            } else {
                // fallback: simple alert
                alert(e.detail.message || 'Profile updated');
            }
        });
        // Immediate save feedback: show temporary saving toast and disable button
        (function(){
            const saveBtn = document.getElementById('profileSaveBtn');
            let tempToastEl = null;
            function showSaving() {
                if (typeof createToast === 'function') {
                    // duration: 0 => persistent until removed
                    // add data attribute so we can target and remove it reliably
                    window._savingToastEl = createToast('success', 'Saving...', { duration: 0 });
                    try { window._savingToastEl.setAttribute('data-saving-toast', '1'); } catch(e) {}
                }
                if (saveBtn) saveBtn.disabled = true;
            }
            if (saveBtn) {
                saveBtn.addEventListener('click', function(){
                    // small delay to let Livewire request start
                    setTimeout(showSaving, 50);
                    // as a safety, re-enable after 6s if no response and remove saving toast
                    setTimeout(function(){ if (saveBtn) saveBtn.disabled = false; if (window._savingToastEl) { removeToast(window._savingToastEl); window._savingToastEl = null; } }, 5000);
                });
            }
        })();
    </script>

    @if(isset($toastPayload) && $toastPayload)
        <script>
            (function(){
                var payload = @json($toastPayload);
                var attempts = 0;
                function tryShow() {
                    attempts++;
                    if (typeof createToast === 'function') {
                        createToast(payload.type || 'success', payload.message || 'Done');
                        return;
                    }
                    if (attempts < 50) { // ~5 seconds (50 * 100ms)
                        setTimeout(tryShow, 100);
                        return;
                    }
                    // fallback
                    alert(payload.message || 'Profile updated');
                }
                tryShow();
            })();
        </script>
    @endif

    <!-- Cropper Modal -->
    <div id="editPhotoModal" class="modal" style="display:none;">
        <div class="modal-content" style="max-width:800px;">
            <h3>Crop Profile Photo</h3>
            <div style="display:flex; gap:1rem;">
                <div style="flex:1">
                    <img id="cropperImage" src="" style="max-width:100%; display:block;" />
                </div>
                <div style="width:220px;">
                    <p>Preview</p>
                    <div style="width:200px; height:200px; overflow:hidden; border:1px solid #ddd;">
                        <div id="cropperPreview" class="cropper-preview" style="width:100%; height:100%; background-size: cover; background-position: center;"></div>
                    </div>
                    <div style="margin-top:1rem;">
                        <button id="applyCropBtn" class="settings-form-button">Apply</button>
                        <button id="cancelCropBtn" class="settings-form-button delete">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden file input used to pick new profile image -->
    <input type="file" id="profileImageFileInput" accept="image/*" style="display:none;">

    <!-- Include Cropper assets -->
    <script src="{{ asset('js/cropper.min.js') }}"></script>

    <script>
    (function(){
        // Encapsulate logic so we can re-init after Livewire updates
        function initCropModal() {
            const openBtn = document.getElementById('openEditPhotoBtn');
            const fileInput = document.getElementById('profileImageFileInput');
            const modal = document.getElementById('editPhotoModal');
            const cropperImage = document.getElementById('cropperImage');
            const previewEl = document.getElementById('cropperPreview');
            const applyBtn = document.getElementById('applyCropBtn');
            const cancelBtn = document.getElementById('cancelCropBtn');
            const hiddenInput = document.getElementById('cropped_image_data_input');
            let cropper = null;

            // safety: ensure elements exist
            if (!openBtn || !fileInput || !modal || !cropperImage || !applyBtn || !cancelBtn) return;

            // helper to show/hide modal via class
            function showModal() {
                modal.classList.add('visible');
                modal.style.display = 'flex';
            }
            function hideModal() {
                modal.classList.remove('visible');
                modal.style.display = 'none';
            }

            openBtn.removeEventListener('click', openBtn._cropOpenHandler);
            openBtn._cropOpenHandler = function(e){ e.preventDefault(); fileInput.click(); };
            openBtn.addEventListener('click', openBtn._cropOpenHandler);

            fileInput.removeEventListener('change', fileInput._cropChangeHandler);
            fileInput._cropChangeHandler = function(e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(evt) {
                    cropperImage.src = evt.target.result;
                    if (previewEl) previewEl.style.backgroundImage = `url('${evt.target.result}')`;
                    showModal();

                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }

                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        preview: '#cropperPreview',
                    });
                };
                reader.readAsDataURL(file);
            };
            fileInput.addEventListener('change', fileInput._cropChangeHandler);

            applyBtn.removeEventListener('click', applyBtn._cropApplyHandler);
            applyBtn._cropApplyHandler = function(e){
                e.preventDefault();
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({ width: 400, height: 400, imageSmoothingQuality: 'high' });
                const dataUrl = canvas.toDataURL('image/png');
                if (hiddenInput) hiddenInput.value = dataUrl;
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                const visibleImg = document.getElementById('visibleProfileImage');
                if (visibleImg) visibleImg.src = dataUrl;
                hideModal();
            };
            applyBtn.addEventListener('click', applyBtn._cropApplyHandler);

            cancelBtn.removeEventListener('click', cancelBtn._cropCancelHandler);
            cancelBtn._cropCancelHandler = function(e){
                e.preventDefault();
                if (cropper) { cropper.destroy(); cropper = null; }
                hideModal();
            };
            cancelBtn.addEventListener('click', cancelBtn._cropCancelHandler);
        }

        // Init on Livewire load and after every Livewire update
        if (window.Livewire) {
            document.addEventListener('livewire:load', function(){ initCropModal(); });
            Livewire.hook('message.processed', (message, component) => { initCropModal(); });
        } else {
            // fallback
            document.addEventListener('DOMContentLoaded', initCropModal);
        }
    })();
    </script>
</div>
