<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bootstrap demo</title>
  <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <script src="{{asset('js/sweetAlert.js')}}"></script>
  <style>
    body {
      height: 100%;
      width: 100%;
      background: linear-gradient(to right,
          #e9dfc4 0%,
          #e9dfc4 1%,
          #ede3c8 2%,
          #ede3c8 24%,
          #ebddc3 25%,
          #e9dfc4 48%,
          #ebddc3 49%,
          #e6d8bd 52%,
          #e6d8bd 53%,
          #e9dbc0 54%,
          #e6d8bd 55%,
          #e6d8bd 56%,
          #e9dbc0 57%,
          #e6d8bd 58%,
          #e6d8bd 73%,
          #e9dbc0 74%,
          #e9dbc0 98%,
          #ebddc3 100%);
      background-size: 120px;
      background-repeat: repeat;
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
@if(session('success'))
    <script>
        // Display SweetAlert success message
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000  // Close the alert after 2 seconds
        });
    </script>
@endif


  <div class="registered-agent-list-page">
    <div class="heading">
      <div class="dashboard-content">
        <span class="material-icons home-icon"> home </span>
        <h3 class="dashboard">Registered Agent Dashboard</h3>
      </div>
      <div class="background-points">
        <div class="earned-points">
          Total Subscription Earnings: &#8377;{{$earnings}}
        </div>
      </div>
      <div class="background-points">
        <div class="pending-points text-danger">
          Total Pending: &#8377;{{$pending}}
        </div>
      </div>
    </div>
  </div>
  <a href="{{route('admin.dashboard')}}" class="btn btn-secondary m-2">Home</a>
  <div class="sort-filter">
    <div class="dropdowns">
        <div class="dropdown" style="margin-left: 1rem">
            <label><strong>Sort By Purchased Plan: </strong></label>
            <div class="input-group">
                <select class="form-select" name="plan_name" id="plan_name">
                    <option value="">All</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->name }}" {{ request('plan_name') == $plan->name ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="dropdown" style="margin-left: 1rem">
            <label><strong>Sort By Status: </strong></label>
            <div class="input-group">
                <select class="form-select" name="status" id="status">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expiring" {{ request('status') == 'expiring' ? 'selected' : '' }}>Expiring</option>
                </select>
            </div>
        </div>
    </div>
    <!-- Add a button to trigger the form submission -->
    <button type="button" class="btn btn-primary" onclick="submitForm()">Apply Filters</button>
