
// we make sure that the description tab is always open
current_tab=document.querySelector('#description');
current_tab.style.display='block';
current_tab.style.gridColumn ='span 3';
function opentab(id)
{
    new_tab=document.querySelector(''.concat('#',id))
    console.log(new_tab)
    current_tab.style.display='none';
    new_tab.style.display='block';
    new_tab.style.gridColumn ='span 3';
    current_tab=new_tab;
}

