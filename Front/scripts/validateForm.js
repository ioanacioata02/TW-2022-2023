function validate() {
    var tests = document.getElementById("tests");
    var testsContent = tests.value;
    var regex = /(\{\[.*\]\(.*\)\}\.)*\{\[.*\]\(.*\)\}\//;
    if (regex.test(testsContent)) {
        return true;
    }
    else {
        tests.style.borderColor = "red";
        return false;
    }
}