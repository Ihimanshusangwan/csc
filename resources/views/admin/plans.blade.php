<!-- resources/views/admin/plans/index.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2>Plans</h2>

        <a href="{{route('admin.dashboard')}}" class="btn btn-secondary m-2">Home</a>
        <!-- Add Plan Form in Modal -->
        <button type="button" class="btn btn-primary m-2" data-toggle="modal" data-target="#addPlanModal">
            Add Plan
        </button>

        <!-- Plans Display Section -->
        @forelse ($groupedPlans as $planId => $groupedPlan)
        <div class="card mt-3">
            <div class="card-header">
                <h4>{{ $groupedPlan->first()->plan_name }}</h4>
                <p>Price: ${{ $groupedPlan->first()->price }}</p>
                <p>Duration: {{ $groupedPlan->first()->duration }} days</p>
                <!-- Delete button -->
                <form action="{{ route('plans.destroy', $groupedPlan->first()->plan_id) }}" method="post"
                    class="float-right">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            <div class="card-body">
                <h5>Services:</h5>
                <ul>
                    @foreach ($groupedPlan as $plan)
                    <li>{{ $plan->service_name }} ({{ $plan->service_group_name }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @empty
        <p>No plans available.</p>
        @endforelse

    </div>

    <!-- Add Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1" role="dialog" aria-labelledby="addPlanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPlanModalLabel">Add Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add Plan Form -->
                    <form action="{{ route('plans.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Plan Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration (in days):</label>
                            <input type="number" class="form-control" id="duration" name="duration" required>
                        </div>

                        <!-- Accordions for Service Groups and Services -->
                        @foreach ($serviceGroups as $serviceGroup)
                        <div class="accordion" id="serviceGroup{{ $serviceGroup->id }}">
                            <div class="card">
                                <div class="card-header" id="heading{{ $serviceGroup->id }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapse{{ $serviceGroup->id }}" aria-expanded="true"
                                            aria-controls="collapse{{ $serviceGroup->id }}">
                                            {{ $serviceGroup->name }}
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse{{ $serviceGroup->id }}" class="collapse"
                                    aria-labelledby="heading{{ $serviceGroup->id }}"
                                    data-parent="#serviceGroup{{ $serviceGroup->id }}">
                                    <div class="card-body">
                                        @foreach ($services as $service)
                                        @if ($service->service_group_id == $serviceGroup->id)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="selected_services[]"
                                                value="{{ $service->id }}">
                                            <label class="form-check-label">
                                                {{ $service->name }}
                                            </label>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Input for selected services only -->
                        <input type="hidden" name="selected_services_only" id="selectedServicesOnly" value="">

                        <button type="submit" class="btn btn-primary mt-3">Save Plan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to update selected services only
        function updateSelectedServicesOnly() {
            const selectedServicesOnly = [];

            // Loop through services and check if the service is selected
            @foreach($services as $service)
            if ($('[name="selected_services[]"][value="{{ $service->id }}"]:checked').length > 0) {
                selectedServicesOnly.push("{{ $service->id }}");
            }
            @endforeach

            // Update the value of the hidden input
            $('#selectedServicesOnly').val(selectedServicesOnly.join(','));
        }

        // Attach the function to checkbox change event
        $('[name="selected_services[]"]').on('change', function () {
            updateSelectedServicesOnly();
        });

        // Trigger the function on page load
        $(document).ready(function () {
            updateSelectedServicesOnly();
        });
    </script>

</body>

</html>