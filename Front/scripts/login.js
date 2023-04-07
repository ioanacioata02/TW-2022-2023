if(!sessionStorage.getItem("myCat")) {
    sessionStorage.setItem("myCat", false);
  }
  
  if(sessionStorage.getItem("myCat") === "true") {
    loggedIn();
  }
  
  function loggedIn() {
    let element1 = document.querySelector('#log-in');
    let element2 = document.querySelector('#sign-up');
    element2.style.display="none";
    const newItem = document.createElement('div');
    newItem.id='profile-button';
    newItem.innerHTML='<a href="profile-stats.html">Profile</a>';
    element1.parentNode.replaceChild(newItem, element1);
    sessionStorage.setItem("myCat", true);
  }
  
  function loggedOut() {
    let element1 = document.querySelector('#profile-button');
    let element2 = document.querySelector('#sign-up');
    element2.style.display="block";
    const newItem = document.createElement('div');
    newItem.id='profile-button';
    newItem.innerHTML = '<a class="menu-anchor" href="login.html" id="log-in">Login</a>';
    element1.parentNode.replaceChild(newItem, element1);
    sessionStorage.setItem("myCat", false);
  }
  
