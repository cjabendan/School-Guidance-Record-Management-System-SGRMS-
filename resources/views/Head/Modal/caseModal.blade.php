<!-- =========================
     ADD CASE MODAL
========================= -->
<div class="modal case-modal" id="addCaseModal" tabindex="-1" aria-labelledby="addCaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('Head.cases.store') }}">
            @csrf
            <div class="modal-content case-modal-content">
                <div class="modal-header">
                    <h5 class="case-modal-title" id="addCaseModalLabel">New Case Record</h5>
                    <span class="close add-modal-close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                </div>

                <div class="modal-body case-modal-body">
                    <!-- ================= CASE IDENTIFICATION ================= -->
                    <h6 class="section-title">Case Identification</h6>
                    <!-- Student Search Input -->
                    <div class="form-row">
                        <div class="add-field-col" style="flex:1; position: relative;">
                            <label for="student_search" class="add-label">Search Student</label>
                            <div id="student-tag-input" class="student-tag-input">
                                <input type="text" id="student_search" class="add-input student-search-input" placeholder="Type name or ID" autocomplete="off">
                            </div>
                            <div id="student_search_results" class="list-group" style="display: none;"></div>
                            <input type="hidden" name="involved_students" id="involved_students">
                        </div>
                    </div>

                    <!-- Row 1: Type & Severity -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="case_type_id" class="add-label">Case Type</label>
                            <select class="add-input" name="case_type_id" id="case_type_id"  onchange="toggleOtherType(this)">
                                <option value="">Select Type</option>
                                @foreach (\App\Models\CaseType::all() as $type)
                                    <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>
                            <input type="text" class="add-input mt-2" name="other_case_type" id="other_case_type" placeholder="Enter new case type" style="display:none;">
                        </div>
                        <div class="add-field-col">
                            <label for="severity" class="add-label">Severity</label>
                            <select class="add-input" name="severity" >
                                <option value="Minor">Minor</option>
                                <option value="Major">Major</option>
                                <option value="Grave">Grave</option>
                            </select>
                        </div>
                    </div>

                    <!-- ================= DETAILS ================= -->
                    <h6 class="section-title">Details</h6>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="presenting_problem" class="add-label">Presenting Problem</label>
                            <textarea class="add-input" name="presenting_problem" id="presenting_problem" rows="3" placeholder="Describe the initial issue or complaint"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="action_taken" class="add-label">Action Taken</label>
                            <textarea class="add-input" name="action_taken" id="action_taken" rows="2" placeholder="Describe any immediate actions taken"></textarea>
                        </div>
                    </div>

                    <!-- Row 3: Filed Date, Time, Status -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="filed_date" class="add-label">Filed Date</label>
                            <input type="date" class="add-input" name="filed_date" >
                        </div>
                        <div class="add-field-col">
                            <label for="filed_time" class="add-label">Filed Time</label>
                            <input type="time" class="add-input" name="filed_time" >
                        </div>
                        <div class="add-field-col">
                            <label for="status" class="add-label">Status</label>
                            <select class="add-input" name="status" >
                                <option value="">Choose Status</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>

                    <!-- ================= RESOLUTION ================= -->
                    <h6 class="section-title">Resolution</h6>

                    <!-- Row 4: Resolved & Follow-Up Dates -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="resolved_date" class="add-label">Resolved Date</label>
                            <input type="date" class="add-input" name="resolved_date">
                        </div>
                        <div class="add-field-col">
                            <label for="follow_up_date" class="add-label">Follow Up Date</label>
                            <input type="date" class="add-input" name="follow_up_date">
                        </div>
                    </div>

                    <!-- Row 5: Description -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="description" class="add-label">Description</label>
                            <textarea class="add-input" name="description" placeholder="Provide a detailed description of the case"></textarea>
                        </div>
                    </div>

                    <!-- Row 6: Witnesses -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="witnesses" class="add-label">Witnesses</label>
                            <textarea class="add-input" name="witnesses" placeholder="List names and contact info of witnesses"></textarea>
                        </div>
                    </div>

                    <!-- Row 7: Investigation Notes -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="investigation_notes" class="add-label">Investigation Notes</label>
                            <textarea class="add-input" name="investigation_notes" placeholder="Record findings from the investigation"></textarea>
                        </div>
                    </div>

                    <!-- Row 8: Evidence -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="evidence" class="add-label">Evidence</label>
                            <textarea class="add-input" name="evidence" placeholder="Describe or list evidence"></textarea>
                        </div>
                    </div>

                    <!-- Row 9: Resolution Notes -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="resolution_notes" class="add-label">Resolution Notes</label>
                            <textarea class="add-input" name="resolution_notes" placeholder="Summarize the resolution and outcomes"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="pro-add-save">Add Case</button>
            </div>
        </form>
    </div>
