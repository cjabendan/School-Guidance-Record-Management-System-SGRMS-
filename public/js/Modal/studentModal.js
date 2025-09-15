// Delete profile image logic for edit modal
document.addEventListener('DOMContentLoaded', function() {
    var deleteBtn = document.getElementById('deleteProfileImageBtn');
    var studentImage = document.getElementById('studentImage');
    var deleteField = document.getElementById('delete_profile_image');
    if (deleteBtn && studentImage && deleteField) {
        deleteBtn.addEventListener('click', function() {
            var defaultSrc = studentImage.getAttribute('data-default');
            if (studentImage.src !== defaultSrc) {
                studentImage.src = defaultSrc;
                deleteField.value = '1';
            }
        });
    }
});
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
    } else if (educLevel === "Junior High School") {
        for (let i = 7; i <= 10; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "Senior High School") {
        for (let i = 11; i <= 12; i++) {
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



function updateEditYearLevel(selectedValue) {
    const educLevel = document.getElementById("edit_educ_level").value;
    const yearLevelSelect = document.getElementById("edit_year_level");
    yearLevelSelect.innerHTML = "";

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
    } else if (educLevel === "Junior High School") {
        for (let i = 7; i <= 10; i++) {
            const option = document.createElement("option");
            option.value = `Grade ${i}`;
            option.text = `Grade ${i}`;
            yearLevelSelect.add(option);
        }
    } else if (educLevel === "Senior High School") {
        for (let i = 11; i <= 12; i++) {
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
    if (selectedValue) {
        yearLevelSelect.value = selectedValue;
    }
}

// Toggle program field enable/disable
function toggleProgramField() {
    const educLevel = document.getElementById('educ_level').value;
    const programInput = document.getElementById('program');
    if (educLevel === 'Senior High School') {
        programInput.disabled = false;
        programInput.placeholder = "Enter program name";
    } else {
        programInput.disabled = true;
        programInput.value = "";
        programInput.placeholder = "Program only for Senior High School";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var educLevelInput = document.getElementById('educ_level');
    if (educLevelInput) {
        educLevelInput.addEventListener('change', function() {
            updateYearLevel();
            toggleProgramField();
        });
    }

    // Modal close logic with debug logs
    const closeBtn = document.getElementById('closeAddModalBtn');
    if (closeBtn) {
        closeBtn.onclick = function(e) {
            console.log('X button clicked');
            e.stopPropagation();
            window.closeAddModal();
        };
    }
    const modal = document.getElementById('addStudentModal');
    if (modal) {    
        modal.onclick = function(event) {
            if (event.target === modal) {
                console.log('Modal background clicked');
                window.closeAddModal();
            } else {
                console.log('Modal inner element clicked:', event.target);
            }
        };
    }
    const modalContent = document.querySelector('#addStudentModal .modal-content');
    if (modalContent) {
        modalContent.onclick = function(e) {
            console.log('Modal content clicked');
            e.stopPropagation();
        };
    }
});
//_________________________________________________________________________________________

// Add/Edit Modal logic
window.openAddEditModal = function openAddEditModal(mode, studentData = null) {
    // If edit mode and only s_id is provided, fetch full data via AJAX
    if (mode === 'edit' && studentData && studentData.s_id && Object.keys(studentData).length === 1) {
        fetch(`/Head/students/${studentData.s_id}/json`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Student not found!');
                    return;
                }
                window.openAddEditModal('edit', data);
            });
        return;
    }
    const modal = document.getElementById('addStudentModal');
    const form = document.getElementById('addStudentForm');
    const title = document.getElementById('addModalTitle');
    const saveBtn = document.getElementById('addEditSaveBtn');
    const imgPreview = document.getElementById('studentImage');
    const imageInput = document.getElementById('profile_image');
    // Reset form
    form.reset();
    // Reset image
    imgPreview.src = imgPreview.getAttribute('data-default');
    // Remove previous image preview
    if (imageInput) imageInput.value = "";
    // Attach image preview event every time modal opens
    if (imageInput) {
        imageInput.onchange = function(event) {
            const [file] = event.target.files;
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
            } else {
                imgPreview.src = imgPreview.getAttribute('data-default');
            }
            imgPreview.style.display = 'block';
        };
    }
    // Set mode
    if (mode === 'add') {
        title.textContent = 'Add Student';
        saveBtn.textContent = 'Save';
        var methodInput = document.getElementById('_method_input');
        if (methodInput) methodInput.remove();
        // Fetch new student ID
        fetch('/Head/students/next-id')
            .then(response => response.json())
            .then(data => {
                document.getElementById('s_id_display').textContent = data.next_id;
                document.getElementById('s_id').value = data.next_id;
            });
    } else if (mode === 'edit' && studentData) {
        title.textContent = 'Edit Student';
        saveBtn.textContent = 'Update';
        // DO NOT set form.action here
        if (!document.getElementById('_method_input')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            methodInput.id = '_method_input';
            form.appendChild(methodInput);
        }
        document.getElementById('s_id_display').textContent = studentData.id_num || '';
        document.getElementById('s_id').value = studentData.id_num || '';
        document.getElementById('first_name').value = studentData.fname || '';
        document.getElementById('middle_name').value = studentData.mname || '';
        document.getElementById('last_name').value = studentData.lname || '';
        document.getElementById('suffix').value = studentData.suffix || '';
        document.getElementById('email').value = studentData.email || '';
        document.getElementById('contact_num').value = studentData.mobile_num || '';
        // Set sex radio button for edit modal (name="sex")
        if (studentData.gender === 'Male' || studentData.sex === 'Male') {
            document.querySelector('input[name="sex"][value="Male"]').checked = true;
            document.querySelector('input[name="sex"][value="Female"]').checked = false;
        } else if (studentData.gender === 'Female' || studentData.sex === 'Female') {
            document.querySelector('input[name="sex"][value="Male"]').checked = false;
            document.querySelector('input[name="sex"][value="Female"]').checked = true;
        } else {
            document.querySelector('input[name="sex"][value="Male"]').checked = false;
            document.querySelector('input[name="sex"][value="Female"]').checked = false;
        }
        document.getElementById('bod').value = studentData.bod || '';
        document.getElementById('address').value = studentData.address || '';
        document.getElementById('religion').value = studentData.religion || '';
        document.getElementById('civil_status').value = studentData.civil_status || '';
        document.getElementById('educ_level').value = studentData.educ_level || '';
        toggleProgramField();
        if (typeof updateYearLevel === 'function') {
            updateYearLevel();
        }
        document.getElementById('program').value = studentData.program || '';
        document.getElementById('year_level').value = studentData.year_level || '';
        document.getElementById('section').value = studentData.section || '';
        document.getElementById('father_name').value = studentData.father_name || '';
        document.getElementById('mother_name').value = studentData.mother_name || '';
        document.getElementById('guardian_name').value = studentData.guardian_name || '';
        document.getElementById('relationship').value = studentData.relationship || '';
        document.getElementById('guardian_contact').value = studentData.guardian_contact || '';
        document.getElementById('guardian_email').value = studentData.guardian_email || '';
        // Set image preview if available
        if (studentData.image_url) {
            imgPreview.src = studentData.image_url;
        }
    }
    modal.style.display = 'block';
    console.log('Modal opened');

    // Attach AJAX submit for both add and edit
    saveBtn.onclick = function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        let url = '';
        let method = '';
        if (mode === 'add') {
            url = '/Head/students';
            method = 'POST';
        } else if (mode === 'edit' && studentData) {
            url = '/Head/students/' + (studentData.id_num || studentData.s_id);
            method = 'POST'; // Laravel expects POST with _method=PUT
            formData.append('_method', 'PUT');
        }
        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.message) {
                window.closeAddModal();
                location.reload();
            } else {
                alert('Save failed!');
            }
        })
        .catch(() => {
            alert('An error occurred while saving.');
        });
    };
}
// Close modal
window.closeAddModal = function closeAddModal() {
    var modal = document.getElementById('addStudentModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('Modal closed');
    }
}


