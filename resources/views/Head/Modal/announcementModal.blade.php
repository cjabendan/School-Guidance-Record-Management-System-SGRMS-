<!-- Add/Edit Announcement Modal -->
<div id="announcementModal" class="modal-announcement">
    <div class="modal-content-announcement">

        <!-- Modal Header -->
        <div class="modal-header-announcement">
            <h2 id="modalTitle">Add Announcement</h2>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>

        <!-- Modal Body -->
        <form id="announcementForm" method="POST" action="{{ route('Head.announcements.store') }}"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="announcement_id" id="announcement_id">

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
                <div id="link-input-group" class="link-group">
                    <input type="url" id="link" name="link" placeholder="https://example.com">
                    <a id="link-view" href="#" target="_blank" class="announcement-btn-link" style="display:none;">Open Link</a>
                </div>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <div class="select-wrapper">
                    <select id="category" name="category" required>
                        <option value="Announcement">Announcement</option>
                        <option value="News">News</option>
                        <option value="Event">Event</option>
                    </select>
                    <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
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
                <label for="image" class="custom-file-label">Upload Image</label>
                <div class="file-group">
                    <label class="custom-upload-btn" for="image">
                        <i class="fas fa-image"></i> Choose Image
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" style="display:none;">
                    <span id="file-chosen" class="file-chosen-text">No file chosen</span>
                </div>
                <div id="image-preview-wrapper" style="display:none;">
                    <img id="image-preview" src="" alt="Image Preview">
                    <button type="button" id="remove-image-btn" class="btn cancel" style="margin-top:8px;">Remove Image</button>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <div class="select-wrapper">
                    <select id="status" name="status">
                        <option value="active" selected>Active</option>
                        <option value="archived">Archived</option>
                    </select>
                    <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn cancel" id="modalCancelBtn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn save" id="saveBtn">Post</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category');
        const eventFields = document.getElementById('event-fields');

        function toggleEventFields() {
            const isEvent = categorySelect.value === 'Event';
            eventFields.style.display = isEvent ? 'block' : 'none';
            document.getElementById('start_date').required = isEvent;
            document.getElementById('start_time').required = isEvent;
            document.getElementById('end_date').required = isEvent;
            document.getElementById('end_time').required = isEvent;
        }

        categorySelect.addEventListener('change', toggleEventFields);
        toggleEventFields();

        const imageInput = document.getElementById('image');
        const previewWrapper = document.getElementById('image-preview-wrapper');
        const previewImg = document.getElementById('image-preview');
        const removeBtn = document.getElementById('remove-image-btn');
        const label = document.querySelector('.custom-upload-btn');
        const fileChosen = document.getElementById('file-chosen');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewWrapper.style.display = 'flex';
                    label.style.display = 'none';
                    fileChosen.style.display = 'none';
                };
                reader.readAsDataURL(file);
                fileChosen.textContent = file.name;
            } else {
                previewImg.src = '';
                previewWrapper.style.display = 'none';
                label.style.display = 'inline-block';
                fileChosen.style.display = 'inline-block';
                fileChosen.textContent = 'No file chosen';
            }
        });

        removeBtn.addEventListener('click', function () {
            imageInput.value = '';
            previewImg.src = '';
            previewWrapper.style.display = 'none';
            label.style.display = 'inline-block';
            fileChosen.style.display = 'inline-block';
        });
    });

    document.getElementById('announcementForm').addEventListener('submit', function (e) {
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

            const startDatetime = `${startDate} ${startTime}`;
            const endDatetime = `${endDate} ${endTime}`;

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

    function openAnnouncementModal(id, mode) {
        const btn = document.querySelector(`[data-id='${id}'][onclick*='${mode}']`);
        if (!btn) return;

        const modal = document.getElementById('announcementModal');
        const form = document.getElementById('announcementForm');
        const formMethod = document.getElementById('form-method');
        const announcementIdInput = document.getElementById('announcement_id');
        const linkField = document.querySelector('.form-group label[for="link"]').parentElement;
        const imageGroup = document.querySelector('.form-group label[for="image"]').parentElement;
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const saveBtn = document.getElementById('saveBtn');
        const modalTitle = document.getElementById('modalTitle');

        imageGroup.style.display = 'block';
        linkField.style.display = 'block';
        modalCancelBtn.style.display = 'inline-block';

        if (mode === 'edit') {
            form.action = '/Head/announcements/' + id;
            formMethod.value = 'PUT';
            announcementIdInput.value = id;
            modalTitle.textContent = 'Edit Announcement';
            saveBtn.textContent = 'Update';
        } else if (mode === 'view') {
            form.action = '{{ route('Head.announcements.store') }}';
            formMethod.value = 'POST';
            announcementIdInput.value = '';
            modalTitle.textContent = 'View Announcement';
            saveBtn.style.display = 'none';
            modalCancelBtn.style.display = 'none';

            if (!btn.dataset.image || btn.dataset.image === 'default.png' || btn.dataset.image === '') {
                imageGroup.style.display = 'none';
            }

            const linkInputGroup = document.getElementById('link-input-group');
            const linkView = document.getElementById('link-view');
            if (btn.dataset.link && btn.dataset.link.trim() !== '') {
                linkInputGroup.querySelector('input').style.display = 'none';
                linkView.style.display = 'inline-block';
                linkView.href = btn.dataset.link;
            } else {
                linkField.style.display = 'none';
            }
        } else {
            form.action = '{{ route('Head.announcements.store') }}';
            formMethod.value = 'POST';
            announcementIdInput.value = '';
            modalTitle.textContent = 'Add Announcement';
            saveBtn.textContent = 'Post';
            saveBtn.style.display = 'inline-block';
        }

        form.title.value = btn.dataset.title || '';
        form.description.value = btn.dataset.description || '';
        form.link.value = btn.dataset.link || '';
        const linkView = document.getElementById('link-view');
        if (linkView) { linkView.style.display = 'none'; linkView.href = '#'; }
        const linkInput = document.querySelector('#link-input-group input');
        if (linkInput) linkInput.style.display = 'inline-block';
        form.category.value = btn.dataset.category || 'Announcement';
        form.status.value = btn.dataset.status || 'active';

        if (btn.dataset.category === 'Event') {
            document.getElementById('event-fields').style.display = 'block';
            if (btn.dataset.start_datetime) {
                const [startDate, startTime] = btn.dataset.start_datetime.split(' ');
                form.start_date.value = startDate;
                form.start_time.value = startTime ? startTime.substring(0, 5) : '';
            }
            if (btn.dataset.end_datetime) {
                const [endDate, endTime] = btn.dataset.end_datetime.split(' ');
                form.end_date.value = endDate;
                form.end_time.value = endTime ? endTime.substring(0, 5) : '';
            }
        } else {
            document.getElementById('event-fields').style.display = 'none';
        }

        const previewWrapper = document.getElementById('image-preview-wrapper');
        const previewImg = document.getElementById('image-preview');
        const label = document.querySelector('.custom-upload-btn');
        const fileChosen = document.getElementById('file-chosen');
        const removeBtn = document.getElementById('remove-image-btn');
        const uploadLabel = document.querySelector('label[for="image"]');

        if (btn.dataset.image && btn.dataset.image !== 'default.png') {
            previewImg.src = '/images/announcements/' + btn.dataset.image;
            previewWrapper.style.display = 'flex';
            label.style.display = 'none';
            fileChosen.style.display = 'none';
        } else {
            previewImg.src = '';
            previewWrapper.style.display = 'none';
            label.style.display = 'inline-block';
            fileChosen.style.display = 'inline-block';
        }

        if (mode === 'view') {
            if (uploadLabel) uploadLabel.textContent = 'Photo Uploaded';
            if (removeBtn) removeBtn.style.display = 'none';
        } else {
            if (uploadLabel) uploadLabel.textContent = 'Upload Image';
            if (removeBtn) removeBtn.style.display = 'inline-block';
        }

        Array.from(form.elements).forEach(el => el.disabled = (mode === 'view'));
        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('announcementModal');
        const form = document.getElementById('announcementForm');
        const saveBtn = document.getElementById('saveBtn');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const previewWrapper = document.getElementById('image-preview-wrapper');
        const label = document.querySelector('.custom-upload-btn');
        const fileChosen = document.getElementById('file-chosen');
        const linkInputGroup = document.getElementById('link-input-group');
        const linkView = document.getElementById('link-view');
        const uploadLabel = document.querySelector('label[for="image"]');
        const removeBtn = document.getElementById('remove-image-btn');

        form.reset();
        previewWrapper.style.display = 'none';
        label.style.display = 'flex';
        fileChosen.style.display = 'inline-block';
        fileChosen.textContent = 'No file chosen';
        if (uploadLabel) uploadLabel.textContent = 'Upload Image';
        if (removeBtn) removeBtn.style.display = 'inline-block';
        if (linkInputGroup) linkInputGroup.querySelector('input').style.display = 'inline-block';
        if (linkView) {
            linkView.style.display = 'none';
            linkView.href = '#';
        }
        saveBtn.textContent = 'Post';
        saveBtn.style.display = 'inline-block';
        modalCancelBtn.style.display = 'inline-block';
        modal.style.display = 'none';
    }
</script>
