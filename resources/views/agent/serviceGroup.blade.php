<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Services</h1>
        <a href="{{route('agent.dashboard')}}" class="btn btn-secondary m-3">Home</a>
        <div class="row">

            @foreach($services as $service)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p><strong>Document Requirements:</strong></p>
                        <ol class="list-group list-group-numbered">
                            @php
                            $requirements = explode(',', $service->requirements);
                            @endphp

                            @foreach ($requirements as $requirement)
                            <li class="list-group-item">{{ trim($requirement) }}</li>
                            @endforeach
                        </ol>

                    </div>
                </div>
            </div>

            @endforeach

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


</body>

</html>