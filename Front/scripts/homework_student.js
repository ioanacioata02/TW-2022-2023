
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
          table += "<td><button class=\"add_button\" onclick=\"solutionPage('" + key + "')\">Solve problem</button></td>";
          table += "</tr>";
        }
      }
    table+="</table>";
    document.getElementById("class-members").innerHTML=table;
    updatePagination();
  }
  
async   function solutionPage(id) {

    try {
      const token = localStorage.getItem('token');
  let studentId = parseJwt(token).id;
    const homeworkId=getHomeworkId();
    const url=`http://localhost/problems/?id=${id}`;
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
      const member = data;
      console.log(member);
      const hwId=getHomeworkId();
      document.location = `solutionPage.html?hwId=${hwId}&id=${id}&name=${member.name}&description=${member.description}&acceptance=0%&difficulty=0&tags=${member.tags.join(',')}`;

    } else {
      console.error('Failed to fetch problem', response.status);
    }
  } catch (error) {
    console.error('Error during fetch:', error);
  }

   
  }

  
  fetchMembers();
  function parseJwt(token) {
    if (!token) {
      return;
    }
    const base64 = token.split('.')[1]; 
    return JSON.parse(window.atob(base64));
    }
    