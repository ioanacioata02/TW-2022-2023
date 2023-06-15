function getId() {
    const urlParams = new URLSearchParams(window.location.search);
    const classId = urlParams.get('classId');
    return classId;
  }
  
  async function fetchMembers() {
    try {
      const token = localStorage.getItem('token');
      const classId = getId();
      const url = `http://localhost/classes/${classId}`;
      const response = await fetch(url, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
      });
  
      if (response.ok) {
        const data = await response.json();
        console.log(data);
        const members = data;
        displayMembers(members);
      } else {
        console.error('Failed to fetch members:', response.status);
      }
    } catch (error) {
      console.error('Error during fetch:', error);
    }
  }
  
  function displayMembers(members) {
    const tableBody = document.getElementById('class-members');
    tableBody.innerHTML = '';
  
    members.forEach((member) => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${member.id}</td>
        <td>${member.first_name}</td>
        <td>${member.last_name}</td>
        <td><button class="add_button" onclick="viewUser(${member.id})">${member.username}</button></td>
      `;
      tableBody.appendChild(row);
    });
  }
  
  function viewUser(id) {
    document.location = `otherProfile.html?id=${id}`;
  }
  
  fetchMembers();
  