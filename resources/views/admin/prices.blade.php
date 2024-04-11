<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prices for Districts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>

<body>

    <div class="container">
        <h2>Prices for Districts</h2>
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
        <a href="{{ route('services.index') }}" class="btn btn-secondary m-3">Back</a>
        <div class="container my-2">
            <form action="{{ route('services.update-appointment-price', ['serviceId' => $serviceId]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="appointmentPrice"><strong>Appointment Price:</strong></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number" class="form-control" id="appointmentPrice"
                                    placeholder="Appointment Price" aria-label="Appointment Price"
                                    aria-describedby="basic-addon1"
                                    value="{{ isset($appointmentPrice) ? $appointmentPrice : '' }}"
                                    name="appointment_price">

                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary ">Update</button>
                        </div>


                    </div>

                </div>

            </form>
        </div>
        
        <h3>Prices with Subscription</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>District</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locations as $key => $location)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $location->district }}</td>
                        <td>{{ $location->state }}</td>
                        <td>
                            <a href="{{ route('prices.plan-based-prices', ['serviceId' => $serviceId, 'locationId' => $location->id]) }}"
                                class="btn btn-primary">Available Plans</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</body>

</html>
