<!-- =========================
     ADD CASE MODAL
========================= -->
<div class="modal case-modal" id="addCaseModal" tabindex="-1" aria-labelledby="addCaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('Head.cases.store') }}">
            @csrf
            <div class="modal-content case-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCaseModalLabel">Add New Case</h5>
                    <span class="close add-modal-close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                </div>

                <div class="modal-body case-modal-body">
                    <!-- Student Search Input -->
                    <div class="form-row">
                        <div class="add-field-col" style="flex:1;">
                            <label for="student_search" class="add-label">Search Student</label>
                            <div id="student-tag-input" class="student-tag-input">
                                <input type="text" id="student_search" class="add-input student-search-input" placeholder="Type name or ID" autocomplete="off">
                            </div>
                            <input type="hidden" name="involved_students" id="involved_students">
                            <div id="student_search_results" class="list-group" style="display: none;"></div>
                        </div>
                    </div>

                    <!-- Row 1: Type & Severity -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="case_type_id" class="add-label">Type</label>
                            <select class="add-input" name="case_type_id" id="case_type_id" required onchange="toggleOtherType(this)">
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
                            <select class="add-input" name="severity" required>
                                <option value="Minor">Minor</option>
                                <option value="Major">Major</option>
                                <option value="Grave">Grave</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Presenting Problem & Action Taken -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="presenting_problem" class="add-label">Presenting Problem</label>
                            <input type="text" class="add-input" name="presenting_problem" required>
                        </div>
                        <div class="add-field-col">
                            <label for="action_taken" class="add-label">Action Taken</label>
                            <input type="text" class="add-input" name="action_taken">
                        </div>
                    </div>

                    <!-- Row 3: Filed Date, Time, Status -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="filed_date" class="add-label">Filed Date</label>
                            <input type="date" class="add-input" name="filed_date" required>
                        </div>
                        <div class="add-field-col">
                            <label for="filed_time" class="add-label">Filed Time</label>
                            <input type="time" class="add-input" name="filed_time" required>
                        </div>
                        <div class="add-field-col">
                            <label for="status" class="add-label">Status</label>
                            <select class="add-input" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Under Investigation">Under Investigation</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

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

                    <!-- Row 5: Description & Witnesses -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="description" class="add-label">Description</label>
                            <textarea class="add-input" name="description" required></textarea>
                        </div>
                        <div class="add-field-col">
                            <label for="witnesses" class="add-label">Witnesses</label>
                            <textarea class="add-input" name="witnesses"></textarea>
                        </div>
                    </div>

                    <!-- Row 6: Investigation Notes & Evidence -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="investigation_notes" class="add-label">Investigation Notes</label>
                            <textarea class="add-input" name="investigation_notes"></textarea>
                        </div>
                        <div class="add-field-col">
                            <label for="evidence" class="add-label">Evidence</label>
                            <textarea class="add-input" name="evidence"></textarea>
                        </div>
                    </div>

                    <!-- Row 7: Resolution Notes -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="resolution_notes" class="add-label">Resolution Notes</label>
                            <textarea class="add-input" name="resolution_notes"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer pro-add-buttons case-modal-footer">
                    <button type="submit" class="pro-add-save">Add Case</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- =========================
     VIEW + EDIT CASE MODALS
