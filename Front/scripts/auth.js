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