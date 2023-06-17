const urlParams =  new URLSearchParams(window.location.search);
let text = urlParams.get("solution");
text=text.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&quot;/g, "\"");
document.getElementById("code_source").textContent=text;
