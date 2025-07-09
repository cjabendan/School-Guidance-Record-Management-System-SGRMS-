<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
</head>
<body>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2 id="addModalTitle">Add Student</h2>
            <form id="addStudentForm" method="POST" action="{{ url('Head/students/add') }}" enctype="multipart/form-data">
                @csrf

            <label for="id_num_display">Student ID:</label>
            <span id="id_num_display">Loading...</span>
            <input type="hidden" id="id_num" name="id_num">

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <img id="studentImage"
                     src="{{ asset('images/stud.img/circle-user.png') }}"
                     data-default="{{ asset('images/stud.img/circle-user.png') }}"
                     alt="Student Image"
                     style="width: 100px; height: auto; margin-top: 10px;">

                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" placeholder="Enter last name">

                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" placeholder="Enter first name">

                <label for="mname">Middle Name:</label>
                <input type="text" id="mname" name="mname" placeholder="Enter middle name">

                <label for="suffix">Suffix:</label>
                <input type="text" id="suffix" name="suffix" placeholder=" e.g. Jr., Sr., III">

                <label for="bod">Birthdate:</label>
                <input type="date" id="bod" name="bod">

                <label for="gender">Sex:</label>
                <select id="gender" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="Enter address">

                <label for="mobile_num">Mobile No:</label>
                <input type="text" id="mobile_num" name="mobile_num" placeholder="Enter mobile number">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email">

                <label for="educ_level">Educational Level:</label>
                <select id="educ_level" name="educ_level">
                    <option value="">Select Level</option>
                    <option value="Elementary">Elementary</option>
                    <option value="High School">High School</option>
                    <option value="College">College</option>
                </select>

                <script>
                    document.getElementById('educ_level').addEventListener('change', function() {
                        toggleFields();
                        updateYearLevel();
                    });
                </script>
                <label for="year_level">Year Level:</label>
                <select id="year_level" name="year_level">
                    <option value="">Select Year Level</option>
                </select>

                <div id="programField" style="display: none;">
                    <label for="program">Program:</label>
                    <input type="text" id="program" name="program" placeholder="Enter program name">
                </div>

                <div id="sectionField">
                    <label for="section">Section:</label>
                    <input type="text" id="section" name="section" placeholder="Enter section name">
                </div>

                <label for="previous_school">Previous School:</label>
                <input type="text" id="previous_school" name="previous_school" placeholder="Enter previous school name">

                <hr style="margin: 20px 0;">

                <div style="margin-bottom: 10px;">
                    <strong>Guardain Information</strong>
                </div>
                <div class="form-group">
                    <label for="guardian_name">Guardian Name:</label>
                    <input type="text" id="guardian_name" placeholder="Enter guardian's name">
                </div>
                <div class="form-group">
                    <label for="relationship">Relationship:</label>
                    <input type="text" id="relationship" placeholder="e.g. Mother, Father, Guardian">
                </div>
                <div class="form-group">
                    <label for="guardian_contact">Guardian Contact:</label>
                    <input type="text" id="guardian_contact" placeholder="Enter guardian's contact number">
                </div>
                <div class="form-group">
                    <label for="guardian_email">Guardian Email:</label>
                    <input type="email" id="guardian_email" placeholder="Enter guardian's email">
                </div>

                <button type="submit">Save</button>
            </form>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

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

    document.addEventListener('DOMContentLoaded', function() {
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
});
    </script>
</body>
</html>
