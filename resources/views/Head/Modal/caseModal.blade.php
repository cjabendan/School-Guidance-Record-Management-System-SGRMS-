<!-- Add Case Modal -->
<div class="modal fade" id="addCaseModal" tabindex="-1" aria-labelledby="addCaseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('Head.cases.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCaseModalLabel">Add New Case</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="case_type_id" class="form-label">Type</label>
            <select class="form-select" name="case_type_id" id="case_type_id" required onchange="toggleOtherType(this)">
              <option value="">Select Type</option>
              @foreach(\App\Models\CaseType::all() as $type)
                <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
              @endforeach
              <option value="other">Other</option>
            </select>
            <input type="text" class="form-control mt-2" name="other_case_type" id="other_case_type" placeholder="Enter new case type" style="display:none;">
          </div>
          <div class="mb-3">
            <label for="presenting_problem" class="form-label">Presenting Problem</label>
            <input type="text" class="form-control" name="presenting_problem" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="severity" class="form-label">Severity</label>
            <select class="form-select" name="severity" required>
              <option value="Low">Low</option>
              <option value="Intermediate">Intermediate</option>
              <option value="Severe">Severe</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="witnesses" class="form-label">Witnesses</label>
            <textarea class="form-control" name="witnesses"></textarea>
          </div>
          <div class="mb-3">
            <label for="investigation_notes" class="form-label">Investigation Notes</label>
            <textarea class="form-control" name="investigation_notes"></textarea>
          </div>
          <div class="mb-3">
            <label for="evidence" class="form-label">Evidence</label>
            <textarea class="form-control" name="evidence"></textarea>
          </div>
          <div class="mb-3">
            <label for="filed_date" class="form-label">Filed Date</label>
            <input type="date" class="form-control" name="filed_date" required>
          </div>
          <div class="mb-3">
            <label for="filed_time" class="form-label">Filed Time</label>
            <input type="time" class="form-control" name="filed_time" required>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="Pending">Pending</option>
              <option value="Under Investigation">Under Investigation</option>
              <option value="Resolved">Resolved</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="action_taken" class="form-label">Action Taken</label>
            <input type="text" class="form-control" name="action_taken">
          </div>
          <div class="mb-3">
            <label for="resolution_notes" class="form-label">Resolution Notes</label>
            <textarea class="form-control" name="resolution_notes"></textarea>
          </div>
          <div class="mb-3">
            <label for="resolved_date" class="form-label">Resolved Date</label>
            <input type="date" class="form-control" name="resolved_date">
          </div>
          <div class="mb-3">
            <label for="follow_up_date" class="form-label">Follow Up Date</label>
            <input type="date" class="form-control" name="follow_up_date">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add Case</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- View Case Modals -->