//_________________________________________________________________________________________


// View Modal logic
window.openViewStudentModal = function openViewStudentModal(s_id) {
    fetch(`/Head/students/${s_id}/json`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Student not found!');
                return;
            }
            // Set all fields in the view modal
                const viewFields = [
                    ['view_s_id_display', data.id_num || data.s_id],
                    ['view_first_name', data.fname],
                    ['view_middle_name', data.mname],
                    ['view_last_name', data.lname],
                    ['view_suffix', data.suffix],
                    ['view_email', data.email],
                    ['view_contact_num', data.mobile_num],
                    ['view_sex', data.gender || data.sex],
                    ['view_bod', data.bod],
                    ['view_address', data.address],
                    ['view_religion', data.religion],
                    ['view_civil_status', data.civil_status],
                    ['view_educ_level', data.educ_level],
                    ['view_program', data.program],
                    ['view_year_level', data.year_level],
                    ['view_section', data.section],
                    ['view_father_name', data.father_name],
                    ['view_mother_name', data.mother_name],
                    ['view_guardian_name', data.guardian_name],
                    ['view_relationship', data.relationship],
                    ['view_guardian_contact', data.guardian_contact],
                    ['view_guardian_email', data.guardian_email],
                ];
                for (const [id, value] of viewFields) {
                    // Sex radio button for view modal (name="view_sex")
                    if (id === 'view_sex') {
                        let displayValue = value;
                        if (displayValue === undefined || displayValue === null || displayValue === '' || displayValue === 'N/A') {
                            displayValue = 'N/A';
                        }
                        if (displayValue === 'Male') {
                            document.getElementById('view_sex_male').checked = true;
                            document.getElementById('view_sex_female').checked = false;
                        } else if (displayValue === 'Female') {
                            document.getElementById('view_sex_male').checked = false;
                            document.getElementById('view_sex_female').checked = true;
                        } else {
                            document.getElementById('view_sex_male').checked = false;
                            document.getElementById('view_sex_female').checked = false;
                        }
                    } else {
                        const el = document.getElementById(id);
                        if (el) {
                            let displayValue = value;
                            if (displayValue === undefined || displayValue === null || displayValue === '' || displayValue === 'N/A') {
                                if (id === 'view_relationship' || id === 'view_guardian_contact' || id === 'view_guardian_email') {
                                    displayValue = 'None';
                                } else {
                                    displayValue = 'N/A';
                                }
                            }
                            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                                el.value = displayValue;
                            } else {
                                el.textContent = displayValue;
                            }
                        }
                    }
                }
            // Set image
            var img = document.getElementById('viewStudentImage');
            if (img && data.image_url) {
                img.src = data.image_url;
            }
            // Show modal
            var modal = document.getElementById('viewStudentModal');
            if (modal) {
                modal.style.display = 'block';
                modal.style.zIndex = 9999;
            }

            // Fetch case records and display
            fetch(`/Head/students/${s_id}/cases`)
                .then(response => response.json())
                .then(cases => {
                    console.log('Case records response:', cases);
                    const caseRecordsDiv = document.getElementById('view_case_records');
                    const template = document.getElementById('case_record_template');
                    if (caseRecordsDiv) {
                        caseRecordsDiv.innerHTML = '';
                        if (!cases || cases.length === 0) {
                            caseRecordsDiv.innerHTML = '<div style="color:#64748b;">No case records found.</div>';
                        } else {
                            cases.forEach(c => {
                                if (!template) return;
                                const clone = template.cloneNode(true);
                                clone.style.display = '';
                                // Fill in values
                                clone.querySelector('.case-title').textContent = c.case_title || 'Case';
                                clone.querySelector('.case-severity').innerHTML = `<span style='font-weight:500;'>Severity:</span> <span style='color:${c.severity === 'Severe' ? '#e11d48' : c.severity === 'Intermediate' ? '#f59e42' : '#2563eb'}; font-weight:600;'>${c.severity || 'N/A'}</span>`;
                                clone.querySelector('.case-date').innerHTML = `<span style='font-weight:500;'>Date:</span> ${c.filed_date || c.date_reported || 'N/A'}`;
                                clone.querySelector('.case-status').innerHTML = `<span style='font-weight:500;'>Status:</span> ${c.status || 'N/A'}`;
                                clone.querySelector('.case-description').textContent = c.description || '';
                                caseRecordsDiv.appendChild(clone);
                            });
                        }
                    } else {
                        console.warn('view_case_records div not found in modal!');
                    }
                })
                .catch(err => {
                    console.error('Error fetching case records:', err);
                });
        });
}

