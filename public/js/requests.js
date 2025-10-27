function openRequestModal(type, id) {
    const modal = document.getElementById("request-modal");
    const parentEl = document.getElementById("modal-parent");
    const emailEl = document.getElementById("modal-email");
    const contactEl = document.getElementById("modal-contact"); // may be null
    const requestedAtEl = document.getElementById("modal-requested-at"); // may be null
    const statusEl = document.getElementById("modal-status"); // may be null
    const tableBody = document.querySelector("#modal-students tbody");

    // show loader if you have one
    const loader = document.querySelector(".small-loader");
    if (loader) loader.style.display = "block";

    fetch(`/Head/requests/${type}/${id}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then((res) => {
            if (!res.ok)
                throw new Error("Network response was not ok: " + res.status);
            return res.json();
        })
        .then((data) => {
            if (loader) loader.style.display = "none";

            parentEl && (parentEl.textContent = data.parent_name ?? "N/A");
            emailEl && (emailEl.textContent = data.email ?? "N/A");
            contactEl && (contactEl.textContent = data.contact ?? "N/A");
            requestedAtEl &&
                (requestedAtEl.textContent = data.requested_at ?? "");
            statusEl && (statusEl.textContent = data.status ?? "");

            // populate table body safely
            tableBody.innerHTML = "";
            const requests = Array.isArray(data.requests) ? data.requests : [];

            if (requests.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" style="text-align:center">No student requests found</td></tr>`;
            } else {

                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

                requests.forEach((r) => {
                    const tr = document.createElement("tr");
                    const studentName = r.student_name ?? r.student ?? "N/A";
                    const grade = r.grade ?? "N/A";
                    const status = r.status ?? "N/A";
                    const date = r.requested_at ?? r.date ?? "N/A";
                    const id = r.id ?? ""; // student request ID

                    tr.innerHTML = `
        <td>${escapeHtml(studentName)}</td>
        <td>${escapeHtml(grade)}</td>
        <td>${escapeHtml(status)}</td>
        <td>${escapeHtml(date)}</td>
        <td>
            ${
                status.toLowerCase() === "pending"
                    ? `
                    <form method="POST" action="/Head/requests/student/${id}/approve" style="display:inline">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="POST" action="/Head/requests/student/${id}/reject" style="display:inline">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                    `
                    : "-"
            }
        </td>
    `;
                    tableBody.appendChild(tr);
                });
            }

            // buttons/forms
            const approveForm = document.getElementById("modal-approve-form");
            const rejectForm = document.getElementById("modal-reject-form");
            const rejectionForm = document.getElementById("modal-rejection");
            const showReasonBtn = document.getElementById("show-reason-btn");
            const cancelRejectBtn =
                document.getElementById("cancel-reject-btn");

            const hasPending = requests.some(
                (r) => (r.status || "").toLowerCase() === "pending"
            );

            if (hasPending) {
                if (approveForm) {
                    approveForm.style.display = "block";
                    approveForm.action = `/Head/requests/${type}/${id}/approve`;
                }
                if (rejectForm) {
                    rejectForm.style.display = "block";
                    rejectForm.action = `/Head/requests/${type}/${id}/reject`;
                }
                if (rejectionForm) rejectionForm.style.display = "none";

                // attach handlers (remove previous to avoid duplicates)
                if (approveForm) {
                    approveForm.onsubmit = function (ev) {
                        ev.preventDefault();
                        const csrf = approveForm.querySelector(
                            'input[name="_token"]'
                        )?.value;
                        if (!csrf) return alert("CSRF token missing");
                        showSmallLoader?.(".request-modal-content");
                        fetch(`/Head/requests/${type}/${id}/approve`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf,
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: JSON.stringify({}),
                        })
                            .then((r) => r.json())
                            .then((resp) => {
                                hideSmallLoader?.(".request-modal-content");
                                if (resp.success) {
                                    closeModal(modal);
                                    typeof loadRequests === "function" &&
                                        loadRequests();
                                } else {
                                    alert(
                                        resp.message ||
                                            "Failed to approve request."
                                    );
                                }
                            })
                            .catch(() => {
                                hideSmallLoader?.(".request-modal-content");
                                alert("Failed to approve request.");
                            });
                    };
                }

                if (showReasonBtn && rejectionForm && rejectForm) {
                    showReasonBtn.onclick = function (e) {
                        e.preventDefault();
                        approveForm && (approveForm.style.display = "none");
                        rejectForm && (rejectForm.style.display = "none");
                        rejectionForm.style.display = "block";
                        document
                            .getElementById("rejection-reason-input")
                            ?.focus();
                    };
                }

                if (
                    cancelRejectBtn &&
                    rejectionForm &&
                    approveForm &&
                    rejectForm
                ) {
                    cancelRejectBtn.onclick = function () {
                        rejectionForm.style.display = "none";
                        approveForm.style.display = "block";
                        rejectForm.style.display = "block";
                    };
                }

                if (rejectionForm) {
                    rejectionForm.onsubmit = function (ev) {
                        ev.preventDefault();
                        const reason =
                            document.getElementById("rejection-reason-input")
                                ?.value || "";
                        const csrf = rejectionForm.querySelector(
                            'input[name="_token"]'
                        )?.value;
                        if (!csrf) return alert("CSRF token missing");
                        showSmallLoader?.(".request-modal-content");
                        fetch(`/Head/requests/${type}/${id}/reject`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf,
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: JSON.stringify({ reason }),
                        })
                            .then((r) => r.json())
                            .then((resp) => {
                                hideSmallLoader?.(".request-modal-content");
                                if (resp.success) {
                                    closeModal(modal);
                                    typeof loadRequests === "function" &&
                                        loadRequests();
                                } else {
                                    alert(
                                        resp.message ||
                                            "Failed to reject request."
                                    );
                                }
                            })
                            .catch(() => {
                                hideSmallLoader?.(".request-modal-content");
                                alert("Failed to reject request.");
                            });
                    };
                }
            } else {
                approveForm && (approveForm.style.display = "none");
                rejectForm && (rejectForm.style.display = "none");
                rejectionForm && (rejectionForm.style.display = "none");
            }

            // finally show modal
            modal.classList.add("show");
            modal.style.display = "flex";
        })
        .catch((err) => {
            if (loader) loader.style.display = "none";
            console.error("Error fetching request:", err);
            alert("Failed to load request details. Check console/network tab.");
        });

    // helper: close modal
    function closeModal(m) {
        if (!m) return;
        m.classList.remove("show");
        m.style.display = "none";
    }

    // helper: simple escape to avoid basic XSS when inserting strings
    function escapeHtml(str) {
        if (typeof str !== "string") return str;
        return str.replace(/[&<>"']/g, (s) => {
            const map = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
            };
            return map[s];
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("request-modal");
    let currentStatus = "all";
    let currentPage = 1;

    // Table filters
    document.querySelectorAll(".type-filter").forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            document
                .querySelectorAll(".type-filter")
                .forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
            currentStatus = this.dataset.type;
            loadRequests();
        });
    });

    // Fetch and display requests
    function loadRequests(page = 1) {
        currentPage = page;
        fetch(`/Head/requests?status=${currentStatus}&page=${page}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => res.json())
            .then((data) => {
                const table = document.getElementById("requests-table");
                const pagination = document.getElementById(
                    "requests-pagination"
                );
                table.innerHTML = "";
                pagination.innerHTML = "";

                if (!data.data || data.data.length === 0) {
                    table.innerHTML = `<div class="no-table-cell">No data found.</div>`;
                    return;
                }

                data.data.forEach((p) => {
                    table.innerHTML += `
        <div class="table-card">
            <div class="table-col">
                <img src="${
                    p.profile_image
                        ? imageBase + "/" + p.profile_image
                        : defaultImage
                }"
                     class="profile-thumb" alt="Profile">
                ${p.parent_name}
            </div>
            <div class="table-col">${p.linked_students}</div>
            <div class="table-col">${p.pending_requests}</div>
            <div class="table-col">${p.last_updated}</div>
           <div class="table-col">
    <a href="#" class="review-btn" data-id="${
        p.id
    }" data-type="${currentStatus}">Manage</a>
