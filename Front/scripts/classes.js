
async function fetchClasses() {
    try {
        const token = localStorage.getItem('token');
      const response = await fetch('http://localhost/classes', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
      });
  
      if (response.ok) {
        const data = await response.json();
        console.log(data);
        const classes = data.classes;
        displayClasses(classes);
      } else {
        console.error('Failed to fetch classes:', response.status);
      }
    } catch (error) {
      console.error('Error during fetch:', error);
    }
  }
  

  function displayClasses(classes) {
    table="<table>";
    for(let i=0;i<classes.length;i++)
    {const myClass = classes[i];
      table+="<tr>";
      table+="<td>"+myClass.id+"</td>";
      table+="<td>"+myClass.name+"</td>";
      table+="<td>"+myClass.numMembers+"</td>";
      table+="<td><button class=\"add_button\" onclick=\"viewClass("+myClass.id+")\">View Class</button></td>";
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
    
  function viewClass(classId) {
    const token = localStorage.getItem('token');
    let status = parseJwt(token).status;

    console.log(status);
    if (status === 0) {
      document.location = `myClass_student.html?classId=${classId}`;
    } else if (status === 1) {
      document.location = `myClasses_teacher.html?classId=${classId}`;
    } else {
      document.location = `index.html`;
    }
 
  }
  function redirectPage()
  {
    document.location = `addClass.html`;
  }
  fetchClasses();
  displayCreateClass();
  