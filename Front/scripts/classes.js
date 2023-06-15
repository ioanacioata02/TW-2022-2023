
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
    const tableBody = document.getElementById('candidate-problems');
    tableBody.innerHTML = '';
  
    classes.forEach((classItem) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${classItem.id}</td>
        <td>${classItem.name}</td>
        <td>${classItem.numMembers}</td>
        <td><button class="add_button" onclick="viewClass(${classItem.id})">View Class</button></td>
      `;
      tableBody.appendChild(row);
    });
  }
  function displayCreateClass()
  {
    const token = localStorage.getItem('token');
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
  