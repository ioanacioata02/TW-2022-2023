

function parseJwt(token) {
  if (!token){ 
    return;
  }
  const base64 = token.split('.')[1]; // extracting payload
  return JSON.parse(window.atob(base64));
}

//login
loginForm = document.getElementById("log-in");
loginForm.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(loginForm);
  const loginData = {
    "email": formData.get('email'),
    "password": formData.get('password')
  };
  try {
    const response = await fetch('http://localhost/authentication', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(loginData)
    });
    if (!response.ok) {
      if (response.status === 401) {
        let err = document.querySelector('form.login .error');
        err.classList.add('show');
      }
      return;
    }
    const data = await response.json();
    const token = data.token;
    console.log(token);
    localStorage.setItem('token', token);
    if(token != null){
      let role = parseJwt(token).role.toLowerCase();
      if(role == "user")
        window.location.href = 'home.html';
      else
        window.location.href = 'admin.html';
    }
    else{
      const err = document.querySelector('form.login .error');
      err.classList.add('show');
    }
  } catch (error) {
    console.error('An error occurred:', error.message);
  }
});

