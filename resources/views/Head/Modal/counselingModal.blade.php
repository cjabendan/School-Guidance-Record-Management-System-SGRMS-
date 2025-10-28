<!-- Add/Edit Counseling Notes Modal -->
<div id="counselingModal" class="modal-counseling">
    <div class="modal-content-counseling">
        <div class="modal-header-counseling">
            <h2 id="counselingModalTitle">Add Counseling Note</h2>
            <span class="close-btn" onclick="closeCounselingModal()">&times;</span>
        </div>

        <form id="counselingForm" method="POST" action="{{ route('Head.counseling.store') }}">
            @csrf
            <input type="hidden" name="_method" id="counseling-method" value="POST">
            <input type="hidden" name="note_id" id="note_id">

            <div class="form-group">
                <label for="student_id">Student</label>
                <!-- now storing student full name as student_name -->
                <select id="student_name" name="student_name" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->user->first_name }} {{ $student->user->last_name }}">
                            {{ $student->user->first_name }} {{ $student->user->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <select id="remarks" name="remarks" required>
                    <option value="Alarming">Alarming</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Low">Low</option>
                </select>
            </div>

            <div class="form-group">
                <label for="content">Counseling Notes</label>
                <textarea id="content" name="content" rows="3" required></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn cancel" onclick="closeCounselingModal()">Cancel</button>
                <button type="submit" class="btn save" id="saveCounselingBtn">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCounselingNotesModal(mode, data = {}) {
        const modal = document.getElementById('counselingModal');
        const form = document.getElementById('counselingForm');
        const method = document.getElementById('counseling-method');
        const title = document.getElementById('counselingModalTitle');
        const saveBtn = document.getElementById('saveCounselingBtn');

        form.reset();
        Array.from(form.elements).forEach(el => el.disabled = false);

        if (mode === 'add') {
            form.action = '{{ route("Head.counseling.store") }}';
            method.value = 'POST';
            title.textContent = 'Add Counseling Note';
            saveBtn.textContent = 'Save';
        }

        if (mode === 'edit') {
            form.action = '/Head/counseling/' + data.note_id;
            method.value = 'PUT';
            title.textContent = 'Edit Counseling Note';
            saveBtn.textContent = 'Update';
            document.getElementById('note_id').value = data.note_id;
            document.getElementById('student_name').value = data.student_name;
            document.getElementById('remarks').value = data.remarks;
            document.getElementById('content').value = data.content;
        }

        if (mode === 'view') {
            title.textContent = 'View Counseling Note';
            saveBtn.style.display = 'none';
            Array.from(form.elements).forEach(el => el.disabled = true);
            document.getElementById('note_id').value = data.note_id;
            document.getElementById('student_name').value = data.student_name;
            document.getElementById('remarks').value = data.remarks;
            document.getElementById('content').value = data.content;
        } else {
            saveBtn.style.display = 'inline-block';
        }

        modal.style.display = 'block';
    }

    function closeCounselingModal() {
        const modal = document.getElementById('counselingModal');
        document.getElementById('counselingForm').reset();
        document.getElementById('saveCounselingBtn').style.display = 'inline-block';
        modal.style.display = 'none';
    }
</script>