// Close logic for view modal
document.addEventListener('DOMContentLoaded', function() {
    var viewModal = document.getElementById('viewStudentModal');
    var closeBtn = document.getElementById('closeViewModalBtn');
    if (closeBtn && viewModal) {
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            viewModal.style.display = 'none';
        };
    }
    if (viewModal) {
        viewModal.onclick = function(event) {
            if (event.target === viewModal) {
                viewModal.style.display = 'none';
            }
        };
        var modalContent = viewModal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.onclick = function(e) {
                e.stopPropagation();
            };
        }
    }
});

// _________________________________________________________________________________________


// Archive Modal logic
window.currentArchiveSId = null;

window.openArchiveStudentModal = function openArchiveStudentModal(s_id) {
    window.currentArchiveSId = s_id;
    // Optionally fetch student data here if needed
    var input = document.getElementById('archive_s_id');
    if (input) input.value = s_id;
    // Display the student ID in the modal title
    var idDisplay = document.getElementById('archiveStudentIdDisplay');
    if (idDisplay) idDisplay.textContent = `(${s_id})`;
    var modal = document.getElementById('archiveStudentModal');
    if (modal) modal.style.display = 'block';
};

window.closeArchiveModal = function closeArchiveModal() {
    var modal = document.getElementById('archiveStudentModal');
    if (modal) modal.style.display = 'none';
    window.currentArchiveSId = null;
    var idDisplay = document.getElementById('archiveStudentIdDisplay');
    if (idDisplay) idDisplay.textContent = '';
};

