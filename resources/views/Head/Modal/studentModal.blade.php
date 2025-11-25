
    <!-- Add/Edit Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content add-modal-content pro-add-modal">
            <div class="modal-header-student">
                <div class="header-left">
                    <i class="fi fi-sr-folder-open"></i>
                    <h2 id="addModalTitle" class="add-modal-title pro-add-title">Add Student</h2>
                </div>
                <span class="qr-close" id="closeAddModalBtn">&times;</span>
            </div>
            <form id="addStudentForm" method="POST" action="{{ url('Head/students') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="delete_profile_image" name="delete_photo" value="0">

                <!-- 🟦 Student Information Section -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="fas fa-user-graduate"></i> Student Information</h3>

                    <div class="form-row image-name-row">
                        <div class="image-col">
                            <div class="student-image-wrapper">
                                <img id="studentImage" src="" alt="Image Preview" class="student-image-box">
                                <button type="button" id="remove-image-btn" class="delete-profile-image-btn" style="display:none;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <label class="custom-upload-btn" for="profile_image">
                                <i class="fas fa-image"></i> Choose Image
                            </label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display:none;">
                            <span id="file-chosen" class="file-chosen-text">No file chosen</span>

                            <div class="student-id-row pro-add-id-row">
                                <label class="add-label">Student ID:</label>
                                <span id="s_id_display" class="pro-add-id-value">Loading...</span>
                            </div>
                            <input type="hidden" id="s_id" name="s_id">
                        </div>

                        <div class="name-fields-col">
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Middle Name:</label>
                                    <input type="text" id="middle_name" name="middle_name" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Suffix:</label>
                                    <input type="text" id="suffix" name="suffix" class="add-input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">Email:</label>
                                    <input type="email" id="email" name="email" class="add-input" required>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Contact Number:</label>
                                    <input type="text" id="contact_num" name="contact_num" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Sex:</label>
                                    <div class="sex-radio-group">
                                        <label><input type="radio" id="sex_male" name="sex" value="Male" required> Male</label>
                                        <label><input type="radio" id="sex_female" name="sex" value="Female" required> Female</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">Birthdate:</label>
                                    <input type="date" id="bod" name="bod" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Address:</label>
                                    <input type="text" id="address" name="address" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Religion:</label>
                                    <input type="text" id="religion" name="religion" class="add-input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">Civil Status:</label>
                                    <input type="text" id="civil_status" name="civil_status" class="add-input">
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Educational Level:</label>
                                    <select id="educ_level" name="educ_level" class="add-input">
                                        <option value="">Select Level</option>
                                        <option value="Kindergarten">Kindergarten</option>
                                        <option value="Elementary">Elementary</option>
                                        <option value="Junior High School">Junior High School</option>
                                        <option value="Senior High School">Senior High School</option>
                                    </select>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Year Level:</label>
                                    <select id="year_level" name="year_level" class="add-input" required>
                                        <option value="">Select Year Level</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p style="color:#003060; font-size:0.95rem; font-weight:500; margin:10px 0;">
                        Note: Student will be automatically assigned to the current school year (e.g., {{ date('Y') }}–{{ date('Y', strtotime('+1 year')) }}).
                    </p>
                </div>

                <!-- 🟩 Parent & Guardian Section -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="fas fa-users"></i> Parent & Guardian Information</h3>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="father_name">Father Name</label>
                            <input type="text" name="father_name" id="father_name" class="add-input">
                        </div>
                        <div class="add-field-col">
                            <label for="mother_name">Mother Name</label>
                            <input type="text" name="mother_name" id="mother_name" class="add-input">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="guardian_name">Guardian Name</label>
                            <input type="text" name="guardian_name" id="guardian_name" class="add-input">
                        </div>
                        <div class="add-field-col">
                            <label for="relationship">Relationship</label>
                            <input type="text" name="relationship" id="relationship" class="add-input">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label for="guardian_contact">Guardian Contact</label>
                            <input type="text" name="guardian_contact" id="guardian_contact" class="add-input">
                        </div>
                        <div class="add-field-col">
                            <label for="guardian_email">Guardian Email</label>
                            <input type="email" name="guardian_email" id="guardian_email" class="add-input">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pro-add-buttons">
                    <button type="submit" id="addEditSaveBtn" class="">Save</button>
                </div>
            </form>
        </div>
    </div>


