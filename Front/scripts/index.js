let img = document.querySelector('#displayed-image');
let language2= document.querySelector('#language-eng');
let language1= document.querySelector('#language-ro');
let dropDown = document.querySelector('.dropdownItems')
language1.addEventListener('click', ()=>{img.src='../assets/flag_ro.svg'});
language2.addEventListener('click', ()=>{img.src='../assets/flag_en.svg'});


function loggedIn()
{
let element1 = document.querySelector('#log-in');
let element2=  document.querySelector('#sign-up');
element2.style.display="none";
const newItem =  document.createElement('li');
newItem.id='profile-button';
newItem.innerHTML='<a href="/">Profile</a>';
element1.parentNode.replaceChild(newItem, element1);
}

function loggedOut()
{
    let element1 = document.querySelector('#profile-button');
    let element2=  document.querySelector('#sign-up');
    element2.style.display="block";
    const newItem =  document.createElement('li');
    newItem.id='profile-button';
    newItem.innerHTML='<a href="/">Log In</a>';
    element1.parentNode.replaceChild(newItem, element1);
}


