// Hide password field in edit mode, show in add mode
function togglePasswordField(show) {
    var pw = document.getElementById('passwordFieldWrapper');
    if (pw) pw.style.display = show ? '' : 'none';
    if (show && document.getElementById('counselor_password')) document.getElementById('counselor_password').required = true;
    else if(document.getElementById('counselor_password')) document.getElementById('counselor_password').required = false;
}

// Patch JS modal logic to call this
const origSetCounselorFormMode = window.setCounselorFormMode;
window.setCounselorFormMode = function(mode, data) {
    if (typeof origSetCounselorFormMode === 'function') origSetCounselorFormMode(mode, data);
    togglePasswordField(mode === 'add');
};

// On page load, default to add mode
document.addEventListener('DOMContentLoaded', function() {
    togglePasswordField(true);
});

// Helper: set form to add or edit mode
function setCounselorFormMode(mode, data = null) {
    // Set modal title based on mode
    var modalTitle = document.querySelector('.counselor-modal-title');
    if (modalTitle) {
        if (mode === 'edit') {
            modalTitle.textContent = 'Edit Counselor Details';
        } else {
            modalTitle.textContent = 'New Counselor';
        }
    }
    // Use correct form ID for add/edit modal
    const form = document.getElementById('counselorForm');
    let methodInput = document.getElementById('_method_patch');
        // If switching to add mode, set action to store, remove any leftover PATCH/_method input and clear fields
    if (mode === 'add') {
            if (form) form.action = '/Head/counselors';
        // remove spoofed method input if present
        if (methodInput && methodInput.parentNode) {
            methodInput.parentNode.removeChild(methodInput);
            methodInput = null;
        }
        // Clear common input variants used in add/edit (some templates use different IDs)
        const idsToClear = [
            'counselor_id_display', 'counselor_id', 'counselor_lname', 'counselor_fname', 'counselor_mname',
            'counselor_email', 'counselor_contact_num', 'counselor_password',
            'lname', 'fname', 'mname', 'email', 'contact_num', 'password'
        ];
        idsToClear.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if ('value' in el) el.value = '';
            else el.textContent = '';
        });
        // Clear sex radio buttons (both naming variants)
        const sexRadios = document.querySelectorAll('input[name="sex"], input[name="counselor_sex"]');
        sexRadios.forEach(r => r.checked = false);
        const sexMale = document.getElementById('counselor_sex_male');
        const sexFemale = document.getElementById('counselor_sex_female');
        if (sexMale) sexMale.checked = false;
        if (sexFemale) sexFemale.checked = false;

        // Reset image preview, file inputs and hidden crop data
        const imgPreview = document.getElementById('counselorImage') || document.querySelector('.counselor-image-box');
        if (imgPreview && imgPreview.getAttribute && imgPreview.getAttribute('data-default')) {
            imgPreview.src = imgPreview.getAttribute('data-default');
        }
        const fileInput = document.getElementById('counselor_profile_image');
        if (fileInput) fileInput.value = '';
        const hiddenInput = document.getElementById('counselorCroppedImageData');
        if (hiddenInput) hiddenInput.value = '';
        const fileChosen = document.getElementById('counselor-file-chosen');
        if (fileChosen) fileChosen.textContent = 'No file chosen';
        const deleteBtn = document.getElementById('remove-counselor-image');
        if (deleteBtn) deleteBtn.style.display = 'none';

        // Hide activate button when adding
        const activateBtn = document.getElementById('activateCounselorBtn');
        if (activateBtn) activateBtn.style.display = 'none';
    }
    if (mode === 'edit' && data) {
    // Set action to the update route for this counselor id (PUT)
    const cId = data.c_id || data.id || '';
    if (form) form.action = `/Head/counselors/${cId}`;
        if (!methodInput) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = 'PUT';
            input.id = '_method_patch';
            form.appendChild(input);
            methodInput = input;
        } else {
            methodInput.value = 'PUT';
        }
        // Fill fields (use add/edit modal field IDs)
        document.getElementById('counselor_id_display').textContent = data.c_id || '';
        document.getElementById('counselor_id').value = data.c_id || '';
        document.getElementById('fname').value = data.fname || '';
        document.getElementById('mname').value = data.mname || '';
        document.getElementById('lname').value = data.lname || '';
        document.getElementById('email').value = data.email || '';
        document.getElementById('contact_num').value = data.contact_num || '';
        if (data.sex) {
            var maleRadio = document.querySelector('input[name="sex"][value="Male"]');
            var femaleRadio = document.querySelector('input[name="sex"][value="Female"]');
            if (maleRadio) maleRadio.checked = (data.sex.toLowerCase() === 'male');
            if (femaleRadio) femaleRadio.checked = (data.sex.toLowerCase() === 'female');
        }
        document.getElementById('password').value = '';
        if (data.profile_image_url) {
            document.getElementById('counselorImage').src = data.profile_image_url;
        }
        var activateBtn = document.getElementById('activateCounselorBtn');
        if (activateBtn) {
            // Show activate button if from past table (inactive)
            if (data && data._fromPastTable) {
                activateBtn.style.display = '';
            } else {
                activateBtn.style.display = 'none';
            }
        }

        // Activate counselor status logic
        window.activateCounselorStatus = function() {
            // Use the correct input for counselor ID in the modal
            var c_id_input = document.getElementById('counselor_id');
            var c_id = c_id_input ? c_id_input.value : '';
            if (!c_id) {
                createToast('error', 'Counselor ID not found.');
                return;
            }
            fetch(`/Head/counselors/${c_id}/activate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ c_id: c_id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.closeFormModal();
                    createToast('success', 'Counselor activated successfully!');
                    setTimeout(() => { refreshCounselorUI(); }, 800);
                } else {
                    createToast('error', data.error || 'Failed to activate counselor.');
                }
            })
            .catch(() => {
                createToast('error', 'Failed to activate counselor.');
            });
        }
    }

    // Hide password field in edit mode, show in add mode
    var pwWrapper = document.getElementById('password').parentElement;
    if (mode === 'edit') {
        if (pwWrapper) pwWrapper.style.display = 'none';
    } else {
        if (pwWrapper) pwWrapper.style.display = '';
    }

    // Show delete image button in edit mode if image is not default
    var imgPreview = document.getElementById('counselorImage');
    var deleteBtn = document.getElementById('remove-counselor-image');
    if (mode === 'edit' && imgPreview && deleteBtn) {
        if (data && data.profile_image_url && !data.profile_image_url.includes('default.jpg')) {
            deleteBtn.style.display = 'inline-block';
        } else {
            deleteBtn.style.display = 'none';
        }
    } else if (deleteBtn) {
        deleteBtn.style.display = 'none';
    }

    // Show file name in edit mode if image exists
    var fileChosen = document.getElementById('counselor-file-chosen');
    if (mode === 'edit' && data && data.profile_image_url && !data.profile_image_url.includes('default.jpg')) {
        // Extract filename from URL
        var parts = data.profile_image_url.split('/');
        var filename = parts[parts.length - 1];
        if (fileChosen) fileChosen.textContent = filename;
    } else if (fileChosen) {
        fileChosen.textContent = "No file chosen";
    }
}

// Edit Counselor: open add modal in edit mode with data
window.editCounselorFromView = function(c_id) {
    var fromPastTable = arguments.length > 1 ? arguments[1] : false;
    window.closeViewCounselorModal();
    fetch(`/Head/counselors/${c_id}/json`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                createToast('error', 'Counselor not found!');
                return;
            }
            if (fromPastTable) data._fromPastTable = true;
            setCounselorFormMode('edit', data);
            var modal = document.getElementById('formModal');
            if (modal) modal.style.display = 'block';
    });
};

document.addEventListener('DOMContentLoaded', function() {
    window.openAddCounselorModal = window.openFormModal;

    // Counselor image logic (preview + filename + delete + crop)
    const imageInput = document.getElementById('counselor_profile_image');
    const imgPreview = document.getElementById('counselorImage');
    const fileChosen = document.getElementById('counselor-file-chosen');
    const deleteBtn = document.getElementById('remove-counselor-image');
    const hiddenInput = document.getElementById('counselorCroppedImageData');

    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                // Show crop modal instead of direct preview
                const cropModal = document.getElementById('cropCounselorImageModal');
                const cropPreview = document.getElementById('counselorCropperPreview');
                if (cropPreview && cropModal) {
                    cropPreview.src = URL.createObjectURL(file);
                    cropModal.style.display = 'block';
                }
                if (fileChosen) fileChosen.textContent = file.name;
                // Hide delete button until crop is applied
                if (deleteBtn) deleteBtn.style.display = 'none';
            } else {
                if (imgPreview) imgPreview.src = imgPreview.getAttribute('data-default');
                if (fileChosen) fileChosen.textContent = "No file chosen";
                if (deleteBtn) deleteBtn.style.display = 'none';
            }
        });
        if (imgPreview) {
            imgPreview.src = imgPreview.getAttribute('data-default');
            imgPreview.style.display = 'block';
        }
    }

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            // Reset image, file input, file name, and hide delete button
            if (imgPreview && imgPreview.getAttribute('data-default')) {
                imgPreview.src = imgPreview.getAttribute('data-default');
            }
            if (imageInput) {
                imageInput.value = "";
            }
            if (fileChosen) {
                fileChosen.textContent = "No file chosen";
            }
            if (hiddenInput) {
                hiddenInput.value = "";
            }
            deleteBtn.style.display = 'none';
        });
    }

    // Add modal: reset to add mode when closed
    var addModal = document.getElementById('formModal');
    var addCloseBtn = addModal ? addModal.querySelector('.counselor-close') : null;
    if (addCloseBtn && addModal) {
        // Close modal when clicking the X button
        addCloseBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeFormModal();
        };
        // Close modal when clicking outside modal content
        addModal.onclick = function(event) {
            if (event.target === addModal) {
                window.closeFormModal();
            }
        };
        var addModalContent = addModal.querySelector('.counselor-modal-content');
        if (addModalContent) {
            addModalContent.onclick = function(e) {
                e.stopPropagation();
            };
        }
    }

    // View modal close logic
    var viewModal = document.getElementById('viewCounselorModal');
    var viewCloseBtn = viewModal ? viewModal.querySelector('.counselor-close, .close') : null;
    if (viewCloseBtn && viewModal) {
        viewCloseBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeViewCounselorModal();
        };
        // Close modal when clicking outside modal content
        viewModal.onclick = function(event) {
            if (event.target === viewModal) {
                window.closeViewCounselorModal();
            }
        };
        var viewModalContent = viewModal.querySelector('.counselor-modal-content');
        if (viewModalContent) {
            viewModalContent.onclick = function(e) {
                e.stopPropagation();
            };
        }
    }

    var editBtn = document.getElementById('editCounselorBtn');
    if (editBtn) {
        editBtn.onclick = function() {
            var c_id = document.getElementById('view_c_id_display').textContent;
            if (c_id) {
                window.editCounselorFromView(c_id);
            }
        };
    }
});

// Intercept counselor form submit to use AJAX and show toasts
function attachCounselorFormHandler() {
    const counselorForm = document.getElementById('counselorForm');
    if (!counselorForm) return;
    if (counselorForm._ajaxAttached) return; // idempotent

    counselorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const action = form.getAttribute('action') || window.location.href;
        const formData = new FormData(form);

        fetch(action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then((res) => res.json().catch(() => ({ success: false, message: 'Unexpected server response' })))
        .then((data) => {
            if (data && data.success) {
                createToast('success', data.message || 'Counselor saved successfully!');
                // hide modal and refresh UI after short delay to allow toast to show
                window.closeFormModal();
                setTimeout(() => { refreshCounselorUI(); }, 800);
            } else {
                const msg = (data && data.message) ? data.message : 'Failed to save counselor.';
                createToast('error', msg);
            }
        })
        .catch((err) => {
            console.error('Counselor form submit error:', err);
            createToast('error', 'An unexpected error occurred.');
        });
    });

    counselorForm._ajaxAttached = true;
}

// Attach immediately and also on DOMContentLoaded in case script runs early/late.
attachCounselorFormHandler();
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachCounselorFormHandler);
}

window.openFormModal = function () {
    setCounselorFormMode('add');
    // Clear all fields explicitly to prevent leftover data
    var c_id_display = document.getElementById('counselor_id_display');
    if (c_id_display) c_id_display.textContent = '';
    var c_id = document.getElementById('counselor_id');
    if (c_id) c_id.value = '';
    var lname = document.getElementById('counselor_lname');
    if (lname) lname.value = '';
    var fname = document.getElementById('counselor_fname');
    if (fname) fname.value = '';
    var mname = document.getElementById('counselor_mname');
    if (mname) mname.value = '';
    var email = document.getElementById('counselor_email');
    if (email) email.value = '';
    var contact_num = document.getElementById('counselor_contact_num');
    if (contact_num) contact_num.value = '';
    var sex_male = document.getElementById('counselor_sex_male');
    if (sex_male) sex_male.checked = false;
    var sex_female = document.getElementById('counselor_sex_female');
    if (sex_female) sex_female.checked = false;
    var password = document.getElementById('counselor_password');
    if (password) password.value = '';
    var imgPreview = document.getElementById('counselorImage');
    if (imgPreview && imgPreview.getAttribute('data-default')) {
        imgPreview.src = imgPreview.getAttribute('data-default');
    }
    var activateBtn = document.getElementById('activateCounselorBtn');
    if (activateBtn) activateBtn.style.display = 'none';
    // Fetch next available counselor ID and set it
    fetch('/Head/counselors/next-id')
        .then(response => response.json())
        .then(data => {
            if (c_id_display) c_id_display.textContent = data.next_c_id;
            if (c_id) c_id.value = data.next_c_id;
        });
    var modal = document.getElementById('formModal');
    if (modal) {
        modal.style.display = 'block';
    } else {
        console.error("Modal not found!");
    }
};

window.closeFormModal = function () {
    var modal = document.getElementById('formModal');
    if (modal) {
        modal.style.display = 'none';
    } else {
        console.error("Modal not found!");
    }
};


// Edit Counselor Modal logic
function openEditCounselorModal(counselorId) {
    // Show modal
    document.getElementById('editCounselorModal').style.display = 'flex';
    // Set form action with correct ID
    var form = document.getElementById('editCounselorForm');
    form.action = '/Head/counselors/' + counselorId;
    // Also set hidden input value
    document.getElementById('edit_c_id').value = counselorId;
    // You can also populate other fields here if needed
}
function closeFormModal() {
    document.getElementById('editCounselorModal').style.display = 'none';
}


//____________________________________________________________________________________


// View Counselor Modal 
window.openViewCounselModal = function(c_id, readonly = false) {
    fetch(`/Head/counselors/${c_id}/json`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                createToast('error', 'Counselor not found!');
                return;
            }
            document.getElementById('view_c_id_display').textContent = data.c_id || '';
            document.getElementById('view_counselor_fname').textContent = data.fname || '';
            document.getElementById('view_counselor_mname').textContent = data.mname || '';
            document.getElementById('view_counselor_lname').textContent = data.lname || '';
            document.getElementById('view_counselor_sex').textContent = data.sex || '';
            document.getElementById('view_counselor_email').textContent = data.email || '';
            document.getElementById('view_counselor_contact_num').textContent = data.contact_num || '';
            var img = document.getElementById('viewCounselorImage');
            if (img && data.profile_image_url) {
                img.src = data.profile_image_url;
            }
            var modal = document.getElementById('viewCounselorModal');
            if (modal) {
                modal.style.display = 'block';
            }
            // Show/hide buttons based on readonly
            var editBtn = document.getElementById('editCounselorBtn');
            var archiveBtn = document.getElementById('archiveCounselorBtn');
            if (readonly) {
                if (editBtn) editBtn.style.display = 'none';
                if (archiveBtn) archiveBtn.style.display = 'none';
            } else {
                if (editBtn) editBtn.style.display = '';
                if (archiveBtn) archiveBtn.style.display = '';
            }
        });
};

function openViewCounselModalReadonly(c_id) {
    openViewCounselModal(c_id, true);
}

window.closeViewCounselorModal = function() {
    var modal = document.getElementById('viewCounselorModal');
    if (modal) {
        modal.style.display = 'none';
    }
};
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('viewCounselorModal');
    var closeBtn = modal ? modal.querySelector('.close') : null;
    if (closeBtn && modal) {
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeViewCounselorModal();
        };
        modal.onclick = function(event) {
            if (event.target === modal) {
                window.closeViewCounselorModal();
            }
        };
        var modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.onclick = function(e) {
                e.stopPropagation();
            };
        }
    }
});

// Archive Counselor logic
window.showArchiveConfirmModal = function(c_id) {
    window._archiveCounselorId = c_id;
    // Get counselor name from view modal
    var name = '';
    var fname = document.getElementById('view_counselor_fname');
    var lname = document.getElementById('view_counselor_lname');
    if (fname && lname) {
        name = fname.textContent + ' ' + lname.textContent;
    } else {
        name = c_id;
    }
    var nameSpan = document.getElementById('archiveCounselorName');
    if (nameSpan) nameSpan.textContent = name.trim();
    var modal = document.getElementById('archiveConfirmModal');
    if (modal) modal.style.display = 'block';
};
window.closeArchiveConfirmModal = function() {
    var modal = document.getElementById('archiveConfirmModal');
    if (modal) modal.style.display = 'none';
    window._archiveCounselorId = null;
};
window.confirmArchiveCounselor = function() {
    var c_id = window._archiveCounselorId;
    if (!c_id) return;
    fetch('/Head/counselors/' + c_id + '/archive', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ c_id: c_id })
    })
        .then(response => response.json())
        .then(data => {
                if (data.success) {
                    window.closeArchiveConfirmModal();
                    window.closeViewCounselorModal();
                    createToast('success', 'Counselor deactivated successfully!');
                    setTimeout(() => { refreshCounselorUI(); }, 800);
                } else {
                let msg = data.error || 'Failed to archive counselor.';
                showArchiveError(msg);
                createToast('error', msg);
            }
        })
        .catch(() => {
            const msg = 'Failed to archive counselor.';
            showArchiveError(msg);
            createToast('error', msg);
        });
};

window.showArchiveError = function(msg) {
    var modal = document.getElementById('archiveConfirmModal');
    if (modal) {
        let err = modal.querySelector('.archive-error-msg');
        if (!err) {
            err = document.createElement('div');
            err.className = 'archive-error-msg';
            err.style.color = '#e11d48';
            err.style.margin = '12px 0 0 0';
            err.style.textAlign = 'center';
            modal.querySelector('.counselor-modal-content').appendChild(err);
        }
        err.textContent = msg;
    } else {
        alert(msg);
    }
};

//_____________________________________________________________________________________

document.addEventListener('DOMContentLoaded', function() {
    let counselorCropper;
    const fileInput = document.getElementById('counselor_profile_image');
    const cropModal = document.getElementById('cropCounselorImageModal');
    const previewImg = document.getElementById('counselorCropperPreview');
    const closeBtn = document.getElementById('closeCropCounselorModal');
    const cancelBtn = document.getElementById('cancelCropCounselorBtn');
    const applyBtn = document.getElementById('applyCropCounselorBtn');
    // Always reference the image with class 'counselor-image-box' and id 'counselorImage'
    const mainPreview = document.querySelector('.counselor-image-box');
    const hiddenInput = document.getElementById('counselorCroppedImageData');

    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                cropModal.style.display = 'block';

                if (counselorCropper) {
                    counselorCropper.destroy();
                }
                counselorCropper = new Cropper(previewImg, {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            }
        });
    }

    function closeCropModal() {
        cropModal.style.display = 'none';
        if (counselorCropper) {
            counselorCropper.destroy();
            counselorCropper = null;
        }
        // Only reset image/file if canceling, not after cropping
        if (window._cancelCrop) {
            if (mainPreview && mainPreview.getAttribute('data-default')) {
                mainPreview.src = mainPreview.getAttribute('data-default');
            }
            if (fileInput) {
                fileInput.value = "";
            }
            var fileChosen = document.getElementById('counselor-file-chosen');
            if (fileChosen) {
                fileChosen.textContent = "No file chosen";
            }
            var deleteBtn = document.getElementById('remove-counselor-image');
            if (deleteBtn) {
                deleteBtn.style.display = 'none';
            }
        }
        window._cancelCrop = false;
    }

    if (closeBtn) closeBtn.onclick = closeCropModal;
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            window._cancelCrop = true;
            closeCropModal();
        };
    }

    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            if (counselorCropper) {
                const canvas = counselorCropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });
                const base64Data = canvas.toDataURL('image/png');
                const previewImg = document.querySelector('.counselor-image-box');
                if (previewImg) {
                    previewImg.src = base64Data;
                }
                if (hiddenInput) {
                    hiddenInput.value = base64Data;
                }
                window._cancelCrop = false;
                closeCropModal();
                // Show delete button after cropping
                var deleteBtn = document.getElementById('remove-counselor-image');
                if (deleteBtn) {
                    deleteBtn.style.display = 'inline-block';
                }
            }
        });
    }
});

// Refresh counselor cards and past-counselor table via AJAX
function refreshCounselorUI() {
    const url = new URL(window.location.origin + '/Head/counselors');
    // Preserve search query for past counselors if present
    const searchInput = document.getElementById('counselor-search-input');
    if (searchInput && searchInput.value) url.searchParams.set('search', searchInput.value);

    fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            // Replace profiles container (cards)
            const newProfiles = doc.querySelector('.profiles-container');
            const profilesContainer = document.querySelector('.profiles-container');
            if (newProfiles && profilesContainer) profilesContainer.innerHTML = newProfiles.innerHTML;
            // Replace past counselors table container
            const newTableContainer = doc.querySelector('.past-counselor-table-container');
            const oldTableContainer = document.querySelector('.past-counselor-table-container');
            if (newTableContainer && oldTableContainer) oldTableContainer.innerHTML = newTableContainer.innerHTML;
        })
        .catch(err => console.error('Failed to refresh counselors UI:', err));
}
