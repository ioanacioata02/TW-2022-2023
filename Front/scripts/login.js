console.log(Boolean( localStorage.getItem("myCat")))
if(!localStorage.getItem("myCat")) {
    localStorage.setItem("myCat", false);
    console.log('Am setat myCat')
  }
console.log(localStorage.getItem("myCat"))
console.log(Boolean(localStorage.getItem("myCat")))
  if(localStorage.getItem("myCat") === "true") {

	  makePostRequest();
  }
  function makePostRequest() {
  if (localStorage.getItem("myCat") === "true") {
    const token = localStorage.getItem("token");
    const url = "http://130.61.233.19:4446/authentication";
    const data = {
      u: "",
      p: ""
    };

    fetch(url, {
      method: "POST",
      headers: {
        "Authorization": token,
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    })
    .then(response => {
      if (response.ok) {
        loggedIn();
      }
    })
    .catch(error => {
      console.error("Error:", error);
    });
  }
}



  function setVariableTrue()
  {
     localStorage.setItem("myCat", true);
  }

function loggedIn() {
  let element1 = document.querySelector('#log-in');
  let element2 = document.querySelector('#sign-up');

  if (element1 && element2) {
    element2.style.display = "none";
    const newItem = document.createElement('div');
    newItem.id = 'profile-button';
    newItem.innerHTML = '<a href="profile-stats.html">Profile</a>';
    element1.parentNode.replaceChild(newItem, element1);
    localStorage.setItem("myCat", true);
  } else {
    console.log("Element '#log-in' or '#sign-up' not found.");
  }
}


  function loggedOut() {
    localStorage.clear();
    let element1 = document.querySelector('#profile-button');
    let element2 = document.querySelector('#sign-up');
    element2.style.display="block";
    const newItem = document.createElement('div');
    newItem.id='profile-button';
    newItem.innerHTML = '<a class="menu-anchor" href="login.html" id="log-in">Login</a>';
    element1.parentNode.replaceChild(newItem, element1);
    localStorage.setItem("myCat", false);
  }