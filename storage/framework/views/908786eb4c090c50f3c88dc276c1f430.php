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
      <form id="startSessionForm" method="POST" style="display:none; margin-right:8px;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary" id="startSessionBtn">Start Session</button>
      </form>
      <form id="endSessionForm" method="POST" style="display:none; margin-right:8px;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-danger" id="endSessionBtn">Complete</button>
      </form>
      <form id="missedForm" method="POST" style="display:none; margin-right:8px;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-danger" id="missedBtn">Mark as Missed</button>
      </form>
      <form id="cancelForm" method="POST" style="display:none; margin-right:8px;">\
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-secondary" id="cancelBtn">Cancel</button>
      </form>
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
function openReviewModal(appointmentId, detailsHtml, approveUrl, declineUrl, cancelUrl, status, startUrl, el) {
  var body = document.getElementById('review-modal-body');
  body.innerHTML = detailsHtml;
  try {
    if (el && el.dataset) {
      var prev = el.dataset.prev || '';
      var req = el.dataset.req || '';
      var extra = '';
      if (prev) extra += '<div style="font-size:0.95em; margin-top:6px;"><strong>Previous Appointment Time:</strong> ' + prev + '</div>';
      if (req) extra += '<div style="font-size:0.95em;"><strong>Preferred New Time:</strong> ' + req + '</div>';
      if (extra) body.innerHTML = body.innerHTML + extra;
    }
  } catch(e) {}
  document.getElementById('reviewAppointmentModal').style.display = 'flex';
  // set form actions
  var approveForm = document.getElementById('approveForm');
  var declineForm = document.getElementById('declineForm');
  var cancelForm = document.getElementById('cancelForm');
  var missedForm = document.getElementById('missedForm');
  if (approveForm) approveForm.action = approveUrl || '';
  if (declineForm) declineForm.action = declineUrl || '';
  if (cancelForm) {
    cancelForm.action = cancelUrl || '';
    cancelForm.style.display = 'none';
  }
  if (missedForm) {
    missedForm.action = '/Counselor/appointments/' + appointmentId + '/missed';
    missedForm.style.display = (status === 'approved' || status === 'rescheduled') ? 'inline-block' : 'none';
  }
  window.currentAppointmentId = appointmentId;
  status = (status || '').toLowerCase();
  // Remove any existing status badges
  var oldIn = document.getElementById('inSessionBadge'); if (oldIn && oldIn.parentNode) oldIn.parentNode.removeChild(oldIn);
  var oldComp = document.getElementById('completedBadge'); if (oldComp && oldComp.parentNode) oldComp.parentNode.removeChild(oldComp);
  var oldCancelled = document.getElementById('cancelledBadge'); if (oldCancelled && oldCancelled.parentNode) oldCancelled.parentNode.removeChild(oldCancelled);
  // Reset decline inputs/buttons
  if (document.getElementById('decline_reason')) document.getElementById('decline_reason').style.display = 'none';
  if (document.getElementById('submitDeclineBtn')) document.getElementById('submitDeclineBtn').style.display = 'none';
  if (document.getElementById('declineBtn')) document.getElementById('declineBtn').style.display = 'inline-block';
  // Reset start/end forms
  if (document.getElementById('startSessionForm')) { document.getElementById('startSessionForm').style.display = 'none'; document.getElementById('startSessionForm').action = ''; }
  if (document.getElementById('endSessionForm')) { document.getElementById('endSessionForm').style.display = 'none'; document.getElementById('endSessionForm').action = ''; }
  var rescheduleEl = document.getElementById('rescheduleBtn');
  var closeEl = document.getElementById('closeReviewBtn');
  // Hide Close and show Reschedule if declined, else show Close and hide Reschedule
  if (status === 'declined') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (rescheduleEl) rescheduleEl.style.display = 'none'; // hide reschedule when declined
    if (closeEl) closeEl.style.display = 'none';
  } else if (status === 'approved' || status === 'rescheduled') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (rescheduleEl) rescheduleEl.style.display = 'inline-block';
    if (closeEl) closeEl.style.display = 'inline-block';
    // Show Start Session button for approved appointments and wire its action
    var startFormEl = document.getElementById('startSessionForm');
    if (startFormEl) {
      var startAction = '';
      try { if (typeof startUrl !== 'undefined' && startUrl) startAction = startUrl; } catch(e) { startAction = ''; }
      if (!startAction) startAction = '/Counselor/appointments/' + appointmentId + '/start';
      startFormEl.action = startAction;
      startFormEl.style.display = 'inline-block';
    }
    // Ensure endSession is hidden when showing start
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
  } else {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'inline';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'inline';
    if (rescheduleEl) rescheduleEl.style.display = 'none';
    if (closeEl) closeEl.style.display = 'inline-block';
  }
  // If appointment is already ongoing, ensure approve/decline/start are hidden and show In Session badge
  if (status === 'ongoing') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (document.getElementById('startSessionForm')) document.getElementById('startSessionForm').style.display = 'none';
    var endForm = document.getElementById('endSessionForm');
    if (endForm) {
      var endAction = '';
      if (typeof startUrl !== 'undefined' && startUrl) {
        try { endAction = startUrl.replace(/\/start(\/?$)/, '/end'); } catch(e) { endAction = ''; }
      }
      if (!endAction) endAction = '/Counselor/appointments/' + appointmentId + '/end';
      endForm.action = endAction;
      endForm.style.display = 'inline-block';
    }
    var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('inSessionBadge')) {
      var badge = document.createElement('span'); badge.className = 'badge badge-warning'; badge.id = 'inSessionBadge'; badge.style.marginRight = '8px'; badge.innerText = 'In Session'; footer.insertBefore(badge, footer.firstChild);
    }
  }
  // If appointment is already completed, hide action buttons and show Completed badge
  if (status === 'completed') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (document.getElementById('cancelForm')) document.getElementById('cancelForm').style.display = 'none';
    if (document.getElementById('startSessionForm')) document.getElementById('startSessionForm').style.display = 'none';
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
    var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
    var inBadge = document.getElementById('inSessionBadge'); if (inBadge && inBadge.parentNode) inBadge.parentNode.removeChild(inBadge);
    var cancelledBadge = document.getElementById('cancelledBadge'); if (cancelledBadge && cancelledBadge.parentNode) cancelledBadge.parentNode.removeChild(cancelledBadge);
    if (footer && !document.getElementById('completedBadge')) {
      var cbadge = document.createElement('span'); cbadge.className = 'badge badge-success'; cbadge.id = 'completedBadge'; cbadge.style.marginRight = '8px'; cbadge.innerText = 'Complete'; footer.insertBefore(cbadge, footer.firstChild);
    }
  }
  // Show cancel when appointment is Pending, Approved, or Rescheduled (but not if Missed)
  if (document.getElementById('cancelForm')) {
    var cancellable = ['pending', 'approved', 'rescheduled'];
    if (cancellable.includes(status) && status !== 'missed') {
      document.getElementById('cancelForm').style.display = 'inline-block';
    } else {
      document.getElementById('cancelForm').style.display = 'none';
    }
  }
  // If appointment is cancelled or missed, hide all action buttons and show appropriate badge
  if (status === 'cancelled' || status === 'missed') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (document.getElementById('startSessionForm')) document.getElementById('startSessionForm').style.display = 'none';
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
    if (document.getElementById('cancelForm')) document.getElementById('cancelForm').style.display = 'none';
    if (rescheduleEl) rescheduleEl.style.display = 'none';
    if (closeEl) closeEl.style.display = 'inline-block';
    var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
    if (status === 'cancelled' && footer && !document.getElementById('cancelledBadge')) {
      var badge = document.createElement('span'); badge.className = 'badge badge-danger'; badge.id = 'cancelledBadge'; badge.style.marginRight = '8px'; badge.innerText = 'Cancelled'; footer.insertBefore(badge, footer.firstChild);
    } else if (status === 'missed' && footer && !document.getElementById('missedBadge')) {
      var badge = document.createElement('span'); badge.className = 'badge badge-danger'; badge.id = 'missedBadge'; badge.style.marginRight = '8px'; badge.innerText = 'Missed'; footer.insertBefore(badge, footer.firstChild);
    }
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

