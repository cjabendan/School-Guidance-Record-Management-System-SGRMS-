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