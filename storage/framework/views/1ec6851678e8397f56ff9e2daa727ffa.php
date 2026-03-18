<div class="welcome welcome-card" role="region" aria-labelledby="welcome-back">
    <?php
        $user = Auth::user();
        $sex = strtolower($user->sex ?? '');
        $lastName = $user->last_name ?? ($user->first_name ?? 'Counselor');

        if ($sex === 'male') {
            $prefix = 'Mr.';
        } elseif ($sex === 'female') {
            $prefix = 'Ms.';
        } else {
            $prefix = '';
        }

        // initials fallback for avatar
        $first = $user->first_name ?? '';
        $initials = trim(($first ? $first[0] : '') . ($user->last_name ? $user->last_name[0] : '')) ?: 'C';
        $avatar = $user->avatar ?? null; // keep as nullable; actual path handling depends on app
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
            <div class="greet-small" aria-hidden="true">Welcome back,</div>
            <h2 class="Counselor-name" id="welcome-back"><?php echo e($prefix ? $prefix . ' ' : ''); ?><?php echo e($lastName); ?></h2>

            <div class="stats-row" aria-hidden="true">
                <span class="pill">Today: <strong>—</strong></span>
                <span class="pill">Appointments: <strong>—</strong></span>
                <span class="pill">Messages: <strong>—</strong></span>
            </div>
        </div>

        <!-- Right-side: tip only (simplified) -->
        <aside class="welcome-tip" aria-label="Counselor tip">
            <div class="small-note">
                <h4>Tip</h4>
                <p class="note-text">Keep student notes brief and actionable. Use the appointments page to manage your schedule.</p>
            </div>
        </aside>
    </div>

    <div class="welcome-subtext" id="current-date"></div>

</div>

<script>
    (function(){
        const dateEl = document.getElementById('current-date');
        if (!dateEl) return;
        const now = new Date();
        // show weekday, long date and time for clarity
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' };
        dateEl.textContent = now.toLocaleString(undefined, opts);
    })();
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/dashboard-sections/welcome-stats.blade.php ENDPATH**/ ?>