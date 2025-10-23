<!-- Appointment Review Modal -->
<div id="reviewAppointmentModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">View Appointment</h5>
      <button type="button" class="close-modal-btn" onclick="closeReviewModal()">×</button>
    </div>
    <div class="modal-body" id="review-modal-body">
      <!-- Appointment details will be loaded here via JS -->
    </div>
    <div class="modal-footer">
      <form id="approveForm" method="POST" style="display:inline;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-success" id="approveBtn">Approve</button>
      </form>
      <form id="declineForm" method="POST" style="display:inline;" onsubmit="return handleDeclineSubmit(event)">
        <?php echo csrf_field(); ?>
  <textarea name="decline_reason" id="decline_reason" class="form-control" placeholder="Enter reason for declining..." style="margin-bottom:12px; display:none; font-size:1.3em; font-weight:600; border-radius:10px; border:2px solid #ef4444; background:#fff5f5; color:#b91c1c; padding:18px 20px; min-height:70px; transition:box-shadow 0.2s; box-shadow:0 2px 8px rgba(239,68,68,0.08);" required></textarea>
        <button type="button" class="btn btn-danger" id="declineBtn" onclick="showDeclineReason()">Decline</button>
        <button type="submit" class="btn btn-danger" id="submitDeclineBtn" style="display:none;">Submit Decline</button>
      </form>
  <button type="button" class="btn btn-warning" id="rescheduleBtn" style="display:none;" onclick="openRescheduleModal(window.currentAppointmentId)">Reschedule</button>
  
    </div>
  </div>
</div>
<script>
function openReviewModal(appointmentId, detailsHtml, approveUrl, declineUrl, status) {
  document.getElementById('review-modal-body').innerHTML = detailsHtml;
  document.getElementById('reviewAppointmentModal').style.display = 'flex';
  document.getElementById('approveForm').action = approveUrl;
  document.getElementById('declineForm').action = declineUrl;
  window.currentAppointmentId = appointmentId;
  status = (status || '').toLowerCase();
  document.getElementById('decline_reason').style.display = 'none';
  document.getElementById('submitDeclineBtn').style.display = 'none';
  document.getElementById('declineBtn').style.display = 'inline-block';
  // Hide Close and show Reschedule if declined, else show Close and hide Reschedule
  if (status === 'declined') {
    document.getElementById('approveForm').style.display = 'none';
    document.getElementById('declineForm').style.display = 'none';
    document.getElementById('rescheduleBtn').style.display = 'inline-block';
    document.getElementById('closeReviewBtn').style.display = 'none';
  } else if (status === 'approved') {
    document.getElementById('approveForm').style.display = 'none';
    document.getElementById('declineForm').style.display = 'none';
    document.getElementById('rescheduleBtn').style.display = 'inline-block';
    document.getElementById('closeReviewBtn').style.display = 'inline-block';
  } else {
    document.getElementById('approveForm').style.display = 'inline';
    document.getElementById('declineForm').style.display = 'inline';
    document.getElementById('rescheduleBtn').style.display = 'none';
    document.getElementById('closeReviewBtn').style.display = 'inline-block';
  }
}

function showDeclineReason() {
  document.getElementById('decline_reason').style.display = 'block';
  document.getElementById('declineBtn').style.display = 'none';
  document.getElementById('submitDeclineBtn').style.display = 'inline-block';
  document.getElementById('decline_reason').focus();
}

function handleDeclineSubmit(e) {
  var reason = document.getElementById('decline_reason').value.trim();
  if (!reason) {
    alert('Please enter a reason for declining.');
    document.getElementById('decline_reason').focus();
    return false;
  }
  return true;
}
function closeReviewModal() {
  document.getElementById('reviewAppointmentModal').style.display = 'none';
}

function openRescheduleModal(appointmentId) {
  // Close review modal
  closeReviewModal();

  // Open the request modal immediately to improve perceived responsiveness
  const requestModal = document.getElementById('requestAppointmentModal');
  if (requestModal) requestModal.style.display = 'flex';

  // Add a small loading indicator inside the modal if not present
  let loadingEl = document.getElementById('modal-loading-indicator');
  const modalBody = requestModal ? requestModal.querySelector('.modal-body') : null;
  if (!loadingEl && modalBody) {
    loadingEl = document.createElement('div');
    loadingEl.id = 'modal-loading-indicator';
    loadingEl.style.cssText = 'position:relative;padding:8px 0;text-align:center;color:#555;font-weight:600;';
    loadingEl.textContent = 'Loading appointment...';
    modalBody.insertBefore(loadingEl, modalBody.firstChild);
  }

  // Disable form inputs while loading
  const form = document.querySelector('#requestAppointmentModal form');
  const disableForm = (disable) => {
    if (!form) return;
    form.querySelectorAll('input, textarea, button, select').forEach(el => {
      // Keep the close button enabled
      if (el.classList && el.classList.contains('close-modal-btn')) return;
      el.disabled = disable;
    });
  };
  disableForm(true);

  // Fetch appointment data via AJAX and pre-fill counselor request modal
  fetch(`/Counselor/appointments/${appointmentId}/json`)
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      // Fill in the form fields (using jQuery/select2 if available)
      if (window.jQuery && $('#counselor_id').length) $('#counselor_id').val(data.counselor_id).trigger('change');
      if (window.jQuery && $('#type_id').length) $('#type_id').val(data.type_id).trigger('change');
      if (window.jQuery && $('#student_id').length) $('#student_id').val(data.student_ids).trigger('change');
      if (data.reason) {
        const reasonEl = document.getElementById('reason');
        if (reasonEl) reasonEl.value = data.reason;
      }
      if (data.appointment_datetime) {
        const dtEl = document.getElementById('appointment_datetime');
        if (dtEl) dtEl.value = data.appointment_datetime.replace(' ', 'T');
      }

      // Change the form to perform an update (PUT) on the counselor endpoint
      if (form) {
        form.action = `/Counselor/appointments/${appointmentId}`;
        // Add/replace _method hidden input to PUT
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
          methodInput = document.createElement('input');
          methodInput.type = 'hidden';
          methodInput.name = '_method';
          form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
        // Change submit button text if present
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.textContent = 'Update';
      }
    })
    .catch(err => {
      console.error('Failed to load counselor appointment data:', err);
      // Show an inline error message
      let errEl = document.getElementById('modal-error-message');
      if (!errEl && modalBody) {
        errEl = document.createElement('div');
        errEl.id = 'modal-error-message';
        errEl.style.cssText = 'margin:8px 0;padding:8px;border-radius:6px;background:#fff0f0;color:#b91c1c;border:1px solid #fca5a5;font-weight:600;';
        modalBody.insertBefore(errEl, modalBody.firstChild);
      }
      if (errEl) errEl.textContent = 'Unable to load appointment. Please try again.';
    })
    .finally(() => {
      // Remove loading indicator and re-enable form
      const loading = document.getElementById('modal-loading-indicator');
      if (loading) loading.remove();
      disableForm(false);
    });
}
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/Modal/reviewModal.blade.php ENDPATH**/ ?>