</div>

        </div>
    `;
                });

                if (data.last_page > 1) {
                    let pagHtml = "";
                    pagHtml += `<button class="page-link" ${
                        data.current_page === 1 ? "disabled" : ""
                    } data-page="${data.current_page - 1}">&laquo;</button>`;
                    for (let i = 1; i <= data.last_page; i++) {
                        pagHtml += `<button class="page-link${
                            i === data.current_page ? " active" : ""
                        }" data-page="${i}">${i}</button>`;
                    }
                    pagHtml += `<button class="page-link" ${
                        data.current_page === data.last_page ? "disabled" : ""
                    } data-page="${data.current_page + 1}">&raquo;</button>`;
                    pagination.innerHTML = pagHtml;

                    pagination
                        .querySelectorAll("button[data-page]")
                        .forEach((btn) => {
                            btn.addEventListener("click", function () {
                                const page = parseInt(
                                    this.getAttribute("data-page")
                                );
                                if (!isNaN(page)) loadRequests(page);
                            });
                        });
                }

                document.querySelectorAll(".review-btn").forEach((btn) => {
                    btn.addEventListener("click", function (e) {
                        e.preventDefault();
                        const id = this.dataset.id;
                        const type = this.dataset.type;
                        openRequestModal(type, id);
                    });
                });
            })
            .catch((err) => console.error("Error fetching requests:", err));
    }

    // Initial load
    loadRequests();

    document.getElementById("close-modal-btn").addEventListener("click", () => {
        modal.style.display = "none";
    });
    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });
});
