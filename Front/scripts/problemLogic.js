popualteData();


function popualteData()
{
    const urlParams =  new URLSearchParams(window.location.search);
    document.getElementById("title-text").textContent=urlParams.get("id")+" ."+urlParams.get("name");
    document.getElementById("description-text").textContent=urlParams.get('description');
    document.getElementById("rating-text").textContent=urlParams.get("acceptance");
    document.getElementById("difficulty-text").textContent=urlParams.get("difficulty");
    //console.log(text)
}