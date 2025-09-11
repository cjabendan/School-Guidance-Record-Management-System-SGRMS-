<div class="welcome">
    <?php
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
    ?>

    <h2 class="Parent-name">
        <span id='greeting' style="color: #474545;">Hello,</span> <?php echo e($prefix ? $prefix . ' ' : ''); ?><?php echo e($lastName); ?>!
    </h2>
    <p class="welcome-note">Here’s a quick summary of your children’s guidance records.</p>
    <div class="welcome-counts">
        <!--
        <div class="box">
            <div class="icon">
                <i class='bx bxs-user'></i>
            </div>
            <div class="text-container">
                <p class="box-text">My Children</p>
                <h3 class="box-count"><?php echo e($childCount ?? 0); ?></h3>

            </div>
        </div>

    
        <div class="box">
            <div class="icon">
                <i class="fi fi-sr-info"></i>
            </div>
            <div class="text-container">
                <p class="box-text">Active Cases</p>
                <h3 class="box-count"><?php echo e($casesCount ?? 0); ?></h3>
            </div>
        </div>

       
        <div class="box">
            <div class="icon">
                <i class="fi fi-sr-clock-three"></i>
            </div>
            <div class="text-container">
                <p class="box-text">Pending Requests</p>
                <h3 class="box-count"><?php echo e($pendingRequestCount ?? 0); ?></h3>

            </div>
        </div>
    -->
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Parent\dashboard-sections\welcome-stats.blade.php ENDPATH**/ ?>