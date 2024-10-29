
/* Email Validation */
function validateEmail() {
    const emailInput = document.getElementById("email");
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (!emailPattern.test(emailInput.value)) {
        emailInput.classList.add("errorBorder");
        return false;
    } else {
        emailInput.classList.remove("errorBorder");
    }
    return true;
}
