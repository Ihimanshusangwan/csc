<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminDashboard.css') }}">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- Brand -->
    <a class="navbar-brand" href="#">Dokument Guru</a>

    <!-- Toggler/collapsible Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="modal"
                        data-bs-target="#agentRegistrationModal">
                    Register Agent
                </button>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.requested-agents') }}">Agents Request</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.registered-agents') }}">Registered Agents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.registered-staff') }}">Registered Staffs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.registered-fieldboy') }}">Registered Field Boys</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.staff_managers') }}">Registered Staff Managers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('service-groups.index') }}">Manage Service Groups</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('services.index') }}">Manage Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('locations.index') }}">Manage Locations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('plans.index') }}">Manage Plans</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.filter') }}">Filter</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.recharge-history') }}">Recharge History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.appointment-history') }}">Appointments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('staffs.create') }}">Register Staff</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register-manager') }}">Register Staff Manager</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('fieldboy.create') }}">Register Field Boy</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.bill') }}">Create Bill</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.bill-filter') }}">View Bill</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.troubleshoot') }}">Run Troubleshooter</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.delete-form') }}">Clear Database</a>
            </li>
        </ul>
    </div>

    <!-- Logout Button -->
    <div class="navbar-nav">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-danger">Logout</button>
        </form>
    </div>
</nav>


