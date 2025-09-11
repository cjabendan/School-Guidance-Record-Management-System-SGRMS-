<header>
    <div class="logo">
        <img src="<?php echo e(asset('images/logo/logo.svg')); ?>" class="brand-logo" alt="SGRMS Logo">
    </div>
    <nav class="navbar">
        <a class="nav-link" href="<?php echo e(url('/')); ?>">Home</a>
        <a class="nav-link" href="<?php echo e(url('/#about')); ?>">About</a>
        <a class="nav-link" href="<?php echo e(url('/#services')); ?>">Services</a>
        <a class="nav-link" href="<?php echo e(url('/#staff')); ?>">Meet Our Staff</a>
        <a class="nav-link" href="<?php echo e(url('/#faq')); ?>">FAQ's</a>
        <a class="nav-link" href="<?php echo e(url('announcements')); ?>">Announcements</a>
    </nav>
    <div class="navigation">
        <div class="navigation-links">
            <a href="#" class="btn-login" onclick="openLoginModal()">Log in</a>
            <a class="btn-primary" href="<?php echo e(url('register')); ?>">
                <span class="text">Sign Up</span>
            </a>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\partials\header.blade.php ENDPATH**/ ?>