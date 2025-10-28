

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
            studentImage.src = '/images/user/default.png'; 
            imageInput.value = '';
            if (fileChosen) fileChosen.textContent = 'No file chosen';
            if (deleteField) deleteField.value = '1';
            // clear any cropped image data so saving won't re-add it
            const croppedEl = document.getElementById('cropped_image_data');
            if (croppedEl) croppedEl.value = '';
            removeBtn.style.display = 'none';
        });
    }
});


// Add/Edit Modal logic
window.openAddEditModal = function openAddEditModal(mode, studentData = null) {
    if (mode === 'edit' && studentData && studentData.s_id && Object.keys(studentData).length === 1) {
        fetch(`/Head/students/${studentData.s_id}/json`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire('Error', 'Student not found!', 'error');
                    return;
                }
                window.openAddEditModal('edit', data);
            })
            .catch(err => {
                console.error("Failed to fetch student:", err);
                Swal.fire('Error', 'Error fetching student details.', 'error');
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

    form.reset();
    // reset delete flag and cropped data on modal open/reset
    try {
        const deleteFieldOnOpen = document.getElementById('delete_profile_image');
        if (deleteFieldOnOpen) deleteFieldOnOpen.value = '0';
        const croppedOnOpen = document.getElementById('cropped_image_data');
        if (croppedOnOpen) croppedOnOpen.value = '';
    } catch (e) {
        // ignore
    }
    if (fileChosen) fileChosen.textContent = 'No file chosen';
    if (imageInput) imageInput.value = "";

    if (mode === 'add') {
        imgPreview.src = '/images/user/default.png'; 
        if (removeBtn) removeBtn.style.display = 'none';
    } else {
        imgPreview.src = '';
        if (removeBtn) removeBtn.style.display = 'none';
    }

    if (imageInput) {
        imageInput.onchange = function(event) {
            const [file] = event.target.files;
            if (file) {
                // store original filename for server
                const croppedNameEl = document.getElementById('cropped_image_name');
                if (croppedNameEl) croppedNameEl.value = file.name;
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
                        // clear cropped data when user cancels
                        const croppedElCancel = document.getElementById('cropped_image_data');
                        if (croppedElCancel) croppedElCancel.value = '';
                        // clear name
                        const croppedNameElCancel = document.getElementById('cropped_image_name');
                        if (croppedNameElCancel) croppedNameElCancel.value = '';
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
                const croppedNameEl = document.getElementById('cropped_image_name');
                if (croppedNameEl) croppedNameEl.value = '';
            }
            imgPreview.style.display = 'block';
        };
    }

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

        document.getElementById('s_id_display').textContent = studentData.s_id || studentData.id_num || '';
        document.getElementById('s_id').value = studentData.s_id || studentData.id_num || '';
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
    // Ensure cropped original filename and delete flag are present in FormData
    const croppedNameEl = document.getElementById('cropped_image_name');
    if (croppedNameEl && croppedNameEl.value) formData.set('cropped_image_name', croppedNameEl.value);
    const deleteFlagEl = document.getElementById('delete_profile_image');
    if (deleteFlagEl) formData.set('delete_photo', deleteFlagEl.value || '0');
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
                // Determine student id (s_id)
                let sid = null;
                if (data.student && (data.student.s_id || data.student.id_num)) {
                    sid = data.student.s_id || data.student.id_num;
                } else if (studentData && (studentData.s_id || studentData.id_num)) {
                    sid = studentData.s_id || studentData.id_num;
                } else if (form && form.querySelector('#s_id')) {
                    sid = form.querySelector('#s_id').value;
                }

                // 1) If server returned the new filename, use it
                if (data.student && (data.student.profile_image || data.student.profile_image_name)) {
                    const imgFile = data.student.profile_image || data.student.profile_image_name;
                    if (sid && imgFile) updateStudentImageInDOM(sid, imgFile);
                } else {
                    // If the form indicated deletion, immediately set the table image to default
                    const deleteFlagEl = form.querySelector('#delete_profile_image');
                    const isDeleted = deleteFlagEl && (deleteFlagEl.value === '1' || deleteFlagEl.value === 1);
                    if (sid && isDeleted) {
                        // Set table image to default with cache-busting
                        const imgEl = document.querySelector(`#student-list img.profile-thumb[data-sid='${sid}']`);
                        if (imgEl) imgEl.src = window.location.origin + '/images/user/default.jpg?v=' + Date.now();
                    }
                    // 2) Fallback: if the modal has cropped image data, use that data URL to update the table image instantly
                    const croppedDataEl = form.querySelector('#cropped_image_data');
                    if (sid && croppedDataEl && croppedDataEl.value) {
                        const imgEl = document.querySelector(`#student-list img.profile-thumb[data-sid='${sid}']`);
                        if (imgEl) imgEl.src = croppedDataEl.value;
                    } else {
                        // 3) Fallback: if the file input has a selected file, read it locally and set data URL
                        const fileInputEl = form.querySelector('#profile_image');
                        if (sid && fileInputEl && fileInputEl.files && fileInputEl.files[0]) {
                            const file = fileInputEl.files[0];
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imgEl = document.querySelector(`#student-list img.profile-thumb[data-sid='${sid}']`);
                                if (imgEl) imgEl.src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }

                window.closeAddModal();
                refreshStudentTable();
                createToast('success', mode === 'add' ? 'Student Added Successfully!' : 'Student Updated Successfully!');
            } else {
                createToast('error', 'Save failed.');
            }
        })
        .catch(() => {
            createToast('error', 'An error occurred while saving.');
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

            // Fetch case records and display + offense summary
            fetch(`/Head/students/${s_id}/cases`)
                .then(response => response.json())
                .then(cases => {
                    console.log('Case records response:', cases);
                    const caseRecordsDiv = document.getElementById('view_case_records');
                    const template = document.getElementById('case_record_template');
                    const viewMoreBtn = document.getElementById('viewMoreCasesBtn');
                    const offenseContainer = document.getElementById('offenseSummaryContainer');
                    const offenseTotalEl = document.getElementById('offenseTotal');

                    // Helper: compute school year start (assume school year starts July 1)
                    function getSchoolYearLabel(dateStr) {
                        if (!dateStr) return null;
                        const d = new Date(dateStr);
                        if (isNaN(d.getTime())) return null;
                        const month = d.getMonth(); // 0-11
                        let startYear = (month >= 6) ? d.getFullYear() : d.getFullYear() - 1; // July or later => startYear = year
                        return `${startYear}-${startYear + 1}`;
                    }

                    // Build offense counts grouped by school year
                    const counts = {};
                    let minYear = null;
                    let maxYear = null;

                    if (Array.isArray(cases) && cases.length > 0) {
                        cases.forEach(c => {
                            const dateStr = c.filed_date || c.date_reported || c.created_at || c.date;
                            const label = getSchoolYearLabel(dateStr) || 'Unknown';
                            if (label !== 'Unknown') {
                                const start = parseInt(label.split('-')[0], 10);
                                if (minYear === null || start < minYear) minYear = start;
                                if (maxYear === null || start > maxYear) maxYear = start;
                            }
                            counts[label] = (counts[label] || 0) + 1;
                        });
                    }

                    // If we have a range of years, ensure every year in range is represented (0 if none)
                    const now = new Date();
                    const currentStart = (now.getMonth() >= 6) ? now.getFullYear() : now.getFullYear() - 1;
                    if (minYear === null && maxYear === null) {
                        // no cases: show current year only
                        minYear = currentStart;
                        maxYear = currentStart;
                    } else {
                        // ensure we at least include up to currentStart
                        if (maxYear < currentStart) maxYear = currentStart;
                    }

                    // Render offense summary
                    if (offenseContainer) {
                        offenseContainer.innerHTML = '';
                        for (let y = minYear; y <= maxYear; y++) {
                            const label = `${y}-${y + 1}`;
                            const count = counts[label] || 0;
                            const entry = document.createElement('div');
                            entry.className = 'offense-year-row';
                            entry.innerHTML = `<div class="offense-year">${label}</div><div class="offense-count">Offense ${count}</div>`;
                            offenseContainer.appendChild(entry);
                        }
                    }

                    // Total offenses
                    const total = Object.keys(counts).reduce((sum, k) => sum + (counts[k] || 0), 0);
                    if (offenseTotalEl) {
                        offenseTotalEl.textContent = `Total Offenses: ${total}`;
                    }

                    // Render case cards: show up to 2 recent by default, toggle to show all
                    if (caseRecordsDiv) {
                        caseRecordsDiv.innerHTML = '';
                        if (!cases || cases.length === 0) {
                            caseRecordsDiv.innerHTML = '<div style="color:#64748b;">No case records found.</div>';
                            if (viewMoreBtn) viewMoreBtn.style.display = 'none';
                        } else {
                            // show first 2 records by default
                            const initialCount = 1;
                            function renderList(list) {
                                caseRecordsDiv.innerHTML = '';
                                list.forEach(c => {
                                    if (!template) return;
                                    const clone = template.cloneNode(true);
                                    clone.style.display = '';
                                    const titleEl = clone.querySelector('.case-title');
                                    if (titleEl) titleEl.textContent = c.case_title || 'Case';
                                    const sevEl = clone.querySelector('.case-severity');
                                    const sevTextEl = sevEl ? sevEl.querySelector('.badge-text') : null;
                                    if (sevTextEl) {
                                        // Map server severity to color classes or inline color
                                        let color = '#2563eb';
                                        if (c.severity && (c.severity.toLowerCase() === 'grave' || c.severity.toLowerCase() === 'severe')) color = '#e11d48';
                                        else if (c.severity && c.severity.toLowerCase() === 'minor') color = '#10b981';
                                        else if (c.severity && c.severity.toLowerCase() === 'intermediate') color = '#f59e42';
                                        sevTextEl.textContent = c.severity || 'N/A';
                                        sevTextEl.style.color = color;
                                        sevTextEl.style.fontWeight = '600';
                                    }
                                    const dateEl = clone.querySelector('.case-date');
                                    if (dateEl) dateEl.innerHTML = `<span style='font-weight:500;'>Date:</span> ${c.filed_date || c.date_reported || 'N/A'}`;
                                    const statusEl = clone.querySelector('.case-status');
                                    if (statusEl) statusEl.innerHTML = `<span style='font-weight:500;'>Status:</span> ${c.status || 'N/A'}`;
                                    const descEl = clone.querySelector('.case-description') || clone.querySelector('.record-description');
                                    if (descEl) descEl.textContent = c.description || '';
                                    caseRecordsDiv.appendChild(clone);
                                });
                            }

                            // initial render
                            const initialList = cases.slice(0, initialCount);
                            renderList(initialList);

                            // View more toggle (show all / collapse)
                            if (viewMoreBtn) {
                                viewMoreBtn.style.display = (cases.length >= 2) ? 'inline-block' : 'none';
                                viewMoreBtn.textContent = 'View More...';
                                viewMoreBtn.dataset.expanded = 'false';
                                viewMoreBtn.onclick = function(e) {
                                    e.preventDefault();
                                    const expanded = viewMoreBtn.dataset.expanded === 'true';
                                    if (!expanded) {
                                        renderList(cases);
                                        viewMoreBtn.textContent = 'Show Less';
                                        viewMoreBtn.dataset.expanded = 'true';
                                    } else {
                                        renderList(initialList);
                                        viewMoreBtn.textContent = 'View More...';
                                        viewMoreBtn.dataset.expanded = 'false';
                                    }
                                };
                            }
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
window.archiveStudentOnly = function archiveStudentOnly() {
    if (!window.currentArchiveSId) return;

    const status = document.getElementById("archive_status").value;
    if (!status) {
        Swal.fire("Missing Status", "Please select a status.", "warning");
        return;
    }

    fetch("/Head/students/archive", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ s_id: window.currentArchiveSId, status }),
    })
        .then(async (res) => {
            const text = await res.text();
            let data = {};
            try {
                data = text ? JSON.parse(text) : {};
            } catch {
                data = {};
            }

            if (data.success || res.ok) {
                updateStudentStatusCell(window.currentArchiveSId, status);
                closeArchiveModal();
                refreshStudentTable();
                createToast('success', 'Student status updated successfully!');
            } else {
                createToast('error', data.error || 'Failed to update student status.');
            }
        })
        .catch((err) => {
            console.error("Fetch error:", err);
            Swal.fire("Error", "An unexpected error occurred.", "error");
        });
};

window.archiveStudentAndDisable = function archiveStudentAndDisable() {
    if (!window.currentArchiveSId) return;

    const status = document.getElementById("archive_status").value;
    if (!status) {
        Swal.fire("Missing Status", "Please select a status.", "warning");
        return;
    }

    fetch("/Head/students/archive-disable", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ s_id: window.currentArchiveSId, status }),
    })
        .then(async (res) => {
            const text = await res.text();
            let data = {};
            try {
                data = text ? JSON.parse(text) : {};
            } catch {
                data = {};
            }

            if (data.success || res.ok) {
                updateStudentStatusCell(window.currentArchiveSId, status);
                closeArchiveModal();
                refreshStudentTable();
                createToast('success', 'Student status updated and account disabled!');
            } else {
                createToast('error', data.error || 'Failed to update student status.');
            }
        })
        .catch((err) => {
            console.error("Fetch error:", err);
            createToast('error', 'An unexpected error occurred.');
        });
};

// ================================
// Helper: Update Student Status Cell
// ================================
function updateStudentStatusCell(s_id, newStatus) {
    const colorMap = {
        Enrolled: "#16a34a",
        Incoming: "#f97316",
        Probation: "#eab308",
        Suspended: "#dc2626",
        Dropped: "#475569",
        Transferred: "#3b82f6",
        Graduated: "#8b5cf6",
        Deceased: "#94a3b8",
        Expelled: "#b91c1c",
    };

    const color = colorMap[newStatus] || "#64748b"; // default gray

    // Find student card in your current responsive layout
    const card = document.querySelector(`#student-list .table-card[data-id="${s_id}"]`);
    if (!card) return;

    // Prefer locating the status column by header text (if table has a header), else fallback to index
    let statusCol = null;

    // If there is a header row in the table wrapper, try to find which column index contains 'Status'
    try {
        const tableRoot = document.querySelector('#student-list .table');
        if (tableRoot) {
            const header = tableRoot.querySelector('.table-header');
            if (header) {
                const headerCols = Array.from(header.querySelectorAll('.table-col'));
                const idx = headerCols.findIndex(h => (h.textContent || '').trim().toLowerCase() === 'status');
                if (idx >= 0) {
                    const cols = card.querySelectorAll('.table-col');
                    statusCol = cols[idx];
                }
            }
        }
    } catch (e) {
        // ignore header parsing errors
    }

    // Fallback: common layout places status in column index 4 (0-based), otherwise try to find by badge
    if (!statusCol) {
        const cols = card.querySelectorAll('.table-col');
        if (cols.length > 4) {
            statusCol = cols[4];
        } else if (cols.length > 3) {
            statusCol = cols[3];
        } else {
            // last resort: find element containing the status badge
            statusCol = card.querySelector('.table-col');
        }
    }

    if (!statusCol) return;

    statusCol.innerHTML = `
        <span style="display:inline-block;
                     padding:4px 14px;
                     border-radius:16px;
                     font-weight:600;
                     background:${color}20;
                     color:${color};
                     border:1px solid ${color};
                     min-width:90px;
                     text-align:center;">
            ${newStatus}
        </span>`;
}


// ================================
// Refresh Student Table (Real-Time)
// ================================
function refreshStudentTable() {
    fetch("/Head/students/table", {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
        .then((res) => {
            if (!res.ok) throw new Error("Failed to fetch updated student list.");
            return res.text();
        })
        .then((html) => {
            const temp = document.createElement("div");
            temp.innerHTML = html.trim();

            const newContent = temp.querySelector(".table");
            const newPagination = temp.querySelector(".pagination");
            const currentList = document.querySelector("#student-list");

            if (currentList && newContent) {
                // Replace table cards
                const existingTable = currentList.querySelector(".table");
                if (existingTable) {
                    existingTable.replaceWith(newContent);
                } else {
                    currentList.appendChild(newContent);
                }

                // Replace pagination (if any)
                const existingPagination = currentList.querySelector(".pagination");
                if (existingPagination && newPagination) {
                    existingPagination.replaceWith(newPagination);
                } else if (newPagination) {
                    currentList.appendChild(newPagination);
                }
            }
        })
        .catch((err) => console.error("Error refreshing table:", err));
}

// Update a single student's profile image in the DOM with cache-busting
function updateStudentImageInDOM(s_id, imageFileName) {
    try {
        const img = document.querySelector(`#student-list img.profile-thumb[data-sid='${s_id}']`);
        if (!img) return;
        const base = window.location.origin + '/images/user/';
        const timestamp = Date.now();
        img.src = base + encodeURIComponent(imageFileName) + '?v=' + timestamp;
        img.setAttribute('data-img', imageFileName);
    } catch (e) {
        console.error('Failed to update student image in DOM', e);
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
    var closeArchiveBtn = document.getElementById('closeArchiveModalBtn');
    if (closeArchiveBtn) {
        closeArchiveBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeArchiveModal();
        };
    }
});

// Show meaning of selected status in archive modal
document.addEventListener('DOMContentLoaded', function() {
    const statusDropdown = document.getElementById('archive_status');
    const statusMeaning = document.getElementById('statusMeaning');

    if (statusDropdown && statusMeaning) {
        const meanings = {
            'Enrolled': 'Student is currently registered and attending classes.',
            'Incoming': 'Student is expected to enroll soon but has not completed registration.',
            'Probation': 'Student is under academic or behavioral monitoring and must improve performance.',
            'Suspended': 'Student is temporarily removed from classes due to disciplinary action.',
            'Dropped': 'Student voluntarily withdrew from the program or course.',
            'Transferred': 'Student has officially moved to another school.',
            'Graduated': 'Student has completed all requirements and successfully finished the program.',
            'Deceased': 'Student is no longer active due to passing away or other permanent reasons.',
            'Expelled': 'Student is permanently removed due to serious disciplinary violations.'
        };

        statusDropdown.addEventListener('change', function() {
            const selected = statusDropdown.value;
            statusMeaning.textContent = meanings[selected] || 'Select a status to see its meaning.';
        });

        // Optional: show meaning on load
        statusMeaning.textContent = meanings[statusDropdown.value] || 'Select a status to see its meaning.';
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
    // Use fetch to check if export is successful before download
    fetch(url.toString(), { method: 'GET' })
        .then(async (res) => {
            if (res.ok) {
                // Download file
                const a = document.createElement('a');
                a.href = url.toString();
                a.setAttribute('download', 'students_export.' + (format === 'pdf' ? 'pdf' : 'xlsx'));
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                createToast('success', 'Export successful!');
            } else {
                let errorMsg = 'Export failed.';
                try {
                    const data = await res.json();
                    errorMsg = data.error || errorMsg;
                } catch {}
                createToast('error', errorMsg);
            }
        })
        .catch(() => {
            createToast('error', 'Export failed.');
        });
}

