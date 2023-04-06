let titleLinks = document.querySelectorAll(".link-to-probl");
titleLinks.forEach(titleLink => {
    titleLink.addEventListener("click",()=>{
        window.location = "proposedProblem.html";
    })
});