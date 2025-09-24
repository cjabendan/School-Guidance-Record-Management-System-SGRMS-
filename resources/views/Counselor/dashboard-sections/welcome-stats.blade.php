<div class="welcome">
    @php
        $user = Auth::user();
        $sex = strtolower($user->sex ?? '');
        $lastName = $user->last_name ?? 'Unknown';

        if ($sex === 'male') {
            $prefix = 'Mr.';
        } elseif ($sex === 'female') {
            $prefix = 'Ms.';
        } else {
            $prefix = '';
        }
    @endphp

    <h2 class="Counselor-name">
        <span id='greeting' style="color: #474545;">Hello,</span> {{ $prefix ? $prefix . ' ' : '' }}{{ $lastName }}!
    </h2>
    <p class="welcome-note">As your counselor, I’ve prepared a quick summary of the student’s guidance records.</p>

</div>

<script>
    const greetingEl = document.getElementById('greeting');
    const hour = new Date().getHours();
    let greeting = 'Hello';
    let emoji = ''; // default emoji

    if (hour >= 5 && hour < 12) {
        greeting = 'Good Morning';
        emoji = '';
    } else if (hour >= 12 && hour < 17) {
        greeting = 'Good Afternoon';
        emoji = '';
    } else if (hour >= 17 && hour < 20) {
        greeting = 'Good Evening';
        emoji = '';
    } else {
        greeting = 'Good Night';
        emoji = '';
    }

    greetingEl.textContent = emoji + ' ' + greeting + ',';
</script>
