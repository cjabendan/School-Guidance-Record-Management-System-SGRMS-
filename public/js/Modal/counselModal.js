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
        // Password field left blank for security
        document.getElementById('counselor_password').value = '';
        // Image preview (optional, not changing file input)
        if (data.profile_image_url) {
            document.getElementById('counselorImage').src = data.profile_image_url;
        }
    } else {
        // Set form action to store route
        form.action = `/Head/counselors/store`;
        // Remove _method input if exists
        if (methodInput) methodInput.remove();
        // Reset fields
        form.reset();
        // Reset image preview
        const imgPreview = document.getElementById('counselorImage');
        if (imgPreview) {
            imgPreview.src = imgPreview.getAttribute('data-default');
        }
        // Fetch and set next ID
        fetch('/Head/counselors/next-id')
            .then(response => response.json())
            .then(data => {
                document.getElementById('c_id_display').textContent = data.next_c_id;
                document.getElementById('c_id').value = data.next_c_id;
            });
    }
}

// Edit Counselor: open add modal in edit mode with data
window.editCounselorFromView = function(c_id) {
    // Close view modal
    window.closeViewCounselorModal();
    // Fetch counselor data
    fetch(`/Head/counselors/${c_id}/json`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Counselor not found!');
                return;
            }
            setCounselorFormMode('edit', data);
            // Open add/edit modal
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

// View Counselor Modal 
window.openViewCounselModal = function(c_id) {
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
        });
};

window.closeViewCounselorModal = function() {
    var modal = document.getElementById('viewCounselorModal');
    if (modal) {
        modal.style.display = 'none';
    }
};
document.addEventListener('DOMContentLoaded', function() {

window.openAddCounselorModal = window.openFormModal;
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

    var modal = document.getElementById('formModal');
    var closeBtn = modal ? modal.querySelector('.close') : null;
    if (closeBtn && modal) {
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            window.closeFormModal();
        };
        modal.onclick = function(event) {
            if (event.target === modal) {
                window.closeFormModal();
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

window.openFormModal = function () {
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


//____________________________________________________________________________________


// View Counselor Modal 
window.openViewCounselModal = function(c_id) {
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
        });
};

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