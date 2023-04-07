let role = document.getElementById("rol").innerText.toLocaleLowerCase();
let proposeProblem = document.getElementById("propune-problema");

proposeProblem.classList.add("hidden");

if (role === "teacher") {
    proposeProblem.classList.remove("hidden");
}

let logout = document.getElementById("logout");
logout.addEventListener("click", () => { loggedOut(); });