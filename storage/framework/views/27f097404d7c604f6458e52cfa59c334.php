
    <!-- Add/Edit Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content add-modal-content pro-add-modal">
            <span class="close add-modal-close pro-add-close" id="closeAddModalBtn">&times;</span>
            <h2 id="addModalTitle" class="add-modal-title pro-add-title">Add Student</h2>

            <form id="addStudentForm" method="POST" action="<?php echo e(url('Head/students')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-row image-name-row">
                    <div class="image-col">
                        <div class="form-group">
                            <div class="student-image-wrapper">
                                <img id="studentImage" src="" alt="Image Preview" class="student-image-box">
                                <button type="button" id="remove-image-btn" class="delete-profile-image-btn" style="display:none;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <label class="custom-upload-btn" for="profile_image" style="margin-top:10px;">
                                <i class="fas fa-image"></i> Choose Image
                            </label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display:none;">
                            <span id="file-chosen" class="file-chosen-text">No file chosen</span>
                        </div>
                        <div class="student-id-row pro-add-id-row">
                            <label for="s_id_display" class="add-label" style="margin-bottom:0;">Student ID:</label>
                            <span id="s_id_display" class="pro-add-id-value">Loading...</span>
                        </div>
                        <input type="hidden" id="s_id" name="s_id">
                    </div>
                    <div class="name-fields-col">
                        <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                            <div class="add-field-col">
                                <label for="first_name" class="add-label">First Name:</label>
                                <input type="text" id="first_name" name="first_name" class="add-input" required>
                            </div>
                            <div class="add-field-col">
                                <label for="middle_name" class="add-label">Middle Name:</label>
                                <input type="text" id="middle_name" name="middle_name" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="last_name" class="add-label">Last Name:</label>
                                <input type="text" id="last_name" name="last_name" class="add-input" required>
                            </div>
                            <div class="add-field-col">
                                <label for="suffix" class="add-label">Suffix:</label>
                                <input type="text" id="suffix" name="suffix" class="add-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="email" class="add-label">Email:</label>
                                <input type="email" id="email" name="email" class="add-input" required>
                            </div>
                            <div class="add-field-col">
                                <label for="contact_num" class="add-label">Contact Number:</label>
                                <input type="text" id="contact_num" name="contact_num" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Sex:</label>
                                <div style="display: flex; gap: 16px; align-items: center;">
                                    <label style="margin-bottom:0;">
                                        <input type="radio" id="sex_male" name="sex" value="Male" required>
                                        Male
                                    </label>
                                    <label style="margin-bottom:0;">
                                        <input type="radio" id="sex_female" name="sex" value="Female" required>
                                        Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="bod" class="add-label">Birthdate:</label>
                                <input type="date" id="bod" name="bod" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="address" class="add-label">Address:</label>
                                <input type="text" id="address" name="address" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="religion" class="add-label">Religion:</label>
                                <input type="text" id="religion" name="religion" class="add-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="civil_status" class="add-label">Civil Status:</label>
                                <input type="text" id="civil_status" name="civil_status" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="educ_level" class="add-label">Educational Level:</label>
                                <select id="educ_level" name="educ_level" class="add-input">
                                    <option value="">Select Level</option>
                                    <option value="Kindergarten">Kindergarten</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="Junior High School">Junior High School</option>
                                    <option value="Senior High School">Senior High School</option>
                                </select>
                            </div>
                            <div class="add-field-col">
                                <label for="year_level" class="add-label">Year Level:</label>
                                <select id="year_level" name="year_level" class="add-input" required>
                                    <option value="">Select Year Level</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="add-field-col" style="width:100%; text-align:left;">
                        <p style="color:#2563eb; font-size:0.95rem; font-weight:500; margin:6px 0; background:#f0f7ff; padding:6px 10px; border-radius:6px;">
                            Note: Student will be automatically assigned to the current school year (e.g., <?php echo e(date('Y')); ?>–<?php echo e(date('Y', strtotime('+1 year'))); ?>).
                        </p>
                    </div>
                </div>


                <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                    <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                    <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
                </div>
                <!-- Row: Father Name, Mother Name -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="father_name">Father Name</label>
                        <input type="text" name="father_name" id="father_name" class="add-input" maxlength="255">
                    </div>
                    <div class="add-field-col">
                        <label for="mother_name">Mother Name</label>
                        <input type="text" name="mother_name" id="mother_name" class="add-input" maxlength="255">
                    </div>
                </div>

                <!-- Row: Guardian Name, Relationship -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="guardian_name">Guardian Name</label>
                        <input type="text" name="guardian_name" id="guardian_name" class="add-input" maxlength="255">
                    </div>
                    <div class="add-field-col">
                        <label for="relationship">Relationship</label>
                        <input type="text" name="relationship" id="relationship" class="add-input" maxlength="255">
                    </div>
                </div>

                <!-- Row: Guardian Contact, Guardian Email -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="guardian_contact">Guardian Contact</label>
                        <input type="text" name="guardian_contact" id="guardian_contact" class="add-input" maxlength="255">
                    </div>
                    <div class="add-field-col">
                        <label for="guardian_email">Guardian Email</label>
                        <input type="email" name="guardian_email" id="guardian_email" class="add-input" maxlength="255">
                    </div>
                </div>

                <div class="pro-add-buttons">
                    <button type="submit" id="addEditSaveBtn" class="pro-add-save">Save</button>
                </div>
            </form>
        </div>
    </div>

