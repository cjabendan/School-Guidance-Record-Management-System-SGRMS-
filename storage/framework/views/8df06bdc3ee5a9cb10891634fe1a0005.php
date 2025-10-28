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
      <form id="cancelForm" method="POST" style="display:none; margin-right:8px;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-secondary" id="cancelBtn">Cancel Appointment</button>
      </form>

      <!-- NEW: Mark as Missed form (hidden by default) -->
      <form id="missedForm" method="POST" style="display:none; margin-right:8px;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-dark" id="missedBtn">Mark as Missed</button>
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
  const missedForm = document.getElementById('missedForm');
  if (missedForm) {
    missedForm.action = `/Head/appointments/${appointmentId}/missed`;
    // Show missed button for approved/rescheduled appointments that aren't completed/cancelled
    const st = (status || '').toLowerCase();
    missedForm.style.display = ['approved', 'rescheduled'].includes(st) && 
      !['completed', 'cancelled', 'declined', 'missed'].includes(st) ? 'inline' : 'none';
  }
  var body = document.getElementById('review-modal-body');

  // Get previous schedule if it exists
  var previousSchedule = '';
  if (el) {
    // Try to get previous schedule from data attribute
    previousSchedule = el.dataset.previousSchedule || '';
    
    // If not in data attribute, try to get from span
    if (!previousSchedule && el.querySelector('.appointment-datetime span')) {
      previousSchedule = el.querySelector('.appointment-datetime span')?.textContent?.trim() || '';
    }
  }

  // If we have previous schedule and this is an approved or rescheduled appointment
  if (previousSchedule && (status === 'approved' || status === 'rescheduled')) {
    // Find where we should insert the previous schedule (after Date & Time)
    var dateTimeMatch = detailsHtml.match(/(Date & Time:.*?)(<br>|<\/div>)/i);
    if (dateTimeMatch) {
      var insertPoint = dateTimeMatch.index + dateTimeMatch[1].length;
      
      // Create previous schedule HTML with improved styling
      var prevScheduleHtml = `
        <div style="margin:8px 0 12px 0;padding:10px 12px;border-radius:6px;background:#f0f9ff;border:1px solid #bae6fd;">
          <div style="color:#0369a1;font-size:0.9rem;margin-bottom:2px;">Previous Schedule:</div>
          <div style="color:#0c4a6e;font-weight:600;font-size:1.1rem;">${previousSchedule}</div>
        </div>
      `;
      
      // Insert the previous schedule information
      detailsHtml = detailsHtml.slice(0, insertPoint) + prevScheduleHtml + detailsHtml.slice(insertPoint);
    }
  }
  
  body.innerHTML = detailsHtml;

  // Show previous and preferred times only for rescheduled appointments
  if (el && el.dataset && status === 'rescheduled') {
    var prev = el.dataset.prev || '';
    var req = el.dataset.req || '';
    var extra = '';
    
    // Show previous time if available
    if (prev) {
      extra += '<div style="font-size:0.95em; margin-top:6px;"><strong>Previous:</strong> ' + prev + '</div>';
    }
    
    // Show preferred time if available
    if (req) {
      extra += '<div style="font-size:0.95em; margin-top:6px; color:#2563eb;"><strong>Preferred date to reschedule:</strong> ' + req + '</div>';
    }
    
    if (extra) {
      body.innerHTML += extra;
    }
  }

  document.getElementById('reviewAppointmentModal').style.display = 'flex';
  // set form actions
  var approveForm = document.getElementById('approveForm');
  var declineForm = document.getElementById('declineForm');
  var cancelForm = document.getElementById('cancelForm');
  if (approveForm) approveForm.action = approveUrl || '';
  if (declineForm) declineForm.action = declineUrl || '';
  if (cancelForm) {
    cancelForm.action = cancelUrl || '';
    // hidden by default; will be shown only for pending status
    cancelForm.style.display = 'none';
  }
  window.currentAppointmentId = appointmentId;
  status = (status || '').toLowerCase();
  // Remove any existing status badges from previous modal opens
  var oldIn = document.getElementById('inSessionBadge');
  if (oldIn && oldIn.parentNode) oldIn.parentNode.removeChild(oldIn);
  var oldComp = document.getElementById('completedBadge');
  if (oldComp && oldComp.parentNode) oldComp.parentNode.removeChild(oldComp);
  var oldCancelled = document.getElementById('cancelledBadge');
  if (oldCancelled && oldCancelled.parentNode) oldCancelled.parentNode.removeChild(oldCancelled);
    // Reset decline inputs/buttons
    if (document.getElementById('decline_reason')) document.getElementById('decline_reason').style.display = 'none';
    if (document.getElementById('submitDeclineBtn')) document.getElementById('submitDeclineBtn').style.display = 'none';
    if (document.getElementById('declineBtn')) document.getElementById('declineBtn').style.display = 'inline-block';
    // Always reset start session form visibility/action on open
    if (document.getElementById('startSessionForm')) {
      document.getElementById('startSessionForm').style.display = 'none';
      document.getElementById('startSessionForm').action = '';
    }
    // Always reset end session form visibility/action on open
    if (document.getElementById('endSessionForm')) {
      document.getElementById('endSessionForm').style.display = 'none';
      document.getElementById('endSessionForm').action = '';
    }
    // Safely hide reschedule/close if they don't exist
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
    // ensure End/Complete is hidden unless ongoing
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
    // show Start Session button only if startUrl provided
    if (typeof startUrl !== 'undefined' && startUrl) {
      if (document.getElementById('startSessionForm')) {
        document.getElementById('startSessionForm').action = startUrl;
        document.getElementById('startSessionForm').style.display = 'inline-block';
      }
    } else {
      if (document.getElementById('startSessionForm')) document.getElementById('startSessionForm').style.display = 'none';
    }
  } else if (status === 'missed') {
    // Hide approve/decline buttons for missed appointments
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
    if (rescheduleEl) rescheduleEl.style.display = 'none';
    if (closeEl) closeEl.style.display = 'inline-block';
  } else {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'inline';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'inline';
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
    if (rescheduleEl) rescheduleEl.style.display = 'none';
    if (closeEl) closeEl.style.display = 'inline-block';
  }
  // If appointment is already ongoing, ensure approve/decline/start are hidden and show In Session badge
  if (status === 'ongoing') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (document.getElementById('startSessionForm')) document.getElementById('startSessionForm').style.display = 'none';
    // Set end form action - derive from startUrl if available, fallback to RESTful path
    var endForm = document.getElementById('endSessionForm');
    if (endForm) {
      var endAction = '';
      if (typeof startUrl !== 'undefined' && startUrl) {
        try {
          endAction = startUrl.replace(/\/start(\/?$)/, '/end');
        } catch(e) { endAction = ''; }
      }
      if (!endAction) endAction = '/Head/appointments/' + appointmentId + '/end';
      endForm.action = endAction;
      endForm.style.display = 'inline-block';
    }
    var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('inSessionBadge')) {
      var badge = document.createElement('span');
      badge.className = 'badge badge-warning';
      badge.id = 'inSessionBadge';
      badge.style.marginRight = '8px';
      badge.innerText = 'In Session';
      footer.insertBefore(badge, footer.firstChild);
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
  // remove in-session badge if present
  var inBadge = document.getElementById('inSessionBadge');
  if (inBadge && inBadge.parentNode) inBadge.parentNode.removeChild(inBadge);
  // remove cancelled badge if present (ensure Completed does not also show Cancelled)
  var cancelledBadge = document.getElementById('cancelledBadge');
  if (cancelledBadge && cancelledBadge.parentNode) cancelledBadge.parentNode.removeChild(cancelledBadge);
    if (footer && !document.getElementById('completedBadge')) {
      var cbadge = document.createElement('span');
      cbadge.className = 'badge badge-success';
      cbadge.id = 'completedBadge';
      cbadge.style.marginRight = '8px';
      cbadge.innerText = 'Complete';
      footer.insertBefore(cbadge, footer.firstChild);
    }
  }
  // Show cancel when appointment is Pending, Approved, or Rescheduled
  if (document.getElementById('cancelForm')) {
    var cancellable = ['pending', 'approved', 'rescheduled'];
    if (cancellable.includes(status)) {
      document.getElementById('cancelForm').style.display = 'inline-block';
    } else {
      document.getElementById('cancelForm').style.display = 'none';
    }
  }
  // If appointment is cancelled, hide all action buttons and show a Cancelled badge
  if (status === 'cancelled') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
    if (document.getElementById('startSessionForm')) document.getElementById('startSessionForm').style.display = 'none';
    if (document.getElementById('endSessionForm')) document.getElementById('endSessionForm').style.display = 'none';
    if (document.getElementById('cancelForm')) document.getElementById('cancelForm').style.display = 'none';
    if (rescheduleEl) rescheduleEl.style.display = 'none';
    if (closeEl) closeEl.style.display = 'inline-block';

    var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('cancelledBadge')) {
      var badge = document.createElement('span');
      badge.className = 'badge badge-danger';
      badge.id = 'cancelledBadge';
      badge.style.marginRight = '8px';
      badge.innerText = 'Cancelled';
      footer.insertBefore(badge, footer.firstChild);
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
  // Store current appointment details from the review modal content
  const currentDateTime = document.querySelector('#review-modal-body')?.textContent.match(/Date & Time: ([^\n]+)/)?.[1] || '';

  // Close review modal
  closeReviewModal();

  // Open the request modal immediately to improve perceived responsiveness
  const requestModal = document.getElementById('requestAppointmentModal');
  if (requestModal) {
    requestModal.style.display = 'flex';
    
    // Add previous time info if available
    if (currentDateTime) {
      const infoDiv = document.createElement('div');
      infoDiv.className = 'previous-time-info';
      infoDiv.style.cssText = 'margin:12px 0 20px;padding:12px;border-radius:8px;background:#f0f9ff;color:#0369a1;border:1px solid #bae6fd;font-weight:500;font-size:1.1em;';
      infoDiv.innerHTML = `<strong>Previous Schedule:</strong> ${currentDateTime}`;
      
      const modalBody = requestModal.querySelector('.modal-body');
      if (modalBody) {
        modalBody.insertBefore(infoDiv, modalBody.firstChild);
      }
    }
  }

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

  // Fetch appointment data via AJAX and pre-fill request modal
  fetch(`/Head/appointments/${appointmentId}/json`)
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

      // Change the form to perform an update (PUT) on the head endpoint
      if (form) {
        form.action = `/Head/appointments/${appointmentId}`;
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
      console.error('Failed to load head appointment data:', err);
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
// Attach submit handler to Start Session form to perform AJAX POST
document.addEventListener('DOMContentLoaded', function () {
  var startForm = document.getElementById('startSessionForm');
  if (!startForm) return;

  startForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var action = startForm.action;
    if (!action) return;

    // Gather CSRF token from form
    var tokenInput = startForm.querySelector('input[name="_token"]');
    var token = tokenInput ? tokenInput.value : '';
    // Also try to read Laravel XSRF-TOKEN cookie (some setups use this)
    function getCookie(name) {
      var v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
      return v ? v.pop() : '';
    }
    var xsrf = getCookie('XSRF-TOKEN');

    // Disable button to prevent double submits
    var btn = document.getElementById('startSessionBtn');
    if (btn) { btn.disabled = true; btn.innerText = 'Starting...'; }

    fetch(action, {
      method: 'POST',
      credentials: 'same-origin', // ensure cookies (session) are sent
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
        // try to read response body for debugging
        return res.text().then(function (text) {
          throw new Error('Server returned ' + res.status + ': ' + text);
        });
      }
      return res.json().catch(function () { return { success: true }; });
    }).then(function (data) {
      // On success, update modal UI to show 'In Session' and hide approve/decline
      if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
      if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
      if (startForm) startForm.style.display = 'none';

      // Insert an In Session badge into modal footer
      var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
      if (footer) {
        // remove any cancelled badge left over
        var cb = document.getElementById('cancelledBadge');
        if (cb && cb.parentNode) cb.parentNode.removeChild(cb);
        var badge = document.createElement('span');
        badge.className = 'badge badge-warning';
        badge.id = 'inSessionBadge';
        badge.style.marginRight = '8px';
        badge.innerText = 'Approved';
        footer.insertBefore(badge, footer.firstChild);
      }

      // Try to update in-page appointment status cell if present
      if (window.currentAppointmentId) {
        var statusEl = document.querySelector('[data-appointment-status="' + window.currentAppointmentId + '"]');
        if (statusEl) statusEl.innerText = 'In Session';
      }

      // Optionally show a notification or keep modal open
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
    // Attach submit handler to End Session form to perform AJAX POST (Mark as Done)
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
          // Update modal UI to show Completed state
          var footer = document.querySelector('#reviewAppointmentModal .modal-footer');
          if (footer) {
            // remove any cancelled badge left over
            var cb = document.getElementById('cancelledBadge');
            if (cb && cb.parentNode) cb.parentNode.removeChild(cb);
            var badge = document.getElementById('inSessionBadge');
            if (badge) { badge.innerText = 'Completed'; badge.className = 'badge badge-success'; }
            // hide end form
            if (endForm) endForm.style.display = 'none';
          }

          // Update in-page status cell if present
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
    });
    </script>

    <script>
    // Attach submit handler to Approve form to perform AJAX POST and show centered error modal on conflict
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
                // Always show the same error message for any time conflict
                var msg = 'The appointment is already taken.';
                if (typeof showError === 'function') {
                  showError(msg);
                } else {
                  alert(msg);
                }
                // Throw to prevent running the success handler
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
            badge.innerText = 'In Session';
            footer.insertBefore(badge, footer.firstChild);
          }

          // Update in-page appointment status cell if present
          if (window.currentAppointmentId) {
            var statusEl = document.querySelector('[data-appointment-status="' + window.currentAppointmentId + '"]');
            if (statusEl) statusEl.innerText = 'In Session';
          }
        }).catch(function (err) {
          // 422 errors already displayed via showError; other errors log to console
          console.error('Approve failed', err);
        }).finally(function () {
          if (btn) { btn.disabled = false; btn.innerText = 'Approve'; }
        });
      });
    });
    </script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/Modal/reviewModal.blade.php ENDPATH**/ ?>