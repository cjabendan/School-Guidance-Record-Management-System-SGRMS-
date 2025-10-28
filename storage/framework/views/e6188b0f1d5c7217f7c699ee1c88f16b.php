<!-- Link Child Modal -->
<div id="linkChildModal" class="modal-announcement">
    <div class="modal-content-announcement">

        <!-- Modal Header -->
        <div class="modal-header-announcement">
            <h2 id="modalTitle">Link Children</h2>
            <span class="close-btn" onclick="closeLinkChildModal()">&times;</span>
        </div>

        <!-- Modal Body -->
        <form id="linkChildForm" method="POST" action="<?php echo e(route('Parent.link.request')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="student_ids">Search Students</label>
                <select name="student_ids[]" id="student_ids" class="form-control" multiple required></select>
            </div>

            <div class="form-group">
                <label for="parent_email">Your Email</label>
                <input type="email" name="parent_email" id="parent_email" 
                       value="<?php echo e(Auth::user()->email); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="parent_contact">Your Contact Number</label>
                <input type="text" name="parent_contact" id="parent_contact" 
                       value="<?php echo e(Auth::user()->contact_num); ?>" readonly>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="submit" class="btn save">Send Request</button>
                <button type="button" class="btn cancel" onclick="closeLinkChildModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openLinkChildModal() {
        const modal = document.getElementById('linkChildModal');
        modal.style.display = 'block';
    }

    function closeLinkChildModal() {
        const modal = document.getElementById('linkChildModal');
        const form = document.getElementById('linkChildForm');
        form.reset();
        modal.style.display = 'none';
    }
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/components/child-link.blade.php ENDPATH**/ ?>