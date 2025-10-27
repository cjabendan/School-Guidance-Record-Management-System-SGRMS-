<div class="request-modal" id="request-modal">
    <div class="request-modal-content">
         <button id="close-modal-btn" class="qr-close">&times;</button>
        @include('components.small-loader')
        <h2 id="modal-title"></h2>

        <div><strong>Requested By:</strong> <span id="modal-parent"></span></div>
        <div><strong>Email:</strong> <span id="modal-email"></span></div>
        <div><strong>Contact Number:</strong> <span id="modal-contact"></span></div>

        <table id="modal-students" class="students-table">
        <thead class="students-table-head">
            <tr>
                <th>Student Name</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Date Linked / Requested</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
</table>

        <!-- Rejection Form (hidden by default) -->
        <form id="modal-rejection" style="display:none;" method="POST">
            @csrf
            <label for="rejection-reason-input"><strong>Rejection Reason:</strong></label>
            <input type="text" name="reason" id="rejection-reason-input" placeholder="Enter rejection reason" required>
            <div class="rejection-actions">
                <button type="submit" class="btn btn-reject">Submit</button>
                <button type="button" id="cancel-reject-btn" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
