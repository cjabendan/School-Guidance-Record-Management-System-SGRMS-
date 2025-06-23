function openAddModal() {
    document.getElementById('addStudentModal').style.display = 'block';
    fetchStudentId();
}

function closeAddModal() {
    document.getElementById('addStudentModal').style.display = 'none';
}

window.onclick = function(event) {
    var modal = document.getElementById('addStudentModal');
    if (event.target === modal) {
        closeAddModal();
    }
}