<!---------------------------------------------------------------------------------------->

    <!-- View Student Modal -->
    <div id="viewStudentModal" class="modal">
        <div class="modal-content add-modal-content pro-add-modal">
            <span class="close add-modal-close pro-add-close" id="closeViewModalBtn">&times;</span>
            <h2 class="add-modal-title pro-add-title">Student Information</h2>
            <form id="editStudentForm" method="POST" action="#" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-row image-name-row">
                    <div class="image-col">
                        <img id="viewStudentImage" src="<?php echo e(asset('images/user/default.jpg')); ?>" data-default="<?php echo e(asset('images/user/default.jpg')); ?>" alt="Student Image" class="student-image-box pro-add-image">
                        <div class="student-id-row pro-add-id-row">
                            <label class="add-label" style="margin-bottom:0;">Student ID:</label>
                            <input type="text" id="view_s_id_display" name="s_id" class="add-input" readonly disabled>
                        </div>
                    </div>
                    <div class="name-fields-col">
                        <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                            <div class="add-field-col">
                                <label class="add-label">First Name:</label>
                                <input type="text" id="view_first_name" name="first_name" class="add-input" required readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Middle Name:</label>
                                <input type="text" id="view_middle_name" name="middle_name" class="add-input" readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Last Name:</label>
                                <input type="text" id="view_last_name" name="last_name" class="add-input" required readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Suffix:</label>
                                <input type="text" id="view_suffix" name="suffix" class="add-input" readonly disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Email:</label>
                                <input type="email" id="view_email" name="email" class="add-input" required readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Contact Number:</label>
                                <input type="text" id="view_contact_num" name="contact_num" class="add-input" readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Sex:</label>
                                <div style="display: flex; gap: 16px; align-items: center;">
                                    <label style="margin-bottom:0;">
                                        <input type="radio" id="view_sex_male" name="view_sex" value="Male" disabled readonly>
                                        Male
                                    </label>
                                    <label style="margin-bottom:0;">
                                        <input type="radio" id="view_sex_female" name="view_sex" value="Female" disabled readonly>
                                        Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Birthdate:</label>
                                <input type="date" id="view_bod" name="bod" class="add-input" readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Address:</label>
                                <input type="text" id="view_address" name="address" class="add-input" readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Religion:</label>
                                <input type="text" id="view_religion" name="religion" class="add-input" readonly disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label class="add-label">Civil Status:</label>
                                <input type="text" id="view_civil_status" name="civil_status" class="add-input" readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Educational Level:</label>
                                <input type="text" id="view_educ_level" name="educ_level" class="add-input" readonly disabled>
                            </div>
                            <div class="add-field-col">
                                <label class="add-label">Year Level:</label>
                                <input type="text" id="view_year_level" name="year_level" class="add-input" readonly disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                    <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                    <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label class="add-label">Father Name</label>
                        <input type="text" id="view_father_name" name="father_name" class="add-input" maxlength="255" readonly disabled>
                    </div>
                    <div class="add-field-col">
                        <label class="add-label">Mother Name</label>
                        <input type="text" id="view_mother_name" name="mother_name" class="add-input" maxlength="255" readonly disabled>
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label class="add-label">Guardian Name</label>
                        <input type="text" id="view_guardian_name" name="guardian_name" class="add-input" maxlength="255" readonly disabled>
                    </div>
                    <div class="add-field-col">
                        <label class="add-label">Relationship</label>
                        <input type="text" id="view_relationship" name="relationship" class="add-input" maxlength="255" readonly disabled>
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label class="add-label">Guardian Contact</label>
                        <input type="text" id="view_guardian_contact" name="guardian_contact" class="add-input" maxlength="255" readonly disabled>
                    </div>
                    <div class="add-field-col">
                        <label class="add-label">Guardian Email</label>
                        <input type="email" id="view_guardian_email" name="guardian_email" class="add-input" maxlength="255" readonly disabled>
                    </div>
                </div>

            <h3 style="color:#e11d48; margin-bottom:12px;"><i class="fi fi-rr-folder"></i> Case Records</h3>
            <div id="view_case_records"></div>
            <div id="case_record_template" style="display:none;">
                <div class="case-record-row">
                    <div class="case-record-col">
                        <label class="case-record-label">Case Title:</label>
                        <span class="case-title case-record-value"></span>
                    </div>
                    <div class="case-record-col">
                        <label class="case-record-label">Severity:</label>
                        <span class="case-severity case-record-value"></span>
                    </div>
                </div>
                <div class="case-record-row">
                    <div class="case-record-col">
                        <label class="case-record-label">Date:</label>
                        <span class="case-date case-record-value"></span>
                    </div>
                    <div class="case-record-col">
                        <label class="case-record-label">Status:</label>
                        <span class="case-status case-record-value"></span>
                    </div>
                </div>
                <div class="case-record-row">
                    <div class="case-record-col" style="width:100%;">
                        <label class="case-record-label">Description:</label>
                        <span class="case-description case-record-description"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!---------------------------------------------------------------------------------------->

