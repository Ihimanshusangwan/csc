<!-- resources/views/locations/index.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Districts and States</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Districts and States</h2>
        <a href="{{route('admin.dashboard')}}" class="btn btn-secondary m-3">Home</a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addLocationModal">Add Location</button>
         <!-- Success Message -->
         @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>District</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $key => $location)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $location->district }}</td>
                    <td>{{ $location->state }}</td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal"
                            data-target="#editLocationModal-{{ $location->id }}">Edit</button>

                    </td>
                </tr>

                <!-- Edit Location Modal -->
                <div class="modal fade" id="editLocationModal-{{ $location->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editLocationModalLabel-{{ $location->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editLocationModalLabel-{{ $location->id }}">Edit
                                    Location</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('locations.update', $location->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="editDistrict-{{ $location->id }}">District:</label>
                                        <input type="text" class="form-control" id="editDistrict-{{ $location->id }}"
                                            name="district" value="{{ $location->district }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editState-{{ $location->id }}">State:</label>
                                        <input type="text" class="form-control" id="editState-{{ $location->id }}"
                                            name="state" value="{{ $location->state }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Location</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Location Modal -->
    <div class="modal fade" id="addLocationModal" tabindex="-1" role="dialog" aria-labelledby="addLocationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLocationModalLabel">Add Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="addDistrict">District:</label>
                            <input type="text" class="form-control" id="addDistrict" name="district" required>
                        </div>
                        <div class="form-group">
                            <label for="addState">State:</label>
                            <input type="text" class="form-control" id="addState" name="state" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Location</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
