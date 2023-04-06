
// we make sure that the description tab is always open
current_tab = document.querySelector('#description');
current_tab.style.display = 'block';
current_tab.style.gridColumn = 'span 3';
function opentab(id) {
    new_tab = document.querySelector(''.concat('#', id))
    console.log(new_tab)
    current_tab.style.display = 'none';
    new_tab.style.display = 'block';
    new_tab.style.gridColumn = 'span 3';
    current_tab = new_tab;
}

///feedback logic

const feedback_button = document.querySelector(".subbmit-review");
const close_feedback = document.querySelector(".close");
close_feedback.onclick = () => {
    document.querySelector(".feed-back-from").style.display = 'none';
}
feedback_button.onclick = () => {
    feedback_form = document.querySelector(".feed-back-from")
    if (feedback_form.style.display === 'block') {
        feedback_form.style.display = 'none';
    }
    else{
    feedback_form.style.display = 'block'
        
    }
}