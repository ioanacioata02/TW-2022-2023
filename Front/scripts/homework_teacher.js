function getId() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    return id;
  }
  
  async function fetchMembers() {
    try {
      const token = localStorage.getItem('token');
      const homeworkId = getId();
      const url=`http://localhost/homework/?students=${homeworkId}`;
      const response = await fetch(url, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
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
      table+="<td><button class=\"add_button\" onclick=\"viewSolutions("+member.id+")\">"+member.username+"</button></td>";
      table+="</tr>"
    }
    table+="</table>";
    document.getElementById("class-members").innerHTML=table;
    updatePagination();
  }
  
  function viewSolutions(id) {
    const homeworkId = getId();
    document.location = `viewSolutions_teacher.html?studentId=${id}&hwId=${homeworkId}`;
  }

  
  fetchMembers();
  