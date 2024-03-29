function parseJwt(token) {
	if (!token) {
	  return;
	}
	const base64 = token.split('.')[1]; 
	return JSON.parse(window.atob(base64));
  }
  

// Register
registerForm = document.querySelector('.login-form');
registerForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(registerForm);
    var role = document.getElementById('selection').value ==='student' ? 0 : 1;
    console.log(role);
    const registerData = {
        "username": formData.get('uname'),
        "lastName": formData.get('lname'),
        "firstName": formData.get('fname'),
        "password": formData.get('psw'),
        "email": formData.get('email'),
        "type": role
    };
    console.log(registerData);
    try {
        const response = await fetch('http://localhost/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(registerData)
        });

        if (!response.ok) {
         
                if (response.status === 409 || response.status === 400) {
                    displayMessage("Invalid email", true);
                    
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
            if (status === "0" || status === "1")
                window.location.href = 'index.html';
            else
                window.location.href = 'admin.html';
        } else {
            const err = document.querySelector('.login-form .error');
            err.classList.add('show');
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }
});
