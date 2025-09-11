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
                                    <a href="#" class="active">All</a>
                                    <a href="#">Minor Offense</a>
                                    <a href="#">Major Offense</a>
                                    <a href="#">Grave Offense</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"
                            ><i class="fi fi-br-plus"></i>Add Case</button>
                        </div>
                    </div>
                    <div class="table-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search cases..." id="case-search-input">
                                <?php if(request('category')): ?>
                                    <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                                <?php endif; ?>
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>

                        <button class="toggle-btn" id="toggle-view-btn">
                              <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            <span id="toggle-label"></span>
                        </button>
                    </div>
                </div>

                <!-- Table view -->
                <div class="table-list" id="cases-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Case ID</div>
                        <div class="table-col category">Type</div>
                        <div class="table-col">Severity</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        <?php $__empty_1 = true; $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="table-card">
                                <div class="table-col title"><?php echo e($case->case_id); ?></div>
                                <div class="table-col category"><?php echo e($case->caseType->type_name ?? 'N/A'); ?></div>
                                <div class="table-col"><?php echo e($case->severity); ?></div>
                                <div class="table-col date"><?php echo e($case->filed_date); ?></div>
                                <div class="table-col actions" style="display:flex; gap:8px;">
                                    <button type="button" class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewCaseModal<?php echo e($case->case_id); ?>"><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editCaseModal<?php echo e($case->case_id); ?>"><i class='bx bx-edit'></i></button>
                                    <button type="button" class="archive-btn"
                                        onclick="if(confirm('Archive this case?')) { document.getElementById('archive-form-<?php echo e($case->case_id); ?>').submit(); }"><i class='bx bx-archive'></i></button>
                                    <form id="archive-form-<?php echo e($case->case_id); ?>"
                                        action="<?php echo e(route('Head.cases.archive', $case->case_id)); ?>" method="POST"
                                        style="display:none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="no-table-cell">No cases found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo $__env->make('Head.Modal.caseModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
$(document).ready(function() {
    $('#case-search-input').on('input', function() {
        let query = $(this).val();
        $.ajax({
            url: "<?php echo e(route('Head.cases.index')); ?>",
            type: "GET",
            data: { search: query },
            success: function(response) {
                // Parse the returned HTML and update the table
                let html = $(response).find('#cases-list').html();
                $('#cases-list').html(html);
            }
        });
    });
});
</script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/case.blade.php ENDPATH**/ ?>