
    <!-- Add/Edit Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content add-modal-content pro-add-modal">
            <span class="close add-modal-close pro-add-close" id="closeAddModalBtn">&times;</span>
            <h2 id="addModalTitle" class="add-modal-title pro-add-title">Add Student</h2>

            <form id="addStudentForm" method="POST" action="{{ url('Head/students') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row image-name-row">
                    <div class="image-col">
                        <div style="position: relative; display: inline-block;">
                            <img id="studentImage" src="{{ asset('images/user/default.jpg') }}" data-default="{{ asset('images/user/default.jpg') }}" alt="Student Image" class="student-image-box pro-add-image">
                            <button type="button" id="deleteProfileImageBtn" title="Delete Profile Image" style="position: absolute; top: 8px; right: 8px; background: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.12); cursor: pointer;">
                                <i class="fi fi-rr-trash" style="color: #e53e3e; font-size: 1.2rem;"></i>
                            </button>
                        </div>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="pro-add-image-input">
                        <input type="hidden" id="delete_profile_image" name="delete_profile_image" value="0">
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
                                <label for="sex" class="add-label">Sex:</label>
                                <select id="sex" name="sex" class="add-input" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
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
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="program" class="add-label">Program:</label>
                        <input type="text" id="program" name="program" class="add-input" disabled>
                    </div>
                    <div class="add-field-col">
                        <label for="year_level" class="add-label">Year Level:</label>
                        <select id="year_level" name="year_level" class="add-input" required>
                            <option value="">Select Year Level</option>
                        </select>
                    </div>
                    <div class="add-field-col">
                        <label for="section" class="add-label">Section:</label>
                        <input type="text" id="section" name="section" class="add-input">
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
            <h2 id="viewModalTitle" class="add-modal-title pro-add-title">View Student</h2>
            <div class="form-row image-name-row">
                <div class="image-col">
                    <img id="viewStudentImage" src="{{ asset('images/user/default.jpg') }}" data-default="{{ asset('images/user/default.jpg') }}" alt="Student Image" class="student-image-box pro-add-image">
                    <div class="student-id-row pro-add-id-row">
                        <label class="add-label" style="margin-bottom:0;">Student ID:</label>
                        <span id="view_s_id_display" class="pro-add-id-value">Loading...</span>
                    </div>
                </div>
                <div class="name-fields-col">
                    <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                        <div class="add-field-col">
                            <label class="add-label">First Name:</label>
                            <span id="view_first_name" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Middle Name:</label>
                            <span id="view_middle_name" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Last Name:</label>
                            <span id="view_last_name" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Suffix:</label>
                            <span id="view_suffix" class="view-field"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Email:</label>
                            <span id="view_email" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Contact Number:</label>
                            <span id="view_contact_num" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Sex:</label>
                            <span id="view_sex" class="view-field"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Birthdate:</label>
                            <span id="view_bod" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Address:</label>
                            <span id="view_address" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Religion:</label>
                            <span id="view_religion" class="view-field"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Civil Status:</label>
                            <span id="view_civil_status" class="view-field"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Educational Level:</label>
                            <span id="view_educ_level" class="view-field"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Program:</label>
                    <span id="view_program" class="view-field"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Year Level:</label>
                    <span id="view_year_level" class="view-field"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Section:</label>
                    <span id="view_section" class="view-field"></span>
                </div>
            </div>
            <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Father Name</label>
                    <span id="view_father_name" class="view-field"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Mother Name</label>
                    <span id="view_mother_name" class="view-field"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Guardian Name</label>
                    <span id="view_guardian_name" class="view-field"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Relationship</label>
                    <span id="view_relationship" class="view-field"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Guardian Contact</label>
                    <span id="view_guardian_contact" class="view-field"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Guardian Email</label>
                    <span id="view_guardian_email" class="view-field"></span>
                </div>
            </div>
        </div>
    </div>


<!---------------------------------------------------------------------------------------->

<!-- Archive Modal -->
<div id="archiveStudentModal" class="modal" style="display:none;">
    <div class="modal-content add-modal-content pro-add-modal" style="max-width:400px;">
        <span class="close add-modal-close pro-add-close" id="closeArchiveModalBtn">&times;</span>
        <h2 class="add-modal-title pro-add-title">Archive Student</h2>
        <p style="font-size:1.08rem; margin-bottom:24px;">Are you sure you want to archive this student?</p>
        <div class="pro-add-buttons" style="justify-content:center;">
            <button type="button" class="pro-add-save" id="confirmArchiveBtn">Confirm</button>
            <button type="button" class="pro-add-save" style="background:#e11d48;" onclick="closeArchiveModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Archive Disable Modal -->
<div id="archiveDisableModal" class="modal" style="display:none;">
    <div class="modal-content add-modal-content pro-add-modal" style="max-width:420px;">
        <span class="close add-modal-close pro-add-close" id="closeArchiveDisableModalBtn">&times;</span>
        <h2 class="add-modal-title pro-add-title">Archive Student</h2>
        <p style="font-size:1.08rem; margin-bottom:24px;">Do you also want to disable this student’s login account?</p>
        <div class="pro-add-buttons" style="justify-content:center;">
            <button type="button" class="pro-add-save" id="archiveOnlyBtn" onclick="archiveStudentOnly()">Archive Only</button>
            <button type="button" class="pro-add-save" style="background:#e11d48;" id="archiveAndDisableBtn" onclick="archiveStudentAndDisable()">Archive and Disable</button>
            <button type="button" class="pro-add-save" style="background:#64748b;" onclick="closeArchiveDisableModal()">Cancel</button>
        </div>
    </div>
</div>


<!---------------------------------------------------------------------------------------->

<!-- Import Modal -->
<div id="importModal" class="modal" style="display:none;">
    <div class="modal-content add-modal-content pro-add-modal">
        <span class="close add-modal-close pro-add-close" id="closeImportModalBtn">&times;</span>
        <h2 class="add-modal-title pro-add-title">Import Students</h2>
            <form action="{{ route('Head.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="add-field-col" style="flex:2;">
                        <label for="students_file" class="add-label">Select CSV/Excel File:</label>
                        <input type="file" name="students_file" id="students_file" accept=".csv,.xlsx,.xls" required style="margin-top:8px;">
                    </div>
                </div>
                <div class="pro-add-buttons">
                    <button type="submit" class="pro-add-save">Import</button>
                </div>
            </form>
    </div>
</div>


<!---------------------------------------------------------------------------------------->

