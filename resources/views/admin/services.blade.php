<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>        
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1>Services</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-3">Home</a>
        <!-- Add New Service Button -->
        <button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#addServiceModal">
            Add New Service
        </button>
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">

            <!-- Display Services as Cards -->
            @foreach ($services as $service)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->name }}</h5>
                            <p><strong>Service Group:</strong> {{ $service->service_group_name }}</p>
                            <p><strong>Document Requirements:</strong></p>
                            <ol class="list-group list-group-numbered">
                                @php
                                    $requirements = explode(',', $service->requirements);
                                @endphp

                                @foreach ($requirements as $requirement)
                                    <li class="list-group-item">{{ trim($requirement) }}</li>
                                @endforeach
                            </ol>

                            <!-- Button to Preview Form -->
                            <button type="button" class="btn btn-warning m-1"
                                onclick="previewServiceForm('{{ addslashes($service->form) }}')">Preview Form</button>
                            <a href="{{ route('services.delete', $service->id) }}" class="btn btn-danger m-1"
                                onclick="return confirm('Are you sure you want to delete this service?')">Delete
                                Service</a>
                            <a href="{{ route('prices.index', $service->id) }}" class="btn btn-info m-1">Manage
                                Prices</a>
                            <a href="{{ route('statuses.index', $service->id) }}" class="btn btn-info m-1">Manage
                                Statuses</a>
                            <h6>Visibility</h6>
                            <div class="btn-group" role="group" aria-label="Basic example"
                                id="btn-group-{{ $service->id }}">
                                <button type="button"
                                    class="btn btn-sm btn-secondary {{ $service->visibility == 1 ? 'active' : '' }}"
                                    data-route="{{ route('update-visibility', ['serviceId' => $service->id]) }}"
                                    onclick="handleVisibility(this, {{ $service->id }}, 1)">Appointments</button>
                                <button type="button"
                                    class="btn btn-sm btn-secondary {{ $service->visibility == 2 ? 'active' : '' }}"
                                    data-route="{{ route('update-visibility', ['serviceId' => $service->id]) }}"
                                    onclick="handleVisibility(this, {{ $service->id }}, 2)">Agents</button>
                                <button type="button"
                                    class="btn btn-sm btn-secondary {{ $service->visibility == 3 ? 'active' : '' }}"
                                    data-route="{{ route('update-visibility', ['serviceId' => $service->id]) }}"
                                    onclick="handleVisibility(this, {{ $service->id }}, 3)">Both</button>
                            </div>
                            <h6>Availability</h6>
                            <div class="btn-group" role="group" aria-label="Basic example"
                            id="availability-btn-group-{{ $service->id }}">
                            <button type="button"
                                class="btn btn-sm btn-secondary {{ $service->availability == 1 ? 'active' : '' }}"
                                data-route="{{ route('update-availability', ['serviceId' => $service->id]) }}"
                                onclick="handleAvailability(this, {{ $service->id }}, 1)">Subscription only</button>
                            <button type="button"
                                class="btn btn-sm btn-secondary {{ $service->availability == 2 ? 'active' : '' }}"
                                data-route="{{ route('update-availability', ['serviceId' => $service->id]) }}"
                                onclick="handleAvailability(this, {{ $service->id }}, 2)">With and Without Subscription</button>
                            
                        </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <!-- Add New Service Modal -->
        <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Service Form -->
                        <form method="POST" action="{{ route('services.store') }}">
                            @csrf

                            <!-- Service Name -->
                            <div class="form-group">
                                <label for="serviceName">Service Name</label>
                                <input type="text" class="form-control" id="serviceName" name="name" required>
                            </div>

                            <!-- Service Group Select Box -->
                            <div class="form-group">
                                <label for="serviceGroup">Service Group</label>
                                <select class="form-control" id="serviceGroup" name="service_group_id" required>
                                    @foreach ($serviceGroups as $serviceGroup)
                                        <option value="{{ $serviceGroup->id }}">{{ $serviceGroup->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Service Requirements -->
                            <div class="form-group">
                                <label for="serviceRequirements">Document Requirements (enter comma-separated
                                    values)</label>
                                <textarea class="form-control" id="serviceRequirements" name="requirements" rows="3" required></textarea>
                            </div>

                            <!-- Dynamic Form Construction -->
                            <div class="form-group">
                                <h3 for="form_data">Form Data</h3>
                                <div id="formFields"></div>
                                <button type="button" class="btn btn-success" id="addInputButton">Add Input</button>
                                <input type="hidden" name="form" id="form_data">
                            </div>
                            <!-- Preview Button -->
                            <button type="button" class="btn btn-secondary mr-2" id="previewButton">Preview</button>
                            <!-- Submit Button -->
                            <div>
                                Note: Preview before adding Service
                            </div>
                            <button type="submit" class="btn btn-primary">Add Service</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form Preview Modal -->
        <div class="modal fade" id="formPreviewModal" tabindex="-1" role="dialog"
            aria-labelledby="formPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formPreviewModalLabel">Form Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="formPreview"></div>
                        <!-- Close Button -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="closePreviewModal()">Close</button>

                    </div>
                </div>
            </div>
        </div>


    </div>
    
    <script src="{{ asset('js/sweetAlert.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function handleVisibility(button, serviceId, visibilityId) {
            // Get the button group element
            const buttonGroup = button.parentElement;

            // Get the route URL from the button's data attribute
            const routeUrl = button.dataset.route;

            // Send a fetch request to update visibility
            fetch(`${routeUrl}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        serviceId,
                        visibilityId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response
                    const buttons = buttonGroup.querySelectorAll('button');
                    buttons.forEach(btn => {
                        if (btn === button) {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    });

                    // Visibility names mapping
                    const visibilityNames = {
                        1: 'Appointments',
                        2: 'Agents',
                        3: 'Both'
                    };

                    // Get the visibility name from the mapping
                    const visibilityName = visibilityNames[visibilityId];

                    // Show SweetAlert alert
                    Swal.fire({
                        title: 'Visibility Changed',
                        text: `You chose to update visibility to "${visibilityName}" `,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle errors
                });
        }
        function handleAvailability(button, serviceId, availabilityId) {
            // Get the button group element
            const buttonGroup = button.parentElement;

            // Get the route URL from the button's data attribute
            const routeUrl = button.dataset.route;

            // Send a fetch request to update visibility
            fetch(`${routeUrl}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        serviceId,
                        availabilityId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response
                    const buttons = buttonGroup.querySelectorAll('button');
                    buttons.forEach(btn => {
                        if (btn === button) {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    });

                    //  names mapping
                    const availabilityNames = {
                        1: 'Subscription Only',
                        2: 'With and Without Subscription'
                    };

                    // Get the availabilityName  from the mapping
                    const availabilityName = availabilityNames[availabilityId];

                    // Show SweetAlert alert
                    Swal.fire({
                        title: 'Availability Changed',
                        text: `You chose to update availability to "${availabilityName}" `,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle errors
                });
        }
        document.addEventListener("DOMContentLoaded", function() {
            let formFieldsCount = 0;
            const formFieldsContainer = document.getElementById("formFields");
            const formDataInput = document.getElementById("form_data");

            function addFormField() {
                formFieldsCount++;

                const formField = `
                <div class="form-group">
                    <label for="inputType${formFieldsCount}">Input Type</label>
                    <select class="form-control" id="inputType${formFieldsCount}" name="input_types[]">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="textarea">Textarea</option>
                        <option value="selectbox">Selectbox</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="radio">Radio</option>
                        <option value="date">Date</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputLabel${formFieldsCount}">Input Label</label>
                    <input type="text" class="form-control" id="inputLabel${formFieldsCount}" name="input_labels[]">
                </div>
                <div class="form-group" id="optionsGroup${formFieldsCount}" style="display:none;">
                    <label for="inputOptions${formFieldsCount}">Input Options (comma-separated)</label>
                    <textarea class="form-control" id="inputOptions${formFieldsCount}" name="input_options[]" rows="2"></textarea>
                </div>
                <hr style="border:1px solid ;">`;

                formFieldsContainer.insertAdjacentHTML("beforeend", formField);
                updateFormData();
            }

            function updateFormData() {
                const formData = [];
                for (let i = 1; i <= formFieldsCount; i++) {
                    const inputType = document.getElementById(`inputType${i}`).value;
                    const inputLabel = document.getElementById(`inputLabel${i}`).value
                        .trim(); // Trim to remove leading/trailing spaces
                    if (inputLabel !== '') { // Check if label is not empty
                        const optionsGroup = document.getElementById(`optionsGroup${i}`);
                        const inputOptions = optionsGroup.style.display === "none" ? [] : document.getElementById(
                            `inputOptions${i}`).value.split(',');

                        formData.push({
                            type: inputType,
                            label: inputLabel,
                            options: inputOptions,
                        });
                    }
                }

                formDataInput.value = JSON.stringify(formData);
                // console.log(JSON.stringify(formData));
            }


            formFieldsContainer.addEventListener("change", function(event) {
                const target = event.target;
                if (target && target.tagName === "SELECT" && target.id.startsWith("inputType")) {
                    const optionsGroup = document.getElementById(`optionsGroup${target.id.slice(-1)}`);
                    optionsGroup.style.display = ["checkbox", "radio", "selectbox"].includes(target.value) ?
                        "block" : "none";
                    updateFormData();
                }
            });

            document.getElementById("addInputButton").addEventListener("click", addFormField);
            document.getElementById("previewButton").addEventListener("click", previewForm);

            function previewForm() {
                $('#addServiceModal').modal('hide');
                updateFormData();
                const formPreviewContainer = document.getElementById("formPreview");
                const formData = JSON.parse(document.getElementById("form_data").value);

                let formPreviewHTML = "<h4>Form Preview</h4><form class='row'>";
                formData.forEach(field => {
                    formPreviewHTML += '<div class="form-group col-md-4">';
                    formPreviewHTML += `<label>${field.label}</label>`;

                    if (["text", "textarea", "date"].includes(field.type)) {
                        formPreviewHTML += `<input type="${field.type}" class="form-control">`;
                    } else if (["selectbox"].includes(field.type)) {
                        formPreviewHTML += '<select class="form-control">';
                        field.options.forEach(option => {
                            formPreviewHTML += `<option>${option}</option>`;
                        });
                        formPreviewHTML += '</select>';
                    } else if (["checkbox", "radio"].includes(field.type)) {
                        field.options.forEach(option => {
                            formPreviewHTML += '<div class="form-check">';
                            formPreviewHTML +=
                                `<input type="${field.type}" class="form-check-input" id="${option}">`;
                            formPreviewHTML +=
                                `<label class="form-check-label" for="${option}">${option}</label>`;
                            formPreviewHTML += '</div>';
                        });
                    }

                    formPreviewHTML += '</div>';
                });
                formPreviewHTML += '</form>';

                formPreviewContainer.innerHTML = formPreviewHTML;

                $('#formPreviewModal').modal('show');
            }


        });

        function closePreviewModal() {
            $('#formPreviewModal').modal('hide');
            $('#addServiceModal').modal('show');
        }

        function previewServiceForm(formInputJson) {
            const formPreviewContainer = document.getElementById("formPreview");

            // Parse the JSON string into an array
            const formData = JSON.parse(formInputJson);

            // Generate the form preview HTML
            let formPreviewHTML = "<h4>Form Preview</h4><form class='row'>";
            formData.forEach(field => {
                formPreviewHTML += '<div class="form-group col-md-4">';
                formPreviewHTML += `<label>${field.label}</label>`;

                if (["text", "textarea", "date"].includes(field.type)) {
                    formPreviewHTML += `<input type="${field.type}" class="form-control">`;
                } else if (["selectbox"].includes(field.type)) {
                    formPreviewHTML += '<select class="form-control">';
                    field.options.forEach(option => {
                        formPreviewHTML += `<option>${option}</option>`;
                    });
                    formPreviewHTML += '</select>';
                } else if (["checkbox", "radio"].includes(field.type)) {
                    field.options.forEach(option => {
                        formPreviewHTML += '<div class="form-check">';
                        formPreviewHTML +=
                            `<input type="${field.type}" class="form-check-input" id="${option}">`;
                        formPreviewHTML +=
                            `<label class="form-check-label" for="${option}">${option}</label>`;
                        formPreviewHTML += '</div>';
                    });
                }

                formPreviewHTML += '</div>';
            });
            formPreviewHTML += '</form>';

            // Set the form preview content in the modal
            formPreviewContainer.innerHTML = formPreviewHTML;

            // Show the modal
            $("#formPreviewModal").modal("show");
        }
    </script>


</body>

</html>
