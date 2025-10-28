<!-- Appointment Review Modal for Parent -->
<div id="parentReviewAppointmentModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">View Appointment</h5>
      <button type="button" class="close-modal-btn" onclick="closeParentReviewModal()">×</button>
    </div>
    <div class="modal-body" id="parent-review-modal-body">
      <!-- Appointment details will be loaded here via JS -->
    </div>
    <div class="modal-footer">
      <form id="startSessionForm" method="POST" style="display:none; margin-right:8px;">
        @csrf
        <button type="submit" class="btn btn-primary" id="startSessionBtn">Start Session</button>
      </form>
      <form id="endSessionForm" method="POST" style="display:none; margin-right:8px;">
        @csrf
        <button type="submit" class="btn btn-danger" id="endSessionBtn">Complete</button>
      </form>
      <form id="cancelForm" method="POST" style="display:none; margin-right:8px;">
        @csrf
        <button type="submit" class="btn btn-secondary" id="cancelBtn">Cancel Appointment</button>
      </form>
     
    </div>
  </div>
</div>
<script>
function openParentReviewModal(appointmentId, detailsHtml, cancelUrl, status, startUrl, el) {
  var body = document.getElementById('parent-review-modal-body');
  body.innerHTML = detailsHtml;
  // If caller passed an element with data-prev/data-req, append them
  try {
    if (el && el.dataset) {
      var prev = el.dataset.prev || '';
      var req = el.dataset.req || '';
      var extra = '';
      if (prev) extra += '<div style="font-size:0.95em; margin-top:6px;"><strong>Previous:</strong> ' + prev + '</div>';
  if (req) extra += '<div style="font-size:0.95em;"><strong>Preferred date to reschedule:</strong> ' + req + '</div>';
      if (extra) body.innerHTML = body.innerHTML + extra;
    }
  } catch (e) {
    // ignore
  }
  document.getElementById('parentReviewAppointmentModal').style.display = 'flex';
  var cancelForm = document.getElementById('cancelForm');
  if (cancelForm) { cancelForm.action = cancelUrl || ''; cancelForm.style.display = 'none'; }
  status = (status || '').toLowerCase();
  
  // Hide approve/decline for missed appointments
  if (status === 'missed') {
    if (document.getElementById('approveForm')) document.getElementById('approveForm').style.display = 'none';
    if (document.getElementById('declineForm')) document.getElementById('declineForm').style.display = 'none';
  }
  
  // remove old badges
  var oldComp = document.getElementById('completedBadge'); if (oldComp && oldComp.parentNode) oldComp.parentNode.removeChild(oldComp);
  var oldCancelled = document.getElementById('cancelledBadge'); if (oldCancelled && oldCancelled.parentNode) oldCancelled.parentNode.removeChild(oldCancelled);
  // Show cancel only for pending
  if (cancelForm) {
    if (status === 'pending' || status === 'approved') {
        cancelForm.style.display = 'inline-block';
    } else {
        cancelForm.style.display = 'none';
    }
  }
  // Show reschedule only for approved appointments
  var rescheduleBtn = document.getElementById('parentRescheduleBtn');
  if (rescheduleBtn) {
    if (status === 'approved') { rescheduleBtn.style.display = 'inline-block'; rescheduleBtn.onclick = function() { openParentRescheduleModal(appointmentId); }; }
    else { rescheduleBtn.style.display = 'none'; }
  }
  // If completed, show completed badge
  if (status === 'completed') {
    var footer = document.querySelector('#parentReviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('completedBadge')) {
      var cbadge = document.createElement('span'); cbadge.className = 'badge badge-success'; cbadge.id = 'completedBadge'; cbadge.style.marginRight = '8px'; cbadge.innerText = 'Complete'; footer.insertBefore(cbadge, footer.firstChild);
    }
  }
  // If cancelled, show cancelled badge and hide actions
  if (status === 'cancelled') {
    var footer = document.querySelector('#parentReviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('cancelledBadge')) {
      var badge = document.createElement('span'); badge.className = 'badge badge-danger'; badge.id = 'cancelledBadge'; badge.style.marginRight = '8px'; badge.innerText = 'Cancelled'; footer.insertBefore(badge, footer.firstChild);
    }
  }
}
function closeParentReviewModal() {
  document.getElementById('parentReviewAppointmentModal').style.display = 'none';
}
</script>

<!-- Reschedule Modal for Parent -->
<div id="parentRescheduleModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Request Reschedule</h5>
      <button type="button" class="close-modal-btn" onclick="closeParentRescheduleModal()">×</button>
    </div>
    <div class="modal-body">
      <form id="parentRescheduleForm" method="POST" action="">
        @csrf
        <input type="hidden" name="appointment_id" id="parent_reschedule_appointment_id" value="">
        <div class="form-group">
          <label for="proposed_datetime">Proposed Date & Time</label>
          <input type="datetime-local" name="proposed_datetime" id="parent_proposed_datetime" class="form-control">
        </div>
        <div class="form-group">
          <label for="reason">Reason</label>
          <textarea name="reason" id="parent_reschedule_reason" class="form-control" rows="3" required></textarea>
        </div>
        <div style="margin-top:12px; text-align:right;">
          <button type="button" class="btn btn-secondary" onclick="closeParentRescheduleModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openParentRescheduleModal(appointmentId) {
  document.getElementById('parent_reschedule_appointment_id').value = appointmentId;
  var form = document.getElementById('parentRescheduleForm');
  form.action = "{{ url('Parent/appointments') }}/" + appointmentId + "/reschedule";
  document.getElementById('parentRescheduleModal').style.display = 'flex';
}
function closeParentRescheduleModal() { document.getElementById('parentRescheduleModal').style.display = 'none'; }
</script>
