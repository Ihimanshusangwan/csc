<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script src="{{asset('js/sweetAlert.js')}}"></script>

    <style>
        body {
            height: 100%;
            width: 100%;
            padding: 1rem 1rem;
            background: linear-gradient(to right, #e9dfc4 0%, #e9dfc4 1%, #ede3c8 2%, #ede3c8 24%, #ebddc3 25%, #e9dfc4 48%, #ebddc3 49%, #e6d8bd 52%, #e6d8bd 53%, #e9dbc0 54%, #e6d8bd 55%, #e6d8bd 56%, #e9dbc0 57%, #e6d8bd 58%, #e6d8bd 73%, #e9dbc0 74%, #e9dbc0 98%, #ebddc3 100%);
            background-size: 120px;
            background-repeat: repeat;
        }

        .agent-data-page {
            height: 100%;
            width: 100%;
            padding: 1rem 1rem;
        }

        .heading {
            /* width: 100%; */
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row;
        }

        .sort-filter {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row;
        }

        .total-count {
            margin-top: 2rem;
        }

        .dashboard-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }

        .background-points {
            background-color: white;
            /* width: 3rem; */
            border-radius: 2rem;
            margin-right: 3rem;
            cursor: pointer;
        }

        .points {
            color: green;
            font-family: sans-serif;
            font-weight: bolder;
            font-size: 15px;
            text-align: center;
            padding: 0.3rem 0.5rem;
        }

        .dashboard {
            margin-left: 0.3rem;
        }

        .home-icon {
            margin-bottom: 0.5rem;
        }

        .chevron_right {
            color: white;
            font-size: 50px;
        }

        .background-total-registration {
            background-color: red;
        }

        .background-process-completed {
            background-color: #355E3B;
        }

        .background-pending {
            background-color: darkcyan;
        }

        .todays-registration {
            background-color: darkcyan;
        }

        .background-pending {
            background-color: maroon;
        }

        .align {
            width: 90%;
        }

        .total-registration {
            border-radius: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-direction: row;
            width: 95%;
        }

        .registration-text {
            font-weight: bolder;
            color: white;
            font-size: 20px;
            padding: 1rem 0rem 1rem 1rem;
            font-family: sans-serif;
        }

        .count {
            font-weight: bolder;
            color: white;
            font-size: 18px;
            padding: 0rem 1rem 1rem 1rem;
            font-family: sans-serif;

        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/adminDashboard.css') }}">
</head>

<body>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: true,
            timer: 3000 // Close the alert after 3 seconds
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: true,
            timer: 3000 // Close the alert after 3 seconds
        });
    </script>
