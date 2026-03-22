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
                                    <a href="<?php echo e(url()->current()); ?>?remarks=all" class="<?php echo e(request('remarks', 'all') == 'all' ? 'active' : ''); ?>">All</a>
                                    <a href="<?php echo e(url()->current()); ?>?remarks=Alarming" class="<?php echo e(request('remarks') == 'Alarming' ? 'active' : ''); ?>">Alarming</a>
                                    <a href="<?php echo e(url()->current()); ?>?remarks=Moderate" class="<?php echo e(request('remarks') == 'Moderate' ? 'active' : ''); ?>">Moderate</a>
                                    <a href="<?php echo e(url()->current()); ?>?remarks=Low" class="<?php echo e(request('remarks') == 'Low' ? 'active' : ''); ?>">Low</a>
                                </li>
                            </div>
                            <button class="add-btn" type="button" onclick="openCounselingNotesModal('add')"><i
                                    class="fi fi-br-plus"></i>Add counseling note</button>
                        </div>
                    </div>
                    <div class="table-bar">
                        <div class="table-search">
                            <form method="GET" action="">
                                <i class="fi fi-br-search"></i>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search counseling..." id="counseling-search-input">
                                <?php if(request('category')): ?>
                                    <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                                <?php endif; ?>
                                <button type="submit" style="display:none"></button>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Table view -->
                <div class="table-list" id="counseling-list" style="margin-bottom:0;">
                    <div class="table-header">
                        <div class="table-col title">Note ID</div>
                        <div class="table-col title">Student</div>
                        <div class="table-col date">Filed Date</div>
                        <div class="table-col">Follow-up</div>
                        <div class="table-col status">Remarks</div>
                        <div class="table-col actions">Actions</div>
                    </div>
                    <div class="table">
                        <?php $__empty_1 = true; $__currentLoopData = $counselings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counseling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="table-card">
                                <div class="table-col title"><?php echo e($counseling->note_id); ?></div>
                                <div class="table-col title"><?php echo e($counseling->student_name ?? 'N/A'); ?></div>
                                <div class="table-col"><?php echo e(optional($counseling->created_at)->format('Y-m-d H:i')); ?></div>
                                <div class="table-col"><?php echo e($counseling->follow_up_date ? $counseling->follow_up_date->format('Y-m-d H:i') : '-'); ?></div>
                                <div class="table-col status">
                                    <span class="status-label status-<?php echo e(strtolower($counseling->remarks)); ?>">
                                        <span class="status-dot status-<?php echo e(strtolower($counseling->remarks)); ?>"></span>
                                        <?php echo e(ucfirst($counseling->remarks)); ?>

                                    </span>
                                </div>

                                <div class="table-col actions">
                                    <?php
                                        $data = [
                                            'note_id' => $counseling->note_id,
                                            'student_name' => $counseling->student_name,
                                            'remarks' => $counseling->remarks,
                                            'observations' => $counseling->observations,
                                            'recommendations' => $counseling->recommendations,
                                            'follow_up_needed' => $counseling->follow_up_needed,
                                            'follow_up_date' => optional($counseling->follow_up_date)->format('Y-m-d H:i:s'),
                                            'user_id' => $counseling->user_id,
                                        ];
                                    ?>

                                    <button type="button" class="view-btn" onclick='openCounselingNotesModal("view", <?php echo json_encode($data, 15, 512) ?>)'><i class='bx bx-show'></i></button>
                                    <button type="button" class="edit-btn" onclick='openCounselingNotesModal("edit", <?php echo json_encode($data, 15, 512) ?>)'><i class='bx bx-edit'></i></button>

                                    <form method="POST" action="<?php echo e(url('Head/counseling/'.$counseling->note_id)); ?>" style="display:inline-block;" class="archive-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" class="archive-btn archive-trigger" data-note-id="<?php echo e($counseling->note_id); ?>"><i class='bx bx-archive'></i></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="no-table-cell">No counseling notes found.</div>
                        <?php endif; ?>
                    </div>

                </div>

                <?php if($counselings instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                    <?php $__env->startComponent('components.student-pagination', ['paginator' => $counselings]); ?> <?php echo $__env->renderComponent(); ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php echo $__env->make('Counselor.Modal.counselingModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Archive confirmation modal -->
    <div id="archiveConfirmModal" class="modal-counseling" style="display:none;">
        <div class="modal-content-counseling">
            <div class="modal-header-counseling">
                <h2>Confirm Archive</h2>
                <span class="close-btn" onclick="closeArchiveModal()">&times;</span>
            </div>
            <div style="padding:16px;">
                <p>Are you sure you want to archive this counseling note? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel" onclick="closeArchiveModal()">Cancel</button>
                <button type="button" class="btn save" id="confirmArchiveBtn">Archive</button>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const searchInput = document.getElementById('counseling-search-input');
    const listWrapper = document.getElementById('counseling-list');
    let timeout = null;

    function fetchResults(query){
        const url = new URL(window.location.href);
        if (query && query.trim() !== '') url.searchParams.set('search', query.trim()); else url.searchParams.delete('search');
        // keep remarks if present
        url.searchParams.set('t', Date.now());

        fetch(url.toString())
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newList = doc.querySelector('#counseling-list');
                if (newList && listWrapper) listWrapper.innerHTML = newList.innerHTML;
            })
            .catch(err => console.error(err));
    }

    if (searchInput){
        searchInput.addEventListener('input', function(e){
            clearTimeout(timeout);
            timeout = setTimeout(()=> fetchResults(e.target.value), 250);
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
// Archive modal wiring (Head)
document.addEventListener('DOMContentLoaded', function(){
    const archiveModal = document.getElementById('archiveConfirmModal');
    const confirmBtn = document.getElementById('confirmArchiveBtn');
    let activeForm = null;

    function openArchiveModal(form){
        activeForm = form;
        if (archiveModal) archiveModal.style.display = 'flex';
    }

    function closeArchiveModal(){
        if (archiveModal) archiveModal.style.display = 'none';
        activeForm = null;
    }

    // delegate clicks
    document.body.addEventListener('click', function(e){
        const trg = e.target.closest && e.target.closest('.archive-trigger');
        if (trg) {
            const form = trg.closest('form');
            e.preventDefault();
            openArchiveModal(form);
        }
    });

    if (confirmBtn){
        confirmBtn.addEventListener('click', function(){
            if (!activeForm) return closeArchiveModal();
            activeForm.submit();
        });
    }

    // expose close function to inline handlers
    window.closeArchiveModal = closeArchiveModal;
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/counseling.blade.php ENDPATH**/ ?>