========================= -->
@isset($cases)
    @foreach ($cases as $case)
        <!-- View Case Modal -->
        <div class="modal case-modal" id="viewCaseModal{{ $case->case_id }}" tabindex="-1" aria-labelledby="viewCaseModalLabel{{ $case->case_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content case-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCaseModalLabel{{ $case->case_id }}">Case Details</h5>
                        <span class="close add-modal-close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                    </div>
                    <div class="modal-body case-modal-body">
                        <!-- Involved Students -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Involved Students</label>
                                @if($case->students && count($case->students) > 0)
                                    <ul class="list-group">
                                        @foreach($case->students as $student)
                                            <li class="list-group-item">
                                                {{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }} | {{ $student->s_id }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span>No students involved.</span>
                                @endif
                            </div>
                        </div>

                        <!-- Case Fields -->
                        @php
                            $fields = [
                                ['Type' => $case->caseType->type_name ?? 'N/A', 'Severity' => $case->severity],
                                ['Presenting Problem' => $case->presenting_problem, 'Action Taken' => $case->action_taken],
                                ['Filed Date' => $case->filed_date, 'Filed Time' => $case->filed_time, 'Status' => $case->status],
                                ['Resolved Date' => $case->resolved_date, 'Follow Up Date' => $case->follow_up_date],
                                ['Description' => $case->description, 'Witnesses' => $case->witnesses],
                                ['Investigation Notes' => $case->investigation_notes, 'Evidence' => $case->evidence],
                                ['Resolution Notes' => $case->resolution_notes]
                            ];
                        @endphp

                        @foreach($fields as $row)
                            <div class="form-row">
                                @foreach($row as $label => $value)
                                    <div class="add-field-col">
                                        <label class="add-label">{{ $label }}</label>
                                        <div class="add-input" readonly>{{ $value }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer pro-add-buttons case-modal-footer">
                        <button type="button" class="pro-add-save" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Case Modal -->
        <div class="modal case-modal" id="editCaseModal{{ $case->case_id }}" tabindex="-1" aria-labelledby="editCaseModalLabel{{ $case->case_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('Head.cases.update', $case->case_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content case-modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCaseModalLabel{{ $case->case_id }}">Edit Case</h5>
                            <span class="close add-modal-close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</span>
                        </div>
                        <div class="modal-body case-modal-body">
                            <!-- Student Search Input -->
                            <div class="form-row">
                                <div class="add-field-col" style="flex:1;">
                                    <label for="edit_student_search{{ $case->case_id }}" class="add-label">Search Student</label>
                                    <div id="edit-student-tag-input{{ $case->case_id }}" class="student-tag-input">
                                        <input type="text" id="edit_student_search{{ $case->case_id }}" class="add-input student-search-input" placeholder="Type name or ID" autocomplete="off">
                                    </div>
                                    <input type="hidden" name="involved_students" id="edit_involved_students{{ $case->case_id }}" value="{{ $case->students->pluck('user_id')->implode(',') }}">
                                    <div id="edit_student_search_results{{ $case->case_id }}" class="list-group" style="display: none;"></div>
                                </div>
                            </div>

                            <!-- Row 1: Type & Severity -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_case_type_id{{ $case->case_id }}" class="add-label">Type</label>
                                    <select class="add-input" name="case_type_id" id="edit_case_type_id{{ $case->case_id }}" required onchange="toggleOtherTypeEdit({{ $case->case_id }})">
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
                                    <select class="add-input" name="severity" id="edit_severity{{ $case->case_id }}" required>
                                        <option value="Minor" {{ $case->severity == 'Minor' ? 'selected' : '' }}>Minor</option>
                                        <option value="Major" {{ $case->severity == 'Major' ? 'selected' : '' }}>Major</option>
                                        <option value="Grave" {{ $case->severity == 'Grave' ? 'selected' : '' }}>Grave</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 2: Presenting Problem & Action Taken -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_presenting_problem{{ $case->case_id }}" class="add-label">Presenting Problem</label>
                                    <input type="text" class="add-input" name="presenting_problem" id="edit_presenting_problem{{ $case->case_id }}" value="{{ $case->presenting_problem }}" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_action_taken{{ $case->case_id }}" class="add-label">Action Taken</label>
                                    <input type="text" class="add-input" name="action_taken" id="edit_action_taken{{ $case->case_id }}" value="{{ $case->action_taken }}">
                                </div>
                            </div>

                            <!-- Row 3: Filed Date, Filed Time, Status -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_filed_date{{ $case->case_id }}" class="add-label">Filed Date</label>
                                    <input type="date" class="add-input" name="filed_date" id="edit_filed_date{{ $case->case_id }}" value="{{ $case->filed_date }}" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_filed_time{{ $case->case_id }}" class="add-label">Filed Time</label>
                                    <input type="time" class="add-input" name="filed_time" id="edit_filed_time{{ $case->case_id }}" value="{{ $case->filed_time }}" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_status{{ $case->case_id }}" class="add-label">Status</label>
                                    <select class="add-input" name="status" id="edit_status{{ $case->case_id }}" required>
                                        <option value="Pending" {{ $case->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Under Investigation" {{ $case->status == 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                                        <option value="Resolved" {{ $case->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </div>
                            </div>

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

                            <!-- Row 5: Description & Witnesses -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_description{{ $case->case_id }}" class="add-label">Description</label>
                                    <textarea class="add-input" name="description" id="edit_description{{ $case->case_id }}" required>{{ $case->description }}</textarea>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_witnesses{{ $case->case_id }}" class="add-label">Witnesses</label>
                                    <textarea class="add-input" name="witnesses" id="edit_witnesses{{ $case->case_id }}">{{ $case->witnesses }}</textarea>
                                </div>
                            </div>

                            <!-- Row 6: Investigation Notes & Evidence -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_investigation_notes{{ $case->case_id }}" class="add-label">Investigation Notes</label>
                                    <textarea class="add-input" name="investigation_notes" id="edit_investigation_notes{{ $case->case_id }}">{{ $case->investigation_notes }}</textarea>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_evidence{{ $case->case_id }}" class="add-label">Evidence</label>
                                    <textarea class="add-input" name="evidence" id="edit_evidence{{ $case->case_id }}">{{ $case->evidence }}</textarea>
                                </div>
                            </div>

                            <!-- Row 7: Resolution Notes -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_resolution_notes{{ $case->case_id }}" class="add-label">Resolution Notes</label>
                                    <textarea class="add-input" name="resolution_notes" id="edit_resolution_notes{{ $case->case_id }}">{{ $case->resolution_notes }}</textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer pro-add-buttons case-modal-footer">
                            <button type="submit" class="pro-add-save">Save Changes</button>
                        </div>
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
        <div class="modal-content archive-modal" style="max-width: 500px;">
            <div class="modal-header">
                <h5 class="modal-title">Archive Case</h5>
                <span class="close" id="archiveModalClose">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this case?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" id="archiveCancelBtn">Cancel</button>
                <button type="button" class="btn btn-confirm" id="archiveConfirmBtn">Archive</button>
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
        input.style.display = select.value === 'other' ? 'block' : 'none';
        input.required = select.value === 'other';
    }

    function toggleOtherTypeEdit(caseId) {
        const select = document.getElementById('edit_case_type_id' + caseId);
        const input = document.getElementById('edit_other_case_type' + caseId);
        input.style.display = select.value === 'other' ? 'block' : 'none';
        input.required = select.value === 'other';
    }

    // Modal close when clicking outside content
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
</script>
