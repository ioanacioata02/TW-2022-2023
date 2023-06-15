let paginationNumbers = document.getElementById("pagination-numbers");
let tbody = document.getElementById("candidate-problems");
let rows = tbody ? tbody.querySelectorAll("tr") : null;
let nextButton = document.getElementById("next-button");
let prevButton = document.getElementById("prev-button");
let selectedNr = document.getElementById("rows-per-page");
let nrOfRows = selectedNr.value;
let pageCount = rows ? Math.ceil(rows.length / nrOfRows) : 0;
let currentPage = 1;

prevButton.addEventListener("click", () => {
    setCurrentPage(currentPage - 1);
});

nextButton.addEventListener("click", () => {
    setCurrentPage(currentPage + 1);
});

selectedNr.addEventListener("change", reloadPage);

function disableButton(button) {
    button.classList.add("disabled");
    button.setAttribute("disabled", true);
}

function enableButton(button) {
    button.classList.remove("disabled");
    button.removeAttribute("disabled");
}

function setLeftRightButtons() {
    if (currentPage === 1) {
        disableButton(prevButton);
    } else {
        enableButton(prevButton);
    }

    if (currentPage === pageCount) {
        disableButton(nextButton);
    } else {
        enableButton(nextButton);
    }
}

function setActiveButton() {
    let buttons = paginationNumbers.querySelectorAll(".pagination-number");
    buttons.forEach((button) => {
        button.classList.remove("active");
        let pageIndex = Number(button.getAttribute("page-index"));
        if (pageIndex === currentPage) {
            button.classList.add("active");
        }
    });
}

function appendDots() {
    let pageDots = document.createElement("button");
    pageDots.className = "pagination-dots";
    pageDots.innerHTML = "...";

    paginationNumbers.appendChild(pageDots);
}

function appendPageNumber(index) {
    let pageNumber = document.createElement("button");
    pageNumber.className = "pagination-number";
    pageNumber.innerHTML = index;
    pageNumber.setAttribute("page-index", index);

    pageNumber.addEventListener("click", () => {
        setCurrentPage(index);
    });

    paginationNumbers.appendChild(pageNumber);
}

function setAllButtons() {
    if (currentPage >= 3) {
        appendPageNumber(1);
    }
    if (currentPage >= 4) {
        appendDots();
    }

    if (currentPage - 1 >= 1) {
        appendPageNumber(currentPage - 1);
    }
    appendPageNumber(currentPage);
    if (currentPage + 1 <= pageCount) {
        appendPageNumber(currentPage + 1);
    }

    if (currentPage + 2 < pageCount) {
        appendDots();
        appendPageNumber(pageCount);
    }
    if (currentPage + 2 === pageCount) {
        appendPageNumber(pageCount);
    }
}

function setCurrentPage(pageNum) {
    currentPage = pageNum;

    resetButtons();
    setActiveButton();
    setLeftRightButtons();

    let prevRange = (pageNum - 1) * nrOfRows;
    let currRange = pageNum * nrOfRows;

    if (rows) {
        rows.forEach((row, index) => {
            row.style.display = "none";
            if (index >= prevRange && index < currRange) {
                row.style.display = "";
            }
        });
    }
}

function resetButtons() {
    paginationNumbers.innerHTML = "";
    if (rows) {
        pageCount = Math.ceil(rows.length / nrOfRows);
    } else {
        pageCount = 0;
    }
    setAllButtons();
}

function reloadPage() {
    nrOfRows = Number(selectedNr.value);
    setCurrentPage(1);
}

function loadPage() {
    setCurrentPage(1);
}

loadPage();
