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

async function getDetails() {
    try {
        const response = await fetch('http://localhost/profile', {
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });

        const data = await response.json();

        if (!response.ok) {
            if(response.status === 404){
                displayNotFound();
            }
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
            document.getElementById("main-box-profile").parentNode.classList.remove("hidden");
            console.log(data);
            displayDetails(data);
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }

}

getDetails();

function displayDetails(user) {

    profileBoxPart(user);

    if (user.role.toLocaleLowerCase() === "student") {
        myClass.setAttribute("href", "myClass_student.html");
    }
    else {
        proposeProblem.classList.remove("hidden");
        myClass.setAttribute("href", "myClasses_teacher.html");
    }
    statsPart(user);

}


function displayChangePassword() {
    popUpBox.classList.remove("hidden");
}

function hideChangePassword() {
    popUpBox.classList.add("hidden");
}

function sendImg(input) {
    let file = input.files[0];

    let reader = new FileReader();

    reader.onload = function () {
        let imageContent = reader.result;
        console.log(imageContent);

        fetch('http://localhost/profile/upload-img', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token
            },
            body: JSON.stringify({ "image": imageContent })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('File upload failed');
                }
                return response.json();
            })
            .then(data => {
                //console.log(data);
                userPhoto.src = imageContent;
            })
            .catch(error => {
                console.error('Error uploading file:', error);
            });
    };

    reader.readAsDataURL(file);
}
