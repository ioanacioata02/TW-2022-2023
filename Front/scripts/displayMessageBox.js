function displayMessage(message, isError) {
    let errorBox = document.getElementById("feedback");
    if (isError)
        errorBox.style.backgroundColor = "red";
    else {
        errorBox.style.backgroundColor = "yellow";
    }
    let txt = errorBox.querySelector("p");
    txt.innerText = message;

    errorBox.appendChild(txt);
    errorBox.classList.remove("hidden");
}