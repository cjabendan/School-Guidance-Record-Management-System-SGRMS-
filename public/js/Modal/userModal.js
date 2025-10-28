// ---------- User Modal Functions ----------
function openUserModal(mode, userId = null) {
    const modal = document.getElementById("userModal");
    const form = document.getElementById("userForm");
    const title = document.getElementById("userModalTitle");
    const saveBtn = document.getElementById("userSaveBtn");
    const passwordFields = document.getElementById("passwordFields");
    const confirmFields = document.getElementById("confirmPasswordFields");
    const resetBtnWrapper = document.getElementById("resetPasswordWrapper");

    if (!modal || !form) return console.error("User modal/form not found");

    // Reset form
    form.reset();
    form.removeAttribute("data-user-id");
    saveBtn.style.display = "inline-block";
    Array.from(form.elements).forEach((el) => (el.disabled = false));

    // Mode handling
    if (mode === "add") {
        title.textContent = "Add System User";
        saveBtn.textContent = "Add";
        form.setAttribute("data-mode", "add");
        document.getElementById("password").required = true;
        document.getElementById("confirmPassword").required = true;

        passwordFields.style.display = "block";
        confirmFields.style.display = "block";
        resetBtnWrapper.style.display = "none";
    } else if (mode === "edit") {
        title.textContent = "Edit User";
        saveBtn.textContent = "Update";
        form.setAttribute("data-mode", "edit");
        document.getElementById("password").required = false;
        document.getElementById("confirmPassword").required = false;

        passwordFields.style.display = "none";
        confirmFields.style.display = "none";
        resetBtnWrapper.style.display = "block";
    } else if (mode === "view") {
        title.textContent = "User Information";
        saveBtn.style.display = "none";

        passwordFields.style.display = "none";
        confirmFields.style.display = "none";
        resetBtnWrapper.style.display = "none";
    }

    // Fetch data if editing/viewing
    // Fetch data if editing/viewing
    if ((mode === "edit" || mode === "view") && userId) {
        fetch(`/Head/users/${userId}/get`, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success && data.user) {
                    const u = data.user;

                    form.first_name.value = u.first_name || "";
                    form.middle_name.value = u.middle_name || "";
                    form.last_name.value = u.last_name || "";
                    form.sex.value = u.sex || "";
                    form.contact_num.value = u.contact_num || "";
                    form.email.value = u.email || "";
                    form.role.value = u.role || "";
                    form.status.value = u.status || "active";
                    form.setAttribute("data-user-id", userId);

                    // Hide password fields in edit/view
                    document
                        .querySelectorAll("#password, #confirmPassword")
                        .forEach((el) => {
                            el.value = "";
                            if (mode === "view")
                                el.parentElement.style.display = "none";
                        });

                    // ✅ Hide role if user is student in edit mode
                    const roleGroup = document
                        .querySelector("#role")
                        .closest(".form-group");
                    if (mode === "edit" && u.role === "student") {
                        roleGroup.style.display = "none";
                    } else {
                        roleGroup.style.display = "block";
                    }

                    // Disable form fields if viewing
                    if (mode === "view") {
                        Array.from(form.elements).forEach(
                            (el) => (el.disabled = true)
                        );
                    }
                } else {
                    createToast("error", "Failed to load user data.");
                }
            })
            .catch(() => createToast("error", "Error loading user data."));
    }

    modal.style.display = "block";
}

function closeUserModal() {
    const modal = document.getElementById("userModal");
    if (modal) modal.style.display = "none";
}

function resetPassword() {
    const passwordField = document.getElementById("password");
    const confirmField = document.getElementById("confirmPassword");
    const defaultPassword = "Password123";
    passwordField.value = defaultPassword;
    confirmField.value = defaultPassword;
    passwordField.classList.add("reset-highlight");
    confirmField.classList.add("reset-highlight");
    setTimeout(() => {
        passwordField.classList.remove("reset-highlight");
        confirmField.classList.remove("reset-highlight");
    }, 1500);
}

