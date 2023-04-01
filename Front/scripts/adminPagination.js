let paginationNumbers = document.getElementById("pagination-numbers");
let tbody = document.getElementById("candidate-problems");
let rows = tbody.querySelectorAll("tr");
let nextButton = document.getElementById("next-button");
let prevButton = document.getElementById("prev-button");
let selectedNr = document.getElementById("rows-per-page");
let nrOfRows = selectedNr.value;
let pageCount = Math.ceil(rows.length / nrOfRows);
let currentPage = 1;

prevButton.addEventListener("click", () => {
    setCurrentPage(currentPage - 1);
});


nextButton.addEventListener("click", () => {
    setCurrentPage(currentPage + 1);
});

selectedNr.onchange = reloadPage;

function disableButton(button) {
    button.classList.add("disabled");
    button.setAttribute("disabled", true);
};

function enableButton(button) {
    button.classList.remove("disabled");
    button.removeAttribute("disabled");
};

function setLeftRightButtons() {
    if (currentPage === 1) {
        disableButton(prevButton);
    } else {
        enableButton(prevButton);
    }

    if (pageCount === currentPage) {
        disableButton(nextButton);
    } else {
        enableButton(nextButton);
    }
};

function setActiveButton() {
    document.querySelectorAll(".pagination-number").forEach((button) => {
        button.classList.remove("active");
        let pageIndex = Number(button.getAttribute("page-index"));
        if (pageIndex == currentPage) {
            button.classList.add("active");
        }
    });
};

function appendPageNumber(index) {
    let pageNumber = document.createElement("button");
    pageNumber.className = "pagination-number";
    pageNumber.innerHTML = index;
    pageNumber.setAttribute("page-index", index);

    paginationNumbers.appendChild(pageNumber);
};

function getPaginationNumbers() {
    for (let i = 1; i <= pageCount; i++) {
        appendPageNumber(i);
    }
};

function setCurrentPage(pageNum) {
    currentPage = pageNum;

    setActiveButton();
    setLeftRightButtons();

    let prevRange = (pageNum - 1) * nrOfRows;
    let currRange = pageNum * nrOfRows;

    rows.forEach((row, index) => {
        row.classList.add("hidden");
        if (index >= prevRange && index < currRange) {
            row.classList.remove("hidden");
        }
    });
};

function loadPage() {
    getPaginationNumbers();
    setCurrentPage(1);

    document.querySelectorAll(".pagination-number").forEach((button) => {
        let pageIndex = Number(button.getAttribute("page-index"));

        if (pageIndex) {
            button.addEventListener("click", () => {
                setCurrentPage(pageIndex);
            });
        }
    });
};

function reset() {
    let btnFirstChild = paginationNumbers.firstChild;
    while (btnFirstChild) {
        paginationNumbers.removeChild(btnFirstChild);
        btnFirstChild = paginationNumbers.firstChild;
    }
    nrOfRows = Number(selectedNr.value);
    pageCount = Math.ceil(rows.length / nrOfRows);
}

function reloadPage() {
    reset();
    loadPage();
}
loadPage();