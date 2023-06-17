const urlParams =  new URLSearchParams(window.location.search);
document.getElementById("code_source").textContent=urlParams.get("solution")