// ---------- Form Submission ----------
document.getElementById("userForm")?.addEventListener("submit", function (e) {
    e.preventDefault();
    const form = e.target;
    const mode = form.dataset.mode;
    const userId = form.dataset.userId;
    const formData = new FormData(form);

    const url =
        mode === "edit" && userId
            ? `/Head/users/${userId}/update`
            : "/Head/users/add";

    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                closeUserModal();
                form.reset();
                createToast(
                    "success",
                    mode === "edit"
                        ? "User updated successfully!"
                        : "User added successfully!"
                );
                setTimeout(refreshUserTable, 800);
            } else {
                createToast("error", data.message || "Failed to save user.");
            }
        })
        .catch(() => createToast("error", "An error occurred."));
});

// ---------- Archive Handling ----------
let archiveUserId = null;
document.getElementById("users-list")?.addEventListener("click", function (e) {
    const archiveBtn = e.target.closest(".archive-btn");
    const editBtn = e.target.closest(".edit-btn");
    const viewBtn = e.target.closest(".view-btn");

    if (archiveBtn) {
        e.preventDefault();
        archiveUserId = archiveBtn.dataset.id;
        document.getElementById("archiveUserModal").style.display = "block";
    }

    if (editBtn) {
        e.preventDefault();
        openUserModal("edit", editBtn.dataset.id);
    }

    if (viewBtn) {
        e.preventDefault();
        openUserModal("view", viewBtn.dataset.id);
    }
});

document
    .getElementById("confirmArchiveUserBtn")
    ?.addEventListener("click", function () {
        if (!archiveUserId) return;
        fetch(`/Head/users/${archiveUserId}/archive`, {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((res) => res.json())
            .then((data) => {
                document.getElementById("archiveUserModal").style.display =
                    "none";
                if (data.success) {
                    createToast("success", "User archived successfully!");
                    setTimeout(refreshUserTable, 800);
                } else {
                    createToast(
                        "error",
                        data.message || "Failed to archive user."
                    );
                }
            })
            .catch(() =>
                createToast("error", "An error occurred while archiving.")
            );
    });

// ---------- Table Refresh ----------
function refreshUserTable() {
    const container = document.getElementById("users-list");
    if (!container) return;
    const searchInput = document.getElementById("parent-search-input");
    const url = new URL(window.location.origin + "/Head/users");
    if (searchInput && searchInput.value)
        url.searchParams.set("search", searchInput.value);

    fetch(url.toString(), { headers: { "X-Requested-With": "XMLHttpRequest" } })
        .then((res) => res.text())
        .then((html) => {
            container.innerHTML = html;
        })
        .catch((err) => console.error("Failed to refresh user table:", err));
}

// ---------- Validation ----------
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("userForm");
    form.addEventListener("submit", function (e) {
        let valid = true;

        // Reset errors
        document
            .querySelectorAll(".error-msg")
            .forEach((el) => (el.textContent = ""));
        document
            .querySelectorAll("input, select")
            .forEach((el) => el.classList.remove("error"));

        // Basic required validation
        ["firstName", "lastName", "sex", "email", "role", "status"].forEach(
            (id) => {
                const el = document.getElementById(id);
                if (!el.value) {
                    document.getElementById(id + "Error").textContent =
                        "This field is required";
                    el.classList.add("error");
                    valid = false;
                }
            }
        );

        // Email validation
        const email = document.getElementById("email");
        if (email.value && !/^\S+@\S+\.\S+$/.test(email.value)) {
            document.getElementById("emailError").textContent =
                "Invalid email address";
            email.classList.add("error");
            valid = false;
        }

        // Password match validation
        const pass = document.getElementById("password");
        const confirmPass = document.getElementById("confirmPassword");
        if (pass.value || confirmPass.value) {
            if (pass.value !== confirmPass.value) {
                document.getElementById("confirmPasswordError").textContent =
                    "Passwords don't match";
                confirmPass.classList.add("error");
                valid = false;
            }
        }

        if (!valid) e.preventDefault();
    });

    // Add button click
    const addBtn = document.getElementById("addUserBtn");
    if (addBtn) {
        addBtn.addEventListener("click", function (e) {
            e.preventDefault();
            openUserModal("add");
        });
    }
});
