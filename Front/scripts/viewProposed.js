let token = localStorage.getItem('token');

async function loadProposed(id) {
    console.log("aici");
    try {
        const response = await fetch(`http://localhost/proposed/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });
        const data = await response.json();
        if (!response.ok) {
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
            console.log(data);
            loadData(data);

        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }

}

function loadData(problem) {
    let title = document.getElementById("title");
    title.value = problem.name;

    let description = document.getElementById("description");
    description.value = problem.description;

    //tags
    let tagsArea = document.getElementById("tags");
    let tags = problem.tags;
    let nrTags = tags.length;
    for (let i = 0; i < nrTags; i++) {
        let tag = document.createElement("div");
        tag.className = "tag";
        tag.setAttribute("contenteditable", "false");

        // content
        tag.innerText = tags[i];

        // X btn
        let xBtn = document.createElement("div");
        xBtn.className = "close-btn";
        xBtn.innerText = "\u2716";

        //appends
        tag.appendChild(xBtn);
        tagsArea.appendChild(tag);
    }


    // tests
    let allTests = problem.tests;
    let nrOfTests = allTests.length;
    let tests = "";
    for (let i = 0; i < nrOfTests - 1; i++) {
        const test = allTests[i];
        const input = test.input;
        const output = test.output;

        const testLine = `{[${input}](${output})}.\n`;
        tests = tests + testLine;
    }
    const test = allTests[nrOfTests - 1];
    const input = test.input;
    const output = test.output;
    const testLine = `{[${input}](${output})}/`;
    tests = tests + testLine;

    let testsArea = document.getElementById("tests");
    testsArea.value = tests;
}

function loadProposedProblem() {
    const url = window.location.href;
    const urlParams = new URL(url).searchParams;
    const id = urlParams.get('id');
    loadProposed(id);
}

loadProposedProblem();