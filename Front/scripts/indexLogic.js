

problemsDisplay=  document.getElementById("problemsDelay");
problemsDisplay.style.display="none";
fetchPopularProblems();

async function fetchPopularProblems(display) {
    const url = "http://localhost/problems/?sort=POPULARITY&limit=4";

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        const container = document.getElementById("popular_problems");
        data.forEach(element => processItem(element, container));
        problemsDisplay.style.display="block";
    } catch (error) {
        console.error('Error:', error);
    }
}

function processItem(element,container)
{
    // Create the <a> element
    let cardLink = document.createElement("a");
    let diff=Number.parseFloat(element.average_grade).toFixed(2);;
    cardLink.href =  "./problem.html?id="+element.id+"&name="+element.name+"&description="+element.description+"&acceptance="+diff*100+"%"+"&difficulty="+diff*5;
    cardLink.className = "card";
    let title = document.createElement("h3");
    title.className = "title-card";
    title.innerHTML = element.name
    // Create the <p> element for the description
    let description = document.createElement("p");
    description.className = "description";
    description.innerHTML = element.description;
    let difficulty = document.createElement("span");
    difficulty.className = "difficulty";
    difficulty.innerHTML = "Difficulty " + Number.parseFloat(element.average_grade).toFixed(2);;
    cardLink.appendChild(title);
    cardLink.appendChild(description);
    cardLink.appendChild(difficulty);
    container.appendChild(cardLink);

}