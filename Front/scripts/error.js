
function displayForbidden() {
    //create elements
    let wrapper = document.createElement("div");

    let forbidden = document.createElement("div");
    forbidden.setAttribute("id", "forbidden");

    let textDiv = document.createElement("div");
    textDiv.classList.add("text");

    let bigTxt = document.createElement("h1");
    bigTxt.innerText = "Access Denied";

    let paragraph1 = document.createElement("p");
    paragraph1.innerText = "You do not have permission to access this page.";

    let paragraph2 = document.createElement("p");
    paragraph2.innerText = "Contact an administrator to get permissions or go to the home page or browse other pages.";

    let btn = document.createElement("button");
    btn.innerText = "Go to home";
    btn.addEventListener("click", () => {
        window.location.href = "index.html";
    });



    //append
    addXSvg(forbidden);

    textDiv.appendChild(bigTxt);
    textDiv.appendChild(paragraph1);
    textDiv.appendChild(paragraph2);
    textDiv.appendChild(btn);

    forbidden.appendChild(textDiv);

    wrapper.appendChild(forbidden);

    let body = document.body;
    body.appendChild(wrapper);
}

function displayUnauthorized() {
    //create elements
    let wrapper = document.createElement("div");

    let forbidden = document.createElement("div");
    forbidden.setAttribute("id", "forbidden");

    let textDiv = document.createElement("div");
    textDiv.classList.add("text");

    let bigTxt = document.createElement("h1");
    bigTxt.innerText = "Unauthorized";

    let paragraph1 = document.createElement("p");
    paragraph1.innerText = "You do not have permission to access this page.";

    let paragraph2 = document.createElement("p");
    paragraph2.innerText = "Please make sure you are logged in.";

    let btn = document.createElement("button");
    btn.innerText = "Go to home";
    btn.addEventListener("click", () => {
        window.location.href = "index.html";
    });



    //append
    addXSvg(forbidden);

    textDiv.appendChild(bigTxt);
    textDiv.appendChild(paragraph1);
    textDiv.appendChild(paragraph2);
    textDiv.appendChild(btn);

    forbidden.appendChild(textDiv);

    wrapper.appendChild(forbidden);

    let body = document.body;
    body.appendChild(wrapper);
}

function addXSvg(forbidden) {
    // create elements
    let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("viewBox", "0 0 100 100");

    let circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    circle.setAttribute("cx", "50");
    circle.setAttribute("cy", "50");
    circle.setAttribute("r", "50");
    circle.setAttribute("fill", "red");

    let diagonal1 = document.createElementNS("http://www.w3.org/2000/svg", "line");
    diagonal1.setAttribute("x1", "25");
    diagonal1.setAttribute("y1", "25");
    diagonal1.setAttribute("x2", "75");
    diagonal1.setAttribute("y2", "75");
    diagonal1.setAttribute("stroke", "white");
    diagonal1.setAttribute("stroke-width", "15");
    diagonal1.setAttribute("stroke-linecap", "round");

    let diagonal2 = document.createElementNS("http://www.w3.org/2000/svg", "line");
    diagonal2.setAttribute("x1", "25");
    diagonal2.setAttribute("y1", "75");
    diagonal2.setAttribute("x2", "75");
    diagonal2.setAttribute("y2", "25");
    diagonal2.setAttribute("stroke", "white");
    diagonal2.setAttribute("stroke-width", "15");
    diagonal2.setAttribute("stroke-linecap", "round");


    //append
    svg.appendChild(circle);
    svg.appendChild(diagonal1);
    svg.appendChild(diagonal2);

    forbidden.appendChild(svg);
}


function displayNotFound() {
    // create elements
    let notFound = document.createElement("div");
    notFound.setAttribute("id", "not-found");

    let number = document.createElement("h1");
    number.innerText = "404";

    let bigTxt = document.createElement("h2");
    bigTxt.innerText = "Not found";

    let paragraph = document.createElement("p");
    paragraph.innerText = "The page you are looking for may have been moved, deleted or possibly never existed.";



    //append
    notFound.appendChild(number);
    notFound.appendChild(bigTxt);
    notFound.appendChild(paragraph);
    document.body.appendChild(notFound);
}