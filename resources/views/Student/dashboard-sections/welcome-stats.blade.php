<div class="welcome">
    @php
        $user = Auth::user();
        $sex = strtolower($user->sex ?? '');
        $lastName = $user->last_name ?? 'Unknown';

    @endphp

    <h2 class="Student-name">
        <span id='greeting' style="color: #474545;">Hello,</span> {{ $lastName }}!
    </h2>
    <p class="welcome-note">Welcome back! Here’s a quick overview of your guidance records.</p>
    <div class="welcome-counts">
        <div class="box">
            <div class="icon">
                <i class="fi fi-sr-info"></i>
            </div>
            <div class="text-container">
                <p class="box-text1">Active Cases</p>
                <h3 class="box-count1">{{ $casesCount ?? 0 }}</h3>
            </div>
        </div>
        <!-- New box: Upcoming Appointments for the student -->
        <div class="box">
            <div class="icon">
                <i class="fi fi-sr-calendar"></i>
            </div>
            <div class="text-container">
                <p class="box-text">Upcoming Appointments</p>
                <h3 class="box-count">{{ $appointmentsCount ?? 0 }}</h3>
            </div>
        </div>
    </div>    
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