@isset($cases)
    @foreach($cases as $case)
        <div class="modal fade" id="viewCaseModal{{ $case->case_id }}" tabindex="-1" aria-labelledby="viewCaseModalLabel{{ $case->case_id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="viewCaseModalLabel{{ $case->case_id }}">Case Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <dl class="row">
                  <dt class="col-sm-4">Case ID</dt>
                  <dd class="col-sm-8">{{ $case->case_id }}</dd>
                  <dt class="col-sm-4">Type</dt>
                  <dd class="col-sm-8">{{ $case->caseType->type_name ?? 'N/A' }}</dd>
                  <dt class="col-sm-4">Presenting Problem</dt>
                  <dd class="col-sm-8">{{ $case->presenting_problem }}</dd>
                  <dt class="col-sm-4">Description</dt>
                  <dd class="col-sm-8">{{ $case->description }}</dd>
                  <dt class="col-sm-4">Severity</dt>
                  <dd class="col-sm-8">{{ $case->severity }}</dd>
                  <dt class="col-sm-4">Witnesses</dt>
                  <dd class="col-sm-8">{{ $case->witnesses }}</dd>
                  <dt class="col-sm-4">Investigation Notes</dt>
                  <dd class="col-sm-8">{{ $case->investigation_notes }}</dd>
                  <dt class="col-sm-4">Evidence</dt>
                  <dd class="col-sm-8">{{ $case->evidence }}</dd>
                  <dt class="col-sm-4">Filed Date</dt>
                  <dd class="col-sm-8">{{ $case->filed_date }}</dd>
                  <dt class="col-sm-4">Filed Time</dt>
                  <dd class="col-sm-8">{{ $case->filed_time }}</dd>
                  <dt class="col-sm-4">Status</dt>
                  <dd class="col-sm-8">{{ $case->status }}</dd>
                  <dt class="col-sm-4">Action Taken</dt>
                  <dd class="col-sm-8">{{ $case->action_taken }}</dd>
                  <dt class="col-sm-4">Resolution Notes</dt>
                  <dd class="col-sm-8">{{ $case->resolution_notes }}</dd>
                  <dt class="col-sm-4">Resolved Date</dt>
                  <dd class="col-sm-8">{{ $case->resolved_date }}</dd>
                  <dt class="col-sm-4">Follow Up Date</dt>
                  <dd class="col-sm-8">{{ $case->follow_up_date }}</dd>
                </dl>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Case Modal -->
        <div class="modal fade" id="editCaseModal{{ $case->case_id }}" tabindex="-1" aria-labelledby="editCaseModalLabel{{ $case->case_id }}" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" action="{{ route('Head.cases.update', $case->case_id) }}">
              @csrf
              @method('PUT')
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editCaseModalLabel{{ $case->case_id }}">Edit Case</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <!-- Repeat all fields, pre-filled with $case data -->
                  <div class="mb-3">
                    <label for="case_type_id" class="form-label">Type</label>
                    <select class="form-select" name="case_type_id" id="edit_case_type_id{{ $case->case_id }}" required onchange="toggleOtherTypeEdit({{ $case->case_id }})">
                      @foreach(\App\Models\CaseType::all() as $type)
                        <option value="{{ $type->type_id }}" {{ $case->case_type_id == $type->type_id ? 'selected' : '' }}>
                          {{ $type->type_name }}
                        </option>
                      @endforeach
                      <option value="other">Other</option>
                    </select>
                    <input type="text" class="form-control mt-2"
                           name="other_case_type"
                           id="edit_other_case_type{{ $case->case_id }}"
                           placeholder="Enter new case type"
                           style="display:none;">
                  </div>
                  <div class="mb-3">
                    <label for="presenting_problem" class="form-label">Presenting Problem</label>
                    <input type="text" class="form-control" name="presenting_problem" value="{{ $case->presenting_problem }}" required>
                  </div>
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" required>{{ $case->description }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label for="severity" class="form-label">Severity</label>
                    <select class="form-select" name="severity" required>
                      <option value="Low" {{ $case->severity == 'Low' ? 'selected' : '' }}>Low</option>
                      <option value="Intermediate" {{ $case->severity == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                      <option value="Severe" {{ $case->severity == 'Severe' ? 'selected' : '' }}>Severe</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="witnesses" class="form-label">Witnesses</label>
                    <textarea class="form-control" name="witnesses">{{ $case->witnesses }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label for="investigation_notes" class="form-label">Investigation Notes</label>
                    <textarea class="form-control" name="investigation_notes">{{ $case->investigation_notes }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label for="evidence" class="form-label">Evidence</label>
                    <textarea class="form-control" name="evidence">{{ $case->evidence }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label for="filed_date" class="form-label">Filed Date</label>
                    <input type="date" class="form-control" name="filed_date" value="{{ $case->filed_date }}" required>
                  </div>
                  <div class="mb-3">
                    <label for="filed_time" class="form-label">Filed Time</label>
                    <input type="time" class="form-control" name="filed_time" value="{{ $case->filed_time }}" required>
                  </div>
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                      <option value="Pending" {{ $case->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                      <option value="Under Investigation" {{ $case->status == 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                      <option value="Resolved" {{ $case->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="action_taken" class="form-label">Action Taken</label>
                    <input type="text" class="form-control" name="action_taken" value="{{ $case->action_taken }}">
                  </div>
                  <div class="mb-3">
                    <label for="resolution_notes" class="form-label">Resolution Notes</label>
                    <textarea class="form-control" name="resolution_notes">{{ $case->resolution_notes }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label for="resolved_date" class="form-label">Resolved Date</label>
                    <input type="date" class="form-control" name="resolved_date" value="{{ $case->resolved_date }}">
                  </div>
                  <div class="mb-3">
                    <label for="follow_up_date" class="form-label">Follow Up Date</label>
                    <input type="date" class="form-control" name="follow_up_date" value="{{ $case->follow_up_date }}">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
              </div>
            </form>
          </div>
        </div>
    @endforeach
@endisset

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