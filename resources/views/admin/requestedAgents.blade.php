<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Agent Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body {
            height: 100%;
            width: 100%;
            background: linear-gradient(to right, #e9dfc4 0%, #e9dfc4 1%, #ede3c8 2%, #ede3c8 24%, #ebddc3 25%, #e9dfc4 48%, #ebddc3 49%, #e6d8bd 52%, #e6d8bd 53%, #e9dbc0 54%, #e6d8bd 55%, #e6d8bd 56%, #e9dbc0 57%, #e6d8bd 58%, #e6d8bd 73%, #e9dbc0 74%, #e9dbc0 98%, #ebddc3 100%);
            background-size: 120px;
            background-repeat: repeat;
            padding: 1rem 1rem;
        }

        .registered-agent-list-page {
            padding: 1rem 1rem;
            height: 100%;
            width: 100%;
        }

        .heading {
            /* width: 100%; */
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

        .earned-points {
            color: green;
            font-family: sans-serif;
            font-weight: bolder;
            font-size: 15px;
            text-align: center;
            padding: 0.3rem 0.5rem;
        }

        .pending-points {
            /* color: green; */
            font-family: sans-serif;
            font-weight: bolder;
            font-size: 15px;
            text-align: center;
            padding: 0.3rem 0.5rem;
        }

        .home-icon {
            margin-bottom: 0.5rem;
        }

        a {
            text-decoration: none;
        }

        .dropdowns {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }

        .sort-filter {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row;
            padding: 0rem 2rem;
        }

        .total-count {
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="registered-agent-list-page">
        <div class="heading">
            <div class="dashboard-content">
                <span class="material-icons home-icon"> home </span>
                <h3 class="dashboard">Agents Request</h3>
            </div>


        </div>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-2">Home</a>
    <div class="sort-filter">
        <div class="dropdowns">
            <div class="dropdown">
                <label><strong>Sort By Date:</strong></label>
                <div class="input-group">
                    <input onchange="applyFilter(this)" type="date" class="form-control" id="exampleInputPhone"
                        placeholder="Enter Paid Amount"
                        @if (request()->has('date')) value="{{ request('date') }}" @endif />
                </div>
            </div>
        </div>
    </div>
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
    <table class="table table-striped mt-4">
        <thead>
            <tr class="table-dark text-center">
                <th scope="col">Sr.No</th>
                <th scope="col">Agent Name</th>
                <th scope="col">Date of Registration</th>
                <th scope="col">Preview Details</th>
                <th scope="col">Payment Status</th>
                <th scope="col">Amount Paid (in &#8377;) </th>
                <th scope="col">Unpaid Amount (in &#8377;) </th>
                <th scope="col"> Plan</th>
                <th scope="col">Payment Method</th>
                <th scope="col">Set Status</th>
            </tr>
        </thead>
        @php $counter = 1; @endphp @foreach ($agents as $agent)
            <tr class="text-center">
                <th scope="row">{{ $counter++ }}</th>
                <td>{{ $agent->full_name }}</td>
                <td>{{ $agent->reg_date }}</td>
                <td>
                    <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                        data-target="#agentModal{{ $agent->id }}">
                        preview
                    </span>
                </td>
                <form action="{{ route('agents.approve') }}" method="post">
                    @csrf
                    <td>
                        <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                        <select class="form-select" id="inputGroupSelect03" name="Payment_status" required>
                            <option value="Pending">Pending</option>
                            <option value="Success">Success</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control" id="exampleInputPhone"
                            placeholder="Enter Paid Amount" name="paid_amount" required /></td>
                    <td><input name = "unpaid_amount" type="number" class="form-control" id="exampleInputPhone"
                            placeholder="Enter UnPaid Amount" required /></td>
                    <td>
                        <select class="form-select" name="plan_id" required>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><select class="form-select" id="inputGroupSelect03" name="payment_mode" required>
                            <option value="Net Banking">Net Banking</option>
                            <option value="UPI">UPI</option>
                            <option value="Cash">Cash</option>
                            <option value="NA">NA</option>
                        </select></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <button type="submit" class="btn btn-success">Activate</button>
                </form>

                <form action="{{ route('agents.hold') }}" method="post" id="holdForm">
                    @csrf
                    <button type="button" class="btn btn-warning" onclick="promptForReason()">Hold</button>
                    <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                </form>

                <form action="{{ route('agents.reject') }}" method="post">
                    @csrf
                    <input type="hidden" name="agent_id" value="{{ $agent->id }}"
                        onsubmit="return confirm('Are you sure you want to reject this agent?')">
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
                </div>
                </td>

                <!-- Modal -->
                <div class="modal fade" id="agentModal{{ $agent->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="agentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="agentModalLabel">
                                    Agent Details - {{ $agent->full_name }}
                                </h5>
                            </div>
                            <div class="modal-body">
                                <strong>Full Name:</strong> {{ $agent->full_name }}<br />
                                <strong>Mobile Number:</strong> {{ $agent->mobile_number }}<br />
                                <strong>Email:</strong> {{ $agent->email }}<br />
                                <strong>Address:</strong> {{ $agent->address }}<br />
                                <strong>Shop Name:</strong> {{ $agent->shop_name }}<br />
                                <strong>Shop Address:</strong> {{ $agent->shop_address }}<br />
                                <strong>Username:</strong> {{ $agent->username }}<br />
                                <strong>Password:</strong> {{ $agent->password }}<br />
                                <strong>Location:</strong>
                                {{ $agent->district . ' (' . $agent->state . ')' }}<br />
                                <strong>Payment Status:</strong> {{ $agent->payment_status }}<br />
                                <strong>Registration Date:</strong> {{ $agent->reg_date }}<br />
                                @if ($agent->aadhar_card)
                                    <strong>Aadhar Card:</strong>
                                    <a href="{{ asset($agent->aadhar_card) }}" target="_blank">View Aadhar
                                        Card</a><br />
                                    @endif @if ($agent->shop_license)
                                        <strong>Shop License:</strong>
                                        <a href="{{ asset($agent->shop_license) }}" target="_blank">View Shop
                                            License</a><br />
                                        @endif @if ($agent->owner_photo)
                                            <strong>Owner Photo:</strong>
                                            <a href="{{ asset($agent->owner_photo) }}" target="_blank">View Owner
                                                Photo</a><br />
                                            @endif @if ($agent->supporting_document)
                                                <strong>Supporting Document:</strong>
                                                <a href="{{ asset($agent->supporting_document) }}"
                                                    target="_blank">View Supporting Document</a><br />
                                            @endif
                            </div>
                            <div class="modal-footer">
                                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        {{ $agents->links('pagination::bootstrap-5') }}
    </div>

    <h2 class="my-2">Agents on Hold</h2>
    <table class="table table-striped mt-4">
        <thead>
            <tr class="table-dark text-center">
                <th scope="col">Sr.No</th>
                <th scope="col">Agent Name</th>
                <th scope="col">Date of Registration</th>
                <th scope="col">Preview Details</th>
                <th scope="col">Hold Reason</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        @php $counter = 1; @endphp @foreach ($holds as $hold)
            <tr class="text-center">
                <th scope="row">{{ $counter++ }}</th>
                <td>{{ $hold->full_name }}</td>
                <td>{{ $hold->reg_date }}</td>
                <td>
                    <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                        data-target="#agentModal{{ $hold->id }}">
                        preview
                    </span>
                </td>

                <td>{{ $hold->reason }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <form action="{{ route('agents.unhold') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-warning">UnHold</button>
                            <input type="hidden" name="agent_id" value="{{ $hold->agent_id }}">
                        </form>
                        <form action="{{ route('agents.reject') }}" method="post">
                            @csrf
                            <input type="hidden" name="agent_id" value="{{ $hold->agent_id }}"
                                onsubmit="return confirm('Are you sure you want to reject this agent?')">
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </div>
                </td>
                <!-- Modal -->
                <div class="modal fade" id="agentModal{{ $hold->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="agentModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="agentModalLabel">
                                    Agent Details - {{ $hold->full_name }}
                                </h5>
                            </div>
                            <div class="modal-body">
                                <strong>Full Name:</strong> {{ $hold->full_name }}<br />
                                <strong>Mobile Number:</strong> {{ $hold->mobile_number }}<br />
                                <strong>Email:</strong> {{ $hold->email }}<br />
                                <strong>Address:</strong> {{ $hold->address }}<br />
                                <strong>Shop Name:</strong> {{ $hold->shop_name }}<br />
                                <strong>Shop Address:</strong> {{ $hold->shop_address }}<br />
                                <strong>Username:</strong> {{ $hold->username }}<br />
                                <strong>Password:</strong> {{ $hold->password }}<br />
                                <strong>Location:</strong>
                                {{ $hold->district . ' (' . $hold->state . ')' }}<br />
                                <strong>Registration Date:</strong> {{ $hold->reg_date }}<br />
                                @if ($hold->aadhar_card)
                                    <strong>Aadhar Card:</strong>
                                    <a href="{{ asset($hold->aadhar_card) }}" target="_blank">View Aadhar
                                        Card</a><br />
                                    @endif @if ($hold->shop_license)
                                        <strong>Shop License:</strong>
                                        <a href="{{ asset($hold->shop_license) }}" target="_blank">View Shop
                                            License</a><br />
                                        @endif @if ($hold->owner_photo)
                                            <strong>Owner Photo:</strong>
                                            <a href="{{ asset($hold->owner_photo) }}" target="_blank">View Owner
                                                Photo</a><br />
                                            @endif @if ($hold->supporting_document)
                                                <strong>Supporting Document:</strong>
                                                <a href="{{ asset($hold->supporting_document) }}"
                                                    target="_blank">View Supporting Document</a><br />
                                            @endif
                            </div>
                            <div class="modal-footer">
                                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        {{ $holds->links('pagination::bootstrap-5') }}
    </div>
    <script>
        function promptForReason() {
            // Display a prompt for entering the reason
            var reason = prompt("Enter the reason for putting the agent on hold:");

            // Check if the user entered a reason
            if (reason !== null) {
                // Set the reason value in a hidden input field
                var hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "reason";
                hiddenInput.value = reason;
                document.getElementById("holdForm").appendChild(hiddenInput);

                // Submit the form
                document.getElementById("holdForm").submit();
            }
        }

        function applyFilter(e) {

            var date = e.value;
            var url = '{{ route('admin.requested-agents') }}';
            var queryString = `?date=${encodeURIComponent(date)}`;
            var fullUrl = url + queryString;
            // Redirect to the constructed URL
            window.location.href = fullUrl;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
