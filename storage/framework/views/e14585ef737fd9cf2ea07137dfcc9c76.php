<!-- Add/Edit Counseling Notes Modal -->
<div id="counselingModal" class="modal-counseling">
    <div class="modal-content-counseling">
        <div class="modal-header-counseling">
            <h2 id="counselingModalTitle">Add Counseling Note</h2>
            <span class="close-btn" onclick="closeCounselingModal()">&times;</span>
        </div>

        <?php
            use Illuminate\Support\Str;
            $isCounselor = Str::startsWith(request()->path(), 'Counselor');
            $storeRoute = $isCounselor ? route('Counselor.counseling.store') : route('Head.counseling.store');
            $baseUrl = $isCounselor ? url('Counselor/counseling') : url('Head/counseling');
        ?>

        <form id="counselingForm" method="POST" action="<?php echo e($storeRoute); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="counseling-method" value="POST">
            <input type="hidden" name="note_id" id="note_id">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo e(Auth::id() ?? ''); ?>">

            <div class="form-group" style="position:relative;">
                <label for="student_id">Student</label>
                <!-- now storing student full name as student_name -->
                <input id="student_name" name="student_name" class="form-control" autocomplete="off" required />
                <input type="hidden" id="student_s_id" name="student_s_id" value="" />
                <div id="student-suggestions" class="autocomplete-suggestions" style="display:none; position:absolute; z-index:40; left:0; right:0; background:#fff; border:1px solid #ddd; max-height:220px; overflow:auto;"></div>
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
                <label for="observations">Observations</label>
                <textarea id="observations" name="observations" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="recommendations">Recommendations (optional)</label>
                <textarea id="recommendations" name="recommendations" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:12px;">
                <label for="follow_up_needed" style="margin:0;">Follow-up needed</label>
                <input type="checkbox" id="follow_up_needed" name="follow_up_needed" value="1">
                <span id="follow_up_display" style="display:none; font-weight:600; margin-left:6px;"></span>
            </div>

            <div class="form-group" id="follow-up-wrapper" style="display:none;">
                <label for="follow_up_date">Follow-up Date</label>
                <input type="datetime-local" id="follow_up_date" name="follow_up_date" class="form-control">
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

        const _baseUrl = '<?php echo e($baseUrl); ?>';
        const _storeRoute = '<?php echo e($storeRoute); ?>';

        if (mode === 'add') {
            form.action = _storeRoute;
            method.value = 'POST';
            title.textContent = 'Add Counseling Note';
            saveBtn.textContent = 'Save';
            // defaults
            document.getElementById('observations').value = '';
            document.getElementById('recommendations').value = '';
            document.getElementById('follow_up_needed').checked = false;
            document.getElementById('follow_up_date').value = '';
            document.getElementById('follow-up-wrapper').style.display = 'none';
        }

        if (mode === 'edit') {
            form.action = _baseUrl + '/' + data.note_id;
            method.value = 'PUT';
            title.textContent = 'Edit Counseling Note';
            saveBtn.textContent = 'Update';
            document.getElementById('note_id').value = data.note_id;
            document.getElementById('student_name').value = data.student_name;
            document.getElementById('remarks').value = data.remarks;
            document.getElementById('observations').value = data.observations || data.content || '';
            document.getElementById('recommendations').value = data.recommendations || '';
            document.getElementById('follow_up_needed').checked = !!data.follow_up_needed;
            if (data.follow_up_date) document.getElementById('follow_up_date').value = data.follow_up_date.replace(' ', 'T');
            document.getElementById('follow-up-wrapper').style.display = data.follow_up_needed ? 'block' : 'none';
        }

        if (mode === 'view') {
            title.textContent = 'View Counseling Note';
            saveBtn.style.display = 'none';
            Array.from(form.elements).forEach(el => el.disabled = true);
            document.getElementById('note_id').value = data.note_id;
            document.getElementById('student_name').value = data.student_name;
            document.getElementById('remarks').value = data.remarks;
            document.getElementById('observations').value = data.observations || data.content || '';
            document.getElementById('recommendations').value = data.recommendations || '';
            document.getElementById('follow_up_needed').checked = !!data.follow_up_needed;
            if (data.follow_up_date) document.getElementById('follow_up_date').value = data.follow_up_date.replace(' ', 'T');
            document.getElementById('follow-up-wrapper').style.display = data.follow_up_needed ? 'block' : 'none';
        } else {
            saveBtn.style.display = 'inline-block';
        }

        // toggle follow-up wrapper when checkbox changes and show Yes/No in view mode
        const followCheckbox = document.getElementById('follow_up_needed');
        const followDisplay = document.getElementById('follow_up_display');
        function updateFollowDisplay() {
            if (!followCheckbox || !followDisplay) return;
            const isView = saveBtn && saveBtn.style.display === 'none';
            if (isView) {
                followDisplay.textContent = followCheckbox.checked ? 'Yes' : 'No';
                followDisplay.style.display = 'inline-block';
                followCheckbox.style.display = 'none';
            } else {
                followDisplay.style.display = 'none';
                followCheckbox.style.display = '';
            }
        }

        if (followCheckbox) {
            followCheckbox.onchange = function() {
                document.getElementById('follow-up-wrapper').style.display = this.checked ? 'block' : 'none';
                updateFollowDisplay();
            };
            // initialize display according to current mode
            updateFollowDisplay();
        }

        // Use flex so the modal centers via CSS (.modal-counseling uses flexbox)
        modal.style.display = 'flex';

        // Close when clicking the overlay (outside modal content)
        // Attach a one-time listener to avoid duplicate handlers
        const onOverlayClick = function(e) {
            if (e.target === modal) {
                closeCounselingModal();
            }
        };
        modal.addEventListener('click', onOverlayClick);
        // store the handler so close can remove it
        modal._overlayHandler = onOverlayClick;
    }

    function closeCounselingModal() {
        const modal = document.getElementById('counselingModal');
        document.getElementById('counselingForm').reset();
        document.getElementById('saveCounselingBtn').style.display = 'inline-block';
        modal.style.display = 'none';
        // remove any overlay click listener
        if (modal && modal._overlayHandler) {
            modal.removeEventListener('click', modal._overlayHandler);
            modal._overlayHandler = null;
        }
    }

    // Autocomplete for student_name
    (function(){
        const input = document.getElementById('student_name');
        const hiddenId = document.getElementById('student_s_id');
        const box = document.getElementById('student-suggestions');
        if (!input || !box) return;
        let t = null;

        function clearSuggestions(){ box.innerHTML=''; box.style.display='none'; }

        input.addEventListener('input', function(e){
            const q = this.value.trim();
            hiddenId.value = '';
            clearTimeout(t);
            if (q.length === 0) { clearSuggestions(); return; }
            t = setTimeout(()=>{
                fetch("<?php echo e(route('students.search')); ?>?q="+encodeURIComponent(q), { credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(list => {
                        box.innerHTML = '';
                        if (!Array.isArray(list) || list.length === 0) return clearSuggestions();
                        list.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'suggestion-item';
                            div.style.padding = '8px';
                            div.style.cursor = 'pointer';
                            div.textContent = item.name + (item.s_id ? `  (${item.s_id})` : '');
                            div.dataset.id = item.s_id;
                            div.dataset.name = item.name;
                            div.addEventListener('click', function(){
                                input.value = this.dataset.name;
                                hiddenId.value = this.dataset.id;
                                clearSuggestions();
                            });
                            box.appendChild(div);
                        });
                        box.style.display = 'block';
                    })
                    .catch(()=> clearSuggestions());
            }, 220);
        });

        // hide on outside click
        document.addEventListener('click', function(e){ if (!e.target.closest || !e.target.closest('#student-suggestions') && e.target !== input) clearSuggestions(); });
    })();
</script>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/Modal/counselingModal.blade.php ENDPATH**/ ?>