<!-- Archive Modal -->
<div id="archiveStudentModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:480px;">
        <span class="close add-modal-close pro-add-close" id="closeArchiveModalBtn">&times;</span>
    <h2 class="add-modal-title pro-add-title">Archive Student <span id="archiveStudentIdDisplay" style="color:#2563eb;font-size:1.1rem;font-weight:600;"></span></h2>

        <form id="archiveStudentForm" method="POST" action="<?php echo e(url('/students/archive')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="s_id" id="archive_s_id">
            <p style="font-size:1.05rem; margin-bottom:18px;">
                Select the archive status for this student. You may also disable their login if needed.
            </p>

            <!-- Archive Status -->
            <div class="form-row" style="margin-bottom:18px;">
                <div class="add-field-col" style="width:100%;">
                    <label for="archive_status" class="add-label">Archive Status:</label>
                    <select id="archive_status" name="status" class="add-input" required>
                        <option value="">Select Status</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                        <option value="Graduated">Graduated</option>
                        <option value="Transferred">Transferred</option>
                        <option value="Dropped">Dropped</option>
                        <option value="Suspended">Suspended</option>
                        <option value="Expelled">Expelled</option>
                    </select>
                    <small id="statusMeaning" style="display:block; margin-top:4px; color:#64748b; font-size:0.9rem;">
                        Select a status to see its meaning.
                    </small>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pro-add-buttons" style="justify-content:center; gap: 16px;">
                <button type="button" class="pro-add-save" onclick="archiveStudentOnly()">Archive Only</button>
                <button type="button" class="pro-add-save" style="background:#e11d48;" onclick="archiveStudentAndDisable()">Archive & Disable Account</button>
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
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Head/Modal/studentModal.blade.php ENDPATH**/ ?>