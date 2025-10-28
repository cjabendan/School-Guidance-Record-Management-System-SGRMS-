<!-- filepath: c:\Users\Rhylyn\School-Guidance-Record-Management-System-SGRMS-\resources\views\Parent\modal\requestApppointmentModal.blade.php -->

<?php
  // Determine whether to show the CREATE modal on page load. Only open the
  // create modal automatically when the create form was submitted — we detect
  // that via the hidden `form_origin` input preserved in old input. Other
  // validation errors will be shown in a separate error modal to avoid
  // reusing the full create form as an "error message" UI.
  $modalDisplay = (old('form_origin') === 'create_appointment') ? 'flex' : 'none';
?>
<div id="requestAppointmentModal" class="custom-modal" style="display:<?php echo e($modalDisplay); ?>;">
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
      <input type="hidden" name="form_origin" value="create_appointment">
      <div class="modal-header">
        <span class="modal-title"><i class="fi fi-rr-calendar"></i> Request Appointment</span>
        <button type="button" class="close-modal-btn" onclick="closeModal()">×</button>
      </div>
        <!-- Error-only modal (small) - shows when there are validation errors not originating from create form -->
        <?php $showErrorModal = $errors->any() && old('form_origin') !== 'create_appointment'; ?>
        <div id="appointmentErrorModal" class="custom-modal" style="display:<?php echo e($showErrorModal ? 'flex' : 'none'); ?>;">
          <div class="custom-modal-content" style="max-width:420px;">
            <div class="modal-header">
              <span class="modal-title"><i class="fi fi-rr-exclamation"></i> Error</span>
              <button type="button" class="close-modal-btn" onclick="closeErrorModal()">×</button>
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
              <option value="<?php echo e($counselor->id); ?>" <?php echo e((string)old('counselor_id') === (string)$counselor->id ? 'selected' : ''); ?>><?php echo e($counselor->first_name); ?> <?php echo e($counselor->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
          <label for="type_id" class="form-label">Appointment Type</label>
          <select name="type_id" id="type_id" class="form-control" required>
            <option value="">Select Type</option>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($type->id); ?>" <?php echo e((string)old('type_id') === (string)$type->id ? 'selected' : ''); ?>><?php echo e($type->type_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value="other" <?php echo e(old('type_id') === 'other' ? 'selected' : ''); ?>>Other</option>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3" id="other-type-wrapper" style="display:<?php echo e(old('type_id') === 'other' ? 'block' : 'none'); ?>;">
          <label for="other_type" class="form-label">Other Type</label>
          <input type="text" name="other_type" id="other_type" class="form-control" placeholder="Enter custom appointment type" value="<?php echo e(old('other_type')); ?>">
        </div>
        <div class="divider"></div>
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
        <div class="divider"></div>
        <div class="mb-3">
          <label for="reason" class="form-label">Reason</label>
          <textarea name="reason" id="reason" class="form-control" required><?php echo e(old('reason')); ?></textarea>
        </div>
        <div class="divider"></div>
        <div class="mb-3">
          <label for="appointment_datetime" class="form-label">Date & Time</label>
          <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" class="form-control" required value="<?php echo e(old('appointment_datetime')); ?>">
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
  // Show modal immediately with a loading state so users see instant feedback
  const modal = document.getElementById('requestAppointmentModal');
  const form = document.querySelector('#requestAppointmentModal form');
  modal.style.display = 'flex';

  // Optionally show a small loading indicator inside the modal
  let loadingEl = document.getElementById('modal-loading-indicator');
  if (!loadingEl) {
    loadingEl = document.createElement('div');
    loadingEl.id = 'modal-loading-indicator';
    loadingEl.style.cssText = 'position:relative;padding:8px 0;text-align:center;color:#555;font-weight:600;';
    loadingEl.textContent = 'Loading appointment...';
    const modalBody = modal.querySelector('.modal-body');
    if (modalBody) modalBody.insertBefore(loadingEl, modalBody.firstChild);
  }

  // Disable form inputs while loading
  const disableForm = (disable) => {
    form.querySelectorAll('input, textarea, button, select').forEach(el => el.disabled = disable);
  };
  disableForm(true);

  fetch(`/Head/appointments/${appointmentId}/json`)
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      // Pre-fill fields
      document.getElementById('counselor_id').value = data.counselor_id || '';
      document.getElementById('type_id').value = data.type_id || '';
      // Set students (Select2)
      if (window.jQuery && $('#student_id').length) {
        $('#student_id').val(data.student_ids || []).trigger('change');
      }
      document.getElementById('reason').value = data.reason || '';
      if (data.appointment_datetime) {
        document.getElementById('appointment_datetime').value = data.appointment_datetime.replace(' ', 'T');
      }
      // Change form action/method for update
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
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.textContent = 'Update';
    })
    .catch(err => {
      console.error('Failed to load appointment data:', err);
      // Show error to user inside modal
      let errEl = document.getElementById('modal-error-message');
      if (!errEl) {
        errEl = document.createElement('div');
        errEl.id = 'modal-error-message';
        errEl.style.cssText = 'margin:8px 0;padding:8px;border-radius:6px;background:#fff0f0;color:#b91c1c;border:1px solid #fca5a5;font-weight:600;';
        const modalBody = modal.querySelector('.modal-body');
        if (modalBody) modalBody.insertBefore(errEl, modalBody.firstChild);
      }
      errEl.textContent = 'Unable to load appointment. Please try again.';
      // leave modal open so user can retry or cancel
    })
    .finally(() => {
      // Remove loading indicator and re-enable form
      const loading = document.getElementById('modal-loading-indicator');
      if (loading) loading.remove();
      disableForm(false);
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
  // hide other type input when closing/reset
  const otherWrapper = document.getElementById('other-type-wrapper');
  const otherInput = document.getElementById('other_type');
  if (otherWrapper) otherWrapper.style.display = 'none';
  if (otherInput) otherInput.value = '';
}
      $(document).ready(function() {
        // Initialize Select2 for student multi-select and restore old values if any
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
            if (otherInput) {
              otherInput.required = false;
              // don't wipe value if we want to preserve it on error; only clear when changing away interactively
            }
          }
        }

        if (typeSelect) {
          typeSelect.addEventListener('change', function() {
            // If user changes away from 'other' interactively, clear the other_type field
            if (this.value !== 'other' && otherInput) {
              otherInput.value = '';
            }
            updateOtherVisibility();
          });
        }

        // Run once on load to set correct state (handles old input)
        updateOtherVisibility();
      });
  </script>
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/modal/requestApppointmentModal.blade.php ENDPATH**/ ?>