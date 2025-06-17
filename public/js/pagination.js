const rowsPerPage = 10;

function paginateTable(tableId, paginationId) {
    const table = document.getElementById(tableId);
    const pagination = document.getElementById(paginationId);
    if (!table || !pagination) return;

    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr:not(#noRecordsRow)")).filter(row => row.style.display !== "none");

    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    function renderPage(page) {
        currentPage = page;

        rows.forEach((row, index) => {
            row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? "" : "none";
        });

        pagination.innerHTML = "";

        const prevBtn = document.createElement("li");
        prevBtn.classList.add("page-item");
        prevBtn.innerHTML = `<button class="page-link" ${page === 1 ? "disabled" : ""}>Previous</button>`;
        prevBtn.querySelector("button").addEventListener("click", () => {
            if (currentPage > 1) renderPage(currentPage - 1);
        });
        pagination.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.classList.add("page-item");
            li.innerHTML = `<button class="page-link ${i === page ? 'active' : ''}">${i}</button>`;
            li.querySelector("button").addEventListener("click", () => renderPage(i));
            pagination.appendChild(li);
        }

        const nextBtn = document.createElement("li");
        nextBtn.classList.add("page-item");
        nextBtn.innerHTML = `<button class="page-link" ${page === totalPages ? "disabled" : ""}>Next</button>`;
        nextBtn.querySelector("button").addEventListener("click", () => {
            if (currentPage < totalPages) renderPage(currentPage + 1);
        });
        pagination.appendChild(nextBtn);
    }

    if (rows.length > 0) {
        renderPage(1);
    } else {
        pagination.innerHTML = "";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    paginateTable('studentTable', 'pagination-student');
    paginateTable('caseTable', 'pagination-case');
    paginateTable('parentTable', 'pagination-parent');
});
