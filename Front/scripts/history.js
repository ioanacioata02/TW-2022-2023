let token = localStorage.getItem('token');
let solutionArea = document.getElementById("user-problems");
let pageNr = 1;
let btn = document.getElementById("more");
let nrSubmitsDisplayed = 0;
let solutions = [];

btn.addEventListener("click", () => {
    pageNr++;
    getOwnHistory();
})

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
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }
    console.log(solutions);

}

async function getOtherHistory(id) {
    try {
        const response = await fetch(`http://localhost/profile/history?id=${id}&page=${pageNr}`, {
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
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }

}

function displayOwnHistory(problem) {
    solutions.push(problem.solution);
    displayHistory(problem);
}

function displayOtherHistory(problem) {
    displayHistory(problem);
}

function displayHistory(problem) {

    let container = document.createElement("div");
    container.classList.add("problem");
    container.setAttribute('data-probl', problem.id_problem);
    container.setAttribute('data-nr-row', nrSubmitsDisplayed);
    container.setAttribute('data-success', problem.success);
    nrSubmitsDisplayed++;

    let title = document.createElement("div");
    title.innerText = problem.name_probl;

    let success = document.createElement("div");
    success.innerText = problem.success === true ? "Success" : "Failure";

    let moment = document.createElement("div");
    let date = new Date(problem.moment);
    moment.innerText = date.toLocaleString();

    container.appendChild(title);
    container.appendChild(success);
    container.appendChild(moment);
    solutionArea.append(container);

}

function loadUserId() {
    const url = window.location.href;
    const urlParams = new URL(url).searchParams;
    const id = urlParams.get('id');
    if (id === null) {
        getOwnHistory();
    }
    else {
        getOtherHistory(id);
    }
}

loadUserId();