<script>
// Attach submit handler to Approve form to perform AJAX POST and show centered error modal on conflict (Counselor)
document.addEventListener('DOMContentLoaded', function () {
  var approveForm = document.getElementById('approveForm');
  if (!approveForm) return;

  approveForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var action = approveForm.action;
    if (!action) return;

    var tokenInput = approveForm.querySelector('input[name="_token"]');
    var token = tokenInput ? tokenInput.value : '';
    var btn = document.getElementById('approveBtn');
    if (btn) { btn.disabled = true; btn.innerText = 'Approving...'; }

    fetch(action, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({})
    }).then(function (res) {
      if (!res.ok) {
        if (res.status === 422) {
          return res.json().then(function (err) {
            var msg = (err && (err.error || err.message)) ? (err.error || err.message) : 'The appointment is already taken.';
            if (typeof showError === 'function') {
              showError(msg);
            } else {
              alert(msg);
            }
            throw new Error(msg);
          });
        }
        return res.text().then(function (text) { throw new Error('Server returned ' + res.status + ': ' + text); });
      }
      return res.json().catch(function () { return { success: true }; });
    }).then(function (data) {
      // On success, update modal UI to hide approve/decline and show In Session badge
      if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
      if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';

      var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
      if (footer && !document.getElementById('inSessionBadge')) {
        var badge = document.createElement('span');
        badge.className = 'badge badge-warning';
        badge.id = 'inSessionBadge';
        badge.style.marginRight = '8px';
        badge.innerText = 'Approved';
        footer.insertBefore(badge, footer.firstChild);
      }

      // Update in-page appointment status cell if present
      if (window.currentAppointmentId) {
        var statusEl = document.querySelector('[data-appointment-status="' + window.currentAppointmentId + '"]');
        if (statusEl) statusEl.innerText = 'In Session';
      }
    }).catch(function (err) {
      console.error('Approve failed', err);
    }).finally(function () {
      if (btn) { btn.disabled = false; btn.innerText = 'Approve'; }
    });
  });
});
</script>