window.archiveStudentOnly = function archiveStudentOnly() {
    if (!window.currentArchiveSId) return;
    var status = document.getElementById('archive_status') ? document.getElementById('archive_status').value : '';
    if (!status) {
        alert('Please select a status.');
        return;
    }
    fetch('/Head/students/archive', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ s_id: window.currentArchiveSId, status: status })
    })
    .then(res => {
        if (!res.ok) {
            return res.text().then(text => { throw new Error('Server error: ' + text); });
        }
        return res.json();
    })
    .then(data => {
        window.closeArchiveModal();
        location.reload();
    })
    .catch(err => {
        alert('Archive failed: ' + err.message);
    });
};

window.archiveStudentAndDisable = function archiveStudentAndDisable() {
    if (!window.currentArchiveSId) return;
    var status = document.getElementById('archive_status') ? document.getElementById('archive_status').value : '';
    if (!status) {
        alert('Please select a status.');
        return;
    }
    fetch('/Head/students/archive-disable', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ s_id: window.currentArchiveSId, status: status })
    })
    .then(res => {
        if (!res.ok) {
            return res.text().then(text => { throw new Error('Server error: ' + text); });
        }
        return res.json();
    })
    .then(data => {
        window.closeArchiveModal();
        location.reload();
    })
    .catch(err => {
        alert('Archive & disable failed: ' + err.message);
    });
};

// Attach click event to all archive buttons
document.addEventListener('DOMContentLoaded', function() {
    // No need to attach click handler here if using inline onclick in Blade

    // Archive modal close button
    var closeArchiveBtn = document.getElementById('closeArchiveModalBtn');
    if (closeArchiveBtn) {
        closeArchiveBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeArchiveModal();
        };
    }
});


// _________________________________________________________________________________________

// Import button logic for students.blade.php
document.addEventListener('DOMContentLoaded', function() {
    const importBtn = document.getElementById('importBtn');
    const importFileInput = document.getElementById('importFileInput');
    const importForm = document.getElementById('importForm');
    if (importBtn && importFileInput && importForm) {
        importBtn.addEventListener('click', function() {
            importFileInput.click();
        });
        importFileInput.addEventListener('change', function() {
            if (importFileInput.files.length > 0) {
                importForm.submit();
            }
        });
    }
});


// _________________________________________________________________________________________

// Student export 
const exportBtn = document.getElementById('exportDropdownBtn');
const exportMenu = document.getElementById('exportDropdownMenu');
if (exportBtn && exportMenu) {
    exportBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        exportMenu.style.display = exportMenu.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', function() {
        exportMenu.style.display = 'none';
    });
    // Dropdown hover effect
    document.querySelectorAll('.dropdown-item').forEach(function(item) {
        item.addEventListener('mouseover', function() {
            this.style.background = '#f3f4f6';
        });
        item.addEventListener('mouseout', function() {
            this.style.background = 'none';
        });
    });
}
function downloadExport(format) {
    if (exportMenu) exportMenu.style.display = 'none';
    let url = new URL(window.location.origin + '/Head/students/export');
    url.searchParams.set('format', format);
    // Add status filter if present
    const statusTab = document.querySelector('.tab.active');
    if (statusTab && statusTab.href.includes('status=')) {
        const status = new URL(statusTab.href).searchParams.get('status');
        if (status) url.searchParams.set('status', status);
    }
    // Use anchor to force download
    const a = document.createElement('a');
    a.href = url.toString();
    a.setAttribute('download', 'students_export.' + (format === 'pdf' ? 'pdf' : 'xlsx'));
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

