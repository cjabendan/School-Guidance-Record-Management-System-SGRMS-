function openAddModal() {
    document.getElementById("addStudentModal").style.display = "block";
    fetchStudentId();
}

function closeAddModal() {
    document.getElementById("addStudentModal").style.display = "none";
}

window.onclick = function (event) {
    var modal = document.getElementById("addStudentModal");
    if (event.target === modal) {
        closeAddModal();
    }
};

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

    if (educLevel === "Elementary") {
        for (let i = 1; i <= 6; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "High School") {
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

function openChooseAddModal() {
    document.getElementById("chooseAddModal").style.display = "block";
}

function closeChooseAddModal() {
    document.getElementById("chooseAddModal").style.display = "none";
}

function openImportModal() {
    closeChooseAddModal();
    document.getElementById("importModal").style.display = "block";
}

function closeImportModal() {
    document.getElementById("importModal").style.display = "none";
}

function openAddStudentModal() {
    closeChooseAddModal();
    document.getElementById("addStudentModal").style.display = "block";
}

function openViewStudentModal(id_num) {
    document.getElementById("viewStudentModal").style.display = "block";
    fetch(`/Head/students/${id_num}/json`)
        .then((response) => response.json())
        .then((data) => {
            window.lastViewedStudent = data; // Store for editing
            document.getElementById("viewStudentContent").innerHTML = `
                <p><strong>ID:</strong> ${data.id_num}</p>
                <p><strong>Name:</strong> ${data.lname}, ${data.fname} ${
                data.mname ?? ""
            } ${data.suffix ?? ""}</p>
                <p><strong>Age:</strong> ${data.age}</p>
                <p><strong>Educational Level:</strong> ${data.educ_level}</p>
                <p><strong>Section/Program:</strong> ${
                    data.section ?? data.program
                }</p>
            `;
        });
}

function closeViewStudentModal() {
    document.getElementById("viewStudentModal").style.display = "none";
}

function openEditStudentModal() {
    // Get the last loaded student data from the view modal
    const data = window.lastViewedStudent;
    if (!data) return;

    document.getElementById("editStudentModal").style.display = "block";
    document.getElementById("edit_id_num").value = data.id_num;
    document.getElementById("edit_lname").value = data.lname;
    document.getElementById("edit_fname").value = data.fname;
    document.getElementById("edit_mname").value = data.mname ?? "";
    document.getElementById("edit_suffix").value = data.suffix ?? "";
    document.getElementById("edit_bod").value = data.bod;
    document.getElementById("edit_gender").value = data.sex;
    // Populate other fields as needed
}

function closeEditStudentModal() {
    document.getElementById("editStudentModal").style.display = "none";
}
