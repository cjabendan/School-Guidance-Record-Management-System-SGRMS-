<div class="welcome welcome-card student-welcome" role="region" aria-labelledby="student-welcome">
    <?php
        $user = Auth::user();
        $sex = strtolower($user->sex ?? '');
        $lastName = $user->last_name ?? ($user->first_name ?? 'Student');

        // initials fallback for avatar
        $first = $user->first_name ?? '';
        $initials = trim(($first ? $first[0] : '') . ($user->last_name ? $user->last_name[0] : '')) ?: 'S';
        $avatar = $user->avatar ?? null;
    ?>

    <div class="welcome-top">
        <div class="avatar" aria-hidden="true">
            <?php if($avatar): ?>
                <img src="<?php echo e(asset($avatar)); ?>" alt="<?php echo e($user->first_name ?? $lastName); ?> avatar" />
            <?php else: ?>
                <span class="initials"><?php echo e(strtoupper($initials)); ?></span>
            <?php endif; ?>
        </div>

        <div class="welcome-main">
            <div class="greet-small" aria-hidden="true"><span id="greeting" style="color:#474545">Hello,</span></div>
            <h2 class="Student-name" id="student-welcome"><?php echo e($lastName); ?>!</h2>

            <div class="stats-row" aria-hidden="true">
                <span class="pill">Courses: <strong>—</strong></span>
                <span class="pill">Due Assignments: <strong>—</strong></span>
                <span class="pill">Notifications: <strong>—</strong></span>
            </div>
        </div>

        <aside class="welcome-tip" aria-label="Student tip">
            <div class="small-note">
                <h4>Quick tip</h4>
                <p class="note-text">Check assignments due within 3 days and mark attendance after sessions.</p>
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

        // date/time display (localized)
        const dateEl = document.getElementById('current-date');
        if (dateEl) {
            const now = new Date();
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' };
            dateEl.textContent = now.toLocaleString(undefined, opts);
        }
    })();
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Student/dashboard-sections/welcome-stats.blade.php ENDPATH**/ ?>