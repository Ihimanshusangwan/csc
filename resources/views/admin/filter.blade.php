<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Page</title>
    <!-- Bootstrap CSS --> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
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
        @media print{
            .no-print{
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-2">Home</a>
    <button class="btn btn-warning m-2" onclick="window.print()"> Print</button>
    <div class="container mt-5">
        <h2>Filter Options</h2>
        <form method="GET" action="">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dateFrom">Date From:</label>
                        <input type="date" class="form-control" id="dateFrom" name="dateFrom"
                            value="{{ request('dateFrom') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dateTo">Date To:</label>
                        <input type="date" class="form-control" id="dateTo" name="dateTo"
                            value="{{ request('dateTo') }}">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="agentName">Agent Name:</label>
                        <select class="form-control" id="agentName" name="agentName">
                            <option value="" selected>All</option>
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}"
                                    {{ request('agentName') == $agent->id ? 'selected' : '' }}>{{ $agent->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Agent Type:</label>
                        <select class="form-control" id="status" name="agent_type">
                            <option value="" selected >All</option>
                            <option value="0" {{ request('agent_type') === 0 ? 'selected' : '' }}>Unsubscribed
                            </option>
                            <option value="1" {{ request('agent_type') == 1 ? 'selected' : '' }}>Subscribed
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="applicantName">Applicant Name:</label>
                        <input type="text" class="form-control" id="applicantName" name="applicantName"
                            value="{{ request('applicantName') }}">
                    </div>
                </div>
                <div class="col-md-6">
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
            </div>


            <button type="submit" class="btn btn-primary m-2">Filter</button>
        </form>
    </div>
</div>
    <div class="heading">
        <div class="dashboard-content">

        </div>
        <div class="background-points">
            <div class="points">Total Earnings: &#8377;{{ $sumOfPrices }}</div>
        </div>
        <div class="background-points">
            <div class="points">Total Commission: &#8377;{{ $sumOfCommission }}</div>
        </div>
        <div class="background-points">
            <div class="points">Total Tax : &#8377;{{ $sumOfTax }}</div>
        </div>
        <div class="background-points">
            <div class="points">Total Govt. Price : &#8377;{{ $sumOfGovtPrice }}</div>
        </div>
    </div>
    </div>
    <h4 class="no-print">View Detailed Earnings</h4>
    <div class="accordion m-3 no-print" id="accordionExample">
        @foreach($structuredData as $index => $data)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                    {{ $data['service_group_name'] }}
                </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>Service</th>
                                <th>Total Price</th>
                                <th>Total Govt Price</th>
                                <th>Total Commission</th>
                                <th>Total Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['services'] as $serviceName => $serviceData)
                            <tr>
                                <td>{{ $serviceName }}</td>
                                <td>{{ $serviceData['total_price'] }}</td>
                                <td>{{ $serviceData['total_govt_price'] }}</td>
                                <td>{{ $serviceData['total_commission'] }}</td>
                                <td>{{ $serviceData['total_tax'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <strong>Total Price:</strong> {{ $data['total_price'] }}<br>
                        <strong>Total Govt Price:</strong> {{ $data['total_govt_price'] }}<br>
                        <strong>Total Commission:</strong> {{ $data['total_commission'] }}<br>
                        <strong>Total Tax:</strong> {{ $data['total_tax'] }}<br>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    
    
    <h4>Applications List</h4>
    <table class="table table-striped mt-4">
        <thead>
            <tr class="table-dark text-center">
                <th scope="col">Sr.No</th>
                <th scope="col">Applicant Name</th>
                <th scope="col">Agent Name</th>
                <th scope="col">Agent Type</th>
                <th scope="col">Date of Application</th>
                <th scope="col">Estimated Delivery Date</th>
                <th scope="col">Status</th>
                <th scope="col">Applied For</th>
                <th scope="col" class="no-print">Preview</th>
                <th scope="col">Type</th>
                <th scope="col">Ruppes(&#8377;)</th>
                <th scope="col">Commission(&#8377;)</th>
                <th scope="col">Govt. Price(&#8377;)</th>
                <th scope="col">Tax(&#8377;)</th>
            </tr>
        </thead>
        <tbody>

            @php   $counter = 1; @endphp @foreach ($applications as $application)
                <tr class="text-center " >
                    <th scope="row">{{ $counter++ }}</th>
                    <td>{{ $application->customer_name }}</td>
                    <td>{{$application->shop_name}} ( {{ $application->agent_name }} )</td>
                    <td>{{ $application->is_agent_subscribed ? "Subscribed" : "Unsubscribed" }}</td>
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
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($application->status == 2)
                            <span class="text-success font-weight-bold">Completed</span><br><span class="no-print">
                            Document: <a href="{{ asset($application->delivery) }}" target="_blank"
                                style="color: blue;">View Document</a> </span>
                        @elseif ($application->status == 0)
                            <span class="text-info font-weight-bold">Initiated</span>
                        @elseif ($application->status == 1)
                            <span class="text-warning font-weight-bold">In Progress</span>
                        @else
                            @php
                                $statusesArray = explode(',', $application->statuses);
                                foreach ($statusesArray as $status) {
                                    [$id, $statusName ,$statusColor] = explode(':', $status);
                                    if ($application->status == $id) {
                                        echo '<span class="font-weight-bold" style="color: ' . $statusColor . '">' . ucfirst($statusName) . '</span>';
                                        break;
                                    }
                                }
                            @endphp
                        @endif



                    </td>
                    <td>{{ $application->service_name }}</td>
                    <td class="no-print"> 
                        <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                            data-target="#agentModal{{ $application->id }}">
                            preview
                        </span>
                    </td>
                    
                    <td>{{ $application->price_type === "default" ? "Default" : "Tatkal" }}</td>
                    <td class="text-success"> &#8377;{{ $application->price }}</td>
                    <td class="text-success"> &#8377;{{ $application->commission }}</td>
                    <td class="text-success"> &#8377;{{ $application->govt_price }}</td>
                    <td class="text-success"> &#8377;{{ $application->tax }}</td>


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
                                    <p><strong>Mobile Number:</strong> {{$application->customer_mobile}}</p>
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
                                                href="{{ asset($value) }}" target="_blank">View
                                                {{ $key }}</a></p>
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
        {{-- {{ $applications->links('pagination::bootstrap-5') }} --}}
    </div>

    <!-- Bootstrap JS and jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
