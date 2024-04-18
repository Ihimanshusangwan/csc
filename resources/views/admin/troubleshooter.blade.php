<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Issues</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-3">Home</a>
    <div class="container m-5">
        <div class="row">
            <div class="col-md-4">
                <h3>Form Data Not Available</h3>
                <ul class="list-group">             
                    @foreach($issues['withoutFormData'] as $issue)
                    <li class="list-group-item">
                        <strong>Service Name:</strong> {{ $issue->name }}<br>
                        <strong>Service Group Name:</strong> {{ $issue->service_group_name }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4">
                <h3>Prices without Subscription not Configured</h3>
                <ul class="list-group">
                    @foreach($issues['withoutLocationPrice'] as $issue)
                    <li class="list-group-item">
                        <strong>Service Name:</strong> {{ $issue['service_name'] }}<br>
                        <strong>Service Group Name:</strong> {{ $issue['service_group_name'] }}<br>
                        <strong>District:</strong> {{ $issue['district'] }}<br>
                        <strong>State:</strong> {{ $issue['state'] }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4">
                <h3> Subscription Prices not Configured</h3>
                <ul class="list-group">
                    @foreach($issues['withoutPlanPrice'] as $issue)
                    <li class="list-group-item">
                        <strong>Service Name:</strong> {{ $issue['service_name'] }}<br>
                        <strong>Service Group Name:</strong> {{ $issue['service_group_name'] }}<br>
                        <strong>District:</strong> {{ $issue['district'] }}<br>
                        <strong>State:</strong> {{ $issue['state'] }}<br>
                        <strong>Plan:</strong> {{ $issue['plan'] }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
