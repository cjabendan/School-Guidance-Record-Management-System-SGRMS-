<?php $__env->startSection('title', 'Parent Accounts'); ?>
<?php $__env->startSection('content'); ?>

<section id="content">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="wrapper">
        <div class="table-container">
            <div class="table-management">
                <div class="table-nav">
                    <div class="table-filter">
                        <button class="add-btn" id="addParentBtn">
                            <i class="fi fi-br-plus"></i>Add Parent
                        </button>
                    </div>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search parents..." id="parent-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col title">Parent Name</div>
                <div class="table-col">Contact</div>
                <div class="table-col">Email</div>
                <div class="table-col">Status</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="parent-list">
                <?php echo $__env->make('Head.partials.parent_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</section>

<?php echo $__env->make('Head.Modal.parentModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script src="<?php echo e(asset('js/Modal/parentModal.js')); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('parent-search-input');
    const tableList = document.getElementById('parent-list');
    let searchTimeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            const query = searchInput.value;
            fetch(`/Head/parents?search=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                tableList.innerHTML = html;
            });
        }, 300);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/profiling/parents.blade.php ENDPATH**/ ?>