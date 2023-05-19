const numberIds = ["number1", "number2", "number3", "number4"];
const allCircles = ["all-svg", "easy-svg", "medium-svg", "hard-svg"];


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

for (let i = 0; i < numberIds.length; i++) {
    let number = document.getElementById(numberIds[i]);
    let myCircle = document.getElementById(allCircles[i]);
    let counterLimit = Number(number.innerHTML);
    let counter = 0;
    loadProgressBar(number, counter, counterLimit, myCircle, 496);
}