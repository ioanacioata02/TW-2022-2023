
function displayForbidden(element) {
    let bigTxt = element.querySelector("h1");
    bigTxt.innerText = "Access Denied";

    let paragraphs = element.querySelectorAll("p");
    paragraphs[0].innerText = "You do not have permission to access this page.";
    paragraphs[1].innerText = "Contact an administrator to get permissions or go to the home page or browse other pages.";

    let btn = element.querySelector("button");
    btn.innerText = "Go to home";
    btn.addEventListener("click", () => {
        window.location.href = "index.html";
    });

    element.parentNode.classList.remove("hidden");
}


function displayUnauthorized(element) {
    let bigTxt = element.querySelector("h1");
    bigTxt.innerText = "Unauthorized";

    let paragraphs = element.querySelectorAll("p");
    paragraphs[0].innerText = "You do not have permission to access this page.";
    paragraphs[1].innerText = "Please make sure you are logged in.";

    let btn = element.querySelector("button");
    btn.innerText = "Go to home";
    btn.addEventListener("click", () => {
        window.location.href = "index.html";
    });

    element.parentNode.classList.remove("hidden");
}