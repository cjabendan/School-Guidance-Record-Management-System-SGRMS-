
<?php $__env->startSection('title', 'SGRMS - School Guidance Records Management System'); ?>
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
                                    <a href="#" class="active" data-type="all">All</a>
                                    <a href="#" data-type="link">Child Link</a>
                                    <a href="#" data-type="document">Documents</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <button class="toggle-btn" id="toggle-view-btn">
                        <i class="fi fi-br-bars-filter" id="toggle-icon"></i>
                        <span id="toggle-label"></span>
                    </button>
                </div>

                <div class="table-list">
                    <div class="table-header">
                        <div class="table-col type">Request Type</div>
                        <div class="table-col requested-by">Requested By</div>
                        <div class="table-col requested-at">Requested At</div>
                        <div class="table-col status">Status</div>
                        <div class="table-col actions">Actions</div>
                    </div>

                    <div class="table">
                        <?php $__currentLoopData = $allRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="table-card">

                                <div class="table-col type"><?php echo e($req['type']); ?></div>
                                <div class="table-col requested-by"><?php echo e($req['parent_name']); ?></div>
                                <div class="table-col requested-at"><?php echo e($req['requested_at']); ?></div>
                                <div class="table-col status">
                                    <?php
                                        $status = strtolower($req['status']);
                                        $dotClass = match ($status) {
                                            'active' => 'status-dot status-approved',
                                            'archived' => 'status-dot status-declined',
                                            'pending' => 'status-dot status-pending',
                                            default => 'status-dot',
                                        };
                                        $labelClass = match ($status) {
                                            'active' => 'status-label status-approved',
                                            'archived' => 'status-label status-declined',
                                            'pending' => 'status-label status-pending',
                                            default => 'status-label',
                                        };
                                    ?>
                                    <span class="<?php echo e($labelClass); ?>">
                                        <span class="<?php echo e($dotClass); ?>"></span>
                                        <?php echo e(ucfirst($req['status'])); ?>

                                    </span>
                                </div>
                                <div class="table-col actions">
                                    <?php if($req['status'] === 'Pending'): ?>
                                        <a href="" class=" view-btn">Review</a>
                                        <button class="btn btn-danger btn-sm reject-btn" data-id="<?php echo e($req['id']); ?>"
                                            data-type="<?php echo e($req['type']); ?>">Reject</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('Head.Modal.caseModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterLinks = document.querySelectorAll('.filters a');
                const rows = document.querySelectorAll('.table-row');

                filterLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const type = this.dataset.type;

                        filterLinks.forEach(l => l.classList.remove('active'));
                        this.classList.add('active');

                        rows.forEach(row => {
                            if (type === 'all') {
                                row.style.display = '';
                            } else {
                                row.style.display = row.dataset.type === type ? '' : 'none';
                            }
                        });
                    });
                });

                // Handle reject button click (open modal or prompt)
                document.querySelectorAll('.reject-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const type = this.dataset.type;
                        const reason = prompt("Enter rejection reason:");

                        if (reason) {
                            const url = type === 'link' ?
                                `/head/requests/reject/${id}` :
                                `/head/requests/rejectDocument/${id}`;

                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                },
                                body: JSON.stringify({
                                    reason
                                })
                            }).then(res => location.reload());
                        }
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/requests.blade.php ENDPATH**/ ?>