let role = document.getElementById("rol").innerText.toLocaleLowerCase();
let proposeProblem = document.getElementById("propune-problema");
let myClass = document.getElementById("link-to-class");
let popUpBox = document.getElementById("pop-up-box");

proposeProblem.classList.add("hidden");

if (role === "teacher") {
    proposeProblem.classList.remove("hidden");
    myClass.setAttribute("href", "myClasses_teacher.html");
}
else {
    myClass.setAttribute("href", "myClass_student.html");
}

function displayChangePassword() {
    popUpBox.classList.remove("hidden");
}

function hideChangePassword() {
    popUpBox.classList.add("hidden");
}