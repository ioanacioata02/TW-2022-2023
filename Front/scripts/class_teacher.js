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
    table="<table>";
    for(let i=0;i<members.length;i++)
    {const member = members[i];
      table+="<tr>";
      table+="<td>"+member.id+"</td>";
      table+="<td>"+member.first_name+"</td>";
      table+="<td>"+member.last_name+"</td>";
      table+="<td><button class=\"add_button\" onclick=\"viewUser("+member.id+")\">"+member.username+"</button></td>";
      table+="<td><button class=\"add_button\" onclick=\"viewHomework()\">View homeworks</button></td>";
      table+="</tr>"
    }
    table+="</table>";
    document.getElementById("class-members").innerHTML=table;
    updatePagination();
  }
  
  function viewUser(id) {
    document.location = `otherProfile.html?id=${id}`;
  }
  function redirectPage()
  {const classId = getId();
    document.location = `addStudent.html?id=${classId}`;
  }
  
  fetchMembers();
  