console.log(Boolean( sessionStorage.getItem("myCat")))
if(!sessionStorage.getItem("myCat")) {
    sessionStorage.setItem("myCat", false);
    console.log('Am setat myCat')
  }
console.log(sessionStorage.getItem("myCat"))
console.log(Boolean(sessionStorage.getItem("myCat")))
  if(sessionStorage.getItem("myCat") === "true") {
    loggedIn();
  }
  

  function setVariableTrue()
  {
     sessionStorage.setItem("myCat", true);
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
    sessionStorage.setItem("myCat", true);
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
    sessionStorage.setItem("myCat", false);
  }
  
