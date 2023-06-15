let token = localStorage.getItem('token');
let id = getId();

let history = document.getElementById("history");
history.addEventListener("click", () => {
    window.location = `history.html?id=${id}`;
})

async function getDetails(id) {
    try {
        const response = await fetch(`http://localhost/profile/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });
        if (!response.ok) {
            if (response.status === 404) {
                displayNotFound();
            }
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
            document.getElementById("main-box-profile").parentNode.classList.remove("hidden");
            const data = await response.json();
            console.log(data);
            displayDetails(data);
        }
    } catch (error) {
        console.error('An error occurred:', error.message);
    }

}

function displayDetails(user) {

    profileBoxPart(user);
    statsPart(user);

}

function getId() {
    const url = window.location.href;
    const urlParams = new URL(url).searchParams;
    const id = urlParams.get('id');
    return id;
}

getDetails(id);