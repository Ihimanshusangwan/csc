<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Customer</title>
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
        <div class="btn-toolbar mb-2 mx-2">
            <a href="{{ route('customer.logout') }}" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="agent-data-page">
        <div class="heading">
            <div class="dashboard-content">
                <span class="material-icons home-icon"> home </span>
                <h3 class="dashboard">Customer Dashboard</h3>
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
        </div>
        <h3 class="mt-4 text-center">All Time Applications</h3>

        <table class="table table-striped mt-4">
            <thead>
                <tr class="table-dark text-center">
                    <th scope="col">Sr.No</th>
                    <th scope="col">Agent Name</th>
                    <th scope="col">Applied For</th>
                    <th scope="col">Date of Application</th>
                    <th scope="col">Status</th>
                    <th scope="col">Estimated Delivery Date</th>
                    <th scope="col">Ruppes(&#8377;)</th>
                </tr>
            </thead>    
            <tbody>


                @php   $counter = 1; @endphp @foreach ($applications as $application)
                    <tr class="text-center">
                        <th scope="row">{{ $counter++ }}</th>
                        <td>{{ $application->agent_name }}</td>
                        <td>{{ $application->service_name }}</td>
                        <td>{{ $application->apply_date }}</td>
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
                                <span class="text-success font-weight-bold">Completed</span><br>
                                @if($application->delivery){
                                    Document: <a href="{{ asset($application->delivery) }}" target="_blank"
                                        style="color: blue;">View Document</a>
                                }
                                @endif
                            @elseif ($application->status == 0)
                                <span class="text-info font-weight-bold">Initiated</span>
                            @elseif ($application->status == 1)
                                <span class="text-warning font-weight-bold">In Progress</span>
                            @else
                            @php
                            $statusesArray = explode(',', $application->statuses);
                            foreach ($statusesArray as $status) {
                                [$id, $statusName, $statusColor] = explode(':', $status);
                                if ($application->status == $id) {
                                    echo '<span class="font-weight-bold" style="color: ' . $statusColor . '">' . ucfirst($statusName) . '</span>';
                                    break;
                                }
                            }
                            @endphp
                            
                            @endif



                        </td>
                        <td>
                            {{ $application->delivery_date ? $application->delivery_date : 'not yet determined' }}
                        </td>
                        <td class="text-success"> &#8377;{{ $application->price }}</td>
                    </tr>                       
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $applications->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @if(session('reset_password'))
    <!-- Modal -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your reset password form here -->
                    <form id="resetPasswordForm" method="post" action="{{ route('customer.reset-password') }}">
                        @csrf
                        <!-- New Password -->
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <!-- Error Message -->
                        <div id="passwordError" class="text-danger"></div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary m-3" id="submitButton" disabled>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

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
    <script>
        // Function to prevent modal from closing
        function closeModal() {
            if (document.getElementById('resetPasswordForm')) {
                // Prevent closing if form exists
                return;
            }
            document.getElementById('resetPasswordModal').style.display = 'none';
        }
    
        document.addEventListener('DOMContentLoaded', function() {
            // Show modal when page is loaded
            if ('{{ session('reset_password') }}') {
                document.getElementById('resetPasswordModal').style.display = 'block';
            }
    
            // Live validation for password fields
            var newPasswordInput = document.getElementById('newPassword');
            var confirmPasswordInput = document.getElementById('confirmPassword');
            var passwordErrorDiv = document.getElementById('passwordError');
            var submitButton = document.getElementById('submitButton');
    
            newPasswordInput.addEventListener('input', validatePassword);
            confirmPasswordInput.addEventListener('input', validatePassword);
    
            function validatePassword() {
                var newPassword = newPasswordInput.value;
                var confirmPassword = confirmPasswordInput.value;
    
                if (newPassword !== confirmPassword) {
                    passwordErrorDiv.textContent = 'Passwords do not match';
                    submitButton.disabled = true;
                } else if (newPassword.length < 6) {
                    passwordErrorDiv.textContent = 'Password must be at least 6 characters long';
                    submitButton.disabled = true;
                } else {
                    passwordErrorDiv.textContent = '';
                    submitButton.disabled = false;
                }
            }
    
            // Submit the form when modal is closed
            document.getElementById('resetPasswordModal').addEventListener('click', function(event) {
                if (event.target === document.getElementById('resetPasswordModal')) {
                    closeModal();
                }
            });
        });
    </script>
    
    
</body>

</html>


