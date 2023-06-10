var tagsInput = document.getElementById("write");
var tagsArea = document.getElementById("tags");

var nrClicks = 0;
tagsArea.addEventListener("click", () => {
    if (nrClicks === 0){
        var placeholder = document.getElementById("tagsPlaceholder");
        placeholder.remove();
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
            var xBtn = document.createElement("div");
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
    var regex = /(\{\[.*\]\(.*\)\}\.)*\{\[.*\]\(.*\)\}\//;
    if (regex.test(testsContent)) {
        return true;
    }
    else {
        tests.style.borderColor = "red";
        return false;
    }
}

function validate() {
    return validateTests();
}