@endif
<div class="agent-data-page">
    <div class="heading">
        <div class="dashboard-content">
            <span class="material-icons home-icon"> home </span>
            <h3 class="dashboard">Dashboard</h3>
        </div>
        <div class="background-points">
            <div class="points">Total Earnings: &#8377;{{ $sumOfPrices }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <a class="total-registration background-total-registration"
               href="{{ route('agent.applications', ['category' => 'all']) }}">
                <div class="align">
                    <div class="registration-text">Total Applications</div>
                    <div class="count">{{ $totalApplicationCount }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
        <div class="col-3">
            <a class="total-registration todays-registration"
               href="{{ route('agent.applications', ['category' => 'today']) }}">
                <div class="align">
                    <div class="registration-text">Today's Applications</div>
                    <div class="count">{{ $countOfTodaysApplications }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
        <div class="col-3">
            <a class="total-registration background-process-completed"
               href="{{ route('agent.applications', ['category' => 'completed']) }}">
                <div class="align">
                    <div class="registration-text">Completed Applications</div>
                    <div class="count">{{ $completedApplicationsCount }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
        <div class="col-3">
            <a class="total-registration background-pending"
               href="{{ route('agent.applications', ['category' => 'pending']) }}">
                <div class="align">
                    <div class="registration-text">Pending Applications</div>
                    <div class="count"> {{ $pendingApplicationsCount }}</div>
                </div>
                <div class="material-icons chevron_right">chevron_right</div>
            </a>
        </div>
    </div>

    <h3 class="mt-4 text-center">Today's Applications</h3>
    <div class="sort-filter">
        <div class="dropdown">

            <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary m-2">Home</a>
            <a href="{{ route('agent.application-request') }}" class="btn btn-secondary m-2">Application Requests</a>
        </div>

    </div>
    <form method="GET" action="">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-1">
                    <strong>Filters:</strong>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label for="dateFrom">Date From:</label>
                        <input type="date" class="form-control" id="dateFrom" name="dateFrom"
                               value="{{ request('dateFrom') }}">
                    </div>
                    <div class="form-group">
                        <label for="dateTo">Date To:</label>
                        <input type="date" class="form-control" id="dateTo" name="dateTo"
                               value="{{ request('dateTo') }}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="" selected>All</option>
                            <option value="-1" {{ request('status') == -1 ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="0" {{ request('status') === 0 ? 'selected' : '' }}>Initiated
                            </option>
                            <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Completed</option>
                            @if (!empty($statuses))
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ request('status') === $status->id ? 'selected' : '' }}>
                                        {{ $status->status_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Service Type:</label>
                        <select class="form-control" id="status" name="price_type">
                            <option value="" selected>All</option>
                            <option value="default" {{ request('price_type') === "default" ? 'selected' : '' }}>Default
                            </option>
                            <option value="tatkal" {{ request('price_type') === "tatkal" ? 'selected' : '' }}>Tatkal
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="applicantName">Applicant Name:</label>
                        <input type="text" class="form-control" id="applicantName" name="applicantName"
                               value="{{ request('applicantName') }}">
                    </div>
                    <div class="form-group">
                        <label for="services">Services:</label>
                        <select class="form-control" id="services" name="services">
                            <option value="" selected>All</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ request('services') == $service->id ? 'selected' : '' }}>{{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="applicantNumber">Mobile Number:</label>
                        <input type="number" class="form-control" id="applicantNumber" name="applicantNumber"
                               value="{{ request('applicantNumber') }}">
                    </div>
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
                </div>
            </div>
        </div>
    </form>

    <table class="table table-striped mt-4">
        <thead>
        <tr class="table-dark text-center">
            <th scope="col">Sr.No</th>
            <th scope="col">Applicant Name</th>
            <th scope="col">Date of Application</th>
            <th scope="col">Estimated Delivery Date</th>
            <th scope="col">Status</th>
            <th scope="col">Applied For</th>
            <th scope="col">Preview</th>
            <th scope="col">Ruppes(&#8377;)</th>
            <th scope="col">Receipt</th>
        </tr>
        </thead>
        <tbody>

        @php
            $counter = 1;
        @endphp
        @foreach ($applications as $application)
            <tr class="text-center">
                <th scope="row">{{ $counter++ }}</th>
                <td>{{ $application->customer_name }}</td>
                <td>{{ $application->apply_date }}</td>
                <td>
                    {{ $application->delivery_date ? $application->delivery_date : 'not yet determined' }}
                </td>

                <td>
                    @if ($application->status == -1)
                        <span class="text-danger font-weight-bold">Rejected</span><br>
                        <span class="text-secondary" style="text-decoration: underline; cursor: pointer;"
                              data-reason="{{ $application->reason }}" data-toggle="modal"
                              data-target="#reasonModal{{ $application->id }}">Reason</span>

                    @elseif ($application->status == 2)
                        <span class="text-success font-weight-bold">Completed</span>
                    @elseif ($application->status == 0)
                        <span class="text-info font-weight-bold">Initiated</span>
                    @elseif ($application->status == 1)
                        <span class="text-warning font-weight-bold">In Progress</span>
                    @else
                        @php
                            $statusesArray = explode(',', $application->statuses);
                            foreach ($statusesArray as $status) {
                                [$id, $statusName, $statusColor, $askReason] = explode(':', $status);
                                if ($application->status == $id) {
                                    echo '<span class="font-weight-bold" style="color: ' .
                                        $statusColor .
                                        '">' .
                                        ucfirst($statusName) .
                                        '</span>';
                                    if ($askReason) {
                                        echo '
                        <br/><span class="text-secondary" style="text-decoration: underline; cursor: pointer;"
                             data-toggle="modal"
                            data-target="#reasonModal'.$application->id.'">Reason</span>';
                                    }
                                    break;
                                }
                            }
                        @endphp
                    @endif
                    @if ($application->delivery)
                        <br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="flexSwitchCheckDefault"
                                   data-route='{{ route('application.update-doc-approval-status', ['id' => $application->id]) }}'
                                   onclick='updateApprovalStatus(this)'
                            @if ($application->is_doc_approved)
                                @checked(true)
                                @endif>
                            <label class="form-check-label" for="flexSwitchCheckDefault"> Document: <a
                                    href="{{ asset($application->delivery) }}" target="_blank"
                                    style="color: blue;">View Document</a></label>
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
                <td class="text-success"> &#8377;{{ $application->price }}</td>
                <td>
                    @if ($application->receipt)
                        <a href="{{ asset($application->receipt) }}" target="_blank"
                           style="color: blue;">View Receipt</a>
                    @else
                        Not Available
                    @endif
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
                                            <p class="text-danger">
                                                <b>Requested Again with
                                                    Message: </b>{{ $formData['formData'][$key]['message'] }}
                                            </p>
                                            <p>
                                            <form method="POST"
                                                  action="{{ route('document.re-submit-doc', ['id' => $application->id, 'key' => $key]) }}"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" class="form-control" id="document" name="document"
                                                       placeholder="Upload Document" required>
                                                <button type="submit" class="btn btn-sm btn-warning m-1"
                                                >ReSubmit
                                                </button>
                                            </form>
                                            </p>
                                        @else
                                            <p class="text-success">Re Submitted Successfully</p>
                                        @endif

                                    @endif
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <div class="modal-body">{{ $application->reason }}</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Close
                                </button>
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
    <script>
        function updateApprovalStatus(checkbox) {
            var route = checkbox.getAttribute('data-route');
            var isChecked = checkbox.checked;


            var csrfToken = '{{ csrf_token() }}';

            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    isApproved: isChecked
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                })
                .catch(error => {
                    alert('Error updating approval status');
                });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
