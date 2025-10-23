<!-- Appointment Review Modal for Student -->
<div id="studentReviewAppointmentModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">View Appointment</h5>
      <button type="button" class="close-modal-btn" onclick="closeStudentReviewModal()">×</button>
    </div>
    <div class="modal-body" id="student-review-modal-body">
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
        <button type="submit" class="btn btn-secondary" id="cancelBtn">Cancel</button>
      </form>
      <button type="button" class="btn btn-warning" id="studentRescheduleBtn" style="display:none; margin-right:8px;" onclick="openStudentRescheduleModal(0)">Request Reschedule</button>
      <button type="button" class="btn btn-secondary" onclick="closeStudentReviewModal()">Close</button>
    </div>
  </div>
</div>
<script>
function openStudentReviewModal(appointmentId, detailsHtml, cancelUrl, status, startUrl, el) {
  var body = document.getElementById('student-review-modal-body');
  body.innerHTML = detailsHtml;
  try {
    if (el && el.dataset) {
      var prev = el.dataset.prev || '';
      var req = el.dataset.req || '';
      var extra = '';
      if (prev) extra += '<div style="font-size:0.95em; margin-top:6px;"><strong>Previous:</strong> ' + prev + '</div>';
  if (req) extra += '<div style="font-size:0.95em;"><strong>Preferred date to reschedule:</strong> ' + req + '</div>';
      if (extra) body.innerHTML = body.innerHTML + extra;
    }
  } catch (e) {}
  document.getElementById('studentReviewAppointmentModal').style.display = 'flex';
  var cancelForm = document.getElementById('cancelForm');
  if (cancelForm) { cancelForm.action = cancelUrl || ''; cancelForm.style.display = 'none'; }
  status = (status || '').toLowerCase();
  // remove badges
  var oldComp = document.getElementById('completedBadge'); if (oldComp && oldComp.parentNode) oldComp.parentNode.removeChild(oldComp);
  var oldCancelled = document.getElementById('cancelledBadge'); if (oldCancelled && oldCancelled.parentNode) oldCancelled.parentNode.removeChild(oldCancelled);
  // Show cancel only for pending
  if (cancelForm) { if (status === 'pending') cancelForm.style.display = 'inline-block'; else cancelForm.style.display = 'none'; }
  // Show reschedule only for approved appointments
  var rescheduleBtn = document.getElementById('studentRescheduleBtn');
  if (rescheduleBtn) {
    if (status === 'approved') { rescheduleBtn.style.display = 'inline-block'; rescheduleBtn.onclick = function() { openStudentRescheduleModal(appointmentId); }; }
    else { rescheduleBtn.style.display = 'none'; }
  }
  // Completed badge
  if (status === 'completed') {
    var footer = document.querySelector('#studentReviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('completedBadge')) {
      var cbadge = document.createElement('span'); cbadge.className = 'badge badge-success'; cbadge.id = 'completedBadge'; cbadge.style.marginRight = '8px'; cbadge.innerText = 'Complete'; footer.insertBefore(cbadge, footer.firstChild);
    }
  }
  if (status === 'cancelled') {
    var footer = document.querySelector('#studentReviewAppointmentModal .modal-footer');
    if (footer && !document.getElementById('cancelledBadge')) {
      var badge = document.createElement('span'); badge.className = 'badge badge-danger'; badge.id = 'cancelledBadge'; badge.style.marginRight = '8px'; badge.innerText = 'Cancelled'; footer.insertBefore(badge, footer.firstChild);
    }
  }
}
function closeStudentReviewModal() {
  document.getElementById('studentReviewAppointmentModal').style.display = 'none';
}
</script>

<!-- Reschedule Modal for Student -->
<div id="studentRescheduleModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Request Reschedule</h5>
      <button type="button" class="close-modal-btn" onclick="closeStudentRescheduleModal()">×</button>
    </div>
    <div class="modal-body">
      <form id="studentRescheduleForm" method="POST" action="">
        @csrf
        <input type="hidden" name="appointment_id" id="student_reschedule_appointment_id" value="">
        <div class="form-group">
          <label for="proposed_datetime">Proposed Date & Time</label>
          <input type="datetime-local" name="proposed_datetime" id="student_proposed_datetime" class="form-control">
        </div>
        <div class="form-group">
          <label for="reason">Reason</label>
          <textarea name="reason" id="student_reschedule_reason" class="form-control" rows="3" required></textarea>
        </div>
        <div style="margin-top:12px; text-align:right;">
          <button type="button" class="btn btn-secondary" onclick="closeStudentRescheduleModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openStudentRescheduleModal(appointmentId) {
  document.getElementById('student_reschedule_appointment_id').value = appointmentId;
  var form = document.getElementById('studentRescheduleForm');
  // set action to the Student reschedule route
  form.action = "{{ url('Student/appointments') }}/" + appointmentId + "/reschedule";
  document.getElementById('studentRescheduleModal').style.display = 'flex';
}
function closeStudentRescheduleModal() { document.getElementById('studentRescheduleModal').style.display = 'none'; }
</script>
