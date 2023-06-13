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
  
  function viewClass(classId) {
    document.location = `myClass_student.html?classId=${classId}`;
  }
  
  fetchClasses();
  