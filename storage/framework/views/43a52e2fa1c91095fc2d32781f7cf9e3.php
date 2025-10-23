<!-- Archive Parent Confirmation Modal -->
<div class="modal fade" id="archiveParentModal" tabindex="-1" aria-labelledby="archiveParentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="archiveParentModalLabel">Archive Parent Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Do you wish to proceed with archiving this parent account?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmArchiveParentBtn">Archive</button>
      </div>
    </div>
  </div>
</div>

<!-- Shared Add/Edit Parent Modal -->
<div class="modal fade" id="parentModal" tabindex="-1" aria-labelledby="parentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close add-modal-close pro-add-close" id="closeParentModalBtn" style="font-size:2rem;cursor:pointer;position:absolute;top:18px;right:24px;z-index:10;">&times;</span>
        <h5 class="modal-title" id="parentModalLabel">
          <?php echo e(isset($parent) ? 'Edit Parent' : 'Add Parent'); ?>

        </h5>
      </div>
      <form id="parentForm">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="firstName" class="form-label">First Name</label>
              <input type="text" class="form-control" id="firstName" name="first_name" required value="<?php echo e($parent->first_name ?? ''); ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="middleName" class="form-label">Middle Name</label>
              <input type="text" class="form-control" id="middleName" name="middle_name" value="<?php echo e($parent->middle_name ?? ''); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="lastName" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="lastName" name="last_name" required value="<?php echo e($parent->last_name ?? ''); ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sex" class="form-label">Sex</label>
              <select class="form-control" id="sex" name="sex" required>
                <option value="">Select Sex</option>
                <option value="Male" <?php echo e((isset($parent) && $parent->sex == 'Male') ? 'selected' : ''); ?>>Male</option>
                <option value="Female" <?php echo e((isset($parent) && $parent->sex == 'Female') ? 'selected' : ''); ?>>Female</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="contactNum" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="contactNum" name="contact_num" required value="<?php echo e($parent->contact_num ?? ''); ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required value="<?php echo e($parent->email ?? ''); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <?php echo e(isset($parent) ? 'Update Parent' : 'Add Parent'); ?>

          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/Modal/parentModal.blade.php ENDPATH**/ ?>