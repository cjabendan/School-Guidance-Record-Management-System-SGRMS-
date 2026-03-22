<?php $__env->startSection('title', 'Parent Accounts'); ?>
<?php $__env->startSection('content'); ?>

<section id="content">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="wrapper">
        <div class="table-container">
            <div class="table-management">
                <div class="table-nav">
                    <div class="table-filter">
                        
                        <div class="filters">
                                <li>
                                    <a href="#" class="a-nav active" data-filter="all">All</a>
                                    <a href="#" class="a-nav" data-filter="minor">Counselors</a>
                                    <a href="#" class="a-nav" data-filter="major">Parents</a>
                                    <a href="#" class="a-nav" data-filter="grave">Students</a>
                                </li>
                            </div>
                        <button class="add-btn" id="addUserBtn">
                            <i class="fi fi-br-plus"></i>Add user
                        </button>
                    </div>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search user..." id="user-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col title">User Name</div>
                <div class="table-col">Contact</div>
                <div class="table-col">Email</div>
                <div class="table-col">Role</div>
                <div class="table-col">Status</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="users-list">
                <?php echo $__env->make('Head.partials.users_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</section>

<?php echo $__env->make('Head.Modal.userModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script src="<?php echo e(asset('js/Modal/userModal.js')); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add modal open
    const addBtn = document.getElementById('addUserBtn');
    if(addBtn) addBtn.addEventListener('click', e => { e.preventDefault(); openUserModal('add'); });

    // Filter logic
    const filterLinks = document.querySelectorAll('.filters .a-nav');
    const tableContainer = document.getElementById('users-list');
    const searchInput = document.getElementById('user-search-input');
    let currentRole = 'all';
    let searchTimeout;

    filterLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            filterLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            currentRole = link.dataset.filter;
            fetchUsers();
        });
    });

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchUsers, 300);
        });
    }

    function fetchUsers() {
        const query = searchInput?.value || '';
        let roleParam = '';
        switch(currentRole) {
            case 'major': roleParam = 'parent'; break;
            case 'minor': roleParam = 'counselor'; break;
            case 'grave': roleParam = 'student'; break;
            default: roleParam = ''; break;
        }
        const url = new URL(window.location.origin + '/Head/users');
        if(query) url.searchParams.set('search', query);
        if(roleParam) url.searchParams.set('role', roleParam);

        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => { tableContainer.innerHTML = html; })
            .catch(err => console.error('Failed to fetch users:', err));
    }
});


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/profiling/users.blade.php ENDPATH**/ ?>