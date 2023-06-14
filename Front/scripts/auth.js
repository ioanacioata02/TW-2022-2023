function parseJwt(token) {
	if (!token) {
	  return;
	}
	const base64 = token.split('.')[1]; // extracting payload
	return JSON.parse(window.atob(base64));
  }
  
  loginForm = document.getElementById("log-in");
  loginForm.addEventListener('submit', async (event) => {
	event.preventDefault();
	const formData = new FormData(loginForm);
	const loginData = {
	  "email": formData.get('email'),
	  "password": formData.get('password')
	};
	console.log(loginData);
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
			console.log("cv nu eok la register");
			displayMessage("Email or password is incorrect",true);
		}
		return;
	  }
  
	  const data = await response.json();
	  console.log(data);
	  const token = data.JWT;
	  console.log(token);
	  localStorage.setItem('token', token);
	  if (token) {
		let status = parseJwt(token).status;
		if (status == "0"||status=="1")
		  window.location.href = 'index.html';
		else
		  window.location.href = 'admin.html';
	  } else {
		const err = document.querySelector('form.login .error');
		err.classList.add('show');
	  }
	} catch (error) {
	  console.error('An error occurred:', error.message);
	}
  });
  