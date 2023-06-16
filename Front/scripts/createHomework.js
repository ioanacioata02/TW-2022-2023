loginForm = document.getElementById('log-in');
loginForm.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(loginForm);
  const token = localStorage.getItem('token');
  let myId = parseJwt(token).id;
  const idClasses = formData.get('classes');
  const idProblems = formData.get('problems');
  const idClassesArray = validateList(idClasses);
  if (idClassesArray === null) {
    displayMessage("Classes id list is incorrect", true);
    return;
  }
  const idProblemsArray = validateList(idProblems);
  if (idProblemsArray === null) {
    displayMessage("Problem id list is incorrect", true);
    return;
  }

  const loginData = {
    "topic": formData.get('topic'),
    "deadline": formData.get('dline'),
    "id_classes": idClassesArray,
    "id_problems": idProblemsArray,
    "author": myId
  };
  
  try {
    const tkn = localStorage.getItem('token');
    const response = await fetch('http://localhost/homework', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': tkn
      },
      body: JSON.stringify(loginData)
    });

    if (!response.ok) {
      if (response.status === 401) {
        console.log("cv nu eok la register");
        displayMessage("Input data is incorrect", true);
      }
      return;
    }

    const data = await response.json();
    console.log(data);

    window.history.back();
  } catch (error) {
    console.error('Error during fetch:', error);
  }
});


function parseJwt(token) {
  if (!token) {
    return;
  }
  const base64 = token.split('.')[1];
  return JSON.parse(window.atob(base64));
}

function validateList(input) {
  const numberList = input.split(',');
  for (let i = 0; i < numberList.length; i++) {
    const number = Number(numberList[i]);
    if (isNaN(number)) {
      return null;
    }
    numberList[i] = number;
  }

  return numberList;
}
