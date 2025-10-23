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
                                    <a href="#" class="a-nav active" data-filter="all">All</a>
                                    <a href="#" class="a-nav" data-filter="minor">Minor</a>
                                    <a href="#" class="a-nav" data-filter="major">Major</a>
                                    <a href="#" class="a-nav" data-filter="grave">Grave</a>
                                </li>
                            </div>
                            <button class="add-btn" data-bs-toggle="modal" data-bs-target="#addCaseModal"><i
                                    class="fi fi-br-plus"></i>Add case</button>

                        </div>
                    </div>
                    <div class="search-bar">
                        <div class="case-search">
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

                        <!-- Filter Button with Dropdown -->
                        <div class="filter-dropdown" style="position:relative; display:inline-block;">
                            <button class="toggle-btn" id="toggle-view-btn" type="button">
                                <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                            </button>
                            <div id="level-menu" class="level-menu">
                                <button class="level-option" data-level="" type="button">All Levels</button>
                                <button class="level-option" data-level="senior_high" type="button">Senior High School
                                </button>
                                <button class="level-option" data-level="high_school" type="button">High School</button>
                                <button class="level-option" data-level="elementary" type="button">Elementary</button>
                                <button class="level-option" data-level="kinder" type="button">Kinder</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table view -->
                <div class="table-list" id="cases-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Case ID</div>
                        <div class="table-col category">Type</div>
                        <div class="table-col">Severity</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        <?php $__empty_1 = true; $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="table-card">
                                <div class="table-col title"><?php echo e($case->case_id); ?></div>
                                <div class="table-col category"><?php echo e($case->caseType->type_name ?? 'N/A'); ?></div>
                                <div class="table-col"><?php echo e($case->severity); ?></div>
                                <div class="table-col status">
                                    <span class="status-label status-<?php echo e(strtolower($case->status)); ?>">
                                        <span class="status-dot status-<?php echo e(strtolower($case->status)); ?>"></span>
                                        <?php echo e(ucfirst($case->status)); ?>

                                    </span>
                                </div>
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
                let level = $('.level-option.active').data('level') || '';
                $.ajax({
                    url: "<?php echo e(route('Head.cases.index')); ?>",
                    type: "GET",
                    data: {
                        search: query,
                        level: level
                    },
                    success: function(response) {
                        let html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                    }
                });
            });

            $('#toggle-view-btn').on('click', function(e) {
                e.stopPropagation();
                $('#level-menu').toggle();
            });

            $(document).on('click', function() {
                $('#level-menu').hide();
            });

            $('.level-option').on('click', function() {
                $('.level-option').removeClass('active');
                $(this).addClass('active');
                let level = $(this).data('level');
                let search = $('#case-search-input').val();
                $('#level-menu').hide();
                $('#toggle-label').text($(this).text());
                $.ajax({
                    url: "<?php echo e(route('Head.cases.index')); ?>",
                    type: "GET",
                    data: {
                        search: search,
                        level: level
                    },
                    success: function(response) {
                        let html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                    }
                });
            });

            // Import button triggers file input
            $('#import-btn').on('click', function() {
                $('#import-file-input').click();
            });
            $('#import-file-input').on('change', function() {
                $(this).closest('form').submit();
            });

            $('.filters .a-nav').on('click', function(e) {
                e.preventDefault();
                $('.filters .a-nav').removeClass('active');
                $(this).addClass('active');
                let severity = $(this).data('filter');
                let search = $('#case-search-input').val();
                $.ajax({
                    url: "<?php echo e(route('Head.cases.index')); ?>",
                    type: "GET",
                    data: {
                        search: search,
                        filter_severity: severity === 'all' ? '' : severity
                    },
                    success: function(response) {
                        let html = $(response).find('#cases-list').html();
                        $('#cases-list').html(html);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/case.blade.php ENDPATH**/ ?>