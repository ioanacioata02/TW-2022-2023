let token = localStorage.getItem('token');
let solutionArea = document.getElementById("user-problems");
let pageNr = 1;
let btn = document.getElementById("more");
let nrSubmitsDisplayed = 0;


async function getOwnHistory() {
    try {
        const response = await fetch(`http://localhost/profile/history?page=${pageNr}`, {
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 404) {
                displayNotFound();
            }
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
            console.log(data);
            let submits = data.submits;
            let total = data.nrOfSubmits;
            let length = submits.length;
            for (let i = 0; i < length; i++) {
                displayOwnHistory(submits[i]);
            }
            if (nrSubmitsDisplayed === total)
                btn.classList.add("hidden");
            else if (btn.classList.contains("hidden"))
                btn.classList.remove("hidden");

            console.log(submits);
            showAll();
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }
}

async function getOtherHistory(id) {
    try {
        const response = await fetch(`http://localhost/profile/history?id=${id}&page=${pageNr}`, {
            method: 'GET'
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 404) {
                displayNotFound();
            }
            if (response.status === 401) {
                displayUnauthorized();
            }
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
            console.log(data);
            let submits = data.submits;
            let total = data.nrOfSubmits;
            let length = submits.length;

            for (let i = 0; i < length; i++) {
                displayOtherHistory(submits[i]);
            }

            if (nrSubmitsDisplayed === total)
                btn.classList.add("hidden");
            else if (btn.classList.contains("hidden"))
                btn.classList.remove("hidden");

            showAll();
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }

}

function showAll() {
    let allContent = document.getElementById("content-all");

    if (allContent.classList.contains("hidden"))
        allContent.classList.remove("hidden");
}

function displayOwnHistory(submit) {
    let container = displayHistory(submit);
    container.addEventListener("click", () => {
        window.location.href = `viewSolution.html?id=${submit.id}`;
    });
}

function displayOtherHistory(submit) {
    let container = displayHistory(submit);
    container.addEventListener("click", () => {
        getProblem(submit.id_problem)
    });
}

function displayHistory(submit) {

    let container = document.createElement("div");
    container.classList.add("problem");
    //container.setAttribute('data-probl', submit.id_problem);
    //container.setAttribute('data-nr-row', nrSubmitsDisplayed);
    nrSubmitsDisplayed++;

    let title = document.createElement("div");
    title.innerText = submit.name;


    let moment = document.createElement("div");
    let date = new Date(submit.moment);
    moment.innerText = date.toLocaleString();

    container.appendChild(title);
    container.appendChild(moment);
    solutionArea.append(container);

    return container;
}

function loadUserId() {
    const url = window.location.href;
    const urlParams = new URL(url).searchParams;

    const username = urlParams.get('username');

    const id = urlParams.get('id');
    if (id === null) {
        displayUsername(username, true);

        btn.addEventListener("click", () => {
            pageNr++;
            getOwnHistory();
        });

        getOwnHistory();
    }
    else {
        displayUsername(username, false);

        btn.addEventListener("click", () => {
            pageNr++;
            getOtherHistory(id);
        });

        getOtherHistory(id);
    }
}

function displayUsername(username, me) {
    let usernameDiv = document.createElement("div");
    usernameDiv.classList.add("username");

    let text = document.createElement("h1");
    if (!me) {
        if (username !== null)
            text.innerText = username + "'s history:";
        else
            text.innerText = "History:";
    }
    else
        text.innerText = "Your history:";

    let main = document.getElementById("content");

    usernameDiv.appendChild(text);
    main.insertBefore(usernameDiv, main.firstChild);
}

function getProblem(id) {
    fetch(`http://localhost/problems/?id=${id}`, {
        method: 'GET'
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
            let diff = data.nr_attempts / (1 + data.nr_successes);
            window.location = "problem.html?id=" + data.id + "&name=" + data.name + "&description=" + data.description + "&acceptance=" + diff * 100 + "%" + "&difficulty=" + diff * 5;
        })
        .catch(error => {
            console.error('An error occurred:', error.message);
        });
}

loadUserId();