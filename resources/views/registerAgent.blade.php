<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Agent</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="{{ asset('js/sweetAlert.js') }}"></script>
</head>

<body>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000 // Close the alert after 3 seconds
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000 // Close the alert after 3 seconds
            });
        </script>
    @endif

    <div class="container my-5">
        <a href="/" class="btn btn-secondary m-2">Home</a>
        <form method="post" action="{{ route('agents.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title fs-5">Register Agent</h1>
                </div>
                <div class="card-body">
                    <input type="hidden" name="type" value="Request">
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Name" class="form-label"><strong>Full Name:</strong></label>
                                <input required type="text" name="full_name" class="form-control" id="Name"
                                    placeholder="Enter Full Name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputPhone" class="form-label"><strong>Mobile No:</strong></label>
                                <input required type="number" class="form-control" name="mobile_number"
                                    placeholder="Enter Mobile Number" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputEmail" class="form-label"><strong>Email:</strong></label>
                                <input required type="email" class="form-control" name="email"
                                    placeholder="Enter Email id" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputAddress" class="form-label"><strong>Address:</strong></label>
                                <input required type="text" class="form-control" name="address"
                                    placeholder="Enter Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shopName" class="form-label"><strong>Shop Name:</strong></label>
                                <input required type="text" class="form-control" name="shop_name"
                                    placeholder="Enter Shop Name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputShopAddress" class="form-label"><strong>Shop
                                        Address:</strong></label>
                                <input required type="text" class="form-control" name="Shop_address"
                                    placeholder="Enter Shop Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="uploadadhar" class="form-label"><strong>Upload Aadhar Card:</strong></label>
                                <input required type="file" class="form-control" name="upload_aadhar"
                                    placeholder="Enter Shop Name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="uploadShopLicense" class="form-label"><strong>Upload Shop
                                        License:</strong></label>
                                <input required type="file" class="form-control" name="upload_shop_license"
                                    placeholder="Enter Shop Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="uploadOwnerPhoto" class="form-label"><strong>Upload Owner
                                        Photo:</strong></label>
                                <input required type="file" class="form-control" name="upload_owner_photo"
                                    placeholder="Enter Shop Name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="uploadSupportingDocument" class="form-label"><strong>Upload Supporting
                                        Document:</strong></label>
                                <input required type="file" class="form-control" name="uploadSupportingDocument"
                                    placeholder="Enter Shop Address" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Select Location: </strong></label>
                                <select name="location_id" class="form-control">
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">
                                            {{ $location->district . ' (' . $location->state . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputUsername"
                                    class="form-label"><strong>Username:</strong></label>
                                <input required type="text" class="form-control" name="username"
                                    placeholder="Enter Username" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputPassword"
                                    class="form-label"><strong>Password:</strong></label>
                                <input required type="password" class="form-control" name="password"
                                    placeholder="Enter Password" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
