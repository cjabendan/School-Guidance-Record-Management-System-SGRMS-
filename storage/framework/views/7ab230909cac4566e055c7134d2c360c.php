<!-- filepath: c:\Users\Rhylyn\School-Guidance-Record-Management-System-SGRMS-\resources\views\Parent\modal\requestApppointmentModal.blade.php -->
<?php
  // Keep modal open when there are validation errors or when old input exists
  $modalDisplay = ($errors->any() || old('appointment_datetime')) ? 'flex' : 'none';
?>
<div id="requestAppointmentModal" class="custom-modal" style="display:<?php echo e($modalDisplay); ?>;">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <div class="custom-modal-content">
    <form method="POST" action="<?php echo e(route('Counselor.appointments.store')); ?>">
      <?php echo csrf_field(); ?>
      <div class="modal-header">
        <h5 class="modal-title">Request Appointment</h5>
        <button type="button" class="close-modal-btn" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body">
    <?php if($errors->has('appointment_datetime')): ?>
    <div class="alert alert-danger" style="margin-bottom:16px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600; font-size:1.05em; padding:10px 16px; display:flex; align-items:center; gap:8px;">
      <i class="fi fi-rr-exclamation" style="color:#b91c1c; font-size:1.3em;"></i>
      <span><?php echo e($errors->first('appointment_datetime')); ?></span>
    </div>
    <?php endif; ?>
        <!-- Counselor -->
        <div class="mb-3">
          <label for="counselor_id" class="form-label">Choose Counselor</label>
          <select name="counselor_id" id="counselor_id" class="form-control" required>
            <option value="">Select Counselor</option>
            <?php $__currentLoopData = $counselors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counselor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($counselor->id); ?>" <?php echo e(old('counselor_id') == $counselor->id ? 'selected' : ''); ?>><?php echo e($counselor->first_name); ?> <?php echo e($counselor->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <!-- Appointment Type -->
        <div class="mb-3">
          <label for="type_id" class="form-label">Appointment Type</label>
          <select name="type_id" id="type_id" class="form-control" required>
            <option value="">Select Type</option>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($type->id); ?>" <?php echo e(old('type_id') == $type->id ? 'selected' : ''); ?>><?php echo e($type->type_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value="other" <?php echo e(old('type_id') === 'other' ? 'selected' : ''); ?>>Other</option>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3" id="other-type-wrapper" style="display:<?php echo e(old('type_id') === 'other' ? 'block' : 'none'); ?>;">
          <label for="other_type" class="form-label">Other Type</label>
          <input type="text" name="other_type" id="other_type" class="form-control" placeholder="Enter custom appointment type" value="<?php echo e(old('other_type')); ?>">
        </div>
        <!-- Child -->
        <div class="mb-3">
          <label for="student_id" class="form-label">Child</label>
            <select name="student_id[]" id="student_id" class="form-control" multiple required style="width: 100%;">
              <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($child->s_id); ?>" <?php echo e((is_array(old('student_id')) && in_array($child->s_id, old('student_id'))) ? 'selected' : ''); ?>>
                  [ID: <?php echo e($child->s_id); ?>] <?php echo e($child->user->first_name ?? ''); ?> <?php echo e($child->user->last_name ?? ''); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <!-- Reason -->
        <div class="mb-3">
          <label for="reason" class="form-label">Reason</label>
          <textarea name="reason" id="reason" class="form-control" required><?php echo e(old('reason')); ?></textarea>
        </div>
        <!-- Date & Time -->
        <div class="mb-3">
          <label for="appointment_datetime" class="form-label">Date & Time</label>
          <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" class="form-control" required value="<?php echo e(old('appointment_datetime')); ?>">
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Show/hide Other type input and set required when necessary
          const typeSelect = document.getElementById('type_id');
          const otherWrapper = document.getElementById('other-type-wrapper');
          const otherInput = document.getElementById('other_type');
          function updateOtherVisibility() {
            if (!typeSelect) return;
            if (typeSelect.value === 'other') {
              if (otherWrapper) otherWrapper.style.display = 'block';
              if (otherInput) otherInput.required = true;
            } else {
              if (otherWrapper) otherWrapper.style.display = 'none';
              if (otherInput) { otherInput.required = false; otherInput.value = ''; }
            }
          }
          if (typeSelect) {
            typeSelect.addEventListener('change', function() {
              updateOtherVisibility();
            });
          }
          // Run once to handle old input
          updateOtherVisibility();
        });
        </script>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Request</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#student_id').select2({
        placeholder: 'Search Student',
        allowClear: true,
        width: 'resolve',
        dropdownParent: $('#requestAppointmentModal')
      });

      // Restore previous student selections from old input (if any)
      var oldStudents = <?php echo json_encode(old('student_id', []), 512) ?>;
      if (Array.isArray(oldStudents) && oldStudents.length > 0) {
        $('#student_id').val(oldStudents).trigger('change');
      }
    });               
  </script>
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/Modal/requestApppointmentModal.blade.php ENDPATH**/ ?>