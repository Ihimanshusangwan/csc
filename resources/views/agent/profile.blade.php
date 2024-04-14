<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    
    <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary m-2">Home</a>
    <div class="row mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    Agent Profile
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Full Name:</strong> {{ $agentData->full_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Mobile Number:</strong> {{ $agentData->mobile_number }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $agentData->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong> {{ $agentData->address }}
                    </div>
                    <div class="mb-3">
                        <strong>Shop Name:</strong> {{ $agentData->shop_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Username:</strong> {{ $agentData->username }}
                    </div>
                    <div class="mb-3">
                        <strong>Password:</strong> {{ $agentData->password }}
                    </div>
                    <div class="mb-3">
                        <strong>Balance:</strong> {{ $agentData->balance }}
                    </div>
                    <div class="mb-3">
                        <strong>Registration Date:</strong> {{ $agentData->reg_date }}
                    </div>
                    <!-- Displaying Documents -->
                    <div class="mb-3">
                        <strong>Documents:</strong><br>
                        @if($agentData->aadhar_card)
                            <a href="{{ asset($agentData->aadhar_card) }}" target="_blank">Aadhar Card</a><br>
                        @endif
                        @if($agentData->shop_license)
                            <a href="{{ asset($agentData->shop_license) }}" target="_blank">Shop License</a><br>
                        @endif
                        @if($agentData->owner_photo)
                            <a href="{{ asset($agentData->owner_photo) }}" target="_blank">Owner Photo</a><br>
                        @endif
                        @if($agentData->supporting_document)
                            <a href="{{ asset($agentData->supporting_document) }}" target="_blank">Supporting Document</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
