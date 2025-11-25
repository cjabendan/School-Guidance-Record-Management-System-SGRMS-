<div class="chat-app">
    {{-- Chat Sidebar --}}
    @include('livewire.chat-sidebar')

    {{-- Main Chat Area --}}
    @include('livewire.chat-main')

    {{-- User Profile Pane (Only shown if a user is selected) --}}
    @if ($selectedUser)
        @include('livewire.user-profile-pane')
    @endif

    {{-- Delete Chat Modal --}}
    @livewire('modals.delete-chat-modal')

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // ... (Existing listeners for requestProfilePaneState and saveProfilePaneState) ...
        Livewire.on('requestProfilePaneState', ({
            localStorageKey
        }) => {
            const state = localStorage.getItem(localStorageKey);
            Livewire.dispatchSelf('setUserProfileState', state === 'true');
        });

        Livewire.on('saveProfilePaneState', ({
            isVisible,
            localStorageKey
        }) => {
            localStorage.setItem(localStorageKey, isVisible ? 'true' : 'false');
        });

    });
</script>
