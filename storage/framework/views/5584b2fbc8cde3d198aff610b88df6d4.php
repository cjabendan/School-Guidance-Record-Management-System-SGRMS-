<div class="welcome">
    <?php
        $user = Auth::user();
        $sex = strtolower($user->sex ?? '');
        $lastName = $user->last_name ?? 'Unknown';

    ?>

    <h2 class="Student-name">
        <span id='greeting' style="color: #474545;">Hello,</span> <?php echo e($lastName); ?>!
    </h2>
    <p class="welcome-note">Welcome back! Here’s a quick overview of your guidance records.</p>

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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Student/dashboard-sections/welcome-stats.blade.php ENDPATH**/ ?>