</div>
  <table class="table table-striped mt-4">
    <thead>
      <tr class="table-dark text-center">
        <th scope="col">Sr.No</th>
        <th scope="col">Agent Name</th>
        <th scope="col">Date of Registration</th>
        <th scope="col">Username</th>
        <th scope="col">Password</th>
        <th scope="col">Preview Details</th>
        <th scope="col">Date of Purchase</th>
        <th scope="col">Purchased Plan</th>
        <th scope="col">Payment Method</th>
        <th scope="col">Paid Amount(&#8377;)</th>
        <th scope="col">UnPaid Amount(&#8377;)</th>
        <th scope="col">Balance(&#8377;)</th>
        <th scope="col">Details</th>
        <th scope="col">Recharge</th>
      </tr>
    </thead>
    <tbody>
      @php $counter = 1; @endphp @foreach($agents as $agent)
      <tr class="text-center">
        <th scope="row">{{ $counter++ }}</th>
        <td>{{ $agent->full_name}}</td>
        <td>{{ $agent->reg_date}}</td>
        <td>{{ $agent->username}}</td>
        <td>{{ $agent->password}}</td>
        <td>
          <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                            data-target="#agentModal{{ $agent->id }}">
            preview
          </span>
        </td>
        <td>{{ $agent->purchase_date}}</td>
        <td>
  {{ $agent->plan_name }}
  @php
    $expirationDate = strtotime($agent->expiration_date);
    $fifteenDaysFromToday = strtotime('+15 days');
    $today = time();

    if ($expirationDate <= $today) {
        // Plan is expired
        $status = '<span class="text-danger"><strong>(Expired)</strong></span>';
    } elseif ($expirationDate <= $fifteenDaysFromToday) {
        // Plan is expiring in 15 days or less
        $daysLeft = floor(($expirationDate - $today) / (24 * 60 * 60));
        $status = '<span class="text-danger"><strong>(Expiring in ' . $daysLeft . ' days)</strong></span>';
    } else {
        // Plan is active
        $status = '<span class="text-success"><strong>(Active)</strong></span>';
    }

    // Your code to display $status
    echo $status;
@endphp

</td>
        <td>{{ $agent->payment_mode}}</td>
        <td class="text-success"> &#8377;{{ $agent->paid_amount}}</td>
        <td class="text-danger"> &#8377;{{ $agent->unpaid_amount}}</td>
        <td class="text-success"> &#8377;{{ $agent->balance}}</td>
        <td><a href="{{ route('agent.show', ['id' => $agent->id]) }}" class="btn btn-info">View</a></td>
        <td>
          <button style="cursor: pointer" class="btn btn-success" data-toggle="modal"
                            data-target="#agentRechargeModal{{ $agent->id }}">
            Recharge
          </button>
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
                <strong>Plan:</strong> {{ $agent->plan_name }}<br />
                <strong>Location:</strong> {{ $agent->district . ' (' .
                $agent->state . ')' }}<br />
                <strong>Payment Status:</strong> {{ $agent->payment_status
                }}<br />
                <strong>Payment Mode:</strong> {{ $agent->payment_mode }}<br />
                <strong>Paid Amount (&#8377;):</strong> {{ $agent->paid_amount
                }}<br />
                <strong>Unpaid Amount (&#8377;):</strong> {{
                $agent->unpaid_amount }}<br />
                <strong>Balance Amount (&#8377;):</strong> {{
                $agent->balance }}<br />
                <strong>Registration Date:</strong> {{ $agent->reg_date }}<br />
                <strong>Purchase Date:</strong> {{ $agent->purchase_date }}<br />
                <strong>Plan Expiration Date:</strong> {{ $agent->expiration_date }}<br />
                @if($agent->aadhar_card)
                <strong>Aadhar Card:</strong>
                <a href="{{ asset($agent->aadhar_card) }}" target="_blank">View Aadhar Card</a><br />
                @endif @if($agent->shop_license)
                <strong>Shop License:</strong>
                <a href="{{ asset($agent->shop_license) }}" target="_blank">View Shop License</a><br />
                @endif @if($agent->owner_photo)
                <strong>Owner Photo:</strong>
                <a href="{{ asset($agent->owner_photo) }}" target="_blank">View Owner Photo</a><br />
                @endif @if($agent->supporting_document)
                <strong>Supporting Document:</strong>
                <a href="{{ asset($agent->supporting_document) }}" target="_blank">View Supporting Document</a><br />
                @endif
              </div>
              <div class="modal-footer">
              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
              </div>
            </div>
          </div>
        </div>

        <!-- recharge modal -->
                <div class="modal fade" id="agentRechargeModal{{ $agent->id }}" tabindex="-1" role="dialog"
          aria-labelledby="agentModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="agentModalLabel">
                {{$agent->full_name }} Account Balance - {{$agent->balance }}
                </h5>
              </div>
              <div class="modal-body">
    <form action="{{ route('agent.recharge', ['id' => $agent->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="recharge_amount">Recharge Amount:</label>
            <input type="number" class="form-control" id="recharge_amount" name="recharge_amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Recharge</button>
    </form>
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
        {{ $agents->links('pagination::bootstrap-5') }}   
  </div>  
 <script>
  function submitForm() {
        // Get selected values
        var planName = document.getElementById('plan_name').value;
        var status = document.getElementById('status').value;
        // Construct the URL with parameters
        var url = '{{route('admin.registered-agents')}}'; 
        var queryString = `?plan_name=${encodeURIComponent(planName)}&status=${encodeURIComponent(status)}`;
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