<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
</head>

<body>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content add-modal-content pro-add-modal">
            <span class="close add-modal-close pro-add-close" onclick="closeAddModal()">&times;</span>
            <h2 id="addModalTitle" class="add-modal-title pro-add-title">Add Student</h2>
            <form id="addStudentForm" method="POST" action="{{ url('Head/students/add') }}"
                enctype="multipart/form-data">
                @csrf

                <!-- Row: Student ID and Image -->
                <div class="form-row image-name-row">
                    <div class="image-col">
                    <img id="studentImage" src="{{ asset('images/stud.img/circle-user.png') }}"
                        data-default="{{ asset('images/stud.img/circle-user.png') }}" alt="Student Image"
                        class="student-image-box pro-add-image">
                    <input type="file" id="image" name="image" accept="image/*" class="pro-add-image-input">
                    <div class="student-id-row pro-add-id-row">
                        <label for="id_num_display" class="add-label" style="margin-bottom:0;">Student ID:</label>
                        <span id="id_num_display" class="pro-add-id-value">Loading...</span>
                    </div>
                    <input type="hidden" id="id_num" name="id_num">
                    </div>
                    <div class="name-fields-col">
                        <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                            <div class="add-field-col">
                                <label for="lname" class="add-label">Last Name:</label>
                                <input type="text" id="lname" name="lname" placeholder="Enter last name" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="fname" class="add-label">First Name:</label>
                                <input type="text" id="fname" name="fname" placeholder="Enter first name" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="mname" class="add-label">Middle Name:</label>
                                <input type="text" id="mname" name="mname" placeholder="Enter middle name" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="suffix" class="add-label">Suffix:</label>
                                <input type="text" id="suffix" name="suffix" placeholder="e.g. Jr., Sr., III" class="add-input">
                            </div>
                        </div>

                        <!-- Row: Birthdate, Sex, Religion -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="bod" class="add-label">Birthdate:</label>
                                <input type="date" id="bod" name="bod" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="gender" class="add-label">Sex:</label>
                                <select id="gender" name="gender" class="add-input">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="add-field-col">
                                <label for="religion" class="add-label">Religion:</label>
                                <input type="text" id="religion" name="religion" placeholder="Enter religion" class="add-input">
                            </div>
                        </div>

                        <!-- Row: Civil Status, Address -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="civil_status" class="add-label">Civil Status:</label>
                                <input type="text" id="civil_status" name="civil_status" placeholder="e.g. Single, Married, etc." class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="address" class="add-label">Address:</label>
                                <input type="text" id="address" name="address" placeholder="Enter address" class="add-input">
                            </div>
                        </div>

                        <!-- Row: Mobile No., Email -->
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="mobile_num" class="add-label">Mobile No:</label>
                                <input type="text" id="mobile_num" name="mobile_num" placeholder="Enter mobile number" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="email" class="add-label">Email:</label>
                                <input type="email" id="email" name="email" placeholder="Enter email" class="add-input">
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Row: Educational Level, Year Level, Program, Section -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="educ_level" class="add-label">Educational Level:</label>
                        <input list="educ_levels" id="educ_level" name="educ_level"
                            placeholder="Enter or select educational level" autocomplete="off" class="add-input">
                        <datalist id="educ_levels">
                            <option value="Kindergarten">
                            <option value="Elementary">
                            <option value="High School">
                            <option value="Senior High School">
                            <option value="College">
                        </datalist>
                    </div>
                    <div class="add-field-col">
                        <label for="year_level" class="add-label">Year Level:</label>
                        <select id="year_level" name="year_level" class="add-input">
                            <option value="">Select Year Level</option>
                        </select>
                    </div>
                    <div id="programField" class="program-section-field add-field-col" style="display: none;">
                        <label for="program" class="add-label">Program:</label>
                        <input type="text" id="program" name="program" placeholder="Enter program name" class="add-input">
                    </div>
                    <div id="sectionField" class="program-section-field add-field-col">
                        <label for="section" class="add-label">Section:</label>
                        <input type="text" id="section" name="section" placeholder="Enter section name" class="add-input">
                    </div>
                </div>

                <!-- Row: Previous School and Address -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="previous_school" class="add-label">Previous School Attended:</label>
                        <input type="text" id="previous_school" name="previous_school"
                            placeholder="Enter previous school name" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="previous_school_address" class="add-label">Previous School Address:</label>
                        <input type="text" id="previous_school_address" name="previous_school_address"
                            placeholder="Enter previous school address" class="add-input">
                    </div>
                </div>

                
                <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                    <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                    <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
                </div>
                <!-- Row: Father Name, Mother Name -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="father_name" class="add-label">Father's Name:</label>
                        <input type="text" id="father_name" name="father_name" placeholder="Enter father's name" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="mother_name" class="add-label">Mother's Name:</label>
                        <input type="text" id="mother_name" name="mother_name" placeholder="Enter mother's name" class="add-input">
                    </div>
                </div>

                <!-- Row: Guardian Name, Relationship -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="guardian_name" class="add-label">Guardian Name:</label>
                        <input type="text" id="guardian_name" name="guardian_name" placeholder="Enter guardian's name" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="relationship" class="add-label">Relationship:</label>
                        <input type="text" id="relationship" name="relationship" placeholder="e.g. Mother, Father, Guardian" class="add-input">
                    </div>
                </div>

                <!-- Row: Guardian Contact, Guardian Email -->
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="guardian_contact" class="add-label">Guardian Contact:</label>
                        <input type="text" id="guardian_contact" name="guardian_contact" placeholder="Enter guardian's contact number" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="guardian_email" class="add-label">Guardian Email:</label>
                        <input type="email" id="guardian_email" name="guardian_email" placeholder="Enter guardian's email" class="add-input">
                    </div>
                </div>



                <div class="pro-add-buttons">
                    <button type="submit" class="pro-add-save">Save</button>
                </div>
            </form>

        </div>
    </div>

    <!-- View Student Modal -->
    <div id="viewStudentModal" class="modal" style="display:none;">
        <div class="modal-content add-modal-content pro-add-modal view-modal-content">
            <span class="close view-modal-close pro-add-close" onclick="closeViewStudentModal()">&times;</span>
            <h2 class="view-modal-title pro-add-title" style="font-size: 2.1rem; font-weight: 800; margin-bottom: 22px; letter-spacing: 1px;">Student Details</h2>
            
            <div class="form-row image-name-row view-image-row" style="gap: 32px; align-items: flex-start; margin-bottom: 18px;">
                <div class="image-col view-image-col">
                    <img id="view_studentImage" src="{{ asset('images/stud.img/circle-user.png') }}" alt="Student Image" class="student-image-box pro-add-image" />
                    <div class="student-id-row view-student-id-row pro-add-id-row">
                        <label class="add-label">ID NO:</label>
                        <span id="view_id_num_display" class="pro-add-id-value">Loading...</span>
                    </div>
                </div>
                <div class="name-fields-col view-name-fields-col">
                    <div class="form-row view-name-row">
                        <div class="view-field-col">
                            <label class="view-label name">Last Name:</label>
                            <span id="view_lname" class="view-value name"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label name">First Name:</label>
                            <span id="view_fname" class="view-value name"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label name">Middle Name:</label>
                            <span id="view_mname" class="view-value name"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label name">Suffix:</label>
                            <span id="view_suffix" class="view-value name"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Birthdate:</label>
                            <span id="view_bod" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Sex:</label>
                            <span id="view_gender" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Religion:</label>
                            <span id="view_religion" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Civil Status:</label>
                            <span id="view_civil_status" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Address:</label>
                            <span id="view_address" class="view-value"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Mobile No:</label>
                            <span id="view_mobile_num" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Email:</label>
                            <span id="view_email" class="view-value"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Educational Level:</label>
                            <span id="view_educ_level" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Year Level:</label>
                            <span id="view_year_level" class="view-value"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Program:</label>
                            <span id="view_program" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Section:</label>
                            <span id="view_section" class="view-value"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Previous School Attended:</label>
                            <span id="view_previous_school" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Previous School Address:</label>
                            <span id="view_previous_school_address" class="view-value"></span>
                        </div>
                    </div>
                    <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                        <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                        <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Father's Name:</label>
                            <span id="view_father_name" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Mother's Name:</label>
                            <span id="view_mother_name" class="view-value"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Guardian Name:</label>
                            <span id="view_guardian_name" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Relationship:</label>
                            <span id="view_relationship" class="view-value"></span>
                        </div>
                    </div>
                    <div class="form-row view-info-row" style="gap: 18px;">
                        <div class="view-field-col">
                            <label class="view-label">Guardian Contact:</label>
                            <span id="view_guardian_contact" class="view-value"></span>
                        </div>
                        <div class="view-field-col">
                            <label class="view-label">Guardian Email:</label>
                            <span id="view_guardian_email" class="view-value"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pro-add-buttons">
                <button class="pro-add-save" onclick="openEditStudentModal()" type="button">Edit</button>
                <button class="btn cancel" onclick="closeViewStudentModal()" type="button">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="modal" style="display:none;">
        <div class="modal-content add-modal-content pro-add-modal">
            <span class="close add-modal-close pro-add-close" onclick="closeEditStudentModal()">&times;</span>
            <h2 id="editModalTitle" class="add-modal-title pro-add-title">Edit Student</h2>
            <form id="editStudentForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-row image-name-row">
                    <div class="image-col">
                        <img id="edit_studentImage" src="{{ asset('images/stud.img/circle-user.png') }}" data-default="{{ asset('images/stud.img/circle-user.png') }}" alt="Student Image" class="student-image-box pro-add-image">
                        <input type="file" id="edit_image" name="image" accept="image/*" class="pro-add-image-input">
                        <div class="student-id-row pro-add-id-row">
                            <label for="edit_id_num_display" class="add-label">Student ID:</label>
                            <span id="edit_id_num_display" class="pro-add-id-value">Loading...</span>
                        </div>
                        <input type="hidden" id="edit_id_num" name="id_num">
                    </div>
                    <div class="name-fields-col">
                        <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                            <div class="add-field-col">
                                <label for="edit_lname" class="add-label">Last Name:</label>
                                <input type="text" id="edit_lname" name="lname" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="edit_fname" class="add-label">First Name:</label>
                                <input type="text" id="edit_fname" name="fname" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="edit_mname" class="add-label">Middle Name:</label>
                                <input type="text" id="edit_mname" name="mname" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="edit_suffix" class="add-label">Suffix:</label>
                                <input type="text" id="edit_suffix" name="suffix" class="add-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="edit_bod" class="add-label">Birthdate:</label>
                                <input type="date" id="edit_bod" name="bod" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="edit_gender" class="add-label">Sex:</label>
                                <select id="edit_gender" name="gender" class="add-input">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="add-field-col">
                                <label for="edit_religion" class="add-label">Religion:</label>
                                <input type="text" id="edit_religion" name="religion" class="add-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="edit_civil_status" class="add-label">Civil Status:</label>
                                <input type="text" id="edit_civil_status" name="civil_status" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="edit_address" class="add-label">Address:</label>
                                <input type="text" id="edit_address" name="address" class="add-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="add-field-col">
                                <label for="edit_mobile_num" class="add-label">Mobile No:</label>
                                <input type="text" id="edit_mobile_num" name="mobile_num" class="add-input">
                            </div>
                            <div class="add-field-col">
                                <label for="edit_email" class="add-label">Email:</label>
                                <input type="email" id="edit_email" name="email" class="add-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="edit_educ_level" class="add-label">Educational Level:</label>
                        <input list="educ_levels" id="edit_educ_level" name="educ_level" autocomplete="off" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="edit_year_level" class="add-label">Year Level:</label>
                        <select id="edit_year_level" name="year_level" class="add-input">
                            <option value="">Select Year Level</option>
                        </select>
                    </div>
                    <div id="edit_programField" class="program-section-field add-field-col" style="display: none;">
                        <label for="edit_program" class="add-label">Program:</label>
                        <input type="text" id="edit_program" name="program" class="add-input">
                    </div>
                    <div id="edit_sectionField" class="program-section-field add-field-col">
                        <label for="edit_section" class="add-label">Section:</label>
                        <input type="text" id="edit_section" name="section" class="add-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="edit_previous_school" class="add-label">Previous School Attended:</label>
                        <input type="text" id="edit_previous_school" name="previous_school" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="edit_previous_school_address" class="add-label">Previous School Address:</label>
                        <input type="text" id="edit_previous_school_address" name="previous_school_address" class="add-input">
                    </div>
                </div>
                <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                    <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                    <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="edit_father_name" class="add-label">Father's Name:</label>
                        <input type="text" id="edit_father_name" name="father_name" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="edit_mother_name" class="add-label">Mother's Name:</label>
                        <input type="text" id="edit_mother_name" name="mother_name" class="add-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="edit_guardian_name" class="add-label">Guardian Name:</label>
                        <input type="text" id="edit_guardian_name" name="guardian_name" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="edit_relationship" class="add-label">Relationship:</label>
                        <input type="text" id="edit_relationship" name="relationship" class="add-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="add-field-col">
                        <label for="edit_guardian_contact" class="add-label">Guardian Contact:</label>
                        <input type="text" id="edit_guardian_contact" name="guardian_contact" class="add-input">
                    </div>
                    <div class="add-field-col">
                        <label for="edit_guardian_email" class="add-label">Guardian Email:</label>
                        <input type="email" id="edit_guardian_email" name="guardian_email" class="add-input">
                    </div>
                </div>
                <div class="pro-add-buttons">
                    <button type="submit" class="pro-add-save">Save</button>
                    <button type="button" class="btn cancel" onclick="closeEditStudentModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/studentModal.js') }}"></script>
    <script>

        function toggleFields() {
            const educLevel = document.getElementById('educ_level').value;
            const programField = document.getElementById('programField');
            const sectionField = document.getElementById('sectionField');

            if (educLevel === 'College') {
                programField.style.display = 'block';
                sectionField.style.display = 'none';
            } else {
                programField.style.display = 'none';
                sectionField.style.display = 'block';
            }
        }


        (function() {
            const imageInput = document.getElementById('image');
            const imgPreview = document.getElementById('studentImage');
            if (imageInput) {
                imageInput.addEventListener('change', function(event) {
                    const [file] = event.target.files;
                    if (file) {
                        imgPreview.src = URL.createObjectURL(file);
                    } else {
                        imgPreview.src = imgPreview.getAttribute('data-default');
                    }
                    imgPreview.style.display = 'block';
                });
            }
            // Always show the default image when modal opens
            imgPreview.src = imgPreview.getAttribute('data-default');
            imgPreview.style.display = 'block';
        })();

        function openModal() {
            document.getElementById("studentModal").style.display = "block";
            fetchStudentId();
        }

        function closeModal() {
            document.getElementById("studentModal").style.display = "none";
        }

        function fetchStudentId() {
            const idNumDisplay = document.getElementById('id_num_display');
            const idNumHidden = document.getElementById('id_num');
            fetch('/Head/students/next-id')
                .then(response => response.json())
                .then(data => {
                    idNumDisplay.textContent = data.next_id;
                    idNumHidden.value = data.next_id;
                })
                .catch(error => {
                    idNumDisplay.textContent = 'Error generating ID';
                });
        }
    </script>
</body>

</html>
