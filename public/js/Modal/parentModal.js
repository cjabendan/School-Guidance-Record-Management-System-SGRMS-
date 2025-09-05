// Show the Add Parent modal when the Add button is clicked
function openAddParentModal() {
    const modal = new bootstrap.Modal(document.getElementById('addParentModal'));
    modal.show();
}

// Attach event listener to Add Parent button
document.addEventListener('DOMContentLoaded', function() {
    var addBtn = document.getElementById('addParentBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openAddParentModal();
        });
    }
});

// Handle form submission for Add Parent
const addParentForm = document.getElementById('addParentForm');
if (addParentForm) {
    addParentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // Collect form data
        const formData = new FormData(addParentForm);
        // Example: send data via AJAX (customize URL and handling as needed)
        fetch('/Head/parents/add', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal and optionally refresh parent list
                    bootstrap.Modal.getInstance(document.getElementById('addParentModal')).hide();
                    location.reload(); // Automatically refresh the table
            } else {
                // Show error message
                alert(data.message || 'Failed to add parent.');
            }
        })
        .catch(error => {
            alert('An error occurred.');
        });
    });
}
// Close the Add Parent modal
function closeAddParentModal() {
    const modal = document.getElementById('addParentModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

// Add event listener for X button
const closeBtn = document.getElementById('closeAddParentModalBtn');
if (closeBtn) {
    closeBtn.addEventListener('click', closeAddParentModal);
}

// Add event listener for clicking outside modal (Bootstrap modal)
const addParentModal = document.getElementById('addParentModal');
if (addParentModal) {
    // Improved: Close modal when clicking anywhere outside modal-content
    addParentModal.addEventListener('mousedown', function(e) {
        var modalContent = addParentModal.querySelector('.modal-content');
        if (modalContent && !modalContent.contains(e.target)) {
            bootstrap.Modal.getInstance(addParentModal).hide();
        }
    });
}
