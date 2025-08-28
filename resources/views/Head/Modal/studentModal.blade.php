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
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="addStudentForm" method="POST" action="{{ url('Head/students') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row image-name-row">
                    <div class="image-col">
                        <img id="studentImage" src="{{ asset('images/stud.img/default.png') }}" data-default="{{ asset('images/stud.img/default.png') }}" alt="Student Image" class="student-image-box pro-add-image">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="pro-add-image-input">
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
                        </div>
                    </div>
                </div>
                <div class="form-row">
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
                    <div id="programField" class="program-section-field add-field-col" style="display: none;">
                        <label for="program" class="add-label">Program:</label>
                        <input type="text" id="program" name="program" placeholder="Enter program name" class="add-input">
                    </div>
                    <div id="sectionField" class="program-section-field add-field-col">
                        <label for="section" class="add-label">Section:</label>
                        <input type="text" id="section" name="section" placeholder="Enter section name" class="add-input">
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
        <div class="modal-content add-modal-content pro-add-modal">
            <span class="close add-modal-close pro-add-close" onclick="closeViewStudentModal()">&times;</span>
            <h2 id="viewModalTitle" class="add-modal-title pro-add-title">View Student</h2>
            <div class="form-row image-name-row">
                <div class="image-col">
                    <img id="view_studentImage" src="{{ asset('images/stud.img/default.png') }}" data-default="{{ asset('images/stud.img/default.png') }}" alt="Student Image" class="student-image-box pro-add-image">
                    <div class="student-id-row pro-add-id-row">
                        <label for="view_s_id_display" class="add-label" style="margin-bottom:0;">Student ID:</label>
                        <span id="view_s_id_display" class="pro-add-id-value">Loading...</span>
                    </div>
                </div>
                <div class="name-fields-col">
                    <div class="form-row" style="margin-bottom: 0; gap: 12px;">
                        <div class="add-field-col">
                            <label class="add-label">First Name:</label>
                            <span id="view_first_name" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Middle Name:</label>
                            <span id="view_middle_name" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Last Name:</label>
                            <span id="view_last_name" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Suffix:</label>
                            <span id="view_suffix" class="add-input"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Email:</label>
                            <span id="view_email" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Contact Number:</label>
                            <span id="view_contact_num" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Sex:</label>
                            <span id="view_sex" class="add-input"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Birthdate:</label>
                            <span id="view_bod" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Address:</label>
                            <span id="view_address" class="add-input"></span>
                        </div>
                        <div class="add-field-col">
                            <label class="add-label">Religion:</label>
                            <span id="view_religion" class="add-input"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="add-field-col">
                            <label class="add-label">Civil Status:</label>
                            <span id="view_civil_status" class="add-input"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Educational Level:</label>
                    <span id="view_educ_level" class="add-input"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Year Level:</label>
                    <span id="view_year_level" class="add-input"></span>
                </div>
                <div id="view_programField" class="program-section-field add-field-col" style="display: none;">
                    <label class="add-label">Program:</label>
                    <span id="view_program" class="add-input"></span>
                </div>
                <div id="view_sectionField" class="program-section-field add-field-col">
                    <label class="add-label">Section:</label>
                    <span id="view_section" class="add-input"></span>
                </div>
            </div>
            <div style="width: 100%; text-align: center; margin: 28px 0 18px 0; position: relative;">
                <span style="background: #fff; position: relative; z-index: 1; padding: 0 18px; font-size: 1.08rem; font-weight: 600; color: #2563eb; letter-spacing: 0.04em;">Parent & Guardian Information</span>
                <hr style="position: absolute; top: 50%; left: 0; width: 100%; border: none; border-top: 2px solid #2563eb; z-index: 0; margin: 0;">
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Father's Name:</label>
                    <span id="view_father_name" class="add-input"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Mother's Name:</label>
                    <span id="view_mother_name" class="add-input"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Guardian Name:</label>
                    <span id="view_guardian_name" class="add-input"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Relationship:</label>
                    <span id="view_relationship" class="add-input"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="add-field-col">
                    <label class="add-label">Guardian Contact:</label>
                    <span id="view_guardian_contact" class="add-input"></span>
                </div>
                <div class="add-field-col">
                    <label class="add-label">Guardian Email:</label>
                    <span id="view_guardian_email" class="add-input"></span>
                </div>
            </div>
            <div class="pro-add-buttons">
                <button type="button" class="pro-add-save" onclick="openEditStudentModal()">
                    <i class="bx bx-edit"></i> Edit
                </button>
                <button type="button" class="btn cancel" onclick="closeViewStudentModal()">Close</button>
            </div>
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
            const sIdDisplay = document.getElementById('s_id_display');
            const sIdHidden = document.getElementById('s_id');
            fetch('/Head/students/next-id')
                .then(response => response.json())
                .then(data => {
                    sIdDisplay.textContent = data.next_id;
                    sIdHidden.value = data.next_id;
                })
                .catch(error => {
                    sIdDisplay.textContent = 'Error generating ID';
                });
        }
    </script>
</body>

</html>