<!---------------------------------------------------------------------------------------->

    <!-- View Student Modal -->
    <div id="viewStudentModal" class="modal">
        <div class="modal-content add-modal-content pro-add-modal">
            <div class="modal-header-student">
                <div class="header-left">
                    <i class="fi fi-sr-folder-open"></i>
                    <h2 class="add-modal-title pro-add-title">Student Records</h2>
                </div>
                <span class="qr-close" id="closeViewModalBtn">&times;</span>
            </div>  
            <form id="editStudentForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf

                <!-- 🟦 SECTION 1: STUDENT INFORMATION -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="fas fa-user-graduate"></i> Student Information</h3>

                    <div class="form-row image-name-row">
                        <div class="image-col">
                            <img id="viewStudentImage" src="{{ asset('images/user/default.jpg') }}"
                                data-default="{{ asset('images/user/default.jpg') }}"
                                alt="Student Image" class="student-image-box pro-add-image">

                            <div class="student-id-row pro-add-id-row">
                                <label class="add-label" style="margin-bottom:0;">Student ID:</label>
                                <span id="view_s_id_display" class="pro-add-id-value"></span>
                            </div>
                        </div>

                        <div class="name-fields-col">
                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">First Name:</label>
                                    <input type="text" id="view_first_name" name="first_name"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Middle Name:</label>
                                    <input type="text" id="view_middle_name" name="middle_name"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Last Name:</label>
                                    <input type="text" id="view_last_name" name="last_name"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Suffix:</label>
                                    <input type="text" id="view_suffix" name="suffix"
                                        class="add-input" readonly disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">Email:</label>
                                    <input type="email" id="view_email" name="email"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Contact Number:</label>
                                    <input type="text" id="view_contact_num" name="contact_num"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Sex:</label>
                                    <div class="sex-radio-group">
                                        <label style="margin-bottom:0;">
                                            <input type="radio" id="view_sex_male"
                                                name="view_sex" value="Male"
                                                disabled readonly> Male
                                        </label>
                                        <label style="margin-bottom:0;">
                                            <input type="radio" id="view_sex_female"
                                                name="view_sex" value="Female"
                                                disabled readonly> Female
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">Birthdate:</label>
                                    <input type="date" id="view_bod" name="bod"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Address:</label>
                                    <input type="text" id="view_address" name="address"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Religion:</label>
                                    <input type="text" id="view_religion" name="religion"
                                        class="add-input" readonly disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="add-field-col">
                                    <label class="add-label">Civil Status:</label>
                                    <input type="text" id="view_civil_status" name="civil_status"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Educational Level:</label>
                                    <input type="text" id="view_educ_level" name="educ_level"
                                        class="add-input" readonly disabled>
                                </div>
                                <div class="add-field-col">
                                    <label class="add-label">Year Level:</label>
                                    <input type="text" id="view_year_level" name="year_level"
                                        class="add-input" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 🟩 SECTION 2: PARENT & GUARDIAN INFORMATION -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="fas fa-users"></i> Parent & Guardian Information</h3>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Father Name:</label>
                            <input type="text" id="view_father_name" name="father_name"
                                class="add-input" readonly disabled>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Mother Name:</label>
                            <input type="text" id="view_mother_name" name="mother_name"
                                class="add-input" readonly disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Guardian Name:</label>
                            <input type="text" id="view_guardian_name" name="guardian_name"
                                class="add-input" readonly disabled>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Relationship:</label>
                            <input type="text" id="view_relationship" name="relationship"
                                class="add-input" readonly disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Guardian Contact:</label>
                            <input type="text" id="view_guardian_contact" name="guardian_contact"
                                class="add-input" readonly disabled>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Guardian Email:</label>
                            <input type="email" id="view_guardian_email" name="guardian_email"
                                class="add-input" readonly disabled>
                        </div>
                    </div>
                </div>

                <!-- 🟥 SECTION 3: CASE RECORDS -->
                <div class="case-records-section">
                    <h3 class="form-section-title"><i class="fas fa-file-alt"></i> Case Records & Offense Summary</h3>

                    <div class="case-records-content">
                        <!-- Left Side: Year Offense Summary -->
                        <div class="offense-summary">
                            <h6 class="section-subtitle"><i class="fas fa-chart-bar"></i> Year Offense</h6>
                            <div id="offenseSummaryContainer">
                                <div class="offense-year">
                                    <strong>Loading...</strong>
                                    <span class="offense-count"></span>
                                </div>
                            </div>
                            <div class="offense-total" id="offenseTotal">Total Offenses: 0</div>
                        </div>

                        <!-- Right Side: Case Records -->
                        <div class="current-records-container">
                            <h6 class="section-subtitle"><i class="fas fa-history"></i> Current Records</h6>
                            <div id="view_case_records" class="record-list"></div>
                            <a href="#" id="viewMoreCasesBtn" class="view-more-btn">View More...</a>
                        </div>
                    </div>
                </div>

                <!-- Hidden Template for Dynamic Case Record -->
                <div id="case_record_template" style="display:none;">
                    <div class="record-card">
                        <div class="record-header">
                            <strong class="case-title">Case Title</strong>
                            <!-- badge-text will hold only the severity value; CSS ::before prints the 'Severity:' label -->
                            <span class="badge bg-info case-severity"><span class="badge-text">Severity</span></span>
                        </div>
                        <div class="record-details">
                            <small class="case-date">Date:</small> |
                            <small class="case-status">Status:</small>
                        </div>
                        <p class="record-description">Description</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!------------------------------------s---------------------------------------------------->

