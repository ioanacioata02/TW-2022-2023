let role = document.getElementById("rol").innerText.toLocaleLowerCase();
let proposeProblem = document.getElementById("propune-problema");
let myClass = document.getElementById("link-to-class");
let word = document.getElementById("singular-or-plural");

proposeProblem.classList.add("hidden");

if (role === "teacher") {
    proposeProblem.classList.remove("hidden");
    myClass.setAttribute("href", "myClasses_teacher.html");
    word.innerHTML="classes";
}
else{
    myClass.setAttribute("href", "myClass_student.html");
    word.innerHTML="class";
}