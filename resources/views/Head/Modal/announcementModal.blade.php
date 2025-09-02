<!-- Add/Edit Announcement Modal -->
<div id="announcementModal" class="modal">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h2 id="modalTitle">Add Announcement</h2>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>

        <!-- Modal Body -->
        <form id="announcementForm" method="POST" action="{{ route('Head.announcements.store') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="2" required></textarea>
            </div>

            <div class="form-group">
                <label for="link">External Link (optional)</label>
                <input type="url" id="link" name="link">
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="Announcement">Announcement</option>
                    <option value="News">News</option>
                    <option value="Event">Event</option>
                </select>
            </div>

            <!-- Event-specific fields -->
            <div id="event-fields" style="display:none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date">
                    </div>
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" id="start_time" name="start_time">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date">
                    </div>
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" id="end_time" name="end_time">
                    </div>
                </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const eventFields = document.getElementById('event-fields');

        function toggleEventFields() {
            if (categorySelect.value === 'Event') {
                eventFields.style.display = 'block';
                document.getElementById('start_date').required = true;
                document.getElementById('start_time').required = true;
                document.getElementById('end_date').required = true;
                document.getElementById('end_time').required = true;
            } else {
                eventFields.style.display = 'none';
                document.getElementById('start_date').required = false;
                document.getElementById('start_time').required = false;
                document.getElementById('end_date').required = false;
                document.getElementById('end_time').required = false;
            }
        }

        categorySelect.addEventListener('change', toggleEventFields);
        toggleEventFields(); // initialize on page load
    });

    document.getElementById('announcementForm').addEventListener('submit', function(e) {
        const category = document.getElementById('category').value;
        if (category === 'Event') {
            const startDate = document.getElementById('start_date').value;
            const startTime = document.getElementById('start_time').value;
            const endDate = document.getElementById('end_date').value;
            const endTime = document.getElementById('end_time').value;

            if (!startDate || !startTime || !endDate || !endTime) {
                e.preventDefault();
                alert('Please fill in all event dates and times.');
                return;
            }

            // Combine into ISO datetime strings for backend
            const startDatetime = `${startDate} ${startTime}`;
            const endDatetime = `${endDate} ${endTime}`;

            // Add hidden inputs for backend processing
            let hiddenStart = document.createElement('input');
            hiddenStart.type = 'hidden';
            hiddenStart.name = 'start_datetime';
            hiddenStart.value = startDatetime;

            let hiddenEnd = document.createElement('input');
            hiddenEnd.type = 'hidden';
            hiddenEnd.name = 'end_datetime';
            hiddenEnd.value = endDatetime;

            this.appendChild(hiddenStart);
            this.appendChild(hiddenEnd);
        }
    });
</script>
