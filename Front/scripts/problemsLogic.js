
fetchProblemsDeafult();

function fetchProblemsDeafult() {
    url = "http://localhost/problems/";
    let responseData; // Variable to store the response data
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            container = document.getElementById("problems-list-display");
            data.forEach(element => processItem(element, container));
        })
        .catch(error => {console.error('Error:', error);});
}


function sortByText()
{
    let searchbar =  document.getElementById("searchTEXT");
    url = "http://localhost/problems/?sort=TEXT&text="+searchbar.value;
    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        container = document.getElementById("problems-list-display");
        container.innerHTML="";
        data.forEach(element => processItem(element, container));
    })
    .catch(error => {console.error('Error:', error);});
}



function sortByDifficulty(ORDER)
{
    url = "http://localhost/problems/?sort=DIFFICULTY&type="+ORDER;
    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        container = document.getElementById("problems-list-display");
        container.innerHTML="";
        data.forEach(element => processItem(element, container));
    })
    .catch(error => {console.error('Error:', error);});
}



function processItem(element, contianer)
{
    console.log(element.name)
    console.log(element);
    let diff=(element.average_grade);
    let a = document.createElement('a');
    a.href = "./problem.html?id="+element.id+"&name="+element.name+"&description="+element.description+"&acceptance="+diff*100+"%"+"&difficulty="+diff*5+"&tags="+element.tags;
    a.classList.add('problem-list');
    let div = document.createElement('div');
    div.classList.add('problem');
    let divMainStart = document.createElement('div');
    divMainStart.classList.add('problem-main-start');
    let spanId = document.createElement('span');
    spanId.classList.add('id');
    spanId.textContent = element.id+" .";
    let spanTitle = document.createElement('span');
    spanTitle.classList.add('title');
    spanTitle.textContent = element.name;
    //todo
    let spanMain = document.createElement('span');
    spanMain.classList.add('problem-main');
    spanMain.textContent = "";
    let spanAcceptanceRate = document.createElement('span');
    spanAcceptanceRate.classList.add('problem-main', 'acceptance-rate');
    
 
    let spanMainEnd = document.createElement('span');
    spanMainEnd.classList.add('problem-main-end');
    spanMainEnd.textContent = diff*5;
    divMainStart.appendChild(spanId);
    divMainStart.appendChild(spanTitle);
    div.appendChild(divMainStart);
    div.appendChild(spanMain);
    div.appendChild(spanAcceptanceRate);
    div.appendChild(spanMainEnd);
    a.appendChild(div);
    contianer.appendChild(a)
    
}