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
    

document.addEventListener('livewire:initialized', () => {

    // ⭐ THIS IS THE FIX FOR SCROLLING
    Livewire.on('scrollToMessage', ({ messageId }) => {
        const messageElement = document.getElementById(`message-${messageId}`);
        const container = document.getElementById('messagesContainer');
        
        if (messageElement && container) {
            // Dispatch event to close the profile pane (if it's open)
            Livewire.dispatchSelf('toggleUserProfile');

            // Scroll the message into view, centered if possible
            const offset = container.clientHeight / 3;
            container.scrollTop = messageElement.offsetTop - container.offsetTop - offset;
            
            // Highlight the message element
            messageElement.style.transition = 'background-color 0.5s ease-in-out, border 0.5s ease-in-out';
            messageElement.style.backgroundColor = 'rgba(255, 255, 0, 0.4)'; // Light yellow highlight
            messageElement.style.border = '1px solid #ffcc00';

            setTimeout(() => {
                messageElement.style.backgroundColor = '';
                messageElement.style.border = '';
            }, 2500); // Remove highlight after 2.5 seconds
        }
    });

    // You might also need this for clean-up, but it's not directly related to the fix
    Livewire.on('profileSearchCompleted', () => {
        // Any post-search actions
    });
});
</script>