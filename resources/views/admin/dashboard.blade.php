<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

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
                    <a class="nav-link" href="{{ route('admin.requested-agents')}}">Agents Request</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.registered-agents')}}">Registered Agents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.registered-staff')}}">Registered Staffs</a>
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
                    <a class="nav-link" href="{{ route('admin.delete-form') }}">Clear Database</a>
                </li>
            </ul>
        </div>
    
        <!-- Logout Button -->
        <div class="navbar-nav">
            <a href="{{ route('admin.logout') }}" class="nav-link text-danger">Logout</a>
        </div>
    </nav>
    
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if($errors->has('error'))
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
            </div>
            <div class="background-points">
                <div class="points">Total Earnings: &#8377;{{$sumOfPrices}}</div>
            </div>
        </div>

        <div class="row">
        <div class="col-3">
          <div class="total-registration background-total-registration">
            <div class="align">
              <div class="registration-text">Total Applications</div>
              <div class="count">{{$totalApplicationCount}}</div>
            </div>
            <div class="material-icons chevron_right">chevron_right</div>
          </div>
        </div>
        <div class="col-3">
          <div class="total-registration todays-registration">
            <div class="align">
              <div class="registration-text">Today's Applications</div>
              <div class="count">{{$countOfTodaysApplications}}</div>
            </div>
            <div class="material-icons chevron_right">chevron_right</div>
          </div>
        </div>
        <div class="col-3">
          <div class="total-registration background-process-completed">
            <div class="align">
              <div class="registration-text">Completed Applications</div>
              <div class="count">{{$completedApplicationsCount}}</div>
            </div>
            <div class="material-icons chevron_right">chevron_right</div>
          </div>
        </div>
        <div class="col-3">
          <div class="total-registration background-pending">
            <div class="align">
              <div class="registration-text">Pending Applications</div>
              <div class="count"> {{$pendingApplicationsCount}}</div>
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
                <strong class="text-primary">Total : {{$totalApplicationCount}} </strong>
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
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>


      @php   $counter = 1; @endphp @foreach($applications as $application)
      <tr class="text-center">
        <th scope="row">{{ $counter++ }}</th>
        <td>{{ $application->customer_name}}</td>
        <td>{{ $application->agent_name}}</td>
        <td>{{ $application->apply_date}}</td><td>
            <form action="{{route('application.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="application_id" value="{{$application->id}}"/>
        <input type="date" class="form-control-sm" id="delivery_date" name="delivery_date" value="{{ $application->delivery_date ? $application->delivery_date : '' }}">
        </td>

        <td>
            <select class="form-control-sm" id="status" name="status">
                <option value="0" {{ $application->status == 0 ? 'selected' : '' }} class="text-info">Initiated</option>
                <option value="1" {{ $application->status == 1 ? 'selected' : '' }} class="text-warning">In Progress</option>
                <option value="2" {{ $application->status == 2 ? 'selected' : '' }} class="text-success">Completed</option>
                
                {{-- Explode statuses and create options --}}
                @php
                    $statusesArray = explode(',', $application->statuses);
                @endphp
                @foreach($statusesArray as $status)
                    @php
                        [$id, $statusName] = explode(':', $status);
                    @endphp
                    <option value="{{ $id }}" {{ $application->status == $id ? 'selected' : '' }}>{{ $statusName }}</option>
                @endforeach
            </select>
        </td>
        <td>{{ $application->service_name}}</td>
        <td>
          <span style="cursor: pointer" class="material-icons" data-toggle="modal"
                            data-target="#agentModal{{ $application->id }}">
            preview
          </span>
        </td>
        <td>{{$application->staffName}}</td>
        <td class="text-success"> &#8377;{{ $application->price}}</td>
        <td>
        @if($application->delivery_date)
    <!-- If delivery date exists, display a link to open the document in another tab -->
    <div class="form-group">
        Uploaded Document:<a href="{{ asset($application->delivery) }}" target="_blank"  style="color: blue;">View Document</a>
    </div>
@endif
 <div class="form-group">
        <input type="file" class="form-control-sm" id="document" name="document" placeholder="upload Document">
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
          $i=1;
        @endphp
         @foreach($formData as $category => $fields)
          @if(strtolower($category) !== "service_id" && strtolower($category) !== 'filepaths')
            @foreach($fields as $key => $value)
            @php if($i == 1) {
           $i++;
           continue;}
           @endphp
              @if(!empty($value))
                @if(is_array($value))
                  <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong> {{ implode(', ', $value) }}</p>
                @else
                  <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong> {{ $value }}</p>
                @endif
              @endif
            @endforeach
          @endif
        @endforeach

        @foreach($formData['filePaths'] as $key => $value)
          <p><strong>{{ ucfirst(str_replace('-', ' ', $key)) }}:</strong>  <a href="{{ asset($value) }}" target="_blank" style="color: blue;">View {{ $key }}</a></p>
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
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="{{asset('js/adminDashboard.js')}}"></script>
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
        <form method="post" action="{{route('agents.store')}}" enctype="multipart/form-data">
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
                                    aria-describedby="nameHelp" placeholder="Enter Full Name" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputPhone"><strong>Mobile No:</strong></label>
                                <input type="number" class="form-control" name="mobile_number"
                                    placeholder="Enter Mobile Number" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail"><strong>Email:</strong></label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email id" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputAddress"><strong>Address:</strong></label>
                                <input type="text" class="form-control" name="address" placeholder="Enter Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="shopName"><strong>Shop Name:</strong></label>
                                <input type="text" class="form-control" name="shop_name"
                                    placeholder="Enter Shop Name" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputShopAddress"><strong>Shop Address:</strong></label>
                                <input type="text" class="form-control" name="Shop_address"
                                    placeholder="Enter Shop Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadadhar"><strong>Upload Aadhar Card:</strong></label>
                                <input type="file" class="form-control" name="upload_aadhar"
                                    placeholder="Enter Shop Name" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadShopLicense"><strong>Upload Shop License:</strong></label>
                                <input type="file" class="form-control" name="upload_shop_license"
                                    placeholder="Enter Shop Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadOwnerPhoto"><strong>Upload Owner Photo:</strong></label>
                                <input type="file" class="form-control" name="upload_owner_photo"
                                    placeholder="Enter Shop Name" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uploadSupportingDocument"><strong>Upload Supporting
                                        Document:</strong></label>
                                <input type="file" class="form-control" name="uploadSupportingDocument"
                                    placeholder="Enter Shop Address" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                                <div class="dropdown">
                                    <label><strong>Select Location: </strong></label>
                                    <div class="input-group">
                                        <!-- Dropdown for Locations -->
                                        <select name="location_id" class="form-control">
                                            @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->district . ' (' .
                                                $location->state . ')' }}</option>

                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputUsername"><strong>Username:</strong></label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Username" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputPassword"><strong>Password:</strong></label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Enter Password" />
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
                                            @foreach($plans as $plan)
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
                                        placeholder="Enter Paid Amount" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleUnpaidAmount"><strong>Unpaid Amount(&#8377;):</strong></label>
                                    <input type="number" class="form-control" name="unpaid_amount"
                                        placeholder="Enter Unpaid Amount" />
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