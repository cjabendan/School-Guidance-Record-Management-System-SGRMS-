<div class="chat-app">
    {{-- Chat Sidebar --}}
    @include('livewire.chat-sidebar')

    {{-- Main Chat Area --}}
    @include('livewire.chat-main')

    {{-- User Profile Pane (Only shown if a user is selected) --}}
    @if ($selectedUser)
        @include('livewire.user-profile-pane')
    @endif
</div>

<script>
    // Livewire and scroll-related JavaScript listeners remain here
    document.addEventListener('livewire:initialized', () => {
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

        Livewire.on('chat:selected', () => {
            setTimeout(() => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 50);
        });

        Livewire.on('chat:messageSent', () => {
            setTimeout(() => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 50);
        });
    });
    

</script>