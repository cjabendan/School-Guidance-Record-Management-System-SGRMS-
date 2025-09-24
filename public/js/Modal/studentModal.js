
// Utility: Get year suffix (for college levels)
function getYearSuffix(i) {
    if (i === 1) return "st";
    if (i === 2) return "nd";
    if (i === 3) return "rd";
    return "th";
}

// Populate year levels for Add Student modal
function updateYearLevel() {
    const educLevel = document.getElementById("educ_level").value;
    const yearLevelSelect = document.getElementById("year_level");
    if (!yearLevelSelect) return;

    yearLevelSelect.innerHTML = "";

    // Add default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.text = "Select Year Level";
    yearLevelSelect.add(defaultOption);

    if (educLevel === "Kindergarten") {
        yearLevelSelect.add(new Option("Kindergarten", "Kindergarten"));
    } else if (educLevel === "Elementary") {
        for (let i = 1; i <= 6; i++) {
            yearLevelSelect.add(new Option(`Grade ${i}`, `Grade ${i}`));
        }
    } else if (educLevel === "Junior High School") {
        for (let i = 7; i <= 10; i++) {
            yearLevelSelect.add(new Option(`Grade ${i}`, `Grade ${i}`));
        }
    } else if (educLevel === "Senior High School") {
        for (let i = 11; i <= 12; i++) {
            yearLevelSelect.add(new Option(`Grade ${i}`, `Grade ${i}`));
        }
    } else if (educLevel === "College") {
        for (let i = 1; i <= 4; i++) {
            const suffix = getYearSuffix(i);
            yearLevelSelect.add(new Option(`${i}${suffix} Year`, `${i}${suffix} Year`));
        }
    }
}

// Populate year levels for Edit Student modal
function updateEditYearLevel(selectedValue) {
    const educLevel = document.getElementById("edit_educ_level")?.value;
    const yearLevelSelect = document.getElementById("edit_year_level");
    if (!yearLevelSelect) return;

    yearLevelSelect.innerHTML = "";

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.text = "Select Year Level";
    yearLevelSelect.add(defaultOption);

    if (educLevel === "Kindergarten") {
        yearLevelSelect.add(new Option("Kindergarten", "Kindergarten"));
    } else if (educLevel === "Elementary") {
        for (let i = 1; i <= 6; i++) {
            yearLevelSelect.add(new Option(`Grade ${i}`, `Grade ${i}`));
        }
    } else if (educLevel === "Junior High School") {
        for (let i = 7; i <= 10; i++) {
            yearLevelSelect.add(new Option(`Grade ${i}`, `Grade ${i}`));
        }
    } else if (educLevel === "Senior High School") {
        for (let i = 11; i <= 12; i++) {
            yearLevelSelect.add(new Option(`Grade ${i}`, `Grade ${i}`));
        }
    } else if (educLevel === "College") {
        for (let i = 1; i <= 4; i++) {
            const suffix = getYearSuffix(i);
            yearLevelSelect.add(new Option(`${i}${suffix} Year`, `${i}${suffix} Year`));
        }
    }

    if (selectedValue) {
        yearLevelSelect.value = selectedValue;
    }
}

// Attach listeners once DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const educLevelInput = document.getElementById('educ_level');
    if (educLevelInput) {
        educLevelInput.addEventListener('change', updateYearLevel);
    }

    const editEducLevelInput = document.getElementById('edit_educ_level');
    if (editEducLevelInput) {
        editEducLevelInput.addEventListener('change', function() {
            updateEditYearLevel();
        });
    }
});


//__________________________________________________________________________________________


// Delete profile image logic for Add/Edit Student modal
document.addEventListener('DOMContentLoaded', function() {
    const removeBtn = document.getElementById('remove-image-btn');
    const studentImage = document.getElementById('studentImage');
    const imageInput = document.getElementById('profile_image');
    const fileChosen = document.getElementById('file-chosen');
    const deleteField = document.getElementById('delete_profile_image'); 

    if (removeBtn && studentImage && imageInput) {
        removeBtn.addEventListener('click', function () {
            // Reset to default image
            studentImage.src = '/images/user/default.png'; 
            imageInput.value = '';
            if (fileChosen) fileChosen.textContent = 'No file chosen';

            // Show backend this image was deleted (only for edit mode)
            if (deleteField) deleteField.value = '1';

            // Hide button again after deleting
            removeBtn.style.display = 'none';
        });
    }
});


