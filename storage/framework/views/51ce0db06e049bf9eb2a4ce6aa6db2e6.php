<?php
  $modalDisplay = (old('form_origin') === 'create_appointment') ? 'flex' : 'none';
?>

<div id="requestAppointmentModal" class="custom-modal" style="display:<?php echo e($modalDisplay); ?>;">
  <div class="custom-modal-content">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    /* Make selected items in Select2 render in a vertical column inside the modal */
    #requestAppointmentModal .select2-container--default .select2-selection--multiple {
      display: flex;
      flex-direction: column;
      max-height: 150px;
    }
    #requestAppointmentModal .select2-container--default .select2-selection__choice {
      display: block;
      width: 100%;
      border-radius: 6px;
    }
    /* Keep the search input usable and full width */
    #requestAppointmentModal .select2-container--default .select2-search--inline {
      display: block;
      width: 100%;
    }
  </style>
  <form method="POST" action="<?php echo e(route('Student.appointments.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="form_origin" value="create_appointment">
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
              <option value="<?php echo e($counselor->id); ?>"><?php echo e($counselor->first_name); ?> <?php echo e($counselor->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <!-- Appointment Type -->
        <div class="mb-3">
          <label for="type_id" class="form-label">Appointment Type</label>
          <select name="type_id" id="type_id" class="form-control" required>
            <option value="">Select Type</option>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($type->id); ?>"><?php echo e($type->type_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3" id="other-type-wrapper" style="display:none;">
          <label for="other_type" class="form-label">Other Type</label>
          <input type="text" name="other_type" id="other_type" class="form-control" placeholder="Enter custom appointment type">
        </div>
        <!-- Student (auto-assigned from auth) -->
        <input type="hidden" name="student_id" id="student_id" value="<?php echo e(auth()->id()); ?>">
        <!-- Reason -->
        <div class="mb-3">
          <label for="reason" class="form-label">Reason</label>
          <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>
        <!-- Date & Time -->
        <div class="mb-3">
          <label for="appointment_datetime" class="form-label">Date & Time</label>
          <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" class="form-control" required>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Always use Asia/Manila timezone for input
          function setManilaNow() {
            const now = new Date();
            // Convert to Asia/Manila
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const manilaOffset = 8 * 60; // UTC+8
            const manila = new Date(utc + (manilaOffset * 60000));
            // Format for datetime-local
            const pad = n => n.toString().padStart(2, '0');
            const value = `${manila.getFullYear()}-${pad(manila.getMonth()+1)}-${pad(manila.getDate())}T${pad(manila.getHours())}:${pad(manila.getMinutes())}`;
            document.getElementById('appointment_datetime').value = value;
          }
          setManilaNow();

          // Show/hide Other type input
          const typeSelect = document.getElementById('type_id');
          const otherWrapper = document.getElementById('other-type-wrapper');
          const otherInput = document.getElementById('other_type');
          if (typeSelect) {
            typeSelect.addEventListener('change', function() {
              if (this.value === 'other') {
                if (otherWrapper) otherWrapper.style.display = 'block';
                if (otherInput) otherInput.required = true;
              } else {
                if (otherWrapper) otherWrapper.style.display = 'none';
                if (otherInput) { otherInput.required = false; otherInput.value = ''; }
              }
            });
          }
          // No Select2 for student side; student is determined by authenticated user
        });
        </script>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Request</button>
      </div>
    </form>
  </div>

  <!-- Error-only modal (small) - shows when there are validation errors not originating from create form -->
  <?php $showErrorModal = $errors->any() && old('form_origin') !== 'create_appointment'; ?>
  <div id="appointmentErrorModal" class="custom-modal" style="display:<?php echo e($showErrorModal ? 'flex' : 'none'); ?>;">
    <div class="custom-modal-content" style="max-width:420px;">
      <div class="modal-header">
        <span class="modal-title"><i class="fi fi-rr-exclamation"></i> Error</span>
        <button type="button" class="close-modal-btn" onclick="closeErrorModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" style="margin-bottom:0; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600; font-size:1.05em; padding:12px 16px; display:flex; align-items:center; gap:8px;">
          <i class="fi fi-rr-exclamation" style="color:#b91c1c; font-size:1.3em;"></i>
          <div style="line-height:1.2;"><?php echo e($errors->first()); ?></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeErrorModal()">Close</button>
      </div>
    </div>
  </div>
  <script>
    function closeErrorModal() {
      document.getElementById('appointmentErrorModal').style.display = 'none';
    }
  </script>

</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Student/modal/requestApppointmentModal.blade.php ENDPATH**/ ?>