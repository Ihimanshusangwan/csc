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
        <a href="{{route('services.index')}}" class="btn btn-secondary m-3">Back</a>
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
                            <input type="number" class="form-control" id="appointmentPrice" placeholder="Appointment Price" aria-label="Appointment Price" aria-describedby="basic-addon1" value="{{ isset($appointmentPrice) ? $appointmentPrice : '' }}" name="appointment_price"> 

                          </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary ">Update</button>
                    </div>
                    

                </div>
                
              </div>
             
            </form>
          </div>
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
                @foreach($locations as $key => $location)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $location->district }}</td>
                    <td>{{ $location->state }}</td>
                    <td>
                        @if($location->location_id === null)
                        <!-- No corresponding entry in prices -->
                        <button type="button" class="btn btn-success" onclick="previewPrices({{ json_encode($location) }})">Add Prices</button>
                        @else
                        <!-- Corresponding entry in prices exists -->
                        <button type="button" class="btn btn-primary"
                            onclick="previewPrices({{ json_encode($location) }})">
                            Manage Prices
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- The Modal -->
        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Manage Prices</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Content will be dynamically added here -->
                        <div id="modalContent" class="container">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function previewPrices(location) {
            const modalContentContainer = document.getElementById("modalContent");
            let formType ="";
            if(location.id === null){
                formType =`<form id="pricesForm" method = "post" action="{{ route('prices.store', ['serviceId' => $serviceId]) }}" >
                @csrf
                                <input type="hidden" name="location_id" value="${location.location_id_1}">
                                `;
            }else{
                formType =`<form id="pricesForm" method = "post" action="{{ route('prices.update') }}">
                @csrf
                                <input type="hidden" name="price_id" value="${location.price_id}">`;
            } 

            // Create HTML content for the modal with a form
            let modalContent = `<div class="container">
                                ${formType}
                                <h2 class="mb-4">Prices for ${location.district}, ${location.state}</h2>
                                
                                <h3>Prices without Subscription</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Default Prices</h3>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Govt Price:</strong> <input type="number" name="default_govt_price"  class="form-control" value="${location.default_govt_price || ''}"></li>
                                            <li class="list-group-item"><strong>Commission Price:</strong> <input type="number" name="default_commission_price"  class="form-control" value="${location.default_commission_price || ''}"></li>
                                            <li class="list-group-item"><strong>Tax Percentage:</strong> <input type="number" name="default_tax_percentage" class="form-control" value="${location.default_tax_percentage || ''}"></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>Tatkal Prices</h3>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Govt Price:</strong> <input type="number" name="tatkal_govt_price" class="form-control" value="${location.tatkal_govt_price || ''}"></li>
                                            <li class="list-group-item"><strong>Commission Price:</strong> <input type="number" name="tatkal_commission_price" class="form-control" value="${location.tatkal_commission_price || ''}"></li>
                                            <li class="list-group-item"><strong>Tax Percentage:</strong> <input type="number" name="tatkal_tax_percentage" class="form-control" value="${location.tatkal_tax_percentage || ''}"></li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <h3>Prices with Subscription</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Default Prices</h3>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Govt Price:</strong> <input type="number" name="subscribed_default_govt_price"  class="form-control" value="${location.subscribed_default_govt_price || ''}"></li>
                                            <li class="list-group-item"><strong>Commission Price:</strong> <input type="number" name="subscribed_default_commission_price"  class="form-control" value="${location.subscribed_default_commission_price || ''}"></li>
                                            <li class="list-group-item"><strong>Tax Percentage:</strong> <input type="number" name="subscribed_default_tax_percentage" class="form-control" value="${location.subscribed_default_tax_percentage || ''}"></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>Tatkal Prices</h3>
                                        <ul class="list-group">
                                            <li class="list-group-item"><strong>Govt Price:</strong> <input type="number" name="subscribed_tatkal_govt_price" class="form-control" value="${location.subscribed_tatkal_govt_price || ''}"></li>
                                            <li class="list-group-item"><strong>Commission Price:</strong> <input type="number" name="subscribed_tatkal_commission_price" class="form-control" value="${location.subscribed_tatkal_commission_price || ''}"></li>
                                            <li class="list-group-item"><strong>Tax Percentage:</strong> <input type="number" name="subscribed_tatkal_tax_percentage" class="form-control" value="${location.subscribed_tatkal_tax_percentage || ''}"></li>
                                        </ul>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3" >Save</button>
                            </form>
                        </div>`;

            // Set the content inside the modal
            modalContentContainer.innerHTML = modalContent;

            // Show the modal with additional styling
            $("#myModal").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        }
    </script>

</body>

</html>