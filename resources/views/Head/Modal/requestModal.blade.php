<div class="request-modal" id="request-modal">
    <div class="request-modal-content">
        @include('components.small-loader')
        <h2 id="modal-title"></h2>

        <div><strong>Requested By:</strong> <span id="modal-parent"></span></div>
        <div><strong>Email:</strong> <span id="modal-email"></span></div>
        <div><strong>Contact Number:</strong> <span id="modal-contact"></span></div>
        <div><strong>Requested At:</strong> <span id="modal-requested-at"></span></div>
        <div><strong>Status:</strong> <span id="modal-status"></span></div>

        <div><strong>Students:</strong>
            <ul id="modal-students"></ul>
        </div>

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

        <!-- Action Buttons -->
        <div class="request-actions">
            <form id="modal-approve-form" style="display:none;" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Accept</button>
            </form>

            <form id="modal-reject-form" style="display:none;" method="POST">
                @csrf
                <button type="button" id="show-reason-btn" class="btn btn-danger">Decline</button>
            </form>
        </div>
    </div>
</div>
