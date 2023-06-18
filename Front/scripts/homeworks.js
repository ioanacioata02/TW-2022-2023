
async function fetchHomeworks() {
    try {
        const token = localStorage.getItem('token');
      const response = await fetch('http://localhost/homework', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
        },
      });
  
      if (response.ok) {
        const data = await response.json();
        console.log(data);
        const homeworks = data;
        displayHomeworks(homeworks);
      } else {
        console.error('Failed to fetch homeworks:', response.status);
      }
    } catch (error) {
      console.error('Error during fetch:', error);
    }
  }
  

  function displayHomeworks(homeworks) {
    table="<table>";
    for(let i=0;i<homeworks.length;i++)
    {const myHw = homeworks[i];
      table+="<tr>";
      table+="<td>"+myHw.id+"</td>";
      table+="<td>"+myHw.topic+"</td>";
      table+="<td>"+formatDate(myHw.deadline)+"</td>";
      table+="<td><button class=\"add_button\" onclick=\"viewHomework("+myHw.id+")\">View Homework</button></td>";
      table+="</tr>"
    }
    table+="</table>";
    document.getElementById("candidate-problems").innerHTML=table;
    updatePagination();
  }
  function displayCreateClass() {
    const token = localStorage.getItem('token');
    if (token) {
      const status = parseJwt(token).status;
      const myButton = document.querySelector('.myButton');
      const homeworkButton = myButton.querySelector('.homeworkButton');
      console.log(status);
      if (status === 1) {
        myButton.style.display = 'block';
        homeworkButton.style.display = 'block';
      } else {
        myButton.style.display = 'none';
        homeworkButton.style.display = 'none';
      }
    } else {
      console.log('Token does not exist');
    }
  }
  
  function parseJwt(token) {
    if (!token) {
      return;
    }
    const base64 = token.split('.')[1]; 
    return JSON.parse(window.atob(base64));
    }
    
  function viewHomework(homeworkId) {
    const token = localStorage.getItem('token');
    let status = parseJwt(token).status;

    console.log(status);
    if (status === 0) {
      document.location = `myHomework_student.html?hwId=${homeworkId}`;
    } else  {
      document.location = `myHomeworks_teacher.html?id=${homeworkId}`;
    } 
 
  }
  function redirectPage()
  {
    document.location = `createHomework.html`;
  }
  function formatDate(timestamp) {// formatul nu era human-readable, am folosit functia pentru a transforma
    const date = new Date(timestamp * 1000); 
    const year = date.getFullYear();
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const day = ('0' + date.getDate()).slice(-2);
    const hours = ('0' + date.getHours()).slice(-2);
    const minutes = ('0' + date.getMinutes()).slice(-2);
    const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}`;
    return formattedDate;
  }
  fetchHomeworks();
  displayCreateClass();
  