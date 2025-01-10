<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurations Management</title>
    <!-- Bootstrap CSS (Optional for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Configurations</h1>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">Update Configuration</h5>
                <div class="mb-3">
                    <label for="photo_making_charge" class="form-label">Photo Charge</label>
                    <input type="number" class="form-control" id="photo_making_charge" value="{{ $photoMakingCharge }}"
                        step="0.01" />
                </div>
                <button class="btn btn-primary" id="updateChargeBtn">Update</button>
                <div id="successMessage" class="alert alert-success mt-3 d-none">
                    Configuration updated successfully!
                </div>
                <div id="errorMessage" class="alert alert-danger mt-3 d-none">
                    Something went wrong. Please try again.
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Optional for styling and interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript for AJAX Request -->
    <script>
        document.getElementById('updateChargeBtn').addEventListener('click', function() {
            const value = document.getElementById('photo_making_charge').value;

            fetch('{{ route('admin.configurations.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        key: 'photo_making_charge',
                        value: value,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const successMessage = document.getElementById('successMessage');
                        const errorMessage = document.getElementById('errorMessage');
                        successMessage.classList.remove('d-none');
                        errorMessage.classList.add('d-none');
                        setTimeout(() => successMessage.classList.add('d-none'), 3000);
                    } else {
                        throw new Error();
                    }
                })
                .catch(() => {
                    const successMessage = document.getElementById('successMessage');
                    const errorMessage = document.getElementById('errorMessage');
                    successMessage.classList.add('d-none');
                    errorMessage.classList.remove('d-none');
                });
        });
    </script>
</body>

</html>
