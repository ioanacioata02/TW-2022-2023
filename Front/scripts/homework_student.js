
 function  getHomeworkId()
  { const urlParams = new URLSearchParams(window.location.search);
    const id=urlParams.get('hwId');
    return id;
  }
  
  
  async function fetchMembers() {
    try {
        const token = localStorage.getItem('token');
    let studentId = parseJwt(token).id;
      const homeworkId=getHomeworkId();
      const url=`http://localhost/homework/?problems=${homeworkId}`;
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
        const members = data.problems;
        console.log(members);
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
    for (const key in members) {//parsam json cu solutiile
        if (members.hasOwnProperty(key)) {
          const member = members[key];
          table += "<tr>";
          table += "<td>" + key + "</td>";
          table += "<td>" + member.name + "</td>";
          table += "<td>" + member.status + "</td>";
          table += "<td><button class=\"add_button\" onclick=\"viewSolution('" + member.solution + "')\">View Solution</button></td>";
          table += "</tr>";
        }
      }
    table+="</table>";
    document.getElementById("class-members").innerHTML=table;
    updatePagination();
  }
  
  function viewSolutions(solution) {
    const homeworkId = getId();
    document.location = `ViewSolution.html?solution=${solution}`;
  }

  
  fetchMembers();
  function parseJwt(token) {
    if (!token) {
      return;
    }
    const base64 = token.split('.')[1]; 
    return JSON.parse(window.atob(base64));
    }
    