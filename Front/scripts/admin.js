let popUpBox = document.getElementById("pop-up-box");

function displayPromote() {
    popUpBox.classList.remove("hidden");
    console.log("display");
}

function hidePromote() {
    popUpBox.classList.add("hidden");
    console.log("hide");
}