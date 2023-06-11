var tagsInput = document.getElementById("write");
var tagsArea = document.getElementById("tags");

var nrClicks = 0;

tagsArea.addEventListener("click", () => {
    if (nrClicks === 0) {
        let placeholder = document.getElementById("tagsPlaceholder");
        placeholder.remove();
        nrClicks++;
    }
    else nrClicks++;
});

tagsInput.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();

        var tagTxt = tagsInput.innerText.trim();
        if (tagTxt !== '') {
            //first div
            var tag = document.createElement("div");
            tag.className = "tag";
            tag.setAttribute("contenteditable", "false");

            // content
            tag.innerText = tagTxt;

            // X btn
            let xBtn = document.createElement("div");
            xBtn.className = "close-btn";
            xBtn.innerText = "\u2716";

            //appends
            tag.appendChild(xBtn);
            tagsArea.insertBefore(tag, tagsInput);


            xBtn.addEventListener("click", () => {
                tag.remove();
            });
        }
        tagsInput.innerText = "";
    }
});

function validateTests() {
    var tests = document.getElementById("tests");
    var testsContent = tests.value;
    /*
    Valid example:
    {[input](output)}.
    {[input](output)}.
    {[input](output)}/
    */
    var regex = /(\{\[.+\]\(.+\)\}\.)*\{\[.+\]\(.+\)\}\//;

    if (regex.test(testsContent)) {
        tests.style.borderColor = "#3AAFA9"; // in case it was red before
        return true;
    }
    else {
        tests.style.borderColor = "red";
        return false;
    }
}

function atLeastOneTag() {
    var tags = tagsArea.querySelectorAll('.tag');
    var len = tags.length;

    if (len > 0) {
        tagsArea.style.borderColor = "#3AAFA9";
        return true;
    }

    tagsArea.style.borderColor = "red";
    return false;
}

function trySend(event) {
    event.preventDefault();
    let ok = atLeastOneTag() && validateTests();
    if (ok) {
        sendProblem();
    }
}

async function sendProblem() {
    let jsonProblem = prepareData();

    let token = localStorage.getItem('token');

    try {
        const response = await fetch('http://localhost/proposed', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token
            },
            body: jsonProblem
        });
        const data = await response.json();
        if (!response.ok) {
            console.log('An error occurred:', data.message);
            return;
        } else {
            let mainForm = document.getElementById("main");
            mainForm.classList.add("hidden");

            let popUpBox = document.getElementById("pop-up-box");
            popUpBox.classList.remove("hidden");

            //console.log(data);
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }
}

function prepareData() {
    let title = document.getElementById("title").value;
    let description = document.getElementById("description").value;

    //tags
    var tags = tagsArea.querySelectorAll('.tag');
    let tagsArray = [];
    for (const tag of tags) {
        let copy = tag.cloneNode(true);
        copy.removeChild(copy.querySelector('.close-btn'));
        tagsArray.push(copy.innerText);
    }

    //tests
    const tests = document.getElementById("tests").value;
    let regex = /(\{\[.+\]\(.+\)\}\.)/g;
    let firstTests = tests.match(regex);
    let len = firstTests.length;
    let testArray = [];
    for (let i = 0; i < len; i++) {
        let lineRegex = /\{\[(.+)\]\((.+)\)\}/;
        let test = firstTests[i].match(lineRegex);
        test = {
            "input": test[1],
            "output": test[2]
        }
        testArray.push(test);
    }
    let jsonProblem = {
        "name": title,
        "description": description,
        "tags": tagsArray,
        "tests": testArray
    };
    jsonProblem = JSON.stringify(jsonProblem);
    return jsonProblem;
}