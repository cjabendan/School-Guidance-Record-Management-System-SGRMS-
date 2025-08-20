function openAddModal() {
    document.getElementById('addStudentModal').style.display = 'block';
    fetchStudentId();
}

function closeAddModal() {
    document.getElementById('addStudentModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('addStudentModal');
    if (event.target === modal) {
        closeAddModal();
    }
}

function getYearSuffix(i) {
    if (i === 1) return "st";
    if (i === 2) return "nd";
    if (i === 3) return "rd";
    return "th";
}

// Year level for Add
function updateYearLevel() {
    const educLevel = document.getElementById("educ_level").value;
    const yearLevelSelect = document.getElementById("year_level");
    yearLevelSelect.innerHTML = "";

    // Add default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.text = "Select Year Level";
    yearLevelSelect.add(defaultOption);

    if (educLevel === "Kindergarten") {
        const option = document.createElement("option");
        option.value = "Kindergarten";
        option.text = "Kindergarten";
        yearLevelSelect.add(option);
    } else if (educLevel === "Elementary") {
        for (let i = 1; i <= 6; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "High School" || educLevel === "Senior High School") {
        for (let i = 7; i <= 12; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "College") {
        for (let i = 1; i <= 4; i++) {
            const suffix = getYearSuffix(i);
            const option = document.createElement("option");
            option.value = `${i}${suffix} Year`;
            option.text = `${i}${suffix} Year`;
            yearLevelSelect.add(option);
        }
    }
}

// For Edit Modal
function updateEditYearLevel(selectedValue) {
    const educLevel = document.getElementById("edit_educ_level").value;
    const yearLevelSelect = document.getElementById("edit_year_level");
    yearLevelSelect.innerHTML = "";

    // Add default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.text = "Select Year Level";
    yearLevelSelect.add(defaultOption);

    if (educLevel === "Kindergarten") {
        const option = document.createElement("option");
        option.value = "Kindergarten";
        option.text = "Kindergarten";
        yearLevelSelect.add(option);
    } else if (educLevel === "Elementary") {
        for (let i = 1; i <= 6; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "High School" || educLevel === "Senior High School") {
        for (let i = 7; i <= 12; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "College") {
        for (let i = 1; i <= 4; i++) {
            const suffix = getYearSuffix(i);
            const option = document.createElement("option");
            option.value = `${i}${suffix} Year`;
            option.text = `${i}${suffix} Year`;
            yearLevelSelect.add(option);
        }
    }
    // Set value if provided
    if (selectedValue) {
        yearLevelSelect.value = selectedValue;
    }
}

function openChooseAddModal() {
    document.getElementById('chooseAddModal').style.display = 'block';
}

function closeChooseAddModal() {
    document.getElementById('chooseAddModal').style.display = 'none';
}

function openImportModal() {
    closeChooseAddModal();
    document.getElementById('importModal').style.display = 'block';
}

function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
}

function openAddStudentModal() {
    document.getElementById('addStudentModal').style.display = 'block';
    fetchStudentId();
    updateYearLevel(); // Reset year level options when opening
}

function openViewStudentModal(id_num) {
    document.getElementById('viewStudentModal').style.display = 'block';
    fetch(`/Head/students/${id_num}/json`)
        .then(response => response.json())
        .then(data => {
            window.lastViewedStudent = data; // Store for editing
            document.getElementById('view_studentImage').src = data.image_url || '/images/stud.img/circle-user.png';
            document.getElementById('view_id_num_display').textContent = data.id_num || '';
            document.getElementById('view_lname').textContent = data.lname || '';
            document.getElementById('view_fname').textContent = data.fname || '';
            document.getElementById('view_mname').textContent = data.mname || '';
            document.getElementById('view_suffix').textContent = data.suffix || '';
            document.getElementById('view_bod').textContent = data.bod || '';
            document.getElementById('view_gender').textContent = data.gender || data.sex || '';
            document.getElementById('view_religion').textContent = data.religion || '';
            document.getElementById('view_civil_status').textContent = data.civil_status || '';
            document.getElementById('view_address').textContent = data.address || '';
            document.getElementById('view_mobile_num').textContent = data.mobile_num || '';
            document.getElementById('view_email').textContent = data.email || '';
            document.getElementById('view_educ_level').textContent = data.educ_level || '';
            document.getElementById('view_year_level').textContent = data.year_level || '';
            document.getElementById('view_program').textContent = data.program || '';
            document.getElementById('view_section').textContent = data.section || '';
            document.getElementById('view_previous_school').textContent = data.previous_school || '';
            document.getElementById('view_previous_school_address').textContent = data.previous_school_address || '';
            document.getElementById('view_father_name').textContent = data.father_name || '';
            document.getElementById('view_mother_name').textContent = data.mother_name || '';
            document.getElementById('view_guardian_name').textContent = data.guardian_name || '';
            document.getElementById('view_relationship').textContent = data.relationship || '';
            document.getElementById('view_guardian_contact').textContent = data.guardian_contact || '';
            document.getElementById('view_guardian_email').textContent = data.guardian_email || '';
        });
}

function closeViewStudentModal() {
    document.getElementById('viewStudentModal').style.display = 'none';
}

function openEditStudentModal() {
    document.getElementById('editStudentModal').style.display = 'block';
    // Copy image
    var viewImg = document.getElementById('view_studentImage');
    var editImg = document.getElementById('edit_studentImage');
    editImg.src = viewImg.src;
    // Copy ID
    document.getElementById('edit_id_num_display').textContent = document.getElementById('view_id_num_display').textContent;
    document.getElementById('edit_id_num').value = document.getElementById('view_id_num_display').textContent;
    // Copy text fields
    document.getElementById('edit_lname').value = document.getElementById('view_lname').textContent;
    document.getElementById('edit_fname').value = document.getElementById('view_fname').textContent;
    document.getElementById('edit_mname').value = document.getElementById('view_mname').textContent;
    document.getElementById('edit_suffix').value = document.getElementById('view_suffix').textContent;
    document.getElementById('edit_bod').value = document.getElementById('view_bod').textContent;
    document.getElementById('edit_gender').value = document.getElementById('view_gender').textContent;
    document.getElementById('edit_religion').value = document.getElementById('view_religion').textContent;
    document.getElementById('edit_civil_status').value = document.getElementById('view_civil_status').textContent;
    document.getElementById('edit_address').value = document.getElementById('view_address').textContent;
    document.getElementById('edit_mobile_num').value = document.getElementById('view_mobile_num').textContent;
    document.getElementById('edit_email').value = document.getElementById('view_email').textContent;
    // Set educ level and update year level options before setting year level value
    var educLevel = document.getElementById('view_educ_level').textContent;
    document.getElementById('edit_educ_level').value = educLevel;
    updateEditYearLevel();
    document.getElementById('edit_year_level').value = document.getElementById('view_year_level').textContent;
    document.getElementById('edit_program').value = document.getElementById('view_program').textContent;
    document.getElementById('edit_section').value = document.getElementById('view_section').textContent;
    document.getElementById('edit_previous_school').value = document.getElementById('view_previous_school').textContent;
    document.getElementById('edit_previous_school_address').value = document.getElementById('view_previous_school_address').textContent;
    document.getElementById('edit_father_name').value = document.getElementById('view_father_name').textContent;
    document.getElementById('edit_mother_name').value = document.getElementById('view_mother_name').textContent;
    document.getElementById('edit_guardian_name').value = document.getElementById('view_guardian_name').textContent;
    document.getElementById('edit_relationship').value = document.getElementById('view_relationship').textContent;
    document.getElementById('edit_guardian_contact').value = document.getElementById('view_guardian_contact').textContent;
    document.getElementById('edit_guardian_email').value = document.getElementById('view_guardian_email').textContent;
}

// Edit image preview
(function() {
    const imageInput = document.getElementById('edit_image');
    const imgPreview = document.getElementById('edit_studentImage');
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
})();

// Add event listeners for year level update on educ level change
document.addEventListener('DOMContentLoaded', function() {
    var educLevelInput = document.getElementById('educ_level');
    if (educLevelInput) {
        educLevelInput.addEventListener('change', updateYearLevel);
    }
    var editEducLevelInput = document.getElementById('edit_educ_level');
    if (editEducLevelInput) {
        editEducLevelInput.addEventListener('change', function() {
            updateEditYearLevel();
        });
    }
});

function closeEditStudentModal() {
    document.getElementById('editStudentModal').style.display = 'none';
}
