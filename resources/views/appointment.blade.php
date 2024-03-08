<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Appointment Booking</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" />
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .tile {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tile:hover {
            transform: scale(1.05);
        }

        .selected-tile {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        #datepicker {
            width: 100%;
            font-size: 1.25rem;
        }

        .time-slot {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .time-slot:hover {
            background-color: #f8f9fa;
        }

        .selected-slot {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <!-- Left Menu - Current Step -->
                <div class="mb-3">
                    <h5>Current Step</h5>
                    <ul class="list-group" id="step-menu">
                        <li class="list-group-item active" data-step="1">
                            <i class="fas fa-check mr-2"></i> Services
                        </li>
                        <li class="list-group-item" data-step="2">
                            <i class="far fa-calendar-alt"></i> Date & Time
                        </li>
                        <li class="list-group-item" data-step="3">
                            <i class="fas fa-user"></i> Basic Details
                        </li>
                        <li class="list-group-item" data-step="4">
                            <i class="fas fa-clipboard-check"></i> Summary
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Main Container - Content Changes with Steps -->
                <div id="step-1" class="step-content">
                    <!-- Step 1: Select Tiles -->
                    <h3 class="mb-2">Select Category</h3>
                    <!-- Service Group Tabs -->
                    <ul class="nav nav-tabs" id="serviceGroupTabs" role="tablist">
                        @foreach ($serviceGroups as $index => $serviceGroup)
                            <li class="nav-item">
                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab{{ $serviceGroup->id }}"
                                    data-toggle="tab" href="#content{{ $serviceGroup->id }}" role="tab"
                                    aria-controls="content{{ $serviceGroup->id }}"
                                    aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                    {{ $serviceGroup->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Main Container Content -->
                    <div class="tab-content" id="serviceGroupContent">
                        @foreach ($serviceGroups as $index => $serviceGroup)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                id="content{{ $serviceGroup->id }}" role="tabpanel"
                                aria-labelledby="tab{{ $serviceGroup->id }}">
                                <h4 class="my-2">Select Service</h4>
                                <div class="row">
                                    @foreach ($services as $service)
                                        @if ($service->service_group_id == $serviceGroup->id)
                                            <div class="col-md-4">
                                                <div class="tile text-center p-4 mb-4 bg-light border rounded"
                                                    data-id="{{ $service->id }}">
                                                    <h4>{{ $service->name }}</h4>
                                                    <p>Duration: 1 hr <br> Price: <strong
                                                            class="text-success">₹<span class="price">{{$service->appointment_price}}</span></strong></p>

                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>

                <div id="step-2" class="step-content d-none">
                    <!-- Step 2: Date & Time Selection -->
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-center">Date</h3>
                                <!-- Datepicker -->
                                <div class="form-group">
                                    <input type="text" class="form-control" id="datepicker"
                                        placeholder="Click to select Date" readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="text-center">Time Slots</h3>
                                <!-- Time Slots -->
                                <div class="row" id="time-slots">
                                    Select Date to see available time slots.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="step-3" class="step-content d-none">
                    <!-- Step 3: Basic Details Form -->
                    <h2 class="mb-4">Basic Details</h2>
                    <form id="basic-details-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" required />
                            <div id="nameError" class="invalid-feedback">Name must be at least 3 characters long.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required />
                            <div id="emailError" class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" required />
                            <div id="phoneError" class="invalid-feedback">Phone number must be 10 digits only.</div>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select name="city" id="city" class="form-control" required>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->district . ' (' . $location->state . ')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Complete Address</label>
                            <textarea class="form-control" id="address" rows="3" required></textarea>
                            <div id="addressError" class="invalid-feedback">Address must be at least 10 characters
                                long.</div>
                        </div>
                        <button type="button" id="submit-btn" class="btn btn-primary" disabled>Next:
                            Summary</button>
                    </form>



                </div>

                <div id="step-4" class="step-content d-none">
                    <!-- Step 4: Summary -->
                    <h2 class="mb-4">Summary</h2>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading"><i class="fas fa-check-circle"></i> Confirm Your Appointment!</h4>
                        <div id="summary" class="summary-container">
                            <!-- Summary details will go here -->
                        </div>
                        <hr>
                        <button class="btn btn-success m-3" id="confirm-appointment" data-route="{{ route('appointments.store') }}">Confirm Appointment</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{asset('js/sweetAlert.js')}}"></script>
    <script src="{{ asset('js/appointment.js') }}"></script>
</body>

</html>
