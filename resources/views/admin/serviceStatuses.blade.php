<!-- resources/views/statuss/index.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statuses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Service's Statuses</h2>
        <a href="{{route('services.index')}}" class="btn btn-secondary m-3">Back</a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addstatusModal">Add Status</button>
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
                <tr >
                    <th>Sr. No</th>
                    <th>Status</th>
                    <th>Color code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statuses as $key => $status)
                <tr style = "background-color: {{$status->color}};">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $status->status_name }}</td>
                    <td>{{ $status->color }}</td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal"
                            data-target="#editstatusModal-{{ $status->id }}">Edit</button>

                    </td>
                </tr>

                <!-- Edit status Modal -->
                <div class="modal fade" id="editstatusModal-{{ $status->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editstatusModalLabel-{{ $status->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editstatusModalLabel-{{ $status->id }}">Edit
                                    status</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('statuses.update', $status->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="editstatus_name-{{ $status->id }}">Status:</label>
                                        <input type="text" class="form-control" id="editstatus_name-{{ $status->id }}"
                                            name="status_name" value="{{ $status->status_name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editstatus_name-{{ $status->id }}">Color Hex code:</label>
                                        <input type="text" class="form-control" id="editstatus_name-{{ $status->id }}"
                                            name="color" value="{{ $status->color }}" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Update status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add status Modal -->
    <div class="modal fade" id="addstatusModal" tabindex="-1" role="dialog" aria-labelledby="addstatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addstatusModalLabel">Add status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('statuses.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" value="{{$service_id}}">
                        <div class="form-group">
                            <label for="addstatus_name">Status Name:</label>
                            <input type="text" class="form-control" id="addstatus_name" name="status_name" required>
                        </div>
                        <div class="form-group">
                            <label for="addstatus_name">Status Color Hex Code:</label>
                            <input type="text" class="form-control" id="addstatus_name" name="color" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add status</button>
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