@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if ($errors->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="agent-data-page">
    <div class="heading">
        <div class="dashboard-content">
            <span class="material-icons home-icon"> home </span>
            <h3 class="dashboard">Admin Dashboard</h3>
            <a href="{{ route('admin.configurations.index') }}" class="btn btn-primary btn-sm mx-5">Go to
                Configurations</a>

        </div>
        <div class="background-points">
            <div class="points">Total Earnings: &#8377;{{ $sumOfPrices }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <a class="total-registration background-total-registration"
               href="{{ route('admin.applications', ['category' => 'all']) }}">
                <div class="align">
                    <div class="registration-text">Total Applications</div>
                    <div class="count">{{ $totalApplicationCount }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
        <div class="col-3">
            <a class="total-registration todays-registration"
               href="{{ route('admin.applications', ['category' => 'today']) }}">
                <div class="align">
                    <div class="registration-text">Today's Applications</div>
                    <div class="count">{{ $countOfTodaysApplications }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
        <div class="col-3">
            <a class="total-registration background-process-completed"
               href="{{ route('admin.applications', ['category' => 'completed']) }}">
                <div class="align">
                    <div class="registration-text">Completed Applications</div>
                    <div class="count">{{ $completedApplicationsCount }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
        <div class="col-3">
            <a class="total-registration background-pending"
               href="{{ route('admin.applications', ['category' => 'pending']) }}">
                <div class="align">
                    <div class="registration-text">Pending Applications</div>
                    <div class="count"> {{ $pendingApplicationsCount }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
    </div>

    <h3 class="mt-4 text-center">Latest Applications</h3>
    <div class="sort-filter flex justify-content-start">
        <div class="dropdowns">
            <form method="GET" action="">
                @csrf
                <div class="form-group">
                    <label for="applicantName">Applicant Name:</label>
                    <input type="text" class="form-control" id="applicantName" name="applicantName"
                           value="{{ request('applicantName') }}">
                </div>
        </div>

        <button type="submit" class="btn btn-primary mx-4 mt-4">Search</button>
        </form>
    </div>
</div>

<div class="total-count">
    <strong class="text-primary">Total : {{ $totalApplicationCount }} </strong>
</div>
</div>
<table class="table table-striped mt-4">
    <thead>
    <tr class="table-dark text-center">
        <th scope="col">Sr.No</th>
        <th scope="col">Applicant Name</th>
        <th scope="col">Agent Name</th>
        <th scope="col">Date of Application</th>
        <th scope="col">Estimated Delivery Date</th>
        <th scope="col">Status</th>
        <th scope="col">Applied For</th>
        <th scope="col">Preview</th>
        <th scope="col">Reporting Staff</th>
        <th scope="col">Ruppes(&#8377;)</th>
        <th scope="col">Upload Document</th>
        <th scope="col">Upload Receipt</th>
        <th scope="col" colspan="2">Action</th>
    </tr>
    </thead>
    <tbody>


    @php $counter = 1; @endphp @foreach ($applications as $application)
        <tr class="text-center">
            <th scope="row">{{ $counter++ }}</th>
            <td>{{ $application->customer_name }}</td>
            <td>{{ $application->shop_name }} ( {{ $application->agent_name }} )</td>
            <td>{{ $application->apply_date }}</td>
            <td>
                <form action="{{ route('application.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->id }}"/>
                    <input type="date" class="form-control-sm" id="delivery_date" name="delivery_date"
                           value="{{ $application->delivery_date ? $application->delivery_date : '' }}">
            </td>

            <td>
                <input type="hidden" name="reason" value="NA">
                <select class="form-control-sm statuses-menu" id="status" name="status">
                    <option value="-1" {{ $application->status == -1 ? 'selected' : '' }} class="text-danger">Rejected
                    </option>
                    <option value="0" {{ $application->status == 0 ? 'selected' : '' }} class="text-info">Initiated
                    </option>
                    <option value="1" {{ $application->status == 1 ? 'selected' : '' }} class="text-warning">In
                        Progress
                    </option>
                    <option value="2" {{ $application->status == 2 ? 'selected' : '' }} class="text-success">Completed
                    </option>

                    {{-- Explode statuses and create options --}}
                    @php
                        $statusesArray = explode(',', $application->statuses);
                    @endphp

                    @if (count($statusesArray) > 0)
                        @foreach ($statusesArray as $status)
                            @php
                                $statusParts = explode(':', $status);
                                $id = $statusParts[0] ?? null;
                                $statusName = $statusParts[1] ?? null;
                                $statuscolor = $statusParts[2] ?? null;
                                $askReason = $statusParts[3] ?? null;
                            @endphp

                            @if ($id !== null && $statusName !== null)
                                <option value="{{ $id }}" style="color: {{ $statuscolor }};"
                                        data-ask_reason="{{ $askReason }}"
                                    {{ $application->status == $id ? 'selected' : '' }}>
                                    {{ $statusName }}
                                </option>
                            @endif
                        @endforeach
                    @endif


                </select>
                @if ($application->status == -1)
                    <span class="text-danger" style="text-decoration: underline; cursor: pointer;"
                          data-reason="{{ $application->reason }}" data-toggle="modal"
                          data-target="#reasonModal{{ $application->id }}">Reason</span>

                    <!-- Modal -->
                    <div class="modal fade" id="reasonModal{{ $application->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="reasonModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reasonModalLabel">Reason</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ $application->reason }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </td>
            <td>{{ $application->service_name }}</td>
            <td>
                                <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                                      data-target="#agentModal{{ $application->id }}">
                                    preview
                                </span>
            </td>
            <td>{{ $application->staffName }}</td>
            <td class="text-success"> &#8377;{{ $application->price }}</td>
            <td>
                @if ($application->delivery_date && $application->delivery)
                    <!-- If delivery date exists, display a link to open the document in another tab -->
                    <div class="form-group">
                        Uploaded Document:<a href="{{ asset($application->delivery) }}" target="_blank"
                                             style="color: blue;">View Document</a>
                    </div>
                @endif
                <div class="form-group">
                    <input type="file" class="form-control-sm" id="document" name="document"
                           placeholder="upload Document">
                </div>
            </td>
            <td>
                @if ($application->receipt)
                    <div class="form-group">
                        Uploaded Reciept:<a href="{{ asset($application->receipt) }}" target="_blank"
                                            style="color: blue;">View Receipt</a>
                    </div>
                @endif
                <div class="form-group">
                    <input type="file" class="form-control-sm" id="receipt" name="receipt"
                           placeholder="upload receipt">
                </div>
            </td>
            <td>
                <button type="submit" class="btn btn-sm btn-primary mb-1">Update</button>
                </form>


            </td>
            <td>
                <form action="{{ route('applications.destroy', $application->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this application?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>

            </td>


            <!-- Modal -->
            <div class="modal fade" id="agentModal{{ $application->id }}" tabindex="-1" role="dialog"
                 aria-labelledby="agentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agentModalLabel">
                                Application Details - {{ $application->customer_name }}
                            </h5>
                        </div>
                        <div class="modal-body">
                            <p><strong>Mobile Number:</strong> {{ $application->customer_mobile }}</p>
                            @php
                                $formData = json_decode($application->form_data, true);
                                $i = 1;
                                $filePathsKeys = array_keys($formData['filePaths']);
                            @endphp
                            @foreach ($formData as $category => $fields)
                                @if (strtolower($category) !== 'service_id' && strtolower($category) !== 'filepaths')
                                    @foreach ($fields as $key => $value)

                                        @if(in_array($key, $filePathsKeys) || $i == 1)
                                            @php
                                                $i++;
                                                continue;
                                            @endphp
                                        @endif

                                        @if (!empty($value))
                                            @if (is_array($value))
                                                <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong>
                                                    {{ implode(', ', $value) }}</p>
                                            @else
                                                <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong>
                                                    {{ $value }}</p>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            @foreach ($formData['filePaths'] as $key => $value)
                                <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong>
                                    <a href="{{ asset($value) }}" target="_blank"
                                       class="text-primary">View {{ $key }}</a>
                                </p>
                                @if(isset($formData['formData'][$key]['is_re_requested']))
                                    @if($formData['formData'][$key]['is_re_requested'])
                                        @if($formData['formData'][$key]['is_re_requested'])
                                            <p class="text-danger">
                                                <b>Requested Again with
                                                    Message: </b>{{ $formData['formData'][$key]['message'] }}
                                            </p>
                                        @else
                                            <p class="text-warning"><b>Re Submitted by Agent</b></p>
                                            <a class="btn btn-sm btn-info" href="#"
                                               onclick="requestAgainWithMessage('{{ $application->id }}', '{{ $key }}')">Request
                                                Again with message</a>

                                        @endif
                                    @else
                                        <p class="text-warning"><b>Re Submitted by Agent</b></p>
                                        <a class="btn btn-sm btn-info" href="#"
                                           onclick="requestAgainWithMessage('{{ $application->id }}', '{{ $key }}')">Request
                                            Again with message</a>

                                    @endif
                                @else
                                    <p>
                                        <a class="btn btn-sm btn-warning" href="#"
                                           onclick="requestAgainWithMessage('{{ $application->id }}', '{{ $key }}')">Request
                                            Again with message</a>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </tr>
    @endforeach
    </tbody>
</table>
<div>
    {{ $applications->links('pagination::bootstrap-5') }}
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>
<script src="{{ asset('js/adminDashboard.js') }}"></script>
<script>
    document.querySelectorAll('.statuses-menu').forEach((e) => {
        const reasonInput = e.previousElementSibling;
        e.addEventListener('change', (event) => {
            var askReason = 0;
            let statusId = event.target.value;
            for (var i = 0; i < event.target.options.length; i++) {
                if (event.target.options[i].value === statusId) {
                    askReason = event.target.options[i].dataset.ask_reason;
                    break;
                }
            }
            if (statusId == -1 || askReason == 1) {
                var reason = prompt("Please enter the reason:");
                if (reason !== null) {
                    reasonInput.value = reason;
                }
            }

        });
    });

    function requestAgainWithMessage(applicationId, documentKey) {
        var message = prompt("Please enter your message:");
        if (message !== null) {
            window.location.href = "{{ route('document.request') }}" + "?application_id=" + applicationId + "&document_key=" + documentKey + "&message=" + encodeURIComponent(message);
        }
    }
</script>
</body>

</html>

<!-- PREVIEW DOCUMENTS MODEL -->

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                    Uploaded Documents
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- AGENT REGISTRATION MODAL -->

<!-- Modal -->
<div class="modal fade" id="agentRegistrationModal" tabindex="-1" aria-labelledby="agentRegistrationModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="post" action="{{ route('agents.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Register Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="dropdown">
                                <label><strong>Type(Send to List): </strong></label>
                                <div class="input-group">
                                    <select class="form-control" id="typeSelect" onchange="toggleRegReq(this)"
                                            name="type" required>
                                        <option selected disabled>Choose...</option>
                                        <option value="Register">Register</option>
                                        <option value="Request">Request</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputName"><strong>Full Name:</strong></label>
                                <input type="text" name="full_name" class="form-control" id="Name"
                                       aria-describedby="nameHelp" placeholder="Enter Full Name"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputPhone"><strong>Mobile No:</strong></label>
                                <input type="number" class="form-control" name="mobile_number"
                                       placeholder="Enter Mobile Number"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail"><strong>Email:</strong></label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email id"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputAddress"><strong>Address:</strong></label>
                                <input type="text" class="form-control" name="address" placeholder="Enter Address"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="shopName"><strong>Shop Name:</strong></label>
                                <input type="text" class="form-control" name="shop_name"
                                       placeholder="Enter Shop Name"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputShopAddress"><strong>Shop Address:</strong></label>
                                <input type="text" class="form-control" name="Shop_address"
                                       placeholder="Enter Shop Address"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadadhar"><strong>Upload Aadhar Card:</strong></label>
                                <input type="file" class="form-control" name="upload_aadhar"
                                       placeholder="Enter Shop Name"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadShopLicense"><strong>Upload Shop License:</strong></label>
                                <input type="file" class="form-control" name="upload_shop_license"
                                       placeholder="Enter Shop Address"/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadOwnerPhoto"><strong>Upload Owner Photo:</strong></label>
                                <input type="file" class="form-control" name="upload_owner_photo"
                                       placeholder="Enter Shop Name"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadSupportingDocument"><strong>Upload Supporting
                                        Document:</strong></label>
                                <input type="file" class="form-control" name="uploadSupportingDocument"
                                       placeholder="Enter Shop Address"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="dropdown">
                            <label><strong>Select Location: </strong></label>
                            <div class="input-group">
                                <!-- Dropdown for Locations -->
                                <select name="location_id" class="form-control">
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">
                                            {{ $location->district . ' (' . $location->state . ')' }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputUsername"><strong>Username:</strong></label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Username"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputPassword"><strong>Password:</strong></label>
                                <input type="password" class="form-control" name="password"
                                       placeholder="Enter Password"/>
                            </div>
                        </div>
                    </div>
                    <div id="registerFields" class="d-none">
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="dropdown">
                                    <label><strong>Select Plan: </strong></label>
                                    <div class="input-group">
                                        <!-- Dropdown for Plans -->
                                        <select name="plan_id" class="form-control">
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="dropdown">
                                    <label><strong>Payment Status: </strong></label>
                                    <div class="input-group">
                                        <select class="form-select" name="payment_status">
                                            <option selected disabled>Choose...</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Unpaid">Unpaid</option>
                                            <option value="Free Plan">Free Plan</option>
                                            <option value="To be Paid">To be Paid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="dropdown">
                                    <label><strong>Payment Mode: </strong></label>
                                    <div class="input-group">
                                        <select class="form-select" name="payment_mode">
                                            <option selected disabled>Choose...</option>
                                            <option value="Net Banking">Net Banking</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Cash">Cash</option>
                                            <option value="NA">NA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="examplePaidAmount"><strong>Paid Amount (&#8377;):</strong></label>
                                    <input type="number" class="form-control" name="paid_amount"
                                           placeholder="Enter Paid Amount"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleUnpaidAmount"><strong>Unpaid Amount(&#8377;):</strong></label>
                                    <input type="number" class="form-control" name="unpaid_amount"
                                           placeholder="Enter Unpaid Amount"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
    </div>
</div>
</form>
