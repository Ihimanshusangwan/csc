document.addEventListener("DOMContentLoaded", function () {
    const stepMenuItems = document.querySelectorAll("#step-menu li");
    const stepContents = document.querySelectorAll(".step-content");
    let currentStep = 1;

    // Set initial cursor state
    stepMenuItems.forEach((item) => {
        const step = parseInt(item.getAttribute("data-step"));
        if (step > currentStep) {
            item.style.cursor = "not-allowed";
        } else {
            item.style.cursor = "pointer";
        }
    });

    stepMenuItems.forEach((item) => {
        item.addEventListener("click", function () {
            const targetStep = parseInt(this.getAttribute("data-step"));
            if (targetStep > currentStep) {
                // Can't jump to next step without completing the current one
                return;
            }

            stepMenuItems.forEach((item) => {
                item.classList.remove("active");
                const step = parseInt(item.getAttribute("data-step"));
                if (step > currentStep) {
                    item.style.cursor = "not-allowed";
                } else {
                    item.style.cursor = "pointer";
                }
            });
            this.classList.add("active");

            stepContents.forEach((content) => {
                if (content.getAttribute("id") === `step-${targetStep}`) {
                    content.classList.remove("d-none");
                } else {
                    content.classList.add("d-none");
                }
            });
        });
    });

    function updateSteps() {
        stepMenuItems.forEach((item) => {
            const step = parseInt(item.getAttribute("data-step"));
            if (step === currentStep) {
                item.classList.add("active");
                item.style.cursor = "pointer";
            } else if (step > currentStep) {
                item.classList.remove("active");
                item.style.cursor = "not-allowed";
            } else {
                item.classList.remove("active");
                item.style.cursor = "pointer";
            }
        });

        stepContents.forEach((content) => {
            const step = parseInt(content.getAttribute("id").split("-")[1]);
            if (step === currentStep) {
                content.classList.remove("d-none");
            } else {
                content.classList.add("d-none");
            }
        });
    }

    const tiles = document.querySelectorAll(".tile");
    tiles.forEach((tile) => {
        tile.addEventListener("click", function () {
            tiles.forEach((t) => {
                t.classList.remove("selected-tile");
            });
            this.classList.add("selected-tile");
            currentStep = 2; // Move to next step after selecting a tile
            updateSteps();
        });
    });

    // JavaScript logic for date and time selection will go here

    let selectedDate = null; // Variable to store selected date
    let selectedTimeSlot = null; // Variable to store selected time slot

    // Initialize datepicker
    $("#datepicker")
        .datepicker({
            autoclose: true,
            keepOpen: true,
            todayHighlight: true,
            startDate: "today",
            format: "dd MM yyyy", // Change format to date-month year
        })
        .on("changeDate", function (e) {
            selectedDate = e.date;

            renderTimeSlots();
        });

    // Render time slots based on current time or selected date
    function renderTimeSlots() {
        const currentTime = new Date();
        const currentHour = currentTime.getHours();
        const currentDate = new Date(selectedDate);
        const currentDay = currentDate.setHours(0, 0, 0, 0);
        const timeSlots = {
            Morning: [],
            Afternoon: [],
            Evening: [],
        };

        let startHour;
        if (currentDay === currentTime.setHours(0, 0, 0, 0)) {
            // Selected date is today
            startHour = currentHour + 1; // Start from the next hour
        } else {
            // Selected date is in the future
            startHour = 10; // Start from 10 AM
        }

        for (let i = startHour; i <= 22; i++) {
            // End at 10 PM
            const hour = i % 12 === 0 ? 12 : i % 12; // Convert to 12-hour format
            const ampm = i < 12 ? "AM" : "PM";
            const slot = `${hour}:00 ${ampm}`;

            if (i < 12) {
                timeSlots["Morning"].push(slot);
            } else if (i < 17) {
                timeSlots["Afternoon"].push(slot);
            } else {
                timeSlots["Evening"].push(slot);
            }
        }

        // Clear previous time slots
        const timeSlotsContainer = $("#time-slots").empty();

        // Render time slots as tiles
        Object.keys(timeSlots).forEach((slotCategory) => {
            const timeSlotsInSection = timeSlots[slotCategory];
            if (timeSlotsInSection.length > 0) {
                timeSlotsContainer.append(`<h3>${slotCategory}</h3>`);
                timeSlotsInSection.forEach((slot) => {
                    const tile = $("<div>")
                        .addClass("col-md-4")
                        .append(
                            $("<div>")
                                .addClass("time-slot")
                                .text(slot)
                                .click(function () {
                                    selectedTimeSlot = $(this).text();
                                    currentStep = 3; // Move to Step 3 upon selecting a time slot

                                    // Remove "selected" class from all time slots
                                    $(".time-slot").removeClass(
                                        "selected-slot"
                                    );

                                    // Add "selected" class to the clicked time slot
                                    $(this).addClass("selected-slot");

                                    updateSteps();
                                })
                        );
                    timeSlotsContainer.append(tile);
                });
            }
        });
    }

    // Event listener for submit button click
    document
        .getElementById("submit-btn")
        .addEventListener("click", function () {
            // Get form values
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const phone = document.getElementById("phone").value;
            const city = document.getElementById("city").value;
            const address = document.getElementById("address").value;

            // Move to next step
            currentStep = 4;
            updateSteps();

            // Initialize an object to store collected data
            var formData = {
                name: name,
                email: email,
                phone: phone,
                city: city,
                address: address,
                selectedDate: selectedDate
                    ? selectedDate.toLocaleDateString()
                    : "Not selected",
                selectedTimeSlot: selectedTimeSlot
                    ? selectedTimeSlot
                    : "Not selected",
                selectedTileId: "",
            };

            // Create elements to display collected data
            const nameElement = document.createElement("p");
            nameElement.innerHTML = "<strong>" + formData.name + "</strong>";

            const emailElement = document.createElement("p");
            emailElement.innerHTML = "<strong>" + formData.email + "</strong>";

            const phoneElement = document.createElement("p");
            phoneElement.innerHTML = "<strong>" + formData.phone + "</strong>";

            const addressElement = document.createElement("p");
            addressElement.innerHTML =
                "<strong>" + formData.address + "</strong>";

            const selectedDateElement = document.createElement("p");
            selectedDateElement.innerHTML =
                "<strong>Date:</strong> " + formatDate(formData.selectedDate);

            const selectedTimeSlotElement = document.createElement("p");
            selectedTimeSlotElement.innerHTML =
                "<strong>Time Slot:</strong> " + formData.selectedTimeSlot;

            const PriceElement = document.createElement("p");
            const selectedTileElement = document.createElement("p");
            const selectedTile = document.querySelector(".selected-tile");
            if (selectedTile) {
                selectedTileElement.innerHTML =
                    "<strong>Service:</strong> " +
                    selectedTile.querySelector("h4").textContent;
                let servicePrice =
                    selectedTile.querySelector("span").textContent;
                // Store the data-id attribute value in formData
                formData.selectedTileId = selectedTile.getAttribute("data-id");
                formData.servicePrice = servicePrice;

                PriceElement.innerHTML =
                    "<span class='text-success'><strong>₹" +
                    formData.servicePrice +
                    "</strong></span>";
            } else {
                selectedTileElement.textContent =
                    "<strong>Selected Tile:</strong> Not selected";
            }

            // Update the summary div with collected data
            const summaryDiv = document.getElementById("summary");
            summaryDiv.innerHTML = ""; // Clear previous content

            // Append elements to the summary div
            const elements = [
                nameElement,
                emailElement,
                phoneElement,
                addressElement,
                selectedDateElement,
                selectedTimeSlotElement,
                selectedTileElement,
                PriceElement,
            ];

            elements.forEach((element) => {
                const div = document.createElement("div");
                div.classList.add("mb-2");
                element.classList.add("list-group-item");

                div.appendChild(element);
                summaryDiv.appendChild(div);
            });
            console.log(formData);

            // Now formData contains all the collected data including the selected tile's data-id
            document
                .getElementById("confirm-appointment")
                .addEventListener("click", function () {
                    const button = document.getElementById(
                        "confirm-appointment"
                    );
                    const route = button.dataset.route;
                    const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                    fetch(route, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            // Add any other headers if needed
                        },
                        body: JSON.stringify(formData),
                    })
                        .then((response) => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error("Network response was not ok.");
                        })
                        .then((data) => {
                            // Display SweetAlert alert with response message
                            Swal.fire({
                                title: "Success",
                                text: data.message, // Assuming the response contains a 'message' field
                                icon: "success",
                                confirmButtonText: "OK",
                            }).then(() => {
                                // Redirect to home page
                                window.location.href = "/";
                            });
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            // Handle errors
                            // Display SweetAlert error alert
                            Swal.fire({
                                title: "Error",
                                text: "An error occurred while processing your request.",
                                icon: "error",
                                confirmButtonText: "OK",
                            });
                        });
                });
        });

    // form validations

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const phoneInput = document.getElementById("phone");
    const addressInput = document.getElementById("address");
    const submitBtn = document.getElementById("submit-btn");

    let nameTouched = false;
    let emailTouched = false;
    let phoneTouched = false;
    let addressTouched = false;

    nameInput.addEventListener("input", () => {
        nameTouched = true;
        validateForm();
    });

    emailInput.addEventListener("input", () => {
        emailTouched = true;
        validateForm();
    });

    phoneInput.addEventListener("input", () => {
        phoneTouched = true;
        validateForm();
    });

    addressInput.addEventListener("input", () => {
        addressTouched = true;
        validateForm();
    });
    function validateName() {
        const nameValue = nameInput.value.trim();
        if (nameValue.length < 3) {
            nameInput.classList.add("is-invalid");
            document.getElementById("nameError").style.display = "block";
            return false;
        } else {
            nameInput.classList.remove("is-invalid");
            document.getElementById("nameError").style.display = "none";
            return true;
        }
    }

    function validateEmail() {
        const emailValue = emailInput.value.trim();
        if (!/^\S+@\S+\.\S+$/.test(emailValue)) {
            emailInput.classList.add("is-invalid");
            document.getElementById("emailError").style.display = "block";
            return false;
        } else {
            emailInput.classList.remove("is-invalid");
            document.getElementById("emailError").style.display = "none";
            return true;
        }
    }

    function validatePhone() {
        const phoneValue = phoneInput.value.trim();
        if (!/^\d{10}$/.test(phoneValue)) {
            phoneInput.classList.add("is-invalid");
            document.getElementById("phoneError").style.display = "block";
            return false;
        } else {
            phoneInput.classList.remove("is-invalid");
            document.getElementById("phoneError").style.display = "none";
            return true;
        }
    }

    function validateAddress() {
        const addressValue = addressInput.value.trim();
        if (addressValue.length < 10) {
            addressInput.classList.add("is-invalid");
            document.getElementById("addressError").style.display = "block";
            return false;
        } else {
            addressInput.classList.remove("is-invalid");
            document.getElementById("addressError").style.display = "none";
            return true;
        }
    }

    function validateForm() {
        const isNameValid = nameTouched ? validateName() : true;
        const isEmailValid = emailTouched ? validateEmail() : true;
        const isPhoneValid = phoneTouched ? validatePhone() : true;
        const isAddressValid = addressTouched ? validateAddress() : true;

        if (isNameValid && isEmailValid && isPhoneValid && isAddressValid) {
            submitBtn.removeAttribute("disabled");
        } else {
            submitBtn.setAttribute("disabled", "disabled");
        }
    }
    // Custom function to format date to "date month year"
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { day: "numeric", month: "long", year: "numeric" };
        return date.toLocaleDateString("en-US", options);
    }
});
