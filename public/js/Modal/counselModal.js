// open Add Modal
function openFormModal() {
    document.getElementById('formModal').style.display = 'block';
}

// close Add Modal
function closeFormModal() {
    document.getElementById('formModal').style.display = 'none';
}


window.openFormModal = function () {
    const viewModal = document.getElementById('viewCounselorModal');
    const modal = document.getElementById('formModal');
    if (modal) {
        modal.style.display = 'block';
    } else {
        console.error("Modal not found!");
    }
};

window.closeFormModal = function () {
    const viewModal = document.getElementById('viewCounselorModal');
    const modal = document.getElementById('formModal');
    if (modal) {
        modal.style.display = 'none';
    } else {
        console.error("Modal not found!");
    }
};

function openViewCounselModal(counselorId) {
    fetch(`/Head/counselors/${counselorId}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load counselor info.');
            return response.json();
        })
        .then(data => {

            window.editCounselorData = data;

            document.getElementById('view_fullname').textContent = `${data.lname}, ${data.fname} ${data.mname}`;
            document.getElementById('view_email').textContent = data.email;
            document.getElementById('view_contact').textContent = data.contact_num;
            document.getElementById('view_department').textContent = data.c_level;
            document.getElementById('view_c_image').src = data.profile_image
                ? '/uploads/users/' + data.profile_image
                : '/images/user.img/people.png';
            document.getElementById('viewCounselorModal').style.display = 'block';
        })
        .catch(error => {
            alert(error.message);
        });
}

function closeViewCounselorModal() {
    document.getElementById('viewCounselorModal').style.display = 'none';
}

function openEditFromViewModal() {
    const data = window.editCounselorData;
    if (!data) {
        alert('No counselor data loaded.');
        return;
    }

    document.getElementById('edit_id').value = data.c_id;
    document.getElementById('edit_lname').value = data.lname;
    document.getElementById('edit_fname').value = data.fname;
    document.getElementById('edit_mname').value = data.mname;
    document.getElementById('edit_email').value = data.email;
    document.getElementById('edit_contact').value = data.contact_num;
    document.getElementById('edit_c_level').value = data.c_level;

    closeViewCounselorModal();
    document.getElementById('editModal').style.display = 'block';
}
