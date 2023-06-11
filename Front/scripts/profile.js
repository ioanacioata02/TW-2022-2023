let proposeProblem = document.getElementById("propune-problema");
let myClass = document.getElementById("link-to-class");
let popUpBox = document.getElementById("pop-up-box");
let token = localStorage.getItem('token');


proposeProblem.classList.add("hidden");

async function changePass(event) {
    event.preventDefault();

    const form = document.querySelector(".popUp");
    const data = new FormData(form);

    if (data.get('new-password') !== data.get('confirm-password')) {
        displayMessage("Passwords do not match!", true);
        return;
    }

    let password = data.get('new-password');
    try {
        const response = await fetch('http://localhost/profile/change-pass', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token
            },
            body: JSON.stringify({ "password": password })
        });
        const data = await response.json();
        if (!response.ok) {
            console.log('An error occurred:', data.message);
            displayMessage('Something went wrong. Please try again later.', true);
            return;
        } else {
            displayMessage('Password changed!', false);
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }
}

function displayMessage(message, isError) {
    let errorBox = document.getElementById("feedback");
    if (isError)
        errorBox.style.backgroundColor = "red";
    else {
        errorBox.style.backgroundColor = "yellow";
    }
    let txt = errorBox.querySelector("p");
    txt.innerText = message;

    errorBox.appendChild(txt);
    errorBox.classList.remove("hidden");
}

async function getDetails() {
    console.log("aici");
    try {
        const response = await fetch('http://localhost/profile', {
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });
        const data = await response.json();
        if (!response.ok) {
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
            console.log(data);
            displayDetails(data);
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }

}

getDetails();

function displayDetails(user) {
    const username = document.querySelector(".top-txt h1");
    username.innerText = user.username;


    const firstName = document.querySelector(".full-name h3:first-child");
    firstName.innerText = user.first_name;

    const lastName = document.querySelector(".full-name h3:last-child");
    lastName.innerText = user.last_name;

    const role = document.getElementById("rol");
    role.innerText = user.role;


    if (user.role.toLocaleLowerCase() === "student") {
        myClass.setAttribute("href", "myClass_student.html");
    }
    else {
        proposeProblem.classList.remove("hidden");
        myClass.setAttribute("href", "myClasses_teacher.html");
    }
    
    
    let number = document.getElementById("number1");
    let myCircle = document.getElementById("all-svg");
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
            procentValue.innerHTML = counter;
            progressBar.style.strokeDashoffset = length - length / 100 * counter;
        }
    }, 25)
}

function displayChangePassword() {
    popUpBox.classList.remove("hidden");
}

function hideChangePassword() {
    popUpBox.classList.add("hidden");
}

/*
function sendImg(input) {
    let file = input.files[0];

    let reader = new FileReader();

    reader.onload = function () {
        let imageContent = reader.result;

        const formData = new FormData();
        formData.append('file', file);

        fetch('http://localhost/profile', {
            method: 'PATCH',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    console.log(response);
                } else {
                    throw new Error('File upload failed');
                }
            })
            .catch(error => {
                console.error('Error uploading file:', error);
            });
    };

    reader.readAsArrayBuffer(file);
}
*/