<!-- Student Status Update Modal -->
<div id="archiveStudentModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close add-modal-close pro-add-close" id="closeArchiveModalBtn">&times;</span>

    <!-- Header Icon -->
    <div class="modal-icon">
      <i class="fi fi-sr-user-check"></i>
    </div>

    <!-- Title -->
    <h2 class="add-modal-title pro-add-title">
      Update Student Record
    </h2>

    <form id="archiveStudentForm" method="POST" action="{{ url('/students/archive') }}">
      @csrf
      <input type="hidden" name="s_id" id="archive_s_id">

      <!-- Description with student ID -->
      <p class="modal-description">
        Select the most accurate status for this student below.  
        You may also disable their account access if the record should no longer be active.
        <br><br>
        <span class="student-id-label"><i class="fi fi-sr-id-badge"></i> Student ID:</span>
        <span id="archiveStudentIdDisplay" class="student-id"></span>
      </p>

      <!-- Student Status -->
      <div class="form-row">
        <div class="add-field-col">
          <label for="archive_status" class="add-label">Student Status</label>
          <select id="archive_status" name="status" class="add-input" required>
            <option value="">Select Status</option>
            <option value="Enrolled">Enrolled</option>
            <option value="Incoming">Incoming</option>
            <option value="Probation">Probation</option>
            <option value="Suspended">Suspended</option>
            <option value="Dropped">Dropped</option>
            <option value="Transferred">Transferred</option>
            <option value="Graduated">Graduated</option>
            <option value="Deceased">Deceased</option>
            <option value="Expelled">Expelled</option>
          </select>
          <small id="statusMeaning">Select a status to view its description.</small>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="pro-add-buttons">
        <button type="button" class="pro-add-save" onclick="archiveStudentOnly()">Save Changes</button>
        <button type="button" class="pro-add-save danger" onclick="archiveStudentAndDisable()">Save & Disable Account</button>
      </div>
    </form>
  </div>
</div>



<!---------------------------------------------------------------------------------------->

<!-- Crop Image Modal -->
<div id="cropImageModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:600px; text-align:center;">
        <h3>Crop Profile Image</h3>
        <div style="max-height:400px; overflow:hidden;">
            <img id="cropperImage" style="max-width:100%;">
        </div>
        <div style="margin-top:16px;">
            <button type="button" id="cropApplyBtn" class="pro-add-save">Apply</button>
            <button type="button" id="closeCropModalBtn" class="pro-add-save">Cancel</button>
        </div>
    </div>
</div>

<!-- Hidden input for cropped image -->
<input type="hidden" id="cropped_image_data" name="cropped_image_data">
<!-- Original filename of the selected file (used when cropping) -->
<input type="hidden" id="cropped_image_name" name="cropped_image_name">