// Add/Edit Modal logic
window.openAddEditModal = function openAddEditModal(mode, studentData = null) {
    // ✅ If edit mode and only s_id is provided, fetch full data via AJAX
    if (mode === 'edit' && studentData && studentData.s_id && Object.keys(studentData).length === 1) {
        fetch(`/Head/students/${studentData.s_id}/json`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Student not found!');
                    return;
                }
                // Call again with full student data
                window.openAddEditModal('edit', data);
            })
            .catch(err => {
                console.error("Failed to fetch student:", err);
                alert("Error fetching student details.");
            });
        return;
    }

    const modal = document.getElementById('addStudentModal');
    const form = document.getElementById('addStudentForm');
    const title = document.getElementById('addModalTitle');
    const saveBtn = document.getElementById('addEditSaveBtn');
    const imgPreview = document.getElementById('studentImage'); 
    const imageInput = document.getElementById('profile_image');
    const removeBtn = document.getElementById('remove-image-btn');
    const fileChosen = document.getElementById('file-chosen');

    // Reset form
    form.reset();
    if (fileChosen) fileChosen.textContent = 'No file chosen';
    if (imageInput) imageInput.value = "";

    // Reset image
    if (mode === 'add') {
        imgPreview.src = '/images/user/default.png'; 
        if (removeBtn) removeBtn.style.display = 'none'; // hidden by default
    } else {
        imgPreview.src = '';
        if (removeBtn) removeBtn.style.display = 'none';
    }

    // Image upload + crop logic (same as before)...
    if (imageInput) {
        imageInput.onchange = function(event) {
            const [file] = event.target.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const cropperModal = document.getElementById('cropImageModal');
                    const cropperImage = document.getElementById('cropperImage');
                    cropperImage.src = e.target.result;
                    cropperModal.style.display = 'block';

                    if (window.cropper) {
                        window.cropper.destroy();
                    }
                    window.cropper = new Cropper(cropperImage, {
                        aspectRatio: 1, 
                        viewMode: 1,
                    });

                    document.getElementById('cropApplyBtn').onclick = function() {
                        const canvas = window.cropper.getCroppedCanvas({
                            width: 250,
                            height: 225
                        });
                        imgPreview.src = canvas.toDataURL("image/png");
                        document.getElementById('cropped_image_data').value = canvas.toDataURL("image/png");
                        cropperModal.style.display = 'none';
                        window.cropper.destroy();
                        if (removeBtn) removeBtn.style.display = "block";
                    };

                    document.getElementById('closeCropModalBtn').onclick = function() {
                        cropperModal.style.display = 'none';
                        if (window.cropper) {
                            window.cropper.destroy();
                            window.cropper = null;
                        }
                        if (imageInput) imageInput.value = "";
                        if (fileChosen) fileChosen.textContent = "No file chosen";
                        if (form && form.getAttribute("data-mode") === "add") {
                            imgPreview.src = "/images/user/default.png";
                        }
                        if (removeBtn) removeBtn.style.display = "none";
                    };
                };
                reader.readAsDataURL(file);
                if (fileChosen) fileChosen.textContent = file.name;
            } else {
                imgPreview.src = '';
                if (fileChosen) fileChosen.textContent = 'No file chosen';
                if (removeBtn) removeBtn.style.display = "none";
            }
            imgPreview.style.display = 'block';
        };
    }

    // Set mode details
    if (mode === 'add') {
        title.textContent = 'Add Student';
        saveBtn.textContent = 'Save';
        var methodInput = document.getElementById('_method_input');
        if (methodInput) methodInput.remove();
        fetch('/Head/students/next-id')
            .then(response => response.json())
            .then(data => {
                document.getElementById('s_id_display').textContent = data.next_id;
                document.getElementById('s_id').value = data.next_id;
            });
    } else if (mode === 'edit' && studentData) {
        title.textContent = 'Edit Student';
        saveBtn.textContent = 'Update';

        if (!document.getElementById('_method_input')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            methodInput.id = '_method_input';
            form.appendChild(methodInput);
        }

        // Fill in fields from studentData
        document.getElementById('s_id_display').textContent = studentData.id_num || '';
        document.getElementById('s_id').value = studentData.id_num || '';
        document.getElementById('first_name').value = studentData.fname || '';
        document.getElementById('middle_name').value = studentData.mname || '';
        document.getElementById('last_name').value = studentData.lname || '';
        document.getElementById('suffix').value = studentData.suffix || '';
        document.getElementById('email').value = studentData.email || '';
        document.getElementById('contact_num').value = studentData.mobile_num || '';

        if (studentData.gender === 'Male' || studentData.sex === 'Male') {
            document.getElementById('sex_male').checked = true;
        } else if (studentData.gender === 'Female' || studentData.sex === 'Female') {
            document.getElementById('sex_female').checked = true;
        }

        document.getElementById('bod').value = studentData.bod || '';
        document.getElementById('address').value = studentData.address || '';
        document.getElementById('religion').value = studentData.religion || '';
        document.getElementById('civil_status').value = studentData.civil_status || '';

        const educLevelEl = document.getElementById('educ_level');
        const yearLevelEl = document.getElementById('year_level');
        if (educLevelEl) {
            educLevelEl.value = studentData.educ_level || '';
            updateYearLevel();
        }
        if (yearLevelEl) {
            yearLevelEl.value = studentData.year_level || '';
        }

        document.getElementById('father_name').value = studentData.father_name || '';
        document.getElementById('mother_name').value = studentData.mother_name || '';
        document.getElementById('guardian_name').value = studentData.guardian_name || '';
        document.getElementById('relationship').value = studentData.relationship || '';
        document.getElementById('guardian_contact').value = studentData.guardian_contact || '';
        document.getElementById('guardian_email').value = studentData.guardian_email || '';

        if (studentData.image_url) {
            imgPreview.src = studentData.image_url;
            if (removeBtn) removeBtn.style.display = "block";
        } else {
            imgPreview.src = "/images/user/default.png";
            if (removeBtn) removeBtn.style.display = "none";
        }
    }

    modal.style.display = 'block';
    console.log('Add/Edit modal opened');

    const closeBtn = document.getElementById('closeAddModalBtn');
    if (closeBtn) {
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeAddModal();
        };
    }

    modal.onclick = function(event) {
        if (event.target === modal) {
            window.closeAddModal();
        }
    };

    const modalContent = modal.querySelector('.modal-content');
    if (modalContent) {
        modalContent.onclick = function(e) {
            e.stopPropagation();
        };
    }

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
            method = 'POST';
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

    };

