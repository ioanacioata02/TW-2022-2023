let token = localStorage.getItem('token');

async function getDetails(id) {
    try {
        const response = await fetch(`http://localhost/profile/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': token
            }
        });
        if (!response.ok) {
            console.log('An error occurred:', response.status, data.message);
            return;
        } else {
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

function loadUser() {
    const url = window.location.href;
    const urlParams = new URL(url).searchParams;
    const id = urlParams.get('id');
    getDetails(id);
}

loadUser();