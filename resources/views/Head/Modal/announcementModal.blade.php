<!-- Add/Edit Announcement Modal -->
<div id="announcementModal" class="modal">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h2 id="modalTitle">Add Announcement</h2>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>

        <!-- Modal Body -->
        <form id="announcementForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Short Description</label>
                <textarea id="description" name="description" rows="2" required></textarea>
            </div>

            <div class="form-group">
                <label for="body">Body</label>
                <textarea id="body" name="body" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="link">External Link (optional)</label>
                <input type="url" id="link" name="link">
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" required>
            </div>

            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" selected>Active</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn save" id="saveBtn">Post</button>
            </div>
        </form>
    </div>
</div>