</div>


<!-- =========================
     VIEW CASE MODAL
========================= -->
@isset($cases)
    @foreach ($cases as $case)
        <!-- View Case Modal - use disabled inputs identical to Edit layout -->
        <div class="modal case-modal" id="viewCaseModal{{ $case->case_id }}" tabindex="-1" aria-labelledby="viewCaseModalLabel{{ $case->case_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content case-modal-content">
                    <div class="modal-header">
                        <h5 class="case-modal-title" id="viewCaseModalLabel{{ $case->case_id }}">Case Information</h5>
                        <span class="close add-modal-close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                    </div>
                    <div class="modal-body case-modal-body">
                        <!-- ================= CASE IDENTIFICATION ================= -->
                        <h6 class="section-title">Case Identification</h6>
                        <div class="form-row">
                            <div class="add-field-col" style="flex:1; position: relative;">
                                <label class="add-label">Search Student</label>
                                <div id="view-student-tag-input{{ $case->case_id }}" class="student-tag-input">
                                    @foreach($case->students as $s)
                                        <span class="student-tag">{{ ($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? '') }} | {{ $s->s_id }}</span>
                                    @endforeach
                                </div>
                                <input type="hidden" id="view_involved_students{{ $case->case_id }}" value="{{ $case->students->pluck('s_id')->implode(',') }}">
                            </div>
                        </div>

                        <!-- Row 1: Type & Severity -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Case Type</label>
                                <select class="add-input" disabled>
                                    <option>{{ $case->caseType->type_name ?? 'N/A' }}</option>
                                </select>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Severity</label>
                                <select class="add-input" disabled>
                                    <option>{{ $case->severity }}</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="section-title">Details</h6>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Presenting Problem</label>
                                <textarea class="add-input" rows="3" disabled>{{ $case->presenting_problem }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Action Taken</label>
                                <textarea class="add-input" rows="2" disabled>{{ $case->action_taken }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Filed Date</label>
                                <input type="date" class="add-input" value="{{ $case->filed_date }}" disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Filed Time</label>
                                <input type="time" class="add-input" value="{{ $case->filed_time }}" disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Status</label>
                                <select class="add-input" disabled>
                                    <option>{{ $case->status }}</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="section-title">Resolution</h6>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Resolved Date</label>
                                <input type="date" class="add-input" value="{{ $case->resolved_date }}" disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Follow Up Date</label>
                                <input type="date" class="add-input" value="{{ $case->follow_up_date }}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Description</label>
                                <textarea class="add-input" disabled>{{ $case->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Witnesses</label>
                                <textarea class="add-input" disabled>{{ $case->witnesses }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Investigation Notes</label>
                                <textarea class="add-input" disabled>{{ $case->investigation_notes }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Evidence</label>
                                <textarea class="add-input" disabled>{{ $case->evidence }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Resolution Notes</label>
                                <textarea class="add-input" disabled>{{ $case->resolution_notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!-- =========================
    EDIT CASE MODAL
========================= -->
        <!-- Edit Case Modal -->
        <div class="modal case-modal" id="editCaseModal{{ $case->case_id }}" tabindex="-1" aria-labelledby="editCaseModalLabel{{ $case->case_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('Head.cases.update', $case->case_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content case-modal-content">
                        <div class="modal-header">
                            <h5 class="case-modal-title" id="editCaseModalLabel{{ $case->case_id }}">Update Case Information</h5>
                            <span class="close add-modal-close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                        </div>
                        <div class="modal-body case-modal-body">
                            <!-- ================= CASE IDENTIFICATION ================= -->
                            <h6 class="section-title">Case Identification</h6>
                            <!-- Student Search Input -->
                            <div class="form-row">
                                <div class="add-field-col" style="flex:1; position: relative;">
                                    <label for="edit_student_search{{ $case->case_id }}" class="add-label">Search Student</label>
                                    <div id="edit-student-tag-input{{ $case->case_id }}" class="student-tag-input">
                                        {{-- Server-render existing student tags so AJAX-inserted modals show names immediately --}}
                                        @foreach($case->students as $s)
                                            <span class="student-tag" data-id="{{ $s->s_id }}">{{ ($s->user->first_name ?? '') . ' ' . ($s->user->last_name ?? '') }} | {{ $s->s_id }}<span class="remove-tag" title="Remove">&times;</span></span>
                                        @endforeach
                                        <input type="text" id="edit_student_search{{ $case->case_id }}" class="add-input student-search-input" placeholder="Type name or ID" autocomplete="off">
                                    </div>
                                    <div id="edit_student_search_results{{ $case->case_id }}" class="list-group" style="display: none;"></div>
                                    <input type="hidden" name="involved_students" id="edit_involved_students{{ $case->case_id }}" value="{{ $case->students->pluck('s_id')->implode(',') }}">
                                </div>
                            </div>

                            <!-- Row 1: Type & Severity -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_case_type_id{{ $case->case_id }}" class="add-label">Case Type</label>
                                    <select class="add-input" name="case_type_id" id="edit_case_type_id{{ $case->case_id }}"  onchange="toggleOtherTypeEdit({{ $case->case_id }})">
                                        @foreach (\App\Models\CaseType::all() as $type)
                                            <option value="{{ $type->type_id }}" {{ $case->case_type_id == $type->type_id ? 'selected' : '' }}>
                                                {{ $type->type_name }}
                                            </option>
                                        @endforeach
                                        <option value="other" {{ $case->case_type_id == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <input type="text" class="add-input mt-2" name="other_case_type" id="edit_other_case_type{{ $case->case_id }}" placeholder="Enter new case type" style="{{ $case->case_type_id == 'other' ? 'display:block;' : 'display:none;' }}" value="{{ $case->other_case_type }}">
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_severity{{ $case->case_id }}" class="add-label">Severity</label>
                                    <select class="add-input" name="severity" id="edit_severity{{ $case->case_id }}" >
                                        <option value="Minor" {{ $case->severity == 'Minor' ? 'selected' : '' }}>Minor</option>
                                        <option value="Major" {{ $case->severity == 'Major' ? 'selected' : '' }}>Major</option>
                                        <option value="Grave" {{ $case->severity == 'Grave' ? 'selected' : '' }}>Grave</option>
                                    </select>
                                </div>
                            </div>

                            <!-- ================= DETAILS ================= -->
                            <h6 class="section-title">Details</h6>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_presenting_problem{{ $case->case_id }}" class="add-label">Presenting Problem</label>
                                    <textarea class="add-input" name="presenting_problem" id="edit_presenting_problem{{ $case->case_id }}" rows="3">{{ $case->presenting_problem }}</textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_action_taken{{ $case->case_id }}" class="add-label">Action Taken</label>
                                    <textarea class="add-input" name="action_taken" id="edit_action_taken{{ $case->case_id }}" rows="2">{{ $case->action_taken }}</textarea>
                                </div>
                            </div>

                            <!-- Row 3: Filed Date, Filed Time, Status -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_filed_date{{ $case->case_id }}" class="add-label">Filed Date</label>
                                    <input type="date" class="add-input" name="filed_date" id="edit_filed_date{{ $case->case_id }}" value="{{ $case->filed_date }}" >
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_filed_time{{ $case->case_id }}" class="add-label">Filed Time</label>
                                    <input type="time" class="add-input" name="filed_time" id="edit_filed_time{{ $case->case_id }}" value="{{ $case->filed_time }}" >
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_status{{ $case->case_id }}" class="add-label">Status</label>
                                    <select class="add-input" name="status" id="edit_status{{ $case->case_id }}" >
                                        <option value="">Choose Status</option>
                                        <option value="open" {{ $case->status == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ $case->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                            </div>

                            <!-- ================= RESOLUTION ================= -->
                            <h6 class="section-title">Resolution</h6>

                            <!-- Row 4: Resolved Date & Follow Up Date -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_resolved_date{{ $case->case_id }}" class="add-label">Resolved Date</label>
                                    <input type="date" class="add-input" name="resolved_date" id="edit_resolved_date{{ $case->case_id }}" value="{{ $case->resolved_date }}">
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_follow_up_date{{ $case->case_id }}" class="add-label">Follow Up Date</label>
                                    <input type="date" class="add-input" name="follow_up_date" id="edit_follow_up_date{{ $case->case_id }}" value="{{ $case->follow_up_date }}">
                                </div>
                            </div>

                            <!-- Row 5: Description -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_description{{ $case->case_id }}" class="add-label">Description</label>
                                    <textarea class="add-input" name="description" id="edit_description{{ $case->case_id }}">{{ $case->description }}</textarea>
                                </div>
                            </div>

                            <!-- Row 6: Witnesses -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_witnesses{{ $case->case_id }}" class="add-label">Witnesses</label>
                                    <textarea class="add-input" name="witnesses" id="edit_witnesses{{ $case->case_id }}">{{ $case->witnesses }}</textarea>
                                </div>
                            </div>

                            <!-- Row 7: Investigation Notes -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_investigation_notes{{ $case->case_id }}" class="add-label">Investigation Notes</label>
                                    <textarea class="add-input" name="investigation_notes" id="edit_investigation_notes{{ $case->case_id }}">{{ $case->investigation_notes }}</textarea>
                                </div>
                            </div>

                            <!-- Row 8: Evidence -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_evidence{{ $case->case_id }}" class="add-label">Evidence</label>
                                    <textarea class="add-input" name="evidence" id="edit_evidence{{ $case->case_id }}">{{ $case->evidence }}</textarea>
                                </div>
                            </div>

                            <!-- Row 9: Resolution Notes -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_resolution_notes{{ $case->case_id }}" class="add-label">Resolution Notes</label>
                                    <textarea class="add-input" name="resolution_notes" id="edit_resolution_notes{{ $case->case_id }}">{{ $case->resolution_notes }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="pro-add-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endisset


<!-- =========================
     ARCHIVE CASE MODAL
========================= -->
<div class="modal case-modal" id="archiveCaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content archive-modal-card">
      <div class="header">
        <div class="image">
          <svg
            aria-hidden="true"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24"
            fill="none"
          >
            <path
              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
              stroke-linejoin="round"
              stroke-linecap="round"
            ></path>
          </svg>
        </div>

        <div class="content">
          <span class="case-modal-title" style="font-size: 1.5rem;">Archive Case Record</span>
          <p class="message">
            Are you sure you want to archive this case? Once archived, it will move to
            your archive list and can be restored later if necessary.
          </p>
        </div>

        <div class="actions">
          <button class="archive" type="button" id="archiveConfirmBtn">
            Yes, Archive
          </button>
          <button class="cancel" type="button" id="archiveModalClose">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- =========================
     SCRIPTS
========================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
    // Toggle Other Case Type input
    function toggleOtherType(select) {
        const input = document.getElementById('other_case_type');
        if (!input) return;
        input.style.display = select.value === 'other' ? 'block' : 'none';
        input.required = select.value === 'other';
    }

    function toggleOtherTypeEdit(caseId) {
        const select = document.getElementById('edit_case_type_id' + caseId);
        const input = document.getElementById('edit_other_case_type' + caseId);
        if (!select || !input) return;
        input.style.display = select.value === 'other' ? 'block' : 'none';
        input.required = select.value === 'other';
    }

    // Modal close when clicking outside content (Bootstrap-compatible behavior)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal.case-modal').forEach(modal => {
            modal.addEventListener('mousedown', e => {
                const content = modal.querySelector('.modal-content');
                if (content && !content.contains(e.target)) {
                    try { bootstrap.Modal.getInstance(modal)?.hide(); } catch(e) { modal.style.display = 'none'; }
                }
            });
        });
    });

    (function(){
        // small helper to fetch students from server; empty query returns a default list
        function fetchStudents(query, cb) {
            $.ajax({
                url: "{{ route('Head.students.search') }}",
                dataType: 'json',
                data: { q: query || '' },
                success: function(data){ cb && cb(data); },
                error: function(xhr, status, err){
                    console.error('Student search error:', status, err, xhr.responseText);
                    cb && cb([]);
                }
            });
        }

        // ----- Add modal state -----
        var selectedStudents = [];
        function renderTags() {
            const $tagInput = $("#student-tag-input");
            $tagInput.find('.student-tag').remove();
            selectedStudents.forEach(student => {
                $(`<span class="student-tag" data-id="${student.id}">
                    ${student.text}
                    <span class="remove-tag" title="Remove">&times;</span>
                </span>`).insertBefore($("#student_search"));
            });
            $("#involved_students").val(selectedStudents.map(s => s.id).join(','));
        }

        function renderResults(items, $container, existing) {
            $container.empty();
            const filtered = (items || []).filter(i => !existing.some(s => s.id == i.id));
            if (filtered.length === 0) {
                // show a non-clickable placeholder so the dropdown is visible
                $container.append(`<div class="list-group-item disabled">No students found</div>`);
                $container.show();
                return;
            }
            filtered.forEach(item => {
                $container.append(`<button type="button" class="list-group-item list-group-item-action" data-id="${item.id}" data-text="${item.text}">${item.text}</button>`);
            });
            $container.show();
        }

        // input handlers for add modal
        $(document).on('focus click', '#student_search', function(){
            const $results = $('#student_search_results');
            $results.show(); // ensure container is visible immediately
            // always fetch default list to populate
            fetchStudents('', function(data){ renderResults(data, $results, selectedStudents); });
        });

        $(document).on('input', '#student_search', function(){
            const q = $(this).val();
            const $results = $('#student_search_results');
            if (!q || q.length < 2) { $results.hide(); return; }
            fetchStudents(q, function(data){ renderResults(data, $results, selectedStudents); });
        });

        // click result (add)
        $(document).on('click', '#student_search_results .list-group-item', function(){
            const id = $(this).data('id'); const text = $(this).data('text');
            if (!selectedStudents.some(s => s.id == id)) selectedStudents.push({ id, text });
            renderTags(); $('#student_search').val(''); $('#student_search_results').hide(); $('#student_search').focus();
        });

        // remove tag
        $(document).on('click', '#student-tag-input .remove-tag', function(){
            const id = $(this).parent().data('id'); selectedStudents = selectedStudents.filter(s => s.id != id); renderTags();
        });

        // hide results when clicking outside
        $(document).on('click', function(e){ if (!$(e.target).closest('#student_search, #student_search_results').length) $('#student_search_results').hide(); });

        // ----- Per-case edit inputs -----
        @isset($cases)
            @foreach($cases as $case)
                (function(){
                    var selectedEdit{{ $case->case_id }} = [
                        @foreach($case->students as $s)
                            { id: '{{ $s->s_id }}', text: '{{ ($s->user->first_name ?? '') . " " . ($s->user->last_name ?? '') }} | {{ $s->s_id }}' },
                        @endforeach
                    ];

                    function renderEditTags{{ $case->case_id }}(){
                        var $tagInput = $("#edit-student-tag-input{{ $case->case_id }}"); $tagInput.find('.student-tag').remove();
                        selectedEdit{{ $case->case_id }}.forEach(student => {
                            $(`<span class="student-tag" data-id="${student.id}">${student.text}<span class="remove-tag" title="Remove">&times;</span></span>`).insertBefore($("#edit_student_search{{ $case->case_id }}"));
                        });
                        $("#edit_involved_students{{ $case->case_id }}").val(selectedEdit{{ $case->case_id }}.map(s => s.id).join(','));
                    }

                    // focus shows list
                    $(document).on('focus click', '#edit_student_search{{ $case->case_id }}', function(){
                        var $results = $('#edit_student_search_results{{ $case->case_id }}');
                        $results.show();
                        fetchStudents('', function(data){ renderResults(data, $results, selectedEdit{{ $case->case_id }}); });
                    });

                    // typing search
                    $(document).on('input', '#edit_student_search{{ $case->case_id }}', function(){
                        var q = $(this).val(); var $results = $('#edit_student_search_results{{ $case->case_id }}'); if (!q || q.length < 2) { $results.hide(); return; }
                        fetchStudents(q, function(data){ renderResults(data, $results, selectedEdit{{ $case->case_id }}); });
                    });

                    // click result -> add
                    $(document).on('click', '#edit_student_search_results{{ $case->case_id }} .list-group-item', function(){
                        var id = $(this).data('id'); var text = $(this).data('text'); if (!selectedEdit{{ $case->case_id }}.some(s => s.id == id)) selectedEdit{{ $case->case_id }}.push({id, text}); renderEditTags{{ $case->case_id }}(); $('#edit_student_search{{ $case->case_id }}').val(''); $('#edit_student_search_results{{ $case->case_id }}').hide();
                    });

                    // remove tag
                    $(document).on('click', '#edit-student-tag-input{{ $case->case_id }} .remove-tag', function(){ var id = $(this).parent().data('id'); selectedEdit{{ $case->case_id }} = selectedEdit{{ $case->case_id }}.filter(s => s.id != id); renderEditTags{{ $case->case_id }}(); });

                    // initial render
                    renderEditTags{{ $case->case_id }}();
                })();
            @endforeach
        @endisset

        // ----- Render compact view tags for View Modal -----
        @isset($cases)
            @foreach($cases as $case)
                (function(){
                    var viewStudents{{ $case->case_id }} = [
                        @foreach($case->students as $s)
                            { id: '{{ $s->s_id }}', text: '{{ ($s->user->first_name ?? '') . " " . ($s->user->last_name ?? '') }} | {{ $s->s_id }}' },
                        @endforeach
                    ];

                    function renderViewTags{{ $case->case_id }}(){
                        var $container = $("#view-student-tag-input{{ $case->case_id }}");
                        $container.empty();
                        if(!viewStudents{{ $case->case_id }} || viewStudents{{ $case->case_id }}.length === 0){
                            $container.append('<span class="student-tag">No students</span>');
                            return;
                        }
                        // show first student
                        var first = viewStudents{{ $case->case_id }}[0];
                        $container.append(`<span class="student-tag">${first.text}</span>`);

                        if(viewStudents{{ $case->case_id }}.length > 1){
                            var moreCount = viewStudents{{ $case->case_id }}.length - 1;
                            var $more = $(`<span class="student-more">+${moreCount} view more</span>`);
                            $container.append($more);

                            $more.on('click', function(e){
                                e.stopPropagation();
                                // remove any existing popup
                                $container.find('.student-more-list').remove();
                                var $list = $('<div class="student-more-list" role="menu"></div>');
                                viewStudents{{ $case->case_id }}.slice(1).forEach(function(s){
                                    $list.append(`<div class="student-more-item">${s.text}</div>`);
                                });
                                $container.append($list);

                                // close on outside click
                                $(document).on('click.viewmore{{ $case->case_id }}', function(ev){
                                    if(!$(ev.target).closest('#view-student-tag-input{{ $case->case_id }}').length){
                                        $list.remove();
                                        $(document).off('click.viewmore{{ $case->case_id }}');
                                    }
                                });
                            });
                        }
                    }

                    // initial render
                    renderViewTags{{ $case->case_id }}();
                })();
            @endforeach
        @endisset

    })();
</script>
