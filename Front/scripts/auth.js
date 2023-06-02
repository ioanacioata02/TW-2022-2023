<<<<<<< Updated upstream
class Auth {
	constructor() {
        document.querySelector("body").style.display = "none";
		const auth = localStorage.getItem("auth");
		this.validateAuth(auth);
	}

	validateAuth(auth) {
		if (auth == 1) {
            let element1 = document.querySelector('#log-in');
            let element2 = document.querySelector('#sign-up');
            element2.style.display="none";
            //element1.style.display="none";
            const newItem = document.createElement('div');
            newItem.id='profile-button';
            newItem.innerHTML='<a href="profile-stats.html">Profile</a>';
            element1.parentNode.replaceChild(newItem, element1);
            sessionStorage.setItem("myCat", true);
            document.querySelector("body").style.display = "block";
		}
	}

	logOut() {
		localStorage.removeItem("auth");
		window.location.replace("/");
	}
}
=======
class Login {
	constructor(form) {
		this.form = form;
		this.validateOnSubmit();
	}

	validateOnSubmit() {
		this.form.addEventListener("submit", (e) => {
			e.preventDefault();
			const emailField = this.form.querySelector("input[name=email]");
			const passwordField = this.form.querySelector("input[name=psw]");

			if (this.validateFields(emailField, passwordField)) {
				// Make an AJAX request to your PHP login API endpoint
				const formData = new FormData(this.form);
				const xhr = new XMLHttpRequest();
				xhr.open("POST", "http://localhost/authentication.php", true);
				xhr.onload = function () {
					if (xhr.status === 200) {
						const response = JSON.parse(xhr.responseText);
						if (response.success) {
							localStorage.setItem("myCat", 1);
							const previousPage = document.referrer || "index.html";
							window.location.href = previousPage;
						} else {
							const errorContainer = document.createElement("div");
							errorContainer.classList.add("error-message");
							errorContainer.textContent = response.message;
							emailField.parentElement.appendChild(errorContainer);
						}
					} else {
						console.log("An error occurred during the login process.");
					}
				};
				xhr.send(formData);
			}
		});
	}

	validateFields(...fields) {
		let isValid = true;
		fields.forEach((field) => {
			if (field.value.trim() === "") {
				this.setStatus(
					field,
					`${field.previousElementSibling.textContent} cannot be blank`,
					"error"
				);
				isValid = false;
			} else {
				this.setStatus(field, null, "success");
			}
		});
		return isValid;
	}

	setStatus(field, message, status) {
		const errorContainer = field.parentElement.querySelector(".error-message");

		if (status === "success") {
			if (errorContainer) {
				errorContainer.remove();
			}
			field.classList.remove("input-error");
		}

		if (status === "error") {
			if (!errorContainer) {
				const errorContainer = document.createElement("div");
				errorContainer.classList.add("error-message");
				field.parentElement.appendChild(errorContainer);
			}
			errorContainer.textContent = message;
			field.classList.add("input-error");
		}
	}
}

document.addEventListener("DOMContentLoaded", function () {
	const form = document.querySelector(".login-form");
	if (form) {
		const validator = new Login(form);
	}
});
>>>>>>> Stashed changes
