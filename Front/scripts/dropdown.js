let oppend = null;

function dropDownFunction(dropdownId) {
    const dropdown = document.getElementById(dropdownId);

    if (oppend === dropdownId && dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
        oppend = null;
    } else {
        if (oppend !== null) {
            document.getElementById(oppend).classList.remove('show');
        }
        dropdown.classList.add('show');
        oppend = dropdownId;
    }
}