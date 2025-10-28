<div class="request-modal" id="request-modal">
    <div class="request-modal-content">
        <button id="close-modal-btn" class="qr-close">&times;</button>
        @include('components.small-loader')
        <div class="modal-header-user">
            <i class="fi fi-sr-folder-open"></i>
            <h2 id="modal-title">Child Link Record</h2>
            <span class="close-btn" onclick="closeUserModal()">&times;</span>
        </div>

        <div class="request-info">
            <div class="request-left">
                <img id="modal-parent-image" src="{{ asset('images/default.png') }}" alt="Parent"
                    class="profile-thumb"
                    style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:1px solid #ccc;">
                <div style="display: flex; flex-direction: column;">
                    <span id="modal-parent" class="request-parent"></span>
                    <p class="requester-role">Parent</p>
                </div>
            </div>

            <div class="request-right">
                <div id="modal-contact-info" class="request-user-section">
                    <ul>
                        <li class="user-chat-icon">
                            <a id="modal-email-link" href="#" target="_blank">
                                <i class="fi fi-sr-envelope"></i>
                            </a>
                        </li>
                        <li class="user-chat-icon">
                            <a id="modal-contact-link" href="#" target="_blank">
                                <i class="fi fi-sr-phone-call"></i>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>

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

        <form id="modal-rejection" style="display:none;" method="POST">
            @csrf
            <label for="rejection-reason-input"><strong>Rejection Reason:</strong></label>
            <input type="text" name="reason" id="rejection-reason-input" placeholder="Enter rejection reason"
                required>
            <div class="rejection-actions">
                <button type="submit" class="btn btn-reject">Submit</button>
                <button type="button" id="cancel-reject-btn" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
</div>
