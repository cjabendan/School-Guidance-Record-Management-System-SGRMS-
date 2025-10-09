<!-- filepath: c:\Users\Rhylyn\School-Guidance-Record-Management-System-SGRMS-\resources\views\Parent\modal\requestApppointmentModal.blade.php -->

<div id="requestAppointmentModal" class="custom-modal" style="display:none;">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .close-modal-btn {
      background: none;
      border: none;
      font-size: 2em;
      color: #6b7280;
      cursor: pointer;
      transition: color 0.2s;
    }
    .close-modal-btn:hover {
      color: #ef4444;
    }
  </style>
  <div class="custom-modal-content">
    <form method="POST" action="<?php echo e(route('Head.appointments.store')); ?>">
      <?php echo csrf_field(); ?>
      <div class="modal-header">
        <span class="modal-title"><i class="fi fi-rr-calendar"></i> Request Appointment</span>
        <button type="button" class="close-modal-btn" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body">
        <?php if($errors->has('appointment_datetime')): ?>
        <div class="alert alert-danger" style="margin-bottom:16px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600; font-size:1.05em; padding:10px 16px; display:flex; align-items:center; gap:8px;">
          <i class="fi fi-rr-exclamation" style="color:#b91c1c; font-size:1.3em;"></i>
          <span><?php echo e($errors->first('appointment_datetime')); ?></span>
        </div>
        <?php endif; ?>
        <div class="mb-3">
          <label for="counselor_id" class="form-label">Choose Counselor</label>
          <select name="counselor_id" id="counselor_id" class="form-control" required>
            <option value="">Select Counselor</option>
            <?php $__currentLoopData = $counselors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counselor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($counselor->id); ?>"><?php echo e($counselor->first_name); ?> <?php echo e($counselor->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
          <label for="type_id" class="form-label">Appointment Type</label>
          <select name="type_id" id="type_id" class="form-control" required>
            <option value="">Select Type</option>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($type->id); ?>"><?php echo e($type->type_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
          <label for="student_id" class="form-label">Child</label>
          <select name="student_id[]" id="student_id" class="form-control" multiple required style="width: 100%;">
            <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($child->s_id); ?>">
                [ID: <?php echo e($child->s_id); ?>] <?php echo e($child->user->first_name ?? ''); ?> <?php echo e($child->user->last_name ?? ''); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
          <label for="reason" class="form-label">Reason</label>
          <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
          <label for="appointment_datetime" class="form-label">Date & Time</label>
          <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" style="border-radius:8px; font-weight:600; background:#2563eb; border:none;">Request</button>
      </div>
    </form>
  </div>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
// Edit/Reschedule logic
function openRescheduleModal(appointmentId) {
  alert('Edit button clicked!'); // Debug: confirm button triggers JS
  // Fetch appointment data via AJAX
  fetch(`/Head/appointments/${appointmentId}/json`)
    .then(response => response.json())
    .then(data => {
      // Show modal
      document.getElementById('requestAppointmentModal').style.display = 'flex';
      // Pre-fill fields
      document.getElementById('counselor_id').value = data.counselor_id;
      document.getElementById('type_id').value = data.type_id;
      // Set students (Select2)
      $('#student_id').val(data.student_ids).trigger('change');
      document.getElementById('reason').value = data.reason;
      document.getElementById('appointment_datetime').value = data.appointment_datetime.replace(' ', 'T');
      // Change form action/method for update
      const form = document.querySelector('#requestAppointmentModal form');
      form.action = `/Head/appointments/${appointmentId}`;
      // Add hidden _method input for PUT
      let methodInput = form.querySelector('input[name="_method"]');
      if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
      }
      methodInput.value = 'PUT';
      // Change submit button text
      form.querySelector('button[type="submit"]').textContent = 'Update';
    });
}
// Reset modal on close
function closeModal() {
  document.getElementById('requestAppointmentModal').style.display = 'none';
  const form = document.querySelector('#requestAppointmentModal form');
  form.action = "<?php echo e(route('Head.appointments.store')); ?>";
  let methodInput = form.querySelector('input[name="_method"]');
  if (methodInput) methodInput.remove();
  form.reset();
  $('#student_id').val(null).trigger('change');
  form.querySelector('button[type="submit"]').textContent = 'Request';
}
    $(document).ready(function() {
      $('#student_id').select2({
        placeholder: 'Search Student',
        allowClear: true,
        width: 'resolve',
        dropdownParent: $('#requestAppointmentModal')
      });
    });
  </script>
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/modal/requestApppointmentModal.blade.php ENDPATH**/ ?>