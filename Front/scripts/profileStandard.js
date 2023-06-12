let userPhoto = document.getElementById("userPhoto")
function statsPart(user) {
    const nrSuccesses = document.querySelector('#all span span:first-child');
    nrSuccesses.innerText = user.nr_successes;

    const nrAttempts = document.querySelector("#all span span:last-child");
    nrAttempts.innerText = user.nr_attempts;

    let number = document.getElementById("number1");
    number.innerText = user.nr_attempts === 0 ? 0 : Math.round(user.nr_successes * 100 / user.nr_attempts);

    let myCircle = document.getElementById("all-svg");
    if (number.innerText !== "0")
        myCircle.style.strokeOpacity = 1;

    let counterLimit = Number(number.innerText);
    let counter = 0;
    loadProgressBar(number, counter, counterLimit, myCircle, 496);
}

function loadProgressBar(procentValue, counter, counterLimit, progressBar, length) {
    setInterval(() => {
        if (counter == counterLimit) {
            clearInterval();
        }
        else {
            counter += 1;
            procentValue.innerText = counter;
            progressBar.style.strokeDashoffset = length - length / 100 * counter;
        }
    }, 25)
}

function profileBoxPart(user) {
    const username = document.querySelector(".top-txt h1");
    username.innerText = user.username;


    const firstName = document.querySelector(".full-name h3:first-child");
    firstName.innerText = user.first_name;

    const lastName = document.querySelector(".full-name h3:last-child");
    lastName.innerText = user.last_name;

    const role = document.getElementById("rol");
    role.innerText = user.role;

    if (user.img === null) {
        userPhoto.src = "https://static.vecteezy.com/system/resources/previews/007/167/661/original/user-blue-icon-isolated-on-white-background-free-vector.jpg"
    }
    else{
        userPhoto.src = user.img;
    }
}
