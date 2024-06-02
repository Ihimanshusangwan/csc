<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Applicattions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
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
    </style>

    <link rel="stylesheet" href="{{ asset('css/adminDashboard.css') }}">

  <script src="{{asset('js/sweetAlert.js')}}"></script>
</head>

<body>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000 // Close the alert after 3 seconds
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
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
            
        </div>

        <h3 class="mt-4 text-center">All Applications</h3>
        <div class="sort-filter">
            <div class="dropdown">
                <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary m-2">Home</a>
            </div>
        </div>

        <table class="table table-striped mt-4">
            <thead>
                <tr class="table-dark text-center">
                    <th scope="col">Sr.No</th>
                    <th scope="col">Applicant Name</th>
                    <th scope="col">Date of Application</th>
                    <th scope="col">Applied For</th>
                    <th scope="col">Preview</th>
                    <th scope="col">Price type</th>
                    <th scope="col">Reason(if rejecting)</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $counter = 1;
                @endphp
                @foreach ($applications as $application)

                <form action="{{route('agent.update-application')}}" method="POST">
                    <tr class="text-center">
                        <th scope="row">{{ $counter++ }}</th>
                        <td>{{ $application->customer_name }}</td>
                        <td>{{ $application->apply_date }}</td>
            
                        <td>{{ $application->service_name }}</td>
                        <td>
                            <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                                data-target="#agentModal{{ $application->id }}">
                                preview
                            </span>
                        </td>
                            @csrf
                            <input type="hidden" name="id" value="{{$application->id}}">
                        <td>
                           <select name="price_type" class="form-control" >
                            <option value="default">Default</option>
                            <option value="tatkal">Tatkal</option>
                           </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="reason">
                        </td>
                        <td>
                            <button class="btn btn-success mx-1" type="submit">Approve</button>
                            <button class="btn btn-danger" type="submit">Reject</button>
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
                        </div>
                    </tr>

                </form>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $applications->links('pagination::bootstrap-5') }}
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
