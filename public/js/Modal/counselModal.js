// open Add Modal
function openFormModal() {
    document.getElementById('formModal').style.display = 'block';
}

// close Add Modal
function closeFormModal() {
    document.getElementById('formModal').style.display = 'none';
}

function openViewCounselModal(counselorId) {
    console.log("Opening modal for ID:", counselorId);

    fetch(`/Head/counselor/${counselorId}`)
        .then(response => {
            console.log("Fetch status:", response.status);
            if (!response.ok) throw new Error('Fetch failed');
            return response.json();
        })
        .then(data => {
            console.log("Data:", data);
            document.getElementById('view_fullname').textContent = `${data.lname}, ${data.fname} ${data.mname}`;
            document.getElementById('view_email').textContent = data.email;
            document.getElementById('view_contact').textContent = data.contact_num;
            document.getElementById('view_department').textContent = data.c_level;
            document.getElementById('viewCounselorModal').style.display = 'block';
        })
        .catch(err => {
            console.error('Modal fetch error:', err);
            alert("Failed to load counselor info.");
        });
}

function closeViewCounselModal() {
    document.getElementById('viewCounselorModal').style.display = 'none';
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
