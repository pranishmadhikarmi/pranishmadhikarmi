let currentPage = 1;
const itemsPerPage = 8;
const container = document.getElementById("product-container");
const prevBtn = document.getElementById("prevPage");
const nextBtn = document.getElementById("nextPage");
const pageNumbers = document.getElementById("pageNumbers");
let totalPages = 1;

function loadPage(page) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", `../Manage_product/load_more.php?page=${page}&limit=${itemsPerPage}`, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            container.innerHTML = response.products;
            totalPages = response.totalPages;
            updatePagination(page);
        }
    };
    xhr.send();
}

function updatePagination(page) {
    currentPage = page;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;

    pageNumbers.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        let pageBtn = document.createElement("button");
        pageBtn.textContent = i;
        pageBtn.classList.add("page-btn");
        if (i === currentPage) {
            pageBtn.style.fontWeight = "bold";
            pageBtn.classList.add("active");
        }
        pageBtn.addEventListener("click", function() {
            loadPage(i);
        });
        pageNumbers.appendChild(pageBtn);
    }
}

// Pagination Buttons
prevBtn.addEventListener("click", function() {
    if (currentPage > 1) {
        loadPage(currentPage - 1);
    }
});

nextBtn.addEventListener("click", function() {
    if (currentPage < totalPages) {
        loadPage(currentPage + 1);
    }
});

// Load first page on start
loadPage(1);
