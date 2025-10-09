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
      <button type="button" class="btn btn-secondary" onclick="closeParentReviewModal()">Close</button>
    </div>
  </div>
</div>
<script>
function openParentReviewModal(detailsHtml) {
  document.getElementById('parent-review-modal-body').innerHTML = detailsHtml;
  document.getElementById('parentReviewAppointmentModal').style.display = 'flex';
}
function closeParentReviewModal() {
  document.getElementById('parentReviewAppointmentModal').style.display = 'none';
}
</script>
