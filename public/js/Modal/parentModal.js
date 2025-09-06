// Show the Add/Edit Parent modal
function openParentModal(mode, parentId = null) {
    // Set modal title and button text dynamically
    const modalTitle = document.getElementById('parentModalLabel');
    const submitBtn = document.querySelector('#parentForm button[type="submit"]');
    if (mode === 'view') {
        if (modalTitle) modalTitle.textContent = 'View Parent';
        if (submitBtn) submitBtn.style.display = 'none';
    } else if (mode === 'edit') {
        if (modalTitle) modalTitle.textContent = 'Edit Parent';
        if (submitBtn) {
            submitBtn.textContent = 'Update Parent';
            submitBtn.style.display = '';
        }
    } else {
        if (modalTitle) modalTitle.textContent = 'Add Parent';
        if (submitBtn) {
            submitBtn.textContent = 'Add Parent';
            submitBtn.style.display = '';
        }
    }
    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        console.error('Bootstrap JS is not loaded! Modal cannot open.');
        let errDiv = document.createElement('div');
        errDiv.style.background = '#ffdddd';
        errDiv.style.color = '#a00';
        errDiv.style.padding = '10px';
        errDiv.style.margin = '10px';
        errDiv.innerText = 'ERROR: Bootstrap JS is missing. Modal cannot open. Please check your page scripts.';
        document.body.prepend(errDiv);
        return;
    }
    console.log('openParentModal called with mode:', mode, 'parentId:', parentId);
    const modalEl = document.getElementById('parentModal');
    if (!modalEl) {
        console.error('Modal element #parentModal not found in DOM!');
        let errDiv = document.createElement('div');
        errDiv.style.background = '#ffdddd';
        errDiv.style.color = '#a00';
        errDiv.style.padding = '10px';
        errDiv.style.margin = '10px';
        errDiv.innerText = 'ERROR: Modal element #parentModal not found in DOM!';
        document.body.prepend(errDiv);
        return;
    }
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('parentForm');
    if ((mode === 'edit' || mode === 'view') && parentId) {
        // Fetch parent data via AJAX and populate form
        fetch(`/Head/parents/${parentId}/get`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.parent) {
                // Fill form fields
                form.first_name.value = data.parent.first_name || '';
                form.middle_name.value = data.parent.middle_name || '';
                form.last_name.value = data.parent.last_name || '';
                form.sex.value = data.parent.sex || '';
                form.contact_num.value = data.parent.contact_num || '';
                form.email.value = data.parent.email || '';
                // Always hide password fields in edit/view mode and remove required
                document.querySelectorAll('#password, #confirmPassword').forEach(function(el) {
                    el.value = '';
                    el.parentElement.style.display = 'none';
                    el.removeAttribute('required');
                });
                // Set form action to update/view
                form.setAttribute('data-mode', mode);
                form.setAttribute('data-parent-id', parentId);
                // Disable all fields in view mode
                if (mode === 'view') {
                    Array.from(form.elements).forEach(function(input) {
                        input.setAttribute('disabled', 'disabled');
                    });
                } else {
                    Array.from(form.elements).forEach(function(input) {
                        input.removeAttribute('disabled');
                    });
                }
                setTimeout(() => { modal.show(); }, 100); // Ensure modal pops up
            } else {
                alert('Failed to load parent data.');
            }
        })
        .catch(() => {
            alert('Error loading parent data.');
        });
    } else {
        // Always reset and clear form for add mode
        form.reset();
        form.first_name.value = '';
        form.middle_name.value = '';
        form.last_name.value = '';
        form.sex.value = '';
        form.contact_num.value = '';
        form.email.value = '';
        // Always show password fields in add mode and add required
        document.querySelectorAll('#password, #confirmPassword').forEach(function(el) {
            el.value = '';
            el.parentElement.style.display = '';
            el.setAttribute('required', 'required');
        });
        form.removeAttribute('data-mode');
        form.removeAttribute('data-parent-id');
        setTimeout(() => { modal.show(); }, 100);
    }
    }

