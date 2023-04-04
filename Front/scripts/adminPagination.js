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
})


nextButton.addEventListener("click", () => {
    setCurrentPage(currentPage + 1);
})

selectedNr.onchange = reloadPage;

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

    if (pageCount === currentPage) {
        disableButton(nextButton);
    } else {
        enableButton(nextButton);
    }
}

function setActiveButton() {
    document.querySelectorAll(".pagination-number").forEach((button) => {
        button.classList.remove("active");
        let pageIndex = Number(button.getAttribute("page-index"));
        if (pageIndex == currentPage) {
            button.classList.add("active");
        }
        if (pageIndex) {
            button.addEventListener("click", () => {
                setCurrentPage(pageIndex);
            });
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
        setCurrentPage(pageIndex);
    });

    paginationNumbers.appendChild(pageNumber);
}

function setAllButtons() {
    if(currentPage >= 3)
        appendPageNumber(1);
    if (currentPage  >= 4)
        appendDots();

    if(currentPage - 1 >= 1)
        appendPageNumber(currentPage - 1);
    appendPageNumber(currentPage);
    if(currentPage + 1 <= pageCount)
        appendPageNumber(currentPage+1);
    
    if(currentPage + 2 < pageCount){
        appendDots();
        appendPageNumber(pageCount);
    }
    if(currentPage + 2 === pageCount)
        appendPageNumber(pageCount);

}

function setCurrentPage(pageNum) {
    currentPage = pageNum;

    deleteAllNrBtns();
    setAllButtons();
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
}

function loadPage() {
    setCurrentPage(1);
}

function deleteAllNrBtns() {
    let btnFirstChild = paginationNumbers.firstChild;
    while (btnFirstChild) {
        paginationNumbers.removeChild(btnFirstChild);
        btnFirstChild = paginationNumbers.firstChild;
    }
}

function reset() {
    deleteAllNrBtns();
    nrOfRows = Number(selectedNr.value);
    pageCount = Math.ceil(rows.length / nrOfRows);
}

function reloadPage() {
    reset();
    loadPage();
}

loadPage();