@if (isset($service[0]))
    @php
        $service = $service[0];
        $totalFields = 0;
    @endphp
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $service->name }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <script src="{{ asset('js/sweetAlert.js') }}"></script>
        <style>
            .income-certificate-page {
                width: 100%;
                height: 100%;
                padding-left: 3rem;
                padding-right: 3rem;

            }

            .heading {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }

            .button {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: row;

            }

            .padding-add {
                padding: 0.5rem 4rem;
                border-radius: 8px;
            }

            body {
                height: 100%;
                width: 100%;
                padding: 1rem 1rem;
                background: linear-gradient(to right, #e9dfc4 0%, #e9dfc4 1%, #ede3c8 2%, #ede3c8 24%, #ebddc3 25%, #e9dfc4 48%, #ebddc3 49%, #e6d8bd 52%, #e6d8bd 53%, #e9dbc0 54%, #e6d8bd 55%, #e6d8bd 56%, #e9dbc0 57%, #e6d8bd 58%, #e6d8bd 73%, #e9dbc0 74%, #e9dbc0 98%, #ebddc3 100%);
                background-size: 120px;
                background-repeat: repeat;
            }
        </style>
    </head>

    <body>


        <h2></h2>

        <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary m-3">Home</a>
        <div class="income-certificate-page">
            <div class="heading">
                <h3 class="text-danger mt-4">
                    <strong> {{ $service->name }} Form</strong>
                </h3>
                <p><strong>Note : </strong>Please fill below details carefully</p>
                <div id="progress-bar" class="progress m-4" style="width: 50%">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
            <form method="POST" action="{{ route('submitForm', $service->id) }}" enctype="multipart/form-data"
                id="myForm">

                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <div class="container my-3">

                    <h2>Customer Information</h2>
                    <div class="row ">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="customerName">Customer Name</label>
                                <input type="text" class="form-control" name="customerName"
                                    placeholder="Enter customer name" required>
                            </div>
                        </div>
                        <div class="col-md-5 offset-md-1">
                            <div class="form-group">
                                <label for="mobileNumber">Mobile Number</label>
                                <input type="text" class="form-control" name="mobileNumber"
                                    placeholder="Enter mobile number" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    @foreach (json_decode($service->form) as $field)
                        @php
                            $totalFields++;
                        @endphp
                        <div class="col-4">
                            <div class="form-group">
                                <label for="{{ $field->label }}">{{ $field->label }}</label>
                                @if (in_array($field->type, ['text', 'number', 'date']))
                                    <input type="{{ $field->type }}" name="{{ Str::slug($field->label) }}"
                                        id="{{ $field->label }}" class="form-control" required oninput='updateProgressBar()'>
                                @elseif($field->type == 'selectbox')
                                    <select name="{{ Str::slug($field->label) }}" id="{{ $field->label }}"
                                        class="form-control" oninput='updateProgressBar()' required>
                                        @foreach ($field->options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field->type == 'textarea')
                                    <textarea name="{{ Str::slug($field->label) }}" id="{{ $field->label }}" class="form-control"
                                        oninput='updateProgressBar()' required></textarea>
                                @elseif(in_array($field->type, ['radio', 'checkbox']))
                                    <br>
                                    @foreach ($field->options as $option)
                                        <input type="{{ $field->type }}" name="{{ Str::slug($field->label) }}[]"
                                            id="{{ $option }}" value="{{ $option }}"
                                            class="form-check-input" oninput='updateProgressBar()' required>
                                        <label for="{{ $option }}">{{ $option }}</label>
                                        <br>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <label class="my-2"><strong>Upload Supporting Documents:</strong></label>
                    @foreach (explode(',', $service->requirements) as $requirement)
                        @php
                            $totalFields++;
                        @endphp
                        <div class="col-4">
                            <div class="form-group">
                                <label for="{{ Str::slug($requirement) }}">{{ $requirement }}</label>
                                <input type="file" name="{{ Str::slug($requirement) }}"
                                    id="{{ Str::slug($requirement) }}" class="form-control"
                                    oninput='updateProgressBar()' required>
                            </div>
                        </div>
                    @endforeach


                    <label class='mt-4'>
                        <input type="radio" name="price_type" value="default" checked>
                        Default
                    </label>

                    <label>
                        <input type="radio" name="price_type" value="tatkal">
                        Tatkal
                    </label>

                    <div id="default_price" class="price_section">
                        Default Price: <strong>{{ $defaultPrice }}</strong>
                    </div>

                    <div id="tatkal_price" class="price_section" style="display: none;">
                        Tatkal Price: <strong>{{ $tatkalPrice }}</strong>
                    </div>




                    <!-- <div class="" style="margin-top: 20px">
                    <label><strong>Upload Supporting Documents:</strong></label>
                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Document</th>
                                        <th>Upload Image</th>
                                        <th>Uploaded Image</th>
                                        <th>Delete</th>
                                    </tr>
                                    <tbody id="tbody"></tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-warning" onclick="addItem();">
                                <strong> Upload Documents</strong>
                            </button>
                        </div>
                    </div>
                </div> -->

                    <div class="button m-4">
                        <button type="submit" class="btn btn-danger padding-add" id="submitButton">
                            <strong>Submit</strong>
                        </button>

                    </div>
                @else
                    <p>No service found.</p>
@endif
</form>


</div>
</div>
<script>
    var totalFields = {{ $totalFields }};
    document.addEventListener("DOMContentLoaded", function() {
        var balance = {{ $balance }};
        if (balance === null) {
            balance = 0;
        }

        document.getElementById('submitButton').addEventListener('click', function(event) {
    event.preventDefault();

    var inputs = document.getElementById('myForm').querySelectorAll('input[required]');
    var isValid = true;

    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            isValid = false;
            input.setCustomValidity('This field is required.');
        } else {
            input.setCustomValidity('');
        }
    });

    // If form validation passes
    if (isValid) {
        var selectedPriceType = document.querySelector('input[type=radio][name=price_type]:checked').value;
        var selectedPriceSection = document.getElementById(selectedPriceType + '_price');
        var selectedPrice = parseFloat(selectedPriceSection.innerText.match(/[\d\.]+/));

        if (balance >= selectedPrice) {
            document.getElementById('myForm').submit();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Insufficient Balance',
                text: ` Your Balance is just Rs.${balance}. Please recharge your account.`,
            });
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Form Validation Error, All Fields are required',
            text: 'Please fill in all required fields.',
        });
    }
});

    });

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('input[type=radio][name=price_type]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.price_section').forEach(function(section) {
                    section.style.display = 'none';
                });
                document.getElementById(this.value + '_price').style.display = 'block';
            });
        });
    });

    // function addItem() {
    // var documentNames = 
    // json_encode(explode(',,', $service->requirements)) 
    // !!};
    //     var today = new Date();
    //     var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

    //     // Increment items and i (if you need it)
    //     // items++;
    //     // i++;

    //     var html = "<tr>";
    //     html += "<td>" + today.toJSON().slice(0, 10) + "</td>";
    //     html += "<td>" + time + "</td>";
    //     if (i >= documentNames.length) {
    //         html += "<td class='col-md-2' input type='text'></td>";
    //     } else {
    //         html += "<td>" + documentNames[i] + "</td>";
    //     }
    //     html += "<td class='col-md-2'><input type='file' oninput='updateProgressBar()'></td>";
    //     html += "<td><img src='' alt='image'></td>";
    //     html += "<td><button class='btn btn-danger' type='button' onclick='deleteRow(this);'>Delete</button></td>";
    //     html += "</tr>";

    //     var tbody = document.getElementById("tbody");
    //     tbody.innerHTML += html;
    // }

    // function deleteRow(button) {
    //     var row = button.parentNode.parentNode;
    //     row.parentNode.removeChild(row);
    // }

    // Function to update the progress bar based on the filled data
    function updateProgressBar() {
        var filledFields = 0;

        // Check each input field and count the filled ones
        var inputFields = document.querySelectorAll(
            'input[type="text"], input[type="number"], input[type="email"], input[type="date"], input[type="textarea"], input[type="file"], input[type="radio"], input[type="checkbox"]'
            );


        inputFields.forEach(function(field) {
            if (field.value.trim() !== '') {
                filledFields++;
            }
        });

        // Calculate the progress percentage based on the filled data
        var progressPercentage = (filledFields / totalFields) * 100;

        // Update the progress bar style
        var progressBar = document.getElementById('progress-bar');
        progressBar.querySelector('.progress-bar').style.width = progressPercentage + '%';
        progressBar.querySelector('.progress-bar').setAttribute('aria-valuenow', progressPercentage);
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>
</body>

</html>
