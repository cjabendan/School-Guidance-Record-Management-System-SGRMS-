<div id="chatList" class="chat-list" wire:poll.3s="loadChatList">
    @php
        // Map user role values to route name prefixes. Update if your app stores roles differently.
        $roleToPrefix = [
            'admin' => 'Head',
            'counselor' => 'Counselor',
            'parent' => 'Parent',
            'student' => 'Student',
        ];

        $rolePrefix = 'Head';
        if (auth()->check()) {
            $roleValue = strtolower(auth()->user()->role ?? '');
            if (isset($roleToPrefix[$roleValue])) {
                $candidate = $roleToPrefix[$roleValue];
                $candidateRoute = $candidate . '.messages';
                if (\Illuminate\Support\Facades\Route::has($candidateRoute)) {
                    $rolePrefix = $candidate;
                } elseif (\Illuminate\Support\Facades\Route::has('Head.messages')) {
                    // fallback to Head if candidate route is not defined
                    $rolePrefix = 'Head';
                }
            } else {
                // unknown role: fallback to Head if available
                if (\Illuminate\Support\Facades\Route::has('Head.messages')) {
                    $rolePrefix = 'Head';
                }
            }
        }
    @endphp
    @foreach ($users as $user)
        <a href="{{ route($rolePrefix . '.messages', ['user' => $user->id]) }}" class="chat-item">
            <img src="{{ asset('images/user/' . $user->profile_image) }}" class="user-img" alt="User">
            <div class="chat-item-info">
                <h3 class="chat-item-username">
                    {{ $user->first_name }} {{ $user->last_name }}
                    @if (in_array($user->role, ['admin', 'counselor']))
                        <i class="fi fi-sr-badge-check official-badge" title="Verified"></i>
                    @endif
                </h3>
                <div class="chat-item-preview">
                    <span class="chat-item-lastmessage
                        @if ($user->lastMessage && !$user->lastMessage->is_read && $user->lastMessage->sender_id !== Auth::id()) unread @endif">
                        @if ($user->lastMessage)
                            @if ($user->lastMessage->sender_id === Auth::id())
                                You: {{ $user->lastMessage->message }}
                            @else
                                {{ $user->lastMessage->message}}
                            @endif
                        @else
                            No messages yet.
                        @endif
                    </span>
                    <span class="chat-item-time" data-time="{{ $user->lastMessage?->created_at }}">
                        {{ $this->formatTimeShort($user->lastMessage?->created_at) }} 
                    </span>
                </div>
            </div>
        </a>
    @endforeach
</div>