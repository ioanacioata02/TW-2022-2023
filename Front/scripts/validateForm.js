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

        let tagTxt = tagsInput.innerText.trim();
        if (tagTxt !== '') {
            //first div
            let tag = document.createElement("div");
            tag.className = "tag";
            tag.setAttribute("contenteditable", "false");

            let tagSpan = document.createElement("span");

            // content
            tagSpan.innerText = tagTxt;

            // X btn
            let xBtn = document.createElement("div");
            xBtn.className = "close-btn";
            xBtn.innerText = "\u2716";

            //appends
            tag.appendChild(tagSpan);
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
        if (!response.ok) {
            console.log('An error occurred:', data.message);
            return;
        } else {
            const data = await response.json();
            
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
        let tagSpan = tag.querySelector("span");
        console.log(tagSpan.innerText);
        tagsArray.push(tagSpan.innerText);
    }

    //tests
    let tests = document.getElementById("tests").value;
    let regex = /(\{\[.+\]\(.+\)\}\.)/g;
    let firstTests = tests.match(regex);
    let testArray = [];
    if (firstTests !== null) {
        let len = firstTests.length;
        for (let i = 0; i < len; i++) {
            let lineRegex = /\{\[(.+)\]\((.+)\)\}/;
            let test = firstTests[i].match(lineRegex);
            test = {
                "input": test[1],
                "output": test[2]
            }
            testArray.push(test);
        }
    }
    let lineRegex = /\{\[.+\]\(.+\)\}\//;
    let test = tests.match(lineRegex);
    lineRegex = /\{\[(.+)\]\((.+)\)\}/;
    test = test[0].match(lineRegex);
    test = {
        "input": test[1],
        "output": test[2]
    }
    testArray.push(test);
    //console.log(testArray);

    let jsonProblem = {
        "name": title,
        "description": description,
        "tags": tagsArray,
        "tests": testArray
    };
    jsonProblem = JSON.stringify(jsonProblem);
    //console.log(jsonProblem);
    return jsonProblem;
}