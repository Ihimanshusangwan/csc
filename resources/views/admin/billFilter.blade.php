<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body {
            height: 100%;
            width: 100%;
            padding: 1rem 1rem;
            background: linear-gradient(to right, #e9dfc4 0%, #e9dfc4 1%, #ede3c8 2%, #ede3c8 24%, #ebddc3 25%, #e9dfc4 48%, #ebddc3 49%, #e6d8bd 52%, #e6d8bd 53%, #e9dbc0 54%, #e6d8bd 55%, #e6d8bd 56%, #e9dbc0 57%, #e6d8bd 58%, #e6d8bd 73%, #e9dbc0 74%, #e9dbc0 98%, #ebddc3 100%);
            background-size: 120px;
            background-repeat: repeat;
        }

        .agent-data-page {
            height: 100%;
            width: 100%;
            padding: 1rem 1rem;
        }

        .heading {
            /* width: 100%; */
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row;
        }

        .sort-filter {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row;
        }

        .total-count {
            margin-top: 2rem;
        }

        .dashboard-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
        }

        .background-points {
            background-color: white;
            /* width: 3rem; */
            border-radius: 2rem;
            margin-right: 3rem;
            cursor: pointer;
        }

        .points {
            color: green;
            font-family: sans-serif;
            font-weight: bolder;
            font-size: 15px;
            text-align: center;
            padding: 0.3rem 0.5rem;
        }

        .dashboard {
            margin-left: 0.3rem;
        }

        .home-icon {
            margin-bottom: 0.5rem;
        }

        .chevron_right {
            color: white;
            font-size: 50px;
        }

        .background-total-registration {
            background-color: red;
        }

        .background-process-completed {
            background-color: #355E3B;
        }

        .background-pending {
            background-color: darkcyan;
        }

        .todays-registration {
            background-color: darkcyan;
        }

        .background-pending {
            background-color: maroon;
        }

        .align {
            width: 90%;
        }

        .total-registration {
            border-radius: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-direction: row;
            width: 95%;
        }

        .registration-text {
            font-weight: bolder;
            color: white;
            font-size: 20px;
            padding: 1rem 0rem 1rem 1rem;
            font-family: sans-serif;
        }

        .count {
            font-weight: bolder;
            color: white;
            font-size: 18px;
            padding: 0rem 1rem 1rem 1rem;
            font-family: sans-serif;

        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-2">Home</a>
        <button class="btn btn-warning m-2" onclick="window.print()"> Print</button>
        <div class="container mt-5">
            <h2>Filter Options</h2>
            <form method="GET" action="">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateFrom">Date From:</label>
                            <input type="date" class="form-control" id="dateFrom" name="dateFrom"
                                value="{{ request('dateFrom') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateTo">Date To:</label>
                            <input type="date" class="form-control" id="dateTo" name="dateTo"
                                value="{{ request('dateTo') }}">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="applicantName">Customer Name:</label>
                            <input type="text" class="form-control" id="customerName" name="customerName"
                                value="{{ request('customerName') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="applicantName">Customer Number:</label>
                            <input type="text" class="form-control" id="customerNumber" name="customerNumber"
                                value="{{ request('customerNumber') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="applicantName">Bill Description:</label>
                            <input type="text" class="form-control" id="desc" name="desc"
                                value="{{ request('desc') }}">
                        </div>
                    </div>

                </div>


                <button type="submit" class="btn btn-primary m-2">Filter</button>
            </form>
        </div>
    </div>
    <div class="heading">
        <div class="dashboard-content">

        </div>
        <div class="background-points">
            <div class="points">Total Earnings: &#8377;{{ $sumOfPrices }}</div>
        </div>
        <div class="background-points">
            <div class="points">Total Commission: &#8377;{{ $sumOfCommission }}</div>
        </div>
        <div class="background-points">
            <div class="points">Total Tax : &#8377;{{ $sumOfTax }}</div>
        </div>
    </div>
    </div>

    <h4>Bills</h4>
    <table class="table table-striped mt-4">
        <thead>
            <tr class="table-dark text-center">
                <th scope="col">Sr.No</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Customer Number</th>
                <th scope="col">Description</th>
                <th scope="col">Date</th>
                <th scope="col" class="no-print">Preview items</th>
                <th scope="col">Ruppes(&#8377;)</th>
                <th scope="col">Commission(&#8377;)</th>
                <th scope="col">Tax(&#8377;)</th>
            </tr>
        </thead>
        <tbody>

            @php   $counter = 1; @endphp @foreach ($bills as $bill)
                <tr class="text-center ">
                    <th scope="row">{{ $counter++ }}</th>
                    <td>{{ $bill->customer_name }}</td>
                    <td>{{ $bill->customer_number }}</td>
                    <td>{{ $bill->description }}</td>
                    <td>{{ $bill->created_at }}</td>
                    <td class="no-print">
                        <span style="cursor: pointer" class="material-icons" onclick="fetchItems('{{ route('admin.bill-item-fetch',['billId' => $bill->id]) }}')">
                            preview
                        </span>
                    </td>
                    <td class="text-success"> &#8377;{{ $bill->grand_total }}</td>
                    <td class="text-success"> &#8377;{{ $bill->grand_net_commission }}</td>
                    <td class="text-success"> &#8377;{{ $bill->net_tax }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Bill Items</h5>
                </div>
                <div class="modal-body" id="modal-body">
                    <!-- Items will be displayed here -->
                </div>
            </div>
        </div>
    </div>
    <script>
        function fetchItems(route) {
            // Send AJAX request to fetch items for the given billId
            fetch(route)
                .then(response => response.json())
                .then(data => {
                    // Display the fetched items in a modal
                    displayItemsModal(data);
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                });
        }
    
        function displayItemsModal(items) {
            // Construct modal content with the fetched items
            let modalBody = document.querySelector('#modal-body');
            modalBody.innerHTML = ''; // Clear previous content
    
            if (items.length > 0) {
    let table = document.createElement('table');
    table.classList.add('table');
    let thead = document.createElement('thead');
    let tbody = document.createElement('tbody');

    // Table headers
    let headers = ['Item Name', 'Base Price', 'Commission', 'Tax', 'Quantity', 'Subtotal'];
    let headerRow = document.createElement('tr');
    headers.forEach(header => {
        let th = document.createElement('th');
        th.textContent = header;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);

    // Table body
    items.forEach(item => {
        let row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.item_name}</td>
            <td>${item.base_price}</td>
            <td>${item.commission}</td>
            <td>${item.tax}</td>
            <td>${item.quantity}</td>
            <td>${item.subtotal}</td>
        `;
        tbody.appendChild(row);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    modalBody.appendChild(table);

    // Show the modal
    $('#myModal').modal('show');
} else {
    // If no items are fetched, display a message
    modalBody.textContent = 'No items found for this bill.';
}

        }
    </script>
    

    <!-- Bootstrap JS and jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
