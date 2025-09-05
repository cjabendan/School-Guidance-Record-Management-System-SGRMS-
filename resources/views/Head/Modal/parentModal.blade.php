<!-- parentModal.blade.php -->
<div class="modal fade" id="addParentModal" tabindex="-1" aria-labelledby="addParentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
    <span class="close add-modal-close pro-add-close" id="closeAddParentModalBtn" style="font-size:2rem;cursor:pointer;position:absolute;top:18px;right:24px;z-index:10;">&times;</span>
    <h5 class="modal-title" id="addParentModalLabel">Add Parent</h5>
      </div>
      <form id="addParentForm">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="first_name" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middle_name">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="last_name" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="sex" class="form-label">Sex</label>
                <select class="form-control" id="sex" name="sex" required>
                  <option value="">Select Sex</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="contactNum" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contactNum" name="contact_num" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
              </div>
            </div>
          </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Parent</button>
        </div>
      </form>
    </div>
  </div>
</div>
