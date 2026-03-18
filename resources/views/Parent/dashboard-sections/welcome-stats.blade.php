<div class="welcome welcome-card parent-welcome" role="region" aria-labelledby="parent-welcome">
    @php
        $user = Auth::user();
        $sex = strtolower($user->sex ?? '');
        $lastName = $user->last_name ?? ($user->first_name ?? 'Parent');

        if ($sex === 'male') {
            $prefix = 'Mr.';
        } elseif ($sex === 'female') {
            $prefix = 'Ms.';
        } else {
            $prefix = '';
        }

        // initials fallback for avatar
        $first = $user->first_name ?? '';
        $initials = trim(($first ? $first[0] : '') . ($user->last_name ? $user->last_name[0] : '')) ?: 'P';
        $avatar = $user->avatar ?? null;
    @endphp

    <div class="welcome-top">
        <div class="avatar" aria-hidden="true">
            @if($avatar)
                <img src="{{ asset($avatar) }}" alt="{{ $user->first_name ?? $lastName }} avatar" />
            @else
                <span class="initials">{{ strtoupper($initials) }}</span>
            @endif
        </div>

        <div class="welcome-main">
            <div class="greet-small" aria-hidden="true"><span id="greeting" style="color:#474545">Hello,</span></div>
            <h2 class="Parent-name" id="parent-welcome">{{ $prefix ? $prefix . ' ' : '' }}{{ $lastName }}!</h2>

            <div class="stats-row" aria-hidden="true">
                <span class="pill">Children: <strong>—</strong></span>
                <span class="pill">Upcoming events: <strong>—</strong></span>
                <span class="pill">Messages: <strong>—</strong></span>
            </div>
        </div>

        <aside class="welcome-tip" aria-label="Parent tip">
            <div class="small-note">
                <h4>Note for parents</h4>
                <p class="note-text">Keep contact details updated to receive timely notices. Check your child's assignments weekly.</p>
            </div>
        </aside>
    </div>

    <div class="welcome-subtext" id="current-date"></div>

</div>

<script>
    (function(){
        const greetingEl = document.getElementById('greeting');
        const hour = new Date().getHours();
        let greeting = 'Hello';

        if (hour >= 5 && hour < 12) {
            greeting = 'Good Morning';
        } else if (hour >= 12 && hour < 17) {
            greeting = 'Good Afternoon';
        } else if (hour >= 17 && hour < 20) {
            greeting = 'Good Evening';
        } else {
            greeting = 'Good Night';
        }

        if (greetingEl) greetingEl.textContent = greeting + ',';

        const dateEl = document.getElementById('current-date');
        if (dateEl) {
            const now = new Date();
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' };
            dateEl.textContent = now.toLocaleString(undefined, opts);
        }
    })();
</script>
