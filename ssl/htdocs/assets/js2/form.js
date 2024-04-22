function togglePasswordVisibility(inputId, toggleId) {
    var inputElement = document.getElementById(inputId);
    var toggleElement = document.getElementById(toggleId);
    if (inputElement.type === "password") {
        inputElement.type = "text";
        toggleElement.classList.add("fa-eye-slash");
        toggleElement.classList.remove("fa-eye");
    } else {
        inputElement.type = "password";
        toggleElement.classList.remove("fa-eye-slash");
        toggleElement.classList.add("fa-eye");
    }
}