// Attach event listeners to Add and Edit Parent buttons
document.addEventListener('DOMContentLoaded', function() {
    // Archive parent account with modal confirmation
    let archiveParentId = null;
    document.getElementById('parent-list').addEventListener('click', function(e) {
        if (e.target.closest('.archive-btn')) {
            e.preventDefault();
            var archiveBtn = e.target.closest('.archive-btn');
            archiveParentId = archiveBtn.getAttribute('data-id');
            const archiveModal = new bootstrap.Modal(document.getElementById('archiveParentModal'));
            archiveModal.show();
        }
    });

    // Handle confirm archive button
    const confirmArchiveBtn = document.getElementById('confirmArchiveParentBtn');
    if (confirmArchiveBtn) {
        confirmArchiveBtn.addEventListener('click', function() {
            if (!archiveParentId) return;
            fetch(`/Head/parents/${archiveParentId}/archive`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const archiveModalEl = document.getElementById('archiveParentModal');
                const archiveModalInstance = bootstrap.Modal.getInstance(archiveModalEl);
                archiveModalInstance.hide();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to archive parent.');
                }
            })
            .catch(() => {
                alert('An error occurred while archiving.');
            });
        });
    }
    var addBtn = document.getElementById('addParentBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openParentModal('add');
        });
    }
    // Use event delegation for edit/view buttons (in case parent rows are loaded dynamically)
    document.getElementById('parent-list').addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            e.preventDefault();
            var editBtn = e.target.closest('.edit-btn');
            var parentId = editBtn.getAttribute('data-id');
            openParentModal('edit', parentId);
        }
        if (e.target.closest('.view-btn')) {
            e.preventDefault();
            var viewBtn = e.target.closest('.view-btn');
            var parentId = viewBtn.getAttribute('data-id');
            openParentModal('view', parentId);
        }
    });
});

// Handle form submission for Add/Edit Parent
const parentForm = document.getElementById('parentForm');
if (parentForm) {
    parentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const mode = parentForm.getAttribute('data-mode');
        const parentId = parentForm.getAttribute('data-parent-id');
        const formData = new FormData(parentForm);
        let url = '/Head/parents/add';
        let method = 'POST';
        if (mode === 'edit' && parentId) {
            url = `/Head/parents/${parentId}/update`;
            method = 'POST';
        }
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal and reset form after successful add/edit
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('parentModal'));
                modalInstance.hide();
                parentForm.reset();
                document.querySelectorAll('#password, #confirmPassword').forEach(function(el) {
                    el.parentElement.style.display = '';
                });
                parentForm.removeAttribute('data-mode');
                parentForm.removeAttribute('data-parent-id');
                location.reload();
            } else {
                alert(data.message || 'Failed to save parent.');
            }
        })
        .catch(() => {
            alert('An error occurred.');
        });
    });
}

// Close the Parent modal
function closeParentModal() {
    const modal = document.getElementById('parentModal');
    const form = document.getElementById('parentForm');
    if (form) {
        form.reset();
        document.querySelectorAll('#password, #confirmPassword').forEach(function(el) {
            el.parentElement.style.display = '';
        });
        form.removeAttribute('data-mode');
        form.removeAttribute('data-parent-id');
    }
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

// Add event listener for X button
const closeBtn = document.getElementById('closeParentModalBtn');
if (closeBtn) {
    closeBtn.addEventListener('click', closeParentModal);
}

// Add event listener for clicking outside modal (Bootstrap modal)
const parentModal = document.getElementById('parentModal');
if (parentModal) {
    parentModal.addEventListener('mousedown', function(e) {
        var modalContent = parentModal.querySelector('.modal-content');
        if (modalContent && !modalContent.contains(e.target)) {
            bootstrap.Modal.getInstance(parentModal).hide();
        }
    });
}
