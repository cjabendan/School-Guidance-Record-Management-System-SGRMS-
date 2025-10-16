<div id="rescheduleModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <form id="reschedule-form" method="POST" action="">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Reschedule Appointment</h5>
        <button type="button" class="close-modal-btn" onclick="closeRescheduleModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="reschedule_datetime" class="form-label">New Date & Time</label>
          <input type="datetime-local" name="new_datetime" id="reschedule_datetime" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" onclick="closeRescheduleModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
function openRescheduleModal(id, datetime) {
  var modal = document.getElementById('rescheduleModal');
  var form = document.getElementById('reschedule-form');
  var dtInput = document.getElementById('reschedule_datetime');
  if (form) {
    form.action = '/Counselor/appointments/' + id + '/reschedule';
  }
  if (dtInput) {
    if (datetime && datetime !== '') {
      // ensure format is yyyy-MM-ddTHH:mm
      dtInput.value = datetime;
    } else {
      // default to current date + 1 hour
      var now = new Date();
      now.setHours(now.getHours() + 1);
      var iso = now.toISOString();
      dtInput.value = iso.substring(0,16);
    }
  }
  if (modal) modal.style.display = 'flex';
}

function closeRescheduleModal() {
  var modal = document.getElementById('rescheduleModal');
  if (modal) modal.style.display = 'none';
}

// Close when clicking outside
document.addEventListener('click', function(e) {
  var modal = document.getElementById('rescheduleModal');
  if (modal && modal.style.display === 'flex' && e.target === modal) {
    closeRescheduleModal();
  }
});
</script>
