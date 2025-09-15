<!-- Add Case Modal -->
<div class="modal fade" id="addCaseModal" tabindex="-1" aria-labelledby="addCaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="<?php echo e(route('Head.cases.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-content add-modal-content pro-add-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCaseModalLabel">Add New Case</h5>
                    <button type="button" class="close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Student Search Input (full row) -->
                    <div class="form-row">
                        <div class="add-field-col" style="flex:1;">
                            <label for="student_search" class="add-label">Search Student</label>
                            <div id="student-tag-input" class="student-tag-input">
                                <input type="text" id="student_search" class="add-input student-search-input"
                                    placeholder="Type name or ID" autocomplete="off">
                            </div>
                            <input type="hidden" name="involved_students" id="involved_students">
                            <div id="student_search_results" class="list-group" style="display: none;"></div>
                        </div>
                    </div>
                    <!-- Row 1: 2 fields (Type, Severity) -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="case_type_id" class="add-label">Type</label>
                            <select class="add-input" name="case_type_id" id="case_type_id" required onchange="toggleOtherType(this)">
                                <option value="">Select Type</option>
                                <?php $__currentLoopData = \App\Models\CaseType::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->type_id); ?>"><?php echo e($type->type_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="other">Other</option>
                            </select>
                            <input type="text" class="add-input mt-2" name="other_case_type" id="other_case_type"
                                placeholder="Enter new case type" style="display:none;">
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
                    <!-- Row 2: 2 fields (Presenting Problem, Action Taken) -->
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
                    <!-- Row 3: 3 text fields -->
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
                    <!-- Row 4: 2 text fields -->
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
                    <!-- Row 5: 2 textarea fields -->
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
                    <!-- Row 6: 2 textarea fields -->
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
                    <!-- Row 7: 1 textarea field -->
                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="resolution_notes" class="add-label">Resolution Notes</label>
                            <textarea class="add-input" name="resolution_notes"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pro-add-buttons">
                    <button type="submit" class="pro-add-save">Add Case</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- View Case Modal (form layout, read-only) -->
<?php if(isset($cases)): ?>
    <?php $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="viewCaseModal<?php echo e($case->case_id); ?>" tabindex="-1"
            aria-labelledby="viewCaseModalLabel<?php echo e($case->case_id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content add-modal-content pro-add-modal">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCaseModalLabel<?php echo e($case->case_id); ?>">Case Details</h5>
                        <button type="button" class="close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Involved Students -->
                        <div class="form-row">
                            <div class="add-field-col">
                                    <label class="add-label">Involved Students</label>
                                    <?php if($case->students && count($case->students) > 0): ?>
                                        <ul class="list-group">
                                            <?php $__currentLoopData = $case->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-group-item">
                                                    <?php echo e($student->user->first_name ?? ''); ?> <?php echo e($student->user->last_name ?? ''); ?> | <?php echo e($student->s_id); ?>

                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php else: ?>
                                        <span>No students involved.</span>
                                    <?php endif; ?>
                            </div>
                        </div>
                        <!-- Row 1: 2 fields -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Type</label>
                                <div class="add-input" readonly><?php echo e($case->caseType->type_name ?? 'N/A'); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Severity</label>
                                <div class="add-input" readonly><?php echo e($case->severity); ?></div>
                            </div>
                        </div>
                        <!-- Row 2: 2 fields -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Presenting Problem</label>
                                <div class="add-input" readonly><?php echo e($case->presenting_problem); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Action Taken</label>
                                <div class="add-input" readonly><?php echo e($case->action_taken); ?></div>
                            </div>
                        </div>
                        <!-- Row 3: 3 fields -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Filed Date</label>
                                <div class="add-input" readonly><?php echo e($case->filed_date); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Filed Time</label>
                                <div class="add-input" readonly><?php echo e($case->filed_time); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Status</label>
                                <div class="add-input" readonly><?php echo e($case->status); ?></div>
                            </div>
                        </div>
                        <!-- Row 4: 2 fields -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Resolved Date</label>
                                <div class="add-input" readonly><?php echo e($case->resolved_date); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Follow Up Date</label>
                                <div class="add-input" readonly><?php echo e($case->follow_up_date); ?></div>
                            </div>
                        </div>
                        <!-- Row 5: 2 textarea fields -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Description</label>
                                <div class="add-input" readonly><?php echo e($case->description); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Witnesses</label>
                                <div class="add-input" readonly><?php echo e($case->witnesses); ?></div>
                            </div>
                        </div>
                        <!-- Row 6: 2 textarea fields -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Investigation Notes</label>
                                <div class="add-input" readonly><?php echo e($case->investigation_notes); ?></div>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Evidence</label>
                                <div class="add-input" readonly><?php echo e($case->evidence); ?></div>
                            </div>
                        </div>
                        <!-- Row 7: 1 textarea field -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Resolution Notes</label>
                                <div class="add-input" readonly><?php echo e($case->resolution_notes); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pro-add-buttons">
                        <button type="button" class="pro-add-save" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Case Modal (form layout) -->
        <div class="modal fade" id="editCaseModal<?php echo e($case->case_id); ?>" tabindex="-1"
            aria-labelledby="editCaseModalLabel<?php echo e($case->case_id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="<?php echo e(route('Head.cases.update', $case->case_id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-content add-modal-content pro-add-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCaseModalLabel<?php echo e($case->case_id); ?>">Edit Case</h5>
                            <button type="button" class="close pro-add-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <!-- Student Search Input (full row) -->
                            <div class="form-row">
                                <div class="add-field-col" style="flex:1;">
                                    <label for="edit_student_search<?php echo e($case->case_id); ?>" class="add-label">Search Student</label>
                                    <div id="edit-student-tag-input<?php echo e($case->case_id); ?>" class="student-tag-input">
                                        <input type="text" id="edit_student_search<?php echo e($case->case_id); ?>" class="add-input student-search-input"
                                            placeholder="Type name or ID" autocomplete="off">
                                    </div>
                                    <input type="hidden" name="involved_students" id="edit_involved_students<?php echo e($case->case_id); ?>">
                                    <div id="edit_student_search_results<?php echo e($case->case_id); ?>" class="list-group" style="display: none;"></div>
                                </div>
                            </div>
                            <!-- Row 1: 2 fields -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_case_type_id<?php echo e($case->case_id); ?>" class="add-label">Type</label>
                                    <select class="add-input" name="case_type_id"
                                        id="edit_case_type_id<?php echo e($case->case_id); ?>" required
                                        onchange="toggleOtherTypeEdit(<?php echo e($case->case_id); ?>)">
                                        <?php $__currentLoopData = \App\Models\CaseType::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->type_id); ?>"
                                                <?php echo e($case->case_type_id == $type->type_id ? 'selected' : ''); ?>>
                                                <?php echo e($type->type_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <option value="other">Other</option>
                                    </select>
                                    <input type="text" class="add-input mt-2" name="other_case_type"
                                        id="edit_other_case_type<?php echo e($case->case_id); ?>" placeholder="Enter new case type"
                                        style="display:none;">
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_severity<?php echo e($case->case_id); ?>" class="add-label">Severity</label>
                                    <select class="add-input" name="severity" id="edit_severity<?php echo e($case->case_id); ?>" required>
                                        <option value="Minor" <?php echo e($case->severity == 'Minor' ? 'selected' : ''); ?>>Minor</option>
                                        <option value="Major" <?php echo e($case->severity == 'Major' ? 'selected' : ''); ?>>Major</option>
                                        <option value="Grave" <?php echo e($case->severity == 'Grave' ? 'selected' : ''); ?>>Grave</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Row 2: 2 fields -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_presenting_problem<?php echo e($case->case_id); ?>" class="add-label">Presenting Problem</label>
                                    <input type="text" class="add-input" name="presenting_problem"
                                        id="edit_presenting_problem<?php echo e($case->case_id); ?>"
                                        value="<?php echo e($case->presenting_problem); ?>" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_action_taken<?php echo e($case->case_id); ?>" class="add-label">Action Taken</label>
                                    <input type="text" class="add-input" name="action_taken"
                                        id="edit_action_taken<?php echo e($case->case_id); ?>"
                                        value="<?php echo e($case->action_taken); ?>">
                                </div>
                            </div>
                            <!-- Row 3: 3 text fields -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_filed_date<?php echo e($case->case_id); ?>" class="add-label">Filed Date</label>
                                    <input type="date" class="add-input" name="filed_date"
                                        id="edit_filed_date<?php echo e($case->case_id); ?>"
                                        value="<?php echo e($case->filed_date); ?>" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_filed_time<?php echo e($case->case_id); ?>" class="add-label">Filed Time</label>
                                    <input type="time" class="add-input" name="filed_time"
                                        id="edit_filed_time<?php echo e($case->case_id); ?>"
                                        value="<?php echo e($case->filed_time); ?>" required>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_status<?php echo e($case->case_id); ?>" class="add-label">Status</label>
                                    <select class="add-input" name="status" id="edit_status<?php echo e($case->case_id); ?>" required>
                                        <option value="Pending" <?php echo e($case->status == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="Under Investigation" <?php echo e($case->status == 'Under Investigation' ? 'selected' : ''); ?>>Under Investigation</option>
                                        <option value="Resolved" <?php echo e($case->status == 'Resolved' ? 'selected' : ''); ?>>Resolved</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Row 4: 2 text fields -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_resolved_date<?php echo e($case->case_id); ?>" class="add-label">Resolved Date</label>
                                    <input type="date" class="add-input" name="resolved_date"
                                        id="edit_resolved_date<?php echo e($case->case_id); ?>"
                                        value="<?php echo e($case->resolved_date); ?>">
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_follow_up_date<?php echo e($case->case_id); ?>" class="add-label">Follow Up Date</label>
                                    <input type="date" class="add-input" name="follow_up_date"
                                        id="edit_follow_up_date<?php echo e($case->case_id); ?>"
                                        value="<?php echo e($case->follow_up_date); ?>">
                                </div>
                            </div>
                            <!-- Row 5: 2 textarea fields -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_description<?php echo e($case->case_id); ?>" class="add-label">Description</label>
                                    <textarea class="add-input" name="description"
                                        id="edit_description<?php echo e($case->case_id); ?>" required><?php echo e($case->description); ?></textarea>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_witnesses<?php echo e($case->case_id); ?>" class="add-label">Witnesses</label>
                                    <textarea class="add-input" name="witnesses"
                                        id="edit_witnesses<?php echo e($case->case_id); ?>"><?php echo e($case->witnesses); ?></textarea>
                                </div>
                            </div>
                            <!-- Row 6: 2 textarea fields -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_investigation_notes<?php echo e($case->case_id); ?>" class="add-label">Investigation Notes</label>
                                    <textarea class="add-input" name="investigation_notes"
                                        id="edit_investigation_notes<?php echo e($case->case_id); ?>"><?php echo e($case->investigation_notes); ?></textarea>
                                </div>
                                <div class="add-field-col">
                                    <label for="edit_evidence<?php echo e($case->case_id); ?>" class="add-label">Evidence</label>
                                    <textarea class="add-input" name="evidence"
                                        id="edit_evidence<?php echo e($case->case_id); ?>"><?php echo e($case->evidence); ?></textarea>
                                </div>
                            </div>
                            <!-- Row 7: 1 textarea field -->
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label for="edit_resolution_notes<?php echo e($case->case_id); ?>" class="add-label">Resolution Notes</label>
                                    <textarea class="add-input" name="resolution_notes"
                                        id="edit_resolution_notes<?php echo e($case->case_id); ?>"><?php echo e($case->resolution_notes); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer pro-add-buttons">
                            <button type="submit" class="pro-add-save">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<script>
    function toggleOtherType(select) {
        var otherInput = document.getElementById('other_case_type');
        if (select.value === 'other') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
        }
    }

    function toggleOtherTypeEdit(caseId) {
        var select = document.getElementById('edit_case_type_id' + caseId);
        var otherInput = document.getElementById('edit_other_case_type' + caseId);
        if (select.value === 'other') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
$(function() {
    let selectedStudents = [];

    function renderTags() {
        const $tagInput = $("#student-tag-input");
        $tagInput.find(".student-tag").remove();
        selectedStudents.forEach(student => {
            $(`<span class="student-tag" data-id="${student.id}">
                ${student.text}
                <span class="remove-tag" title="Remove">&times;</span>
            </span>`).insertBefore($("#student_search"));
        });
        $("#involved_students").val(selectedStudents.map(s => s.id).join(','));
    }

    function renderResults(items) {
        const $results = $("#student_search_results");
        $results.empty();
        if (items.length === 0) {
            $results.hide();
            return;
        }
        items.forEach(item => {
            $results.append(
                `<button type="button" class="list-group-item list-group-item-action" data-id="${item.id}" data-text="${item.text}">${item.text}</button>`
            );
        });
        $results.show();
    }

    $("#student_search").on("input", function() {
        const query = $(this).val();
        if (query.length < 2) {
            $("#student_search_results").hide();
            return;
        }
        $.ajax({
            url: "<?php echo e(route('Head.students.search')); ?>",
            dataType: "json",
            data: { q: query },
            success: function(data) {
                // Filter out already selected students
                const filtered = data.filter(item => !selectedStudents.some(s => s.id == item.id));
                renderResults(filtered);
            }
        });
    });

    // Handle click on result
    $("#student_search_results").on("click", ".list-group-item", function() {
        const id = $(this).data("id");
        const text = $(this).data("text");
        if (!selectedStudents.some(s => s.id == id)) {
            selectedStudents.push({id, text});
            renderTags();
        }
        $("#student_search").val('');
        $("#student_search_results").hide();
        $("#student_search").focus();
    });

    // Remove student tag
    $("#student-tag-input").on("click", ".remove-tag", function(e) {
        const id = $(this).parent().data("id");
        selectedStudents = selectedStudents.filter(s => s.id != id);
        renderTags();
    });

    // Hide results when clicking outside
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#student_search, #student_search_results").length) {
            $("#student_search_results").hide();
        }
    });
});
</script>

<script>
<?php if(isset($cases)): ?>
<?php $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
$(function() {
    let selectedStudentsEdit<?php echo e($case->case_id); ?> = [
        <?php $__currentLoopData = $case->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {id: "<?php echo e($student->s_id); ?>", text: "<?php echo e(($student->user->first_name ?? '') . ' ' . ($student->user->last_name ?? '')); ?> | <?php echo e($student->s_id); ?>" },
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ];

    function renderTagsEdit<?php echo e($case->case_id); ?>() {
        const $tagInput = $("#edit-student-tag-input<?php echo e($case->case_id); ?>");
        $tagInput.find(".student-tag").remove();
        selectedStudentsEdit<?php echo e($case->case_id); ?>.forEach(student => {
            $(`<span class="student-tag" data-id="${student.id}">
                ${student.text}
                <span class="remove-tag" title="Remove">&times;</span>
            </span>`).insertBefore($("#edit_student_search<?php echo e($case->case_id); ?>"));
        });
        $("#edit_involved_students<?php echo e($case->case_id); ?>").val(selectedStudentsEdit<?php echo e($case->case_id); ?>.map(s => s.id).join(','));
    }

    function renderResultsEdit<?php echo e($case->case_id); ?>(items) {
        const $results = $("#edit_student_search_results<?php echo e($case->case_id); ?>");
        $results.empty();
        if (items.length === 0) {
            $results.hide();
            return;
        }
        items.forEach(item => {
            $results.append(
                `<button type="button" class="list-group-item list-group-item-action" data-id="${item.id}" data-text="${item.text}">${item.text}</button>`
            );
        });
        $results.show();
    }

    $("#edit_student_search<?php echo e($case->case_id); ?>").on("input", function() {
        const query = $(this).val();
        if (query.length < 2) {
            $("#edit_student_search_results<?php echo e($case->case_id); ?>").hide();
            return;
        }
        $.ajax({
            url: "<?php echo e(route('Head.students.search')); ?>",
            dataType: "json",
            data: { q: query },
            success: function(data) {
                // Filter out already selected students
                const filtered = data.filter(item => !selectedStudentsEdit<?php echo e($case->case_id); ?>.some(s => s.id == item.id));
                renderResultsEdit<?php echo e($case->case_id); ?>(filtered);
            }
        });
    });

    // Handle click on result
    $("#edit_student_search_results<?php echo e($case->case_id); ?>").on("click", ".list-group-item", function() {
        const id = $(this).data("id");
        const text = $(this).data("text");
        if (!selectedStudentsEdit<?php echo e($case->case_id); ?>.some(s => s.id == id)) {
            selectedStudentsEdit<?php echo e($case->case_id); ?>.push({id, text});
            renderTagsEdit<?php echo e($case->case_id); ?>();
        }
        $("#edit_student_search<?php echo e($case->case_id); ?>").val('');
        $("#edit_student_search_results<?php echo e($case->case_id); ?>").hide();
        $("#edit_student_search<?php echo e($case->case_id); ?>").focus();
    });

    // Remove student tag
    $("#edit-student-tag-input<?php echo e($case->case_id); ?>").on("click", ".remove-tag", function(e) {
        const id = $(this).parent().data("id");
        selectedStudentsEdit<?php echo e($case->case_id); ?> = selectedStudentsEdit<?php echo e($case->case_id); ?>.filter(s => s.id != id);
        renderTagsEdit<?php echo e($case->case_id); ?>();
    });

    // Hide results when clicking outside
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#edit_student_search<?php echo e($case->case_id); ?>, #edit_student_search_results<?php echo e($case->case_id); ?>").length) {
            $("#edit_student_search_results<?php echo e($case->case_id); ?>").hide();
        }
    });

    // Initial render
    renderTagsEdit<?php echo e($case->case_id); ?>();
});
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
</script><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS-\resources\views/Head/Modal/caseModal.blade.php ENDPATH**/ ?>