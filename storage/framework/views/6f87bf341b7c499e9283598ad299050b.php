<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>


    <!-- MAIN CONTENT -->
    <section id="content">
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
                    <button class="toggle-btn" onclick="openImportModal()"><i class="fi fi-rr-document-circle-arrow-up"></i></i></button>
                    <div class="dropdown" style="display:inline-block;position:relative;">
                        <button class="toggle-btn" id="exportDropdownBtn" style="padding:8px 12px;border-radius:6px;background:#2563eb;color:#fff;border:none;box-shadow:0 1px 4px rgba(0,0,0,0.05);"><i class="fi fi-rr-file-download"></i></button>
                        <div id="exportDropdownMenu" class="dropdown-menu" style="display:none;position:absolute;right:0;top:110%;z-index:1000;background:#fff;border-radius:8px;border:1px solid #e5e7eb;padding:4px 0;min-width:180px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                            <a href="#" class="dropdown-item" onclick="downloadExport('pdf')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as PDF</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xlsx')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as Excel (.xlsx)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('xls')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as Excel (.xls)</a>
                            <a href="#" class="dropdown-item" onclick="downloadExport('csv')" style="display:block;padding:10px 18px;color:#222;text-decoration:none;font-size:15px;transition:background 0.2s;border:none;background:none;cursor:pointer;">Export as CSV</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-header">
                <div class="table-col title">Student ID</div>
                <div class="table-col">Name</div>
                <div class="table-col">Sex</div>
                <div class="table-col">Educational Level</div>
                <div class="table-col">Year Level</div>
                <div class="table-col">Section</div>
                <div class="table-col actions">Actions</div>
            </div>
            <div id="student-list">
                <div class="table">
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $statusClass = 'green';
                            $statusColor = 'green';
                            if (!empty($row->case_severity)) {
                                if (strtolower($row->case_severity) === 'low') {
                                    $statusClass = 'green';
                                    $statusColor = 'green';
                                } elseif (strtolower($row->case_severity) === 'intermediate') {
                                    $statusClass = 'yellow';
                                    $statusColor = 'yellow';
                                } elseif (strtolower($row->case_severity) === 'severe') {
                                    $statusClass = 'red';
                                    $statusColor = 'red';
                                }
                            }
                            $suffix = $row->suffix !== 'N/A' ? $row->suffix : '';
                            $mname = trim($row->mname);
                            $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                            $name = trim($row->lname . ', ' . $row->fname . ' ' . $mname . ' ' . $suffix);
                            $profileImage = $row->profile_image ?? 'default.png';
                        ?>
                        <div class="table-card">
                            <div class="table-col title">
                                <span class="status-circle <?php echo e($statusClass); ?>" style="background: <?php echo e($statusColor); ?> !important; vertical-align: middle; margin-right: 6px;"></span>
                                <?php echo e($row->s_id); ?>

                            </div>
                            <div class="table-col"><?php echo e($name); ?></div>
                            <div class="table-col"><?php echo e($row->sex); ?></div>
                            <div class="table-col"><?php echo e($row->educ_level); ?></div>
                            <div class="table-col"><?php echo e($row->year_level); ?></div>
                            <div class="table-col"><?php echo e($row->section); ?></div>
                            <div class="table-col actions">
                                <a href="javascript:void(0);" class="view-btn" title="View" onclick="openViewStudentModal('<?php echo e($row->s_id); ?>')">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="javascript:void(0);" class="edit-btn" title="Edit" onclick="openAddEditModal('edit', { s_id: '<?php echo e($row->s_id); ?>' })">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="archive-btn" title="Archive" onclick="openArchiveModal('<?php echo e($row->s_id); ?>')">
                                    <i class='bx bx-archive'></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if($students instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                    <?php $__env->startComponent('components.student-pagination', ['paginator' => $students]); ?> <?php echo $__env->renderComponent(); ?>
                <?php endif; ?>
            </div>
        </div>
        </div>
        
    </section>


    <?php echo $__env->make('Head.Modal.studentModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="<?php echo e(asset('js/head.js')); ?>"></script>
    <script src="<?php echo e(asset('js/Modal/studentModal.js')); ?>"></script>
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

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views\Head\profiling\students.blade.php ENDPATH**/ ?>