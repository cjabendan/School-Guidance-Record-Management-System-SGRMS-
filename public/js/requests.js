function openRequestModal(type, id) {
    const modal = document.getElementById("request-modal");

    fetch(`/Head/requests/${type}/${id}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
        .then(res => res.json())
        .then(data => {
            // Fill modal content
            document.getElementById("modal-title").textContent = `${data.type} Request`;
            document.getElementById("modal-parent").textContent = data.parent_name;
            document.getElementById("modal-email").textContent = data.email;
            document.getElementById("modal-contact").textContent = data.contact;
            document.getElementById("modal-requested-at").textContent = data.requested_at;
            document.getElementById("modal-status").textContent = data.status;

            const studentsUl = document.getElementById("modal-students");
            studentsUl.innerHTML = "";
            data.students.forEach(s => {
                const li = document.createElement("li");
                li.textContent = s;
                studentsUl.appendChild(li);
            });

            const approveForm = document.getElementById("modal-approve-form");
            const rejectForm = document.getElementById("modal-reject-form");
            const rejectionForm = document.getElementById("modal-rejection");
            const showReasonBtn = document.getElementById("show-reason-btn");
            const cancelRejectBtn = document.getElementById("cancel-reject-btn");

            if (data.status.toLowerCase() === "pending") {
                approveForm.style.display = "block";
                approveForm.action = `/Head/requests/${type}/${id}/approve`;

                rejectForm.style.display = "block";
                rejectForm.action = `/Head/requests/${type}/${id}/reject`;

                rejectionForm.style.display = "none";

                approveForm.onsubmit = function(ev) {
                    ev.preventDefault();
                    const csrf = approveForm.querySelector('input[name="_token"]').value;
                    showSmallLoader('.request-modal-content');
                    fetch(`/Head/requests/${type}/${id}/approve`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf,
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => res.json())
                    .then(resp => {
                        hideSmallLoader('.request-modal-content');
                        if (resp.success) {
                            modal.style.display = "none";
                            if (typeof loadRequests === 'function') loadRequests();
                        } else {
                            alert("Failed to approve request.");
                        }
                    })
                    .catch(() => {
                        hideSmallLoader('.request-modal-content');
                        alert("Failed to approve request.");
                    });
                };

                showReasonBtn.onclick = function(e) {
                    e.preventDefault();
                    approveForm.style.display = "none";
                    rejectForm.style.display = "none";
                    rejectionForm.style.display = "block";
                    document.getElementById("rejection-reason-input").focus();
                };

                cancelRejectBtn.onclick = function() {
                    rejectionForm.style.display = "none";
                    approveForm.style.display = "block";
                    rejectForm.style.display = "block";
                };

                rejectionForm.onsubmit = function(ev) {
                    ev.preventDefault();
                    const reason = document.getElementById("rejection-reason-input").value;
                    const csrf = rejectionForm.querySelector('input[name="_token"]').value;
                    showSmallLoader('.request-modal-content');
                    fetch(`/Head/requests/${type}/${id}/reject`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf,
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({ reason })
                    })
                    .then(res => res.json())
                    .then(resp => {
                        hideSmallLoader('.request-modal-content');
                        if (resp.success) {
                            modal.style.display = "none";
                            if (typeof loadRequests === 'function') loadRequests();
                        } else {
                            alert("Failed to reject request.");
                        }
                    })
                    .catch(() => {
                        hideSmallLoader('.request-modal-content');
                        alert("Failed to reject request.");
                    });
                };

            } else {
                approveForm.style.display = "none";
                rejectForm.style.display = "none";
                rejectionForm.style.display = "none";

                if (data.status.toLowerCase() === "rejected") {
                    rejectionForm.style.display = "block";
                    document.getElementById("rejection-reason-input").value = data.rejection_reason ?? "N/A";
                    document.getElementById("rejection-reason-input").disabled = true;
                }
            }

            modal.style.display = "flex";
        })
        .catch(err => console.error("Error fetching request:", err));
}

document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("request-modal");
    let currentStatus = "all";
    let currentPage = 1;

    // Table filters
    document.querySelectorAll(".type-filter").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            document.querySelectorAll(".type-filter").forEach(l => l.classList.remove("active"));
            this.classList.add("active");
            currentStatus = this.dataset.type;
            loadRequests();
        });
    });

    // Fetch and display requests
    function loadRequests(page = 1) {
        currentPage = page;
        fetch(`/Head/requests?status=${currentStatus}&page=${page}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.json())
        .then(data => {
            const table = document.getElementById("requests-table");
            const pagination = document.getElementById("requests-pagination");
            table.innerHTML = "";
            pagination.innerHTML = "";

            if (!data.data || data.data.length === 0) {
                table.innerHTML = `<div class="no-table-cell">No requests found.</div>`;
                return;
            }

            data.data.forEach(req => {
                table.innerHTML += `
                    <div class="table-card">
                        <div class="table-col type">${req.display_type}</div>
                        <div class="table-col requested-by">${req.parent_name}</div>
                        <div class="table-col requested-at">${req.requested_at_display ?? req.requested_at}</div>
                        <div class="table-col status">
                            <span class="status-label status-${req.status}">
                                <span class="status-dot status-${req.status}"></span>
                                ${req.display_status ?? (req.status.charAt(0).toUpperCase() + req.status.slice(1))}
                            </span>
                        </div>
                        <div class="table-col actions">
                            <a href="#" class="review-btn" data-type="${req.type}" data-id="${req.id}">View</a>
                        </div>
                    </div>
                `;
            });

            if (data.last_page > 1) {
                let pagHtml = '';
                pagHtml += `<button class="page-link" ${data.current_page === 1 ? 'disabled' : ''} data-page="${data.current_page - 1}">&laquo;</button>`;
                for (let i = 1; i <= data.last_page; i++) {
                    pagHtml += `<button class="page-link${i === data.current_page ? ' active' : ''}" data-page="${i}">${i}</button>`;
                }
                pagHtml += `<button class="page-link" ${data.current_page === data.last_page ? 'disabled' : ''} data-page="${data.current_page + 1}">&raquo;</button>`;
                pagination.innerHTML = pagHtml;

                pagination.querySelectorAll('button[data-page]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const page = parseInt(this.getAttribute('data-page'));
                        if (!isNaN(page)) loadRequests(page);
                    });
                });
            }

            document.querySelectorAll(".review-btn").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const type = this.dataset.type;
                    openRequestModal(type, id);
                });
            });
        })
        .catch(err => console.error("Error fetching requests:", err));
    }

    // Initial load
    loadRequests();

    document.getElementById("close-modal-btn").addEventListener("click", () => {
        modal.style.display = "none";
    });
    modal.addEventListener("click", e => {
        if (e.target === modal) modal.style.display = "none";
    });
});