<!-- Start/End session AJAX handlers (copied from Head modal) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var startForm = document.getElementById('startSessionForm');
  if (!startForm) return;

  startForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var action = startForm.action;
    if (!action) return;

    var tokenInput = startForm.querySelector('input[name="_token"]');
    var token = tokenInput ? tokenInput.value : '';
    function getCookie(name) {
      var v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
      return v ? v.pop() : '';
    }
    var xsrf = getCookie('XSRF-TOKEN');

    var btn = document.getElementById('startSessionBtn');
    if (btn) { btn.disabled = true; btn.innerText = 'Starting...'; }

    fetch(action, {
      method: 'POST',
      credentials: 'same-origin',
      headers: (function(){
        var h = {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'X-Requested-With': 'XMLHttpRequest'
        };
        if (xsrf) {
          try { h['X-XSRF-TOKEN'] = decodeURIComponent(xsrf); } catch(e){ h['X-XSRF-TOKEN'] = xsrf; }
        }
        return h;
      })(),
      body: JSON.stringify({})
    }).then(function (res) {
      if (!res.ok) {
        return res.text().then(function (text) { throw new Error('Server returned ' + res.status + ': ' + text); });
      }
      return res.json().catch(function () { return { success: true }; });
    }).then(function (data) {
      if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
      if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
      if (startForm) startForm.style.display = 'none';

      var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
      if (footer) {
        var cb = document.getElementById('cancelledBadge');
        if (cb && cb.parentNode) cb.parentNode.removeChild(cb);
        var badge = document.createElement('span');
        badge.className = 'badge badge-warning';
        badge.id = 'inSessionBadge';
        badge.style.marginRight = '8px';
        badge.innerText = 'In Session';
        footer.insertBefore(badge, footer.firstChild);
      }

      if (window.currentAppointmentId) {
        var statusEl = document.querySelector('[data-appointment-status="' + window.currentAppointmentId + '"]');
        if (statusEl) statusEl.innerText = 'In Session';
      }

    }).catch(function (err) {
      console.error('Start session failed', err);
      var msg = err && err.message ? err.message : 'Failed to start session. Refresh the page and try again.';
      alert(msg);
    }).finally(function () {
      if (btn) { btn.disabled = false; btn.innerText = 'Start Session'; }
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var endForm = document.getElementById('endSessionForm');
  if (!endForm) return;

  endForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var action = endForm.action;
    if (!action) return;

    var tokenInput = endForm.querySelector('input[name="_token"]');
    var token = tokenInput ? tokenInput.value : '';
    var btn = document.getElementById('endSessionBtn');
    if (btn) { btn.disabled = true; btn.innerText = 'Completing...'; }

    fetch(action, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({})
    }).then(function (res) {
      if (!res.ok) return res.text().then(function (text) { throw new Error('Server returned ' + res.status + ': ' + text); });
      return res.json().catch(function () { return { success: true }; });
    }).then(function (data) {
      var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
      if (footer) {
        var cb = document.getElementById('cancelledBadge');
        if (cb && cb.parentNode) cb.parentNode.removeChild(cb);
        var badge = document.getElementById('inSessionBadge');
        if (badge) { badge.innerText = 'Completed'; badge.className = 'badge badge-success'; }
        if (endForm) endForm.style.display = 'none';
      }

      if (window.currentAppointmentId) {
        var statusEl = document.querySelector('[data-appointment-status="' + window.currentAppointmentId + '"]');
        if (statusEl) statusEl.innerText = 'Complete';
      }

    }).catch(function (err) {
      console.error('End session failed', err);
      alert(err && err.message ? err.message : 'Failed to mark session as done.');
    }).finally(function () {
      if (btn) { btn.disabled = false; btn.innerText = 'Complete'; }
    });
  });

  function showDeclineReason() {
    var reasonTextarea = document.getElementById('decline_reason');
    var declineBtn = document.getElementById('declineBtn');
    var submitBtn = document.getElementById('submitDeclineBtn');
    
    if (reasonTextarea && declineBtn && submitBtn) {
      reasonTextarea.style.display = 'block';
      declineBtn.style.display = 'none';
      submitBtn.style.display = 'inline-block';
    }
  }

  function handleDeclineSubmit(event) {
    event.preventDefault();
    
    var form = event.target;
    var reasonTextarea = document.getElementById('decline_reason');
    var submitBtn = document.getElementById('submitDeclineBtn');
    
    if (!reasonTextarea || !reasonTextarea.value.trim()) {
      alert('Please enter a reason for declining.');
      return false;
    }

    if (submitBtn) submitBtn.disabled = true;
    
    var formData = new FormData(form);
    fetch(form.action, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData
    })
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      closeReviewModal();
      
      // Update status in appointment list if available
      if (window.currentAppointmentId) {
        var statusEl = document.querySelector('[data-appointment-status="' + window.currentAppointmentId + '"]');
        if (statusEl) statusEl.innerText = 'Declined';
      }
      
      // Refresh calendar if available
      if (window.calendar) window.calendar.refetchEvents();
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Failed to decline appointment. Please try again.');
    })
    .finally(() => {
      if (submitBtn) submitBtn.disabled = false;
    });

    return false;
  }
});
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/Modal/reviewModal.blade.php ENDPATH**/ ?>