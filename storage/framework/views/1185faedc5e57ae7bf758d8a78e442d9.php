<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
<?php $__env->startSection('content'); ?>

    <!-- MAIN CONTENT -->
    <section id="content">
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- COUNSELORS MANAGEMENT -->
        <main class="wrapper">
            <h2>Manage Counselors</h2>
            <div class="profiles-container">
                <!-- Add new profile box -->
                <div class="profile-box add-box" onclick="openAddCounselorModal()">
                    <i class='bx bx-plus add-profile-icon'></i>
                    <h2>Add Counselor</h2>
                </div>

                <?php
                    $activeCounselors = $counselors->filter(function($c) {
                        return isset($c->status) ? strtolower($c->status) === 'active' : true;
                    });
                ?>
                <?php echo $__env->make('components.counselor-card', ['counselors' => $activeCounselors], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Past Counselors Table -->
            <div class="past-counselor-table-container" style="margin-top: 32px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin-bottom: 16px; color: #1e3a8a;">Past Counselor</h3>
                    <div class="table-search" style="margin-bottom: 8px;">
                        <form method="GET" action="">
                            <i class="fi fi-br-search"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search counselors..." id="counselor-search-input">
                            <button type="submit" style="display:none"></button>
                        </form>
                    </div>
                </div>
                <div class="table-header">
                    <div class="table-col title">Counselor ID</div>
                    <div class="table-col">Name</div>
                    <div class="table-col">Email</div>
                    <div class="table-col">Contact No.</div>
                    <div class="table-col">Actions</div>
                </div>
                <div class="table">
                    <?php
                        $search = request('search');
                        $inactiveCounselorsQuery = \DB::table('counselors')
                            ->leftJoin('users', 'counselors.user_id', '=', 'users.id')
                            ->where('users.status', 'inactive')
                            ->select(
                                'counselors.c_id',
                                'users.first_name',
                                'users.middle_name',
                                'users.last_name',
                                'users.email',
                                'users.contact_num'
                            );
                        if ($search) {
                            $inactiveCounselorsQuery->where(function($query) use ($search) {
                                $query->where('counselors.c_id', 'like', "%$search%")
                                      ->orWhere('users.first_name', 'like', "%$search%")
                                      ->orWhere('users.middle_name', 'like', "%$search%")
                                      ->orWhere('users.last_name', 'like', "%$search%");
                            });
                        }
                        $inactiveCounselors = $inactiveCounselorsQuery->paginate(5);
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $inactiveCounselors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mname = trim($row->middle_name);
                            $mname = $mname !== '' ? strtoupper(substr($mname, 0, 1)) . '.' : '';
                            $name = trim($row->last_name . ', ' . $row->first_name . ' ' . $mname);
                        ?>
                        <div class="table-card">
                            <div class="table-col title"><?php echo e($row->c_id); ?></div>
                            <div class="table-col"><?php echo e($name); ?></div>
                            <div class="table-col"><?php echo e($row->email); ?></div>
                            <div class="table-col"><?php echo e($row->contact_num); ?></div>
                            <div class="table-col actions">
                                <a href="javascript:void(0);" class="view-btn" title="View" onclick="openViewCounselModalReadonly('<?php echo e($row->c_id); ?>')">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="javascript:void(0);" class="edit-btn" title="Edit" onclick="editCounselorFromView('<?php echo e($row->c_id); ?>', true)">
                                    <i class='bx bx-edit'></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="no-table-cell">No past counselors found.</div>
                    <?php endif; ?>
                </div>

                <!-- Counsel Pagination links -->
                <div style="margin-top: 16px; text-align: center;">
                    <?php $__env->startComponent('components.counsel-pagination', ['paginator' => $inactiveCounselors]); ?>
                    <?php echo $__env->renderComponent(); ?>
                </div>
                </div>
            </div>
        </main>
    </section>

    <?php echo $__env->make('Head.Modal.counselModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="<?php echo e(asset('js/head.js')); ?>"></script>
    <script src="<?php echo e(asset('js/Modal/counselModal.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('counselor-search-input');
            const tableList = document.querySelector('.past-counselor-table-container .table');
            let searchTimeout = null;
            if (searchInput && tableList) {
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
                            const newTable = doc.querySelector('.past-counselor-table-container .table');
                            if (newTable && tableList) {
                                tableList.innerHTML = newTable.innerHTML;
                            }
                        });
                    }, 300);
                });
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/profiling/counselors.blade.php ENDPATH**/ ?>