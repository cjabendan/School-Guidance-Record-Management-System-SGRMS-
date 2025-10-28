<!-- Add/Edit/View User Modal -->
<div id="userModal" class="modal-user" style="display:none">
    <div class="modal-content-user">

        <!-- Modal Header -->
        <div class="modal-header-user">
          <div class="modal-icon-wrapper">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="modal-icon">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
          </div>
            <h2 id="userModalTitle">Add new systems user</h2>
            <span class="close-btn" onclick="closeUserModal()">&times;</span>
        </div>

        <!-- Modal Body -->
        <form id="userForm" enctype="multipart/form-data">
            <input type="hidden" name="_method" id="userFormMethod" value="POST">
            <input type="hidden" name="user_id" id="user_id">

            <!-- User fields -->
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="first_name" required>
                    <span class="error-msg" id="firstNameError"></span>
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name</label>
                    <input type="text" id="middleName" name="middle_name">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="last_name" required>
                    <span class="error-msg" id="lastNameError"></span>
                </div>
                <div class="form-group">
                    <label for="sex">Sex</label>
                    <div class="select-wrapper">
                        <select id="sex" name="sex" required>
                            <option value="" disabled selected>Select Sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <span class="error-msg" id="sexError"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contactNum">Contact Number</label>
                    <input type="text" id="contactNum" name="contact_num">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error-msg" id="emailError"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role">Role</label>
                    <div class="select-wrapper">
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="counselor">Counselor</option>
                            <option value="parent">Parent</option>
                        </select>
                        <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <div class="select-wrapper">
                        <select id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <span class="select-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>

            <!-- Password / Reset -->
            <div class="form-row">
                <div id="passwordFields" class="form-group password-wrapper">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password">
                        <span class="toggle-password" onclick="togglePassword('password')"></span>
                        <span class="error-msg" id="passwordError"></span>
                    </div>


                </div>

                <div id="confirmPasswordFields" class="form-group password-wrapper">
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirm_password"
                            placeholder="Confirm password">
                        <span class="toggle-password" onclick="togglePassword('confirmPassword')"></span>
                        <span class="error-msg" id="confirmPasswordError"></span>
                    </div>
                </div>

                <div id="resetPasswordWrapper" class="form-group password-wrapper" style="display:none;">
                    <button type="button" class="btn reset-password-btn" onclick="resetPassword()">Reset
                        Password</button>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer-user">
                <button type="button" class="btn cancel" onclick="closeUserModal()">Cancel</button>
                <button type="submit" class="btn save" id="userSaveBtn">Save</button>
            </div>
        </form>
    </div>
</div>
