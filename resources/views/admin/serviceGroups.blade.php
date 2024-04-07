<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Groups</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">
</head>

<body>
    <div class="container">
        <h2>Service Groups</h2>
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-3">Home</a>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary my-3" data-toggle="modal" data-target="#addServiceGroupModal">
            Add New Service Group
        </button>

        <!-- Display existing service groups as cards -->
        <div class="row">
            @foreach ($groupedServiceGroups as $groupId => $group)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <!-- Display service group details -->
                        <img src="{{ asset($group[0]->photo) }}" class="card-img-top" alt="{{ $group[0]->name }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $group[0]->name }}</h5>
                            <h6>Services</h6>
                            <!-- List of Services -->
                            @foreach ($group as $service)
                                <li class="list-group-item">{{ $service->service_name }}</li>
                            @endforeach

                            <a href="{{ route('service-groups.edit', $group[0]->id) }}" class="btn btn-warning mt-3">Edit</a>
                            <div class="form-group mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switch{{ $group[0]->id }}1"
                                           data-route="{{ route('service-groups.update-visibility', $group[0]->id) }}"
                                           data-grpId="{{ $group[0]->id }}" 
                                           {{ $group[0]->visibility ? 'checked' : '' }}
                                           onchange="updateVisibility(this)">
                                    <label class="custom-control-label" for="switch{{ $group[0]->id }}1">Visibility</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="switch{{ $group[0]->id }}2"
                                           data-route="{{ route('service-groups.update-availability', $group[0]->id) }}"
                                           data-grpId="{{ $group[0]->id }}" 
                                           {{ $group[0]->availability ? 'checked' : '' }}
                                           onchange="updateAvailability(this)">
                                    <label class="custom-control-label" for="switch{{ $group[0]->id }}2">Make Available to all </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal for adding a new service group -->
        <div class="modal" id="addServiceGroupModal" tabindex="-1" role="dialog"
            aria-labelledby="addServiceGroupModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceGroupModalLabel">Add New Service Group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to add a new service group -->
                        <form action="{{ route('service-groups.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="groupName">Service Group Name</label>
                                <input type="text" class="form-control" id="groupName" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="groupPhoto">Service Group Photo </label>
                                <input type="file" class="form-control" id="groupPhoto" name="photo" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Service Group</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    
    <script src="{{ asset('js/sweetAlert.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
    <script>
        function updateVisibility(e) {
            const api = e.dataset.route;
            const grpId = e.dataset.grpId;
            const visibility = e.checked ? 1 : 0;

            fetch(api, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        visibility: visibility
                    })
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    // Show success alert using Swal
                    Swal.fire({
                        icon: 'success',
                        title: 'Visibility Updated',
                        text: 'Visibility has been updated successfully!'
                    });
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    // Show error alert using Swal
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update visibility.'
                    });
                });
        }
        function updateAvailability(e) {
            const api = e.dataset.route;
            const grpId = e.dataset.grpId;
            const availability = e.checked ? 1 : 0;

            fetch(api, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        availability: availability
                    })
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    // Show success alert using Swal
                    Swal.fire({
                        icon: 'success',
                        title: 'Availability Updated',
                        text: 'Availability has been updated successfully!'
                    });
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    // Show error alert using Swal
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update availability.'
                    });
                });
        }
    </script>
</body>

</html>
