let token = localStorage.getItem('token');

let paginationNumbers = document.getElementById("pagination-numbers");
let tBody = document.getElementById("candidate-problems");
let nextButton = document.getElementById("next-button");
let prevButton = document.getElementById("prev-button");
let selectedNr = document.getElementById("rows-per-page");
let nrOfRows;
let currentPage = 1;
let pageCount;
let nrOfProblems;
let problems;

prevButton.addEventListener("click", () => {
    setCurrentPage(currentPage - 1);
})

nextButton.addEventListener("click", () => {
    setCurrentPage(currentPage + 1);
})

selectedNr.onchange = loadPage;

function disableButton(button) {
    button.classList.add("disabled");
    button.setAttribute("disabled", true);
}

tBody.addEventListener("click", (event) => {
    const target = event.target;
    if (target.classList.contains("yes")) {
        const row = target.parentNode.parentNode;
        let id = row.childNodes[0].innerText;
        accept(id);
    } else if (target.classList.contains("no")) {
        const row = target.parentNode.parentNode;
        let id = row.childNodes[0].innerText;
        reject(id);
    }
});

function getProblems() {
    nrOfRows = selectedNr.value;
    fetch(`http://localhost/proposed/?page=${currentPage}&limit=${nrOfRows}`, {
        method: 'GET',
        headers: {
            'Authorization': token
        }
    })
        .then(response => {
            if (!response.ok) {
                if (response.status === 403) {
                    displayForbidden();
                }
                if (response.status === 401) {
                    displayUnauthorized();
                }
                return response.json().then(data => {
                    throw new Error(data.message);
                });
            }
            return response.json();
        })
        .then(data => {
            nrOfProblems = data.nrOfProblems;
            problems = data.problems;
            console.log(problems);
            console.log(nrOfProblems);
            pageCount = Math.ceil(nrOfProblems / nrOfRows);

            //console.log(pageCount);
            //console.log("###");

            deleteAllNrBtns();
            setAllButtons();
            //setActiveButton();
            setLeftRightButtons();

            deleteAllRows();

            problems.map((problem) => {
                createRow(problem);
            });
            let content = document.getElementById("content");
            content.classList.remove("hidden");
        })
        .catch(error => {
            console.error('An error occurred:', error.message);
        });
}

function createRow(problem) {
    // create almost all elements
    let line = document.createElement("tr");
    let id = document.createElement("td");
    let title = document.createElement("td");
    title.classList.add("link");
    let tags = document.createElement("td");
    tags.classList.add("tags");
    let user_id = document.createElement("td");
    user_id.classList.add("link");
    let actions = document.createElement("td");
    let yesBtn = document.createElement("button");
    yesBtn.classList.add("yes");
    let noBtn = document.createElement("button");
    noBtn.classList.add("no");

    // add data
    id.innerText = problem.id;
    title.innerText = problem.name;
    user_id.innerText = problem.id_author;
    yesBtn.innerText = "yes";
    noBtn.innerText = "no";

    // add all tags
    let allTags = problem.tags;
    console.log(allTags);
    allTags.map((tag) => {
        let tagBtn = document.createElement("button");
        if (tag.startsWith('"') && tag.endsWith('"')) {
            tag = tag.slice(1);
            tag = tag.slice(0, -1);
        }

        tagBtn.innerText = tag;
        tags.appendChild(tagBtn);
    });

    // event listeners
    title.addEventListener("click", () => {
        window.location = `proposedProblem.html?id=${problem.id}`;
    })

    user_id.addEventListener("click", () => {
        window.location = `otherProfile.html?id=${problem.id_author}`;
    })

    // append
    line.appendChild(id);
    line.appendChild(title);
    line.appendChild(tags);
    line.appendChild(user_id);

    actions.appendChild(yesBtn);
    actions.appendChild(noBtn);

    line.appendChild(actions);
    tBody.appendChild(line);
}

function deleteAllRows() {
    while (tBody.firstChild) {
        tBody.removeChild(tBody.firstChild);
    }
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

/*
function setActiveButton() {
    document.querySelectorAll(".pagination-number").forEach((button) => {
        button.classList.remove("active");
        let pageIndex = Number(button.getAttribute("page-index"));
        if (pageIndex === currentPage) {
            button.classList.add("active");
            button.removeEventListener("click", buttonAction);
        }
        else {
            button.addEventListener("click", buttonAction);
        }
    });
}
*/


function appendDots() {
    let pageDots = document.createElement("button");
    pageDots.className = "pagination-dots";
    pageDots.innerText = "...";

    paginationNumbers.appendChild(pageDots);
}

function appendPageNumber(index) {
    let pageNumber = document.createElement("button");
    pageNumber.className = "pagination-number";
    pageNumber.innerText = index;
    pageNumber.setAttribute("page-index", index);

    if (index === currentPage) {
        pageNumber.classList.add("active");
        pageNumber.removeEventListener("click", () => {
            setCurrentPage(index);
        });
    }
    else {
        pageNumber.addEventListener("click", () => {
            setCurrentPage(index);
        });
    }

    paginationNumbers.appendChild(pageNumber);
}

function setAllButtons() {
    if (currentPage >= 3)
        appendPageNumber(1);
    if (currentPage >= 4)
        appendDots();

    if (currentPage - 1 >= 1)
        appendPageNumber(currentPage - 1);

    appendPageNumber(currentPage);
    if (currentPage + 1 <= pageCount)
        appendPageNumber(currentPage + 1);

    if (currentPage + 2 < pageCount) {
        appendDots();
        appendPageNumber(pageCount);
    }
    if (currentPage + 2 === pageCount)
        appendPageNumber(pageCount);

}

function setCurrentPage(pageNum) {
    currentPage = pageNum;
    getProblems();
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
/*
function reset() {
    deleteAllNrBtns();
    nrOfRows = Number(selectedNr.value);
    pageCount = Math.ceil(nrOfProblems / nrOfRows);
}

function reloadPage() {
    reset();
    loadPage();
}*/

function accept(id) {

    fetch(`http://localhost/proposed/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': token
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            if (currentPage > 1 && problems.length === 1)
                setCurrentPage(currentPage - 1);
            else
                setCurrentPage(currentPage);
        })
        .catch(error => {
            console.error('An error occurred:', error.message);
        });
}

function reject(id) {
    console.log("rejected");
    fetch(`http://localhost/proposed/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': token
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            if (currentPage > 1 && problems.length === 1)
                setCurrentPage(currentPage - 1);
            else
                setCurrentPage(currentPage);
        })
        .catch(error => {
            console.error('An error occurred:', error.message);
        });
}

loadPage();


let popUpBox = document.getElementById("pop-up-box");
let selectedRole = document.getElementById("user-type");
let emailElement = document.getElementById("enter-email");

function displayPromote() {
    popUpBox.classList.remove("hidden");
}

function hidePromote() {
    popUpBox.classList.add("hidden");
}
function trySend(event) {
    event.preventDefault();
    let role = selectedRole.value;
    let email = emailElement.value;

    fetch(`http://localhost/promote/${role}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': token
        },
        body: JSON.stringify({ "email": email })
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    if (response.status === 404 || response.status === 422 || response.status === 409) {
                        displayMessage(data.message, true);
                    }
                    throw new Error(data.message);
                });
            }
            else {
                displayMessage("User promoted", false);
            }
        })
        .catch(error => {
            console.error('An error occurred:', error.message);
        });
}