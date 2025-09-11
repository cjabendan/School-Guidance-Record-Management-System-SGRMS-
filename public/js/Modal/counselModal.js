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
    var modalTitle = document.querySelector('.add-modal-title.pro-add-title');
    if (modalTitle) {
        if (mode === 'edit') {
            modalTitle.textContent = 'Edit Counselor Details';
        } else {
            modalTitle.textContent = 'New Counselor';
        }
    }
    const form = document.getElementById('addCounselorForm');
    let methodInput = document.getElementById('_method_patch');
    if (mode === 'edit' && data) {
        // Set form action to update route (adjust as needed)
        form.action = `/Head/counselors/update`;
        // Add hidden _method input for PUT
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
        // Fill fields
        document.getElementById('c_id_display').textContent = data.c_id || '';
        document.getElementById('c_id').value = data.c_id || '';
        document.getElementById('counselor_fname').value = data.fname || '';
        document.getElementById('counselor_mname').value = data.mname || '';
        document.getElementById('counselor_lname').value = data.lname || '';
        document.getElementById('counselor_email').value = data.email || '';
        document.getElementById('counselor_contact_num').value = data.contact_num || '';
        // Set sex radio button
        if (data.sex) {
            var maleRadio = document.getElementById('counselor_sex_male');
            var femaleRadio = document.getElementById('counselor_sex_female');
            if (maleRadio) maleRadio.checked = (data.sex.toLowerCase() === 'male');
            if (femaleRadio) femaleRadio.checked = (data.sex.toLowerCase() === 'female');
        }
        // Password field left blank for security
        document.getElementById('counselor_password').value = '';
        // Image preview (optional, not changing file input)
        if (data.profile_image_url) {
            document.getElementById('counselorImage').src = data.profile_image_url;
        }
        // Set status field and show/hide activate button
        var activateBtn = document.getElementById('activateCounselorBtn');
        if (activateBtn) {
            if (data._fromPastTable) {
                activateBtn.style.display = '';
            } else {
                activateBtn.style.display = 'none';
            }
        }
// Activate counselor status logic
window.activateCounselorStatus = function() {
    var c_id = document.getElementById('c_id').value;
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
            location.reload();
        } else {
            alert(data.error || 'Failed to activate counselor.');
        }
    })
    .catch(() => {
        alert('Failed to activate counselor.');
    });
}
    }
}

// Edit Counselor: open add modal in edit mode with data
window.editCounselorFromView = function(c_id) {
    // Accept second argument: fromPastTable
    var fromPastTable = arguments.length > 1 ? arguments[1] : false;
    window.closeViewCounselorModal();
    fetch(`/Head/counselors/${c_id}/json`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Counselor not found!');
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
    // Image preview logic
    const imageInput = document.getElementById('counselor_profile_image');
    const imgPreview = document.getElementById('counselorImage');
    if (imageInput && imgPreview) {
        imageInput.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
            } else {
                imgPreview.src = imgPreview.getAttribute('data-default');
            }
            imgPreview.style.display = 'block';
        });
        imgPreview.src = imgPreview.getAttribute('data-default');
        imgPreview.style.display = 'block';
    }

    // Add modal: reset to add mode when closed
    var addModal = document.getElementById('formModal');
    var addCloseBtn = addModal ? addModal.querySelector('.close') : null;
    if (addCloseBtn && addModal) {
        addCloseBtn.onclick = function(e) {
            e.stopPropagation();
            setCounselorFormMode('add');
            window.closeFormModal();
        };
        addModal.onclick = function(event) {
            if (event.target === addModal) {
                setCounselorFormMode('add');
                window.closeFormModal();
            }
        };
        var addModalContent = addModal.querySelector('.modal-content');
        if (addModalContent) {
            addModalContent.onclick = function(e) {
                e.stopPropagation();
            };
        }
    }

    // View modal close logic
    var viewModal = document.getElementById('viewCounselorModal');
    var viewCloseBtn = viewModal ? viewModal.querySelector('.close') : null;
    if (viewCloseBtn && viewModal) {
        viewCloseBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeViewCounselorModal();
        };
        viewModal.onclick = function(event) {
            if (event.target === viewModal) {
                window.closeViewCounselorModal();
            }
        };
        var viewModalContent = viewModal.querySelector('.modal-content');
        if (viewModalContent) {
            viewModalContent.onclick = function(e) {
                e.stopPropagation();
            };
        }
    }
});

window.openFormModal = function () {
    setCounselorFormMode('add');
    // Clear all fields explicitly to prevent leftover data
    document.getElementById('c_id_display').textContent = '';
    document.getElementById('c_id').value = '';
    document.getElementById('counselor_lname').value = '';
    document.getElementById('counselor_fname').value = '';
    document.getElementById('counselor_mname').value = '';
    document.getElementById('counselor_email').value = '';
    document.getElementById('counselor_contact_num').value = '';
    if (document.getElementById('counselor_sex_male')) document.getElementById('counselor_sex_male').checked = false;
    if (document.getElementById('counselor_sex_female')) document.getElementById('counselor_sex_female').checked = false;
    if (document.getElementById('counselor_password')) document.getElementById('counselor_password').value = '';
    var imgPreview = document.getElementById('counselorImage');
    if (imgPreview) {
        imgPreview.src = imgPreview.getAttribute('data-default');
    }
    var activateBtn = document.getElementById('activateCounselorBtn');
    if (activateBtn) activateBtn.style.display = 'none';
    // Fetch next available counselor ID and set it
    fetch('/Head/counselors/next-id')
        .then(response => response.json())
        .then(data => {
            document.getElementById('c_id_display').textContent = data.next_c_id;
            document.getElementById('c_id').value = data.next_c_id;
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
                alert('Counselor not found!');
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
            location.reload();
        } else {
            let msg = data.error || 'Failed to archive counselor.';
            showArchiveError(msg);
        }
    })
    .catch(() => {
        showArchiveError('Failed to archive counselor.');
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