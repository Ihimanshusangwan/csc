const toggleRegReq = (target) => {
    const section = document.getElementById("registerFields");
    if (target.value == "Register") {
        section.classList.remove("d-none");
    } else {
        section.classList.add("d-none");
    }
};