// Close modal
window.closeAddModal = function closeAddModal() {
    var modal = document.getElementById('addStudentModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('Add/Edit modal closed');
    }
};


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
                    ['view_year_level', data.year_level],
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


window.closeArchiveModal = function closeArchiveModal() {
    var modal = document.getElementById('archiveStudentModal');
    if (modal) modal.style.display = 'none';
    window.currentArchiveSId = null;
    var idDisplay = document.getElementById('archiveStudentIdDisplay');
    if (idDisplay) idDisplay.textContent = '';
};

// ================================
// Archive Student Functions
// ================================

// Archive Only
window.archiveStudentOnly = function archiveStudentOnly() {
    if (!window.currentArchiveSId) return;
    var status = document.getElementById('archive_status').value;
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
    .then(res => res.json())
    .then(data => {
        window.closeArchiveModal();
        // Always refresh the student table to show updated status
        var tableList = document.getElementById('student-list');
        if (tableList) {
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('student-list');
                    if (newTable && tableList) {
                        tableList.innerHTML = newTable.innerHTML;
                    }
                });
        }
        if (!data.success) {
            alert(data.error || data.message || 'Archiving failed.');
        }
    })
    .catch(err => {
        window.closeArchiveModal();
        console.log('Archive failed:', err.message);
    });
};

window.archiveStudentAndDisable = function archiveStudentAndDisable() {
    if (!window.currentArchiveSId) return;
    var status = document.getElementById('archive_status').value;
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
    .then(res => res.json())
    .then(data => {
        window.closeArchiveModal();
        // Always refresh the student table to show updated status
        var tableList = document.getElementById('student-list');
        if (tableList) {
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('student-list');
                    if (newTable && tableList) {
                        tableList.innerHTML = newTable.innerHTML;
                    }
                });
        }
        if (!data.success) {
            alert(data.error || data.message || 'Archiving & disabling failed.');
        }
    })
    .catch(err => {
        window.closeArchiveModal();
        console.log('Archive & disable failed:', err.message);
    });
};
// ================================
// Helper: Update Student Status Cell
// ================================
function updateStudentStatusCell(s_id, newStatus) {
    var row = document.querySelector(`#student-list tr[data-id="${s_id}"]`);
    if (row) {
        var statusCell = row.querySelector('td[data-status]');
        if (statusCell) {
            statusCell.textContent = newStatus;

            // reset classes
            statusCell.className = '';
            if (newStatus.toLowerCase() === 'enrolled') {
                statusCell.classList.add('badge', 'bg-success');
            } else {
                statusCell.classList.add('badge', 'bg-danger');
            }
        }
    } else {
        // fallback: reload table content via AJAX
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                document.querySelector('#student-list').innerHTML = html;
            });
    }
}

// ================================
// Archive Modal
// ================================
window.openArchiveStudentModal = function openArchiveStudentModal(s_id) {
    var modal = document.getElementById('archiveStudentModal');
    var input = document.getElementById('archive_s_id');
    var display = document.getElementById('archiveStudentIdDisplay');
    window.currentArchiveSId = s_id;
    if (input) input.value = s_id;
    if (display) display.textContent = `(${s_id})`;
    if (modal) modal.style.display = 'block';
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
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportDropdownBtn');
    const exportMenu = document.getElementById('exportDropdownMenu');
    if (exportBtn && exportMenu) {
        exportBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exportMenu.classList.toggle('show');
        });
        document.addEventListener('mousedown', function(e) {
            if (exportMenu.classList.contains('show')) {
                if (!exportMenu.contains(e.target) && e.target !== exportBtn) {
                    exportMenu.classList.remove('show');
                }
            }
        });
        document.querySelectorAll('.dropdown-item').forEach(function(item) {
            item.addEventListener('mouseover', function() {
                this.style.background = '#f3f4f6';
            });
            item.addEventListener('mouseout', function() {
                this.style.background = 'none';
            });
        });
    }
});

function downloadExport(format) {
    var exportMenu = document.getElementById('exportDropdownMenu');
    if (exportMenu) exportMenu.classList.remove('show');
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

