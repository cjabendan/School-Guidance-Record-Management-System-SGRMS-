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
        @csrf
        <button type="submit" class="btn btn-success" id="approveBtn">Approve</button>
      </form>
      <form id="declineForm" method="POST" style="display:inline;" onsubmit="return handleDeclineSubmit(event)">
        @csrf
  <textarea name="decline_reason" id="decline_reason" class="form-control" placeholder="Enter reason for declining..." style="margin-bottom:12px; display:none; font-size:1.3em; font-weight:600; border-radius:10px; border:2px solid #ef4444; background:#fff5f5; color:#b91c1c; padding:18px 20px; min-height:70px; transition:box-shadow 0.2s; box-shadow:0 2px 8px rgba(239,68,68,0.08);" required></textarea>
        <button type="button" class="btn btn-danger" id="declineBtn" onclick="showDeclineReason()">Decline</button>
        <button type="submit" class="btn btn-danger" id="submitDeclineBtn" style="display:none;">Submit Decline</button>
      </form>
  <!-- Reschedule button removed -->
  
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

// Reschedule function removed
</script>
