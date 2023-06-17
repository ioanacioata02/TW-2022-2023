

const urlParams =  new URLSearchParams(window.location.search);
popualteData();


function popualteData()
{
   
    document.getElementById("title-text").textContent=urlParams.get("id")+" ."+urlParams.get("name");
    document.getElementById("description-text").textContent=urlParams.get('description');
    document.getElementById("rating-text").textContent=urlParams.get("acceptance");
    document.getElementById("difficulty-text").textContent=urlParams.get("difficulty");
    //console.log(text)
}

function getComments()
{
    const token ="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJBRE1JTiIsInN0YXR1cyI6IjIiLCJjcmVhdGlvbkRhdGUiOjE2ODY5MTgyNjcsImV4cGlyYXRpb25EYXRlIjoxNjg5NTEwMjY3fQ==.0tcrRiYYa2wq0X+fYE/gs1zzlEcIrSEWlY0GATnSKhk=" //localStorage.getItem('token');
    const requestOptions = {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization':  token
      },
     
    };
    url = "http://localhost/comments/?id="+urlParams.get("id");
    let responseData; // Variable to store the response data
    fetch(url, requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            //console.log(data);
            container = document.getElementById("comments");
            container.innerHTML="";
            data.forEach(element => appendComment(element, container));
        })
        .catch(error => {console.error('Error:', error);});

}





function submitSolution()
{
    const url = 'http://localhost/solutions/?id='+urlParams.get("id");
    let text=  document.getElementById("code").value;
    if(text == "")
    {
        return;
    }
    const body = JSON.stringify({ solution:text});
    const token ="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJBRE1JTiIsInN0YXR1cyI6IjIiLCJjcmVhdGlvbkRhdGUiOjE2ODY5MTgyNjcsImV4cGlyYXRpb25EYXRlIjoxNjg5NTEwMjY3fQ==.0tcrRiYYa2wq0X+fYE/gs1zzlEcIrSEWlY0GATnSKhk=" //localStorage.getItem('token');

  
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization':  token
      },
      body: body
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Request failed.');
        }
        return response.json();
      })
      .then(data => {
        console.log('Response:', data);
        // Handle the response data here
      })
      .catch(error => {
        console.error('Error:', error);
        // Handle any error that occurred during the request
      });
}


function getSolutions()
{
    const token ="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJBRE1JTiIsInN0YXR1cyI6IjIiLCJjcmVhdGlvbkRhdGUiOjE2ODY5MTgyNjcsImV4cGlyYXRpb25EYXRlIjoxNjg5NTEwMjY3fQ==.0tcrRiYYa2wq0X+fYE/gs1zzlEcIrSEWlY0GATnSKhk=" //localStorage.getItem('token');
    const requestOptions = {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization':  token
      },
     
    };
    url = "http://localhost/solutions/?id="+urlParams.get("id");
    let responseData; // Variable to store the response data
    fetch(url, requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            //console.log(data);
            container = document.getElementById("solutions");
            container.innerHTML="";
            data.forEach(element => appendSolution(element, container));
        })
        .catch(error => {console.error('Error:', error);});
}

function appendSolution(json, container)
{   
    let anchor = document.createElement('a');
    anchor.href = "viewSolution.html?solution="+encodeURIComponent(json.solution);
    anchor.classList.add('solution-format');
  
    // Create div element
    let div = document.createElement('div');
    div.classList.add('solution-date');
    div.innerText = json.date;
  
    // Append div element to anchor element
    anchor.appendChild(div);
  
    // Append anchor element to container
    container.appendChild(anchor);
   
}

function appendComment(json, container) {
    // Create the comment container div
    //console.log(json);
    const commentDiv = document.createElement('div');
    commentDiv.classList.add('comment-format');
  
    // Create the comment title div
    const titleDiv = document.createElement('div');
    titleDiv.classList.add('comment-title');
    titleDiv.textContent = json.title;
    commentDiv.appendChild(titleDiv);
  
    // Create the author and date div
    const authorDateDiv = document.createElement('div');
    authorDateDiv.classList.add('author-date');
  
    // Create the grade div

    const dateDiv = document.createElement('div');
    dateDiv.classList.add('grade');
    dateDiv.innerHTML = json.moment;
   

    const gradeDiv = document.createElement('div');
    gradeDiv.classList.add('grade');
    gradeDiv.innerHTML = "grade"+":"+ json.grade ;


    authorDateDiv.appendChild(dateDiv)
    authorDateDiv.appendChild(gradeDiv);
    commentDiv.appendChild(authorDateDiv);


  
    // Create the comment text div
    const textDiv = document.createElement('div');
    textDiv.classList.add('comment-text');
    textDiv.textContent = json.comment_txt;
    commentDiv.appendChild(textDiv);
  
    // Append the comment container to the specified container element
    container.appendChild(commentDiv);
  }
  


function makeComment() {
    const token ="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJBRE1JTiIsInN0YXR1cyI6IjIiLCJjcmVhdGlvbkRhdGUiOjE2ODY5MTgyNjcsImV4cGlyYXRpb25EYXRlIjoxNjg5NTEwMjY3fQ==.0tcrRiYYa2wq0X+fYE/gs1zzlEcIrSEWlY0GATnSKhk=" //localStorage.getItem('token');
    const body = JSON.stringify({ grade:document.getElementById("difficulty").value, title: document.getElementById("title").value , comment_txt: document.getElementById("justification").value});
    const requestOptions = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization':  token
      },
      body: body
    };
  

    fetch('http://localhost/comments/?id='+urlParams.get("id"), requestOptions)
    .then(response => {
      if (response.ok) {
        // Process the successful response here
      } else {
        response.json().then(errorJson => {
          console.error('Post request failed:', errorJson);
        });
      }
    })
    .catch(error => {
      console.error('An error occurred:', error);
    });
  }

  function submitToHomework()
{
    const url = 'http://localhost/homework/?submit='+urlParams.get("hwId");
    let text=  document.getElementById("code").value;
    if(text == "")
    {displayMessage("No code to submit!",true);
        return;
    }
    const body = JSON.stringify({ solution: text, id_problem: urlParams.get("id")});
    console.log(body);

    const token = localStorage.getItem('token');
  
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization':  token
      },
      body: body
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Request failed.');
        }
        return response.json();
      })
      .then(data => {
        console.log('Response:', data);
        displayMessage(data.message,false);
      })
      .catch(error => {displayMessage("Error while submiting the solution",true);
        console.error('Error:', error);
        // Handle any error that occurred during the request
      });
}

