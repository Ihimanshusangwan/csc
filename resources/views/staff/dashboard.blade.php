<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminDashboard.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg ">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('staff.logout') }}" class="btn btn-danger">Logout</a>
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
                <h3 class="dashboard">Staff Dashboard</h3>
            </div>

        </div>

        <div class="row">
            <div class="col-3">
                <div class="total-registration background-total-registration">
                    <div class="align">
                        <div class="registration-text">Total Applications</div>
                        <div class="count">{{ $totalApplicationCount }}</div>
                    </div>
                    <div class="material-icons chevron_right">chevron_right</div>
                </div>
            </div>
            <div class="col-3">
                <div class="total-registration todays-registration">
                    <div class="align">
                        <div class="registration-text">Today's Applications</div>
                        <div class="count">{{ $countOfTodaysApplications }}</div>
                    </div>
                    <div class="material-icons chevron_right">chevron_right</div>
                </div>
            </div>
            <div class="col-3">
                <div class="total-registration background-process-completed">
                    <div class="align">
                        <div class="registration-text">Completed Applications</div>
                        <div class="count">{{ $completedApplicationsCount }}</div>
                    </div>
                    <div class="material-icons chevron_right">chevron_right</div>
                </div>
            </div>
            <div class="col-3">
                <div class="total-registration background-pending">
                    <div class="align">
                        <div class="registration-text">Pending Applications</div>
                        <div class="count"> {{ $pendingApplicationsCount }}</div>
                    </div>
                    <div class="material-icons chevron_right">chevron_right</div>
                </div>
            </div>
        </div>

        <h3 class="mt-4 text-center">Latest Applications</h3>
        <div class="sort-filter">
            <div class="dropdowns">
                <div class="dropdown">
                    <label><strong>Sort By Status: </strong></label>
                    <div class="input-group">
                        <select class="form-select" id="inputGroupSelect01">
                            <option selected disabled>Choose...</option>
                            <option value="1">All</option>
                            <option value="2">Pending</option>
                            <option value="2">Success</option>
                            <option value="2">Failed</option>
                        </select>
                    </div>
                </div>
                <div class="dropdown" style="margin-left: 1rem">
                    <label><strong>Sort By Agent: </strong></label>
                    <div class="input-group">
                        <select class="form-select" id="inputGroupSelect02">
                            <option selected disabled>Choose Agent Name...</option>
                            <option value="1">All</option>
                            <option value="1">Lokesh Thakare</option>
                            <option value="2">Himanshu Sangwan</option>
                            <option value="2">Sadashiv</option>
                            <option value="2">Avinash</option>
                        </select>
                    </div>
                </div>
                <div class="dropdown" style="margin-left: 1rem">
                    <label><strong>Sort By Requested: </strong></label>
                    <div class="input-group">
                        <select class="form-select" id="inputGroupSelect04">
                            <option selected disabled>Choose...</option>
                            <option value="1">All</option>
                            <option value="1">Requested</option>
                            <option value="2">Approve</option>
                            <option value="2">Rejected</option>
                            <option value="2">Hold</option>
                        </select>
                    </div>
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
                    <th scope="col">Upload Document</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>


                @php   $counter = 1; @endphp @foreach ($applications as $application)
                    <tr class="text-center">
                        <th scope="row">{{ $counter++ }}</th>
                        <td>{{ $application->customer_name }}</td>
                        <td>{{ $application->agent_name }}</td>
                        <td>{{ $application->apply_date }}</td>
                        <td>
                            <form action="{{ route('application.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="application_id" value="{{ $application->id }}" />
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                                    value="{{ $application->delivery_date ? $application->delivery_date : '' }}">
                        </td>

                        <td>
                            <input type="hidden" name="reason" value="NA">
                            <select class="form-control-sm statuses-menu" id="status" name="status">
                                <option value="-1" {{ $application->status == -1 ? 'selected' : '' }}
                                    class="text-danger">Rejected</option>
                                <option value="0" {{ $application->status == 0 ? 'selected' : '' }}
                                    class="text-info">Initiated</option>
                                <option value="1" {{ $application->status == 1 ? 'selected' : '' }}
                                    class="text-warning">In Progress</option>
                                <option value="2" {{ $application->status == 2 ? 'selected' : '' }}
                                    class="text-success">Completed</option>

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
                                        @endphp

                                        @if ($id !== null && $statusName !== null)
                                            <option value="{{ $id }}" style="color: {{ $statuscolor }};"
                                                {{ $application->status == $id ? 'selected' : '' }}>
                                                {{ $statusName }}</option>
                                        @endif
                                    @endforeach
                                @endif


                            </select>
                            @if ($application->status == -1)
                                <span class="text-danger" style="text-decoration: underline; cursor: pointer;"
                                    data-reason="{{ $application->reason }}" data-toggle="modal"
                                    data-target="#reasonModal{{ $application->id }}">Reason</span>

                                <!-- Modal -->
                                <div class="modal fade" id="reasonModal{{ $application->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reasonModalLabel">Reason</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $application->reason }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
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
                        <td>
                            @if ($application->delivery_date && $application->delivery)
                                <!-- If delivery date exists, display a link to open the document in another tab -->
                                <div class="form-group">
                                    Uploaded Document:<a href="{{ asset($application->delivery) }}" target="_blank"
                                        style="color: blue;">View Document</a>
                                </div>
                            @endif
                            <div class="form-group">
                                <input type="file" class="form-control" id="document" name="document"
                                    placeholder="upload Document">
                            </div>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </td>
                        </form>


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
                                        @php
                                            $formData = json_decode($application->form_data, true);
                                            $i = 1;
                                        @endphp
                                        @foreach ($formData as $category => $fields)
                                            @if (strtolower($category) !== 'service_id' && strtolower($category) !== 'filepaths')
                                                @foreach ($fields as $key => $value)
                                                    @php
                                                        if ($i == 1) {
                                                            $i++;
                                                            continue;
                                                        }
                                                    @endphp
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
                                            <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong> <a
                                                    href="{{ asset($value) }}" target="_blank"
                                                    style="color: blue;">View {{ $key }}</a></p>
                                        @endforeach
                                    </div>
                                    <div class="modal-footer">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
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
    <script>
        document.querySelectorAll('.statuses-menu').forEach((e) => {
            const reasonInput = e.previousElementSibling;
            e.addEventListener('change', (event) => {
                let statusId = event.target.value;
                if (statusId == -1) {
                    var reason = prompt("Please enter the reason:");
                    if (reason !== null) {
                        reasonInput.value = reason;
                    }
                }

            });
        });
    </script>
    <script src="{{ asset('js/adminDashboard.js') }}"></script>
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
