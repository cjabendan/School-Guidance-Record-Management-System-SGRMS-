<?php
    use Illuminate\Support\Facades\DB;
?>


<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

<section id="content">

        <script>
        window.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                createToast('success', "<?php echo e(session('success')); ?>");
            <?php endif; ?>
            <?php if(session('import_errors')): ?>
                createToast('error', `<?php echo is_array(session('import_errors')) ? implode('<br>', session('import_errors')) : session('import_errors'); ?>`);
            <?php endif; ?>
            <?php if(session('error')): ?>
                createToast('error', "<?php echo e(session('error')); ?>");
            <?php endif; ?>
        });
        </script>

    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="wrapper">
        <div class="table-container">
            <div class="table-management">
                <div class="table-nav">
                    <div class="table-filter">
                        <div class="filters">
                            <li>
                                <a href="<?php echo e(url('Head/students')); ?>" class="tab <?php echo e(request('status') == null ? 'active' : ''); ?>">All</a>
                                <a href="<?php echo e(url('Head/students') . '?status=seniorhigh'); ?>" class="tab <?php echo e(request('status') == 'seniorhigh' ? 'active' : ''); ?>">Senior Highschool</a>
                                <a href="<?php echo e(url('Head/students') . '?status=juniorhigh'); ?>" class="tab <?php echo e(request('status') == 'juniorhigh' ? 'active' : ''); ?>">Junior Highschool</a>
                                <a href="<?php echo e(url('Head/students') . '?status=elementary'); ?>" class="tab <?php echo e(request('status') == 'elementary' ? 'active' : ''); ?>">Elementary</a>
                                <a href="<?php echo e(url('Head/students') . '?status=kindergarten'); ?>" class="tab <?php echo e(request('status') == 'kindergarten' ? 'active' : ''); ?>">Kindergarten</a>
                            </li>
                        </div>
                        <button class="add-btn" onclick="openAddEditModal('add')"><i class="fi fi-br-plus"></i>Add Student</button>
                    </div>
                </div>
                <div class="search-bar">
                    <div class="table-search">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search students..." id="student-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                        <form id="importForm" action="<?php echo e(route('Head.students.import')); ?>" method="POST" enctype="multipart/form-data" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <input type="file" id="importFileInput" name="students_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="display:none" required>
                            <button type="button" class="toggle-btn import-btn" id="importBtn">
                                <i class="fi fi-rr-document-circle-arrow-up"></i>
                            </button>

                        </form>
                    <div class="dropdown">
                        <button class="export-btn toggle-btn" id="exportDropdownBtn">
                            <i class="fi fi-rr-file-download"></i>
                        </button>

                        <div id="exportDropdownMenu" class="dropdown-menu">
                            <a href="#" class="dropdown-item" onclick="downloadExport('pdf')">Export as PDF</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xlsx')">Export as Excel (.xlsx)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xls')">Export as Excel (.xls)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('csv')">Export as CSV</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col title">Student ID</div>
                <div class="table-col">Student Name</div>
                <div class="table-col">Educational Level</div>
                <div class="table-col">Year Level</div>
                <div class="table-col">Status</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="student-list">
                <?php echo $__env->make('Head.partials.student_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

        </div>
    </div>
</section>

<?php echo $__env->make('Head.Modal.studentModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="<?php echo e(asset('js/head.js')); ?>"></script>
<script src="<?php echo e(asset('js/Modal/studentModal.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('student-search-input');
    const tableList = document.getElementById('student-list');
    let searchTimeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            const query = searchInput.value;
            const url = new URL(window.location.href);
            url.searchParams.set('search', query);
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('student-list');
                if (newTable && tableList) {
                    tableList.innerHTML = newTable.innerHTML;
                }
            });
        }, 300);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/profiling/students.blade.php ENDPATH**/ ?>