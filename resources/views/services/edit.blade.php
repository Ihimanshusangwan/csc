<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Edit Service</h2>
        <form id="serviceForm" method="POST" action="{{ route('services.update', $service->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Service Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $service->name }}"
                    required>
            </div>

            <div class="form-group">
                <label for="service_group_id">Service Group</label>
                <select class="form-control" id="service_group_id" name="service_group_id" required>
                    @foreach ($serviceGroups as $group)
                        <option value="{{ $group->id }}"
                            {{ $group->id == $service->service_group_id ? 'selected' : '' }}>{{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="requirements">Document Requirements</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="3" required>{{ $service->requirements }}</textarea>
            </div>

            <div class="form-group">
                <h3>Form Data</h3>
                <div id="formFields"></div>
                <button type="button" class="btn btn-success" id="addInputButton">Add Input</button>
                <input type="hidden" name="form" id="formData" value="{{ $service->form }}">
            </div>

            <button type="submit" class="btn btn-primary">Update Service</button>
        </form>
    </div>

    <script>
        const formData = JSON.parse('{!! $service->form !!}');
        let fieldCount = 0;

        function populateFormFields() {
            formData.forEach((field, index) => {
                addInputField(field.type, field.label, field.options);
            });
        }

        function addInputField(type = 'text', label = '', options = []) {
            fieldCount++;

            const optionsHtml = options.length > 0 ? options.join(',') : '';

            const fieldHTML = `
            <div class="form-group">
                <label for="inputType${fieldCount}">Input Type</label>
                <select class="form-control" id="inputType${fieldCount}" name="input_types[]" onchange="toggleOptionsGroup(${fieldCount})">
                    <option value="text" ${type === 'text' ? 'selected' : ''}>Text</option>
                    <option value="number" ${type === 'number' ? 'selected' : ''}>Number</option>
                    <option value="textarea" ${type === 'textarea' ? 'selected' : ''}>Textarea</option>
                    <option value="selectbox" ${type === 'selectbox' ? 'selected' : ''}>Selectbox</option>
                    <option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                    <option value="radio" ${type === 'radio' ? 'selected' : ''}>Radio</option>
                    <option value="date" ${type === 'date' ? 'selected' : ''}>Date</option>
                </select>
                <label for="inputLabel${fieldCount}">Input Label</label>
                <input type="text" class="form-control" id="inputLabel${fieldCount}" name="input_labels[]" value="${label}">
                <div id="optionsGroup${fieldCount}" style="display:${type === 'selectbox' || type === 'radio' || type === 'checkbox' ? 'block' : 'none'};">
                    <label for="inputOptions${fieldCount}">Input Options (comma-separated)</label>
                    <textarea class="form-control" id="inputOptions${fieldCount}" name="input_options[]" rows="2">${optionsHtml}</textarea>
                </div>
                <button type="button" class="btn btn-danger mt-2" onclick="removeInputField(${fieldCount})">Remove</button>
            </div>
            <hr>`;

            document.getElementById("formFields").insertAdjacentHTML("beforeend", fieldHTML);
        }

        function removeInputField(index) {
            const fieldGroup = document.querySelector(`#inputType${index}`).closest('.form-group');
            fieldGroup.remove();
            updateFormData();
        }

        function updateFormData() {
            const inputTypes = Array.from(document.querySelectorAll('[name="input_types[]"]'));
            const inputLabels = Array.from(document.querySelectorAll('[name="input_labels[]"]'));
            const inputOptions = Array.from(document.querySelectorAll('[name="input_options[]"]'));

            const formData = inputTypes.map((type, index) => ({
                type: type.value,
                label: inputLabels[index].value,
                options: ['selectbox', 'radio', 'checkbox'].includes(type.value) ? inputOptions[index].value
                    .split(',') : [],
            }));

            document.getElementById("formData").value = JSON.stringify(formData);
        }

        function toggleOptionsGroup(index) {
            const selectBox = document.getElementById(`inputType${index}`);
            const optionsGroup = document.getElementById(`optionsGroup${index}`);
            optionsGroup.style.display = ['selectbox', 'radio', 'checkbox'].includes(selectBox.value) ? 'block' : 'none';
        }

        document.getElementById("addInputButton").addEventListener("click", () => addInputField());

        // Handle form submission to include preview functionality
        document.getElementById("serviceForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission
            updateFormData(); // Update the form data before submission
            this.submit(); // Now submit the form
        });

        // Populate existing fields
        populateFormFields();
    </script>

    <script src="{{ asset('js/sweetAlert.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
