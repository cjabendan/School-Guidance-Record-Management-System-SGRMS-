
@php
  $modalDisplay = (old('form_origin') === 'create_appointment') ? 'flex' : 'none';
@endphp

<div id="requestAppointmentModal" class="custom-modal" style="display:{{ $modalDisplay }};">
  <div class="custom-modal-content">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    /* Make selected items in Select2 render in a vertical column inside the modal */
    #requestAppointmentModal .select2-container--default .select2-selection--multiple {
      display: flex;
      flex-direction: column;
      max-height: 150px;
    }
    #requestAppointmentModal .select2-container--default .select2-selection__choice {
      display: block;
      width: 100%;
      border-radius: 6px;
    }
    /* Keep the search input usable and full width */
    #requestAppointmentModal .select2-container--default .select2-search--inline {
      display: block;
      width: 100%;
    }
  </style>
  <form method="POST" action="{{ route('Parent.appointments.store') }}">
    @csrf
    <input type="hidden" name="form_origin" value="create_appointment">
      <div class="modal-header">
        <h5 class="modal-title">Request Appointment</h5>
        <button type="button" class="close-modal-btn" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body">
    <!-- Removed inline appointment_datetime validation message per request (handled by error modal) -->
        <!-- Counselor -->
        <div class="mb-3">
          <label for="counselor_id" class="form-label">Choose Counselor</label>
          <select name="counselor_id" id="counselor_id" class="form-control" required>
            <option value="">Select Counselor</option>
            @foreach($counselors as $counselor)
              <option value="{{ $counselor->id }}">{{ $counselor->first_name }} {{ $counselor->last_name }}</option>
            @endforeach
          </select>
        </div>
        <!-- Appointment Type -->
        <div class="mb-3">
          <label for="type_id" class="form-label">Appointment Type</label>
          <select name="type_id" id="type_id" class="form-control" required>
            <option value="">Select Type</option>
            @foreach($types as $type)
              <option value="{{ $type->id }}">{{ $type->type_name }}</option>
            @endforeach
            <option value="other">Other</option>
          </select>
        </div>
        <div class="divider"></div>
        <div class="mb-3" id="other-type-wrapper" style="display:none;">
          <label for="other_type" class="form-label">Other Type</label>
          <input type="text" name="other_type" id="other_type" class="form-control" placeholder="Enter custom appointment type">
        </div>
        <!-- Child -->
        <div class="mb-3">
          <label for="student_id" class="form-label">Child</label>
          <select name="student_id[]" id="student_id" class="form-control" multiple required style="width:100%;">
            @foreach($children as $child)
                <option value="{{ $child->s_id }}">
                    {{ $child->user->first_name ?? '' }} {{ $child->user->last_name ?? '' }}
                </option>
            @endforeach
          </select>
        </div>
        <!-- Reason -->
        <div class="mb-3">
          <label for="reason" class="form-label">Reason</label>
          <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>
        <!-- Date & Time -->
        <div class="mb-3">
          <label for="appointment_datetime" class="form-label">Date & Time</label>
          <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" class="form-control" required>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Always use Asia/Manila timezone for input
          function setManilaNow() {
            const now = new Date();
            // Convert to Asia/Manila
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const manilaOffset = 8 * 60; // UTC+8
            const manila = new Date(utc + (manilaOffset * 60000));
            // Format for datetime-local
            const pad = n => n.toString().padStart(2, '0');
            const value = `${manila.getFullYear()}-${pad(manila.getMonth()+1)}-${pad(manila.getDate())}T${pad(manila.getHours())}:${pad(manila.getMinutes())}`;
            document.getElementById('appointment_datetime').value = value;
          }
          setManilaNow();

          // Show/hide Other type input
          const typeSelect = document.getElementById('type_id');
          const otherWrapper = document.getElementById('other-type-wrapper');
          const otherInput = document.getElementById('other_type');
          if (typeSelect) {
            typeSelect.addEventListener('change', function() {
              if (this.value === 'other') {
                if (otherWrapper) otherWrapper.style.display = 'block';
                if (otherInput) otherInput.required = true;
              } else {
                if (otherWrapper) otherWrapper.style.display = 'none';
                if (otherInput) { otherInput.required = false; otherInput.value = ''; }
              }
            });
          }
          // Initialize Select2 for student search (dropdownParent ensures it renders inside modal)
          // Load jQuery and Select2 if not already present on the page
          (function initSelect2() {
            function doInit() {
              if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                return;
              }
              $('#student_id').select2({
                placeholder: 'Search by name or ID',
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('#requestAppointmentModal'),
                minimumInputLength: 1,
                // Only show results when parent types; match both option text and option value (ID)
                matcher: function(params, data) {
                  // If there is no search term, don't display any results
                  if ($.trim(params.term) === '') {
                    return null;
                  }
                  // Default matcher: check text
                  var term = params.term.toLowerCase();
                  if (data.text && data.text.toLowerCase().indexOf(term) > -1) {
                    return data;
                  }
                  // Also check the option value (ID)
                  var id = data.id ? data.id.toString().toLowerCase() : '';
                  if (id.indexOf(term) > -1) {
                    return data;
                  }
                  // No match
                  return null;
                }
              });
            }

            // If jQuery or select2 not loaded, inject scripts then init
            if (typeof $ === 'undefined') {
              var jq = document.createElement('script');
              jq.src = 'https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js';
              jq.onload = function() {
                var s2 = document.createElement('script');
                s2.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
                s2.onload = doInit;
                document.body.appendChild(s2);
              };
              document.body.appendChild(jq);
            } else if (typeof $.fn.select2 === 'undefined') {
              var s2 = document.createElement('script');
              s2.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
              s2.onload = doInit;
              document.body.appendChild(s2);
            } else {
              // both present
              doInit();
            }
          })();
        });
        </script>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Request</button>
      </div>
    </form>
  </div>

  <!-- Error-only modal (small) - shows when there are validation errors not originating from create form -->
  @php $showErrorModal = $errors->any() && old('form_origin') !== 'create_appointment'; @endphp
  <div id="appointmentErrorModal" class="custom-modal" style="display:{{ $showErrorModal ? 'flex' : 'none' }};">
    <div class="custom-modal-content" style="max-width:420px;">
      <div class="modal-header">
        <span class="modal-title"><i class="fi fi-rr-exclamation"></i> Error</span>
        <button type="button" class="close-modal-btn" onclick="closeErrorModal()">×</button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" style="margin-bottom:0; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600; font-size:1.05em; padding:12px 16px; display:flex; align-items:center; gap:8px;">
          <i class="fi fi-rr-exclamation" style="color:#b91c1c; font-size:1.3em;"></i>
          <div style="line-height:1.2;">{{ $errors->first() }}</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeErrorModal()">Close</button>
      </div>
    </div>
  </div>
  <script>
    function closeErrorModal() {
      document.getElementById('appointmentErrorModal').style.display = 'none';
    }
  </script>

</div>