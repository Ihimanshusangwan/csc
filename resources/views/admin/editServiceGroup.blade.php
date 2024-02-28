<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service Group</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Service Group</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <a href="{{route('service-groups.index')}}" class="btn btn-secondary m-3">Return</a>

    <form action="{{ route('service-groups.update', $serviceGroup->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="groupName">Service Group Name</label>
            <input type="text" class="form-control" id="groupName" name="name" value="{{ old('name', $serviceGroup->name) }}" required>
        </div>

        <div class="form-group">
            <label for="groupPhoto">Service Group Photo</label>
            <input type="file" class="form-control" id="groupPhoto" name="photo">
            @if($serviceGroup->photo)
                <img src="{{ asset($serviceGroup->photo) }}" alt="{{ $serviceGroup->name }}" class="img-thumbnail mt-2" style="max-width: 200px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Service Group</button>
    </form>
</div>

<!-- Bootstrap JS and Popper.js scripts (order matters) -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
