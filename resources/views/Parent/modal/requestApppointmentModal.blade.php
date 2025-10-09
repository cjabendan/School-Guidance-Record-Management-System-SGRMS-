
<div id="requestAppointmentModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
  <form method="POST" action="{{ route('Head.appointments.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Request Appointment</h5>
        <button type="button" class="close-modal-btn" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body">
    @if($errors->has('appointment_datetime'))
    <div class="alert alert-danger" style="margin-bottom:16px; border-radius:6px; border:1px solid #fca5a5; background:#fff0f0; color:#b91c1c; font-weight:600; font-size:1.05em; padding:10px 16px; display:flex; align-items:center; gap:8px;">
      <i class="fi fi-rr-exclamation" style="color:#b91c1c; font-size:1.3em;"></i>
      <span>{{ $errors->first('appointment_datetime') }}</span>
    </div>
    @endif
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
          </select>
        </div>
        <!-- Child -->
        <div class="mb-3">
          <label for="student_id" class="form-label">Child</label>
          <select name="student_id[]" id="student_id" class="form-control" multiple required>
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
        });
        </script>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Request</button>
      </div>
    </form>
  </div>
</div>