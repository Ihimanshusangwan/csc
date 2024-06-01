<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #item-table th, #item-table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Billing System</h2>
        <form id="billing-form">
            <div class="form-group">
                <label for="customer-name">Customer Name:</label>
                <input type="text" class="form-control" id="customer-name" required>
            </div>
            <div class="form-group">
                <label for="customer-number">Customer Number:</label>
                <input type="text" class="form-control" id="customer-number" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" id="add-item">Add Item</button>
            </div>
            <table class="table table-bordered" id="item-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Base Price</th>
                        <th>Commission</th>
                        <th>Tax (%)</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Item rows will be added dynamically here -->
                </tbody>
            </table>
            <div class="form-group">
                <label for="grand-total">Grand Total:</label>
                <input type="text" class="form-control" id="grand-total" readonly>
            </div>
            <div class="form-group">
                <label for="grand-net-commission">Grand Net Commission:</label>
                <input type="text" class="form-control" id="grand-net-commission" readonly>
            </div>
            <div class="form-group">
                <label for="net-tax">Net Tax:</label>
                <input type="text" class="form-control" id="net-tax" readonly>
            </div>
            <button type="button" class="btn btn-success" id="generate-bill">Generate Bill</button>
        </form>
        <form id="bill-submit-form" method="post" action="{{route('admin.bill-submit')}}">
            @csrf
            <input type="hidden" name="form_data" id="bill-form-hidden-input">
        </form>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add item row
            document.getElementById("add-item").addEventListener("click", function() {
                var tbody = document.querySelector("#item-table tbody");
                var newRow = document.createElement("tr");
                newRow.innerHTML = `
                    <td><input type="text" class="form-control item-name" required></td>
                    <td><input type="number" class="form-control item-base-price" required></td>
                    <td><input type="number" class="form-control item-commission" required></td>
                    <td><input type="number" class="form-control item-tax" required></td>
                    <td><input type="number" class="form-control item-quantity" required></td>
                    <td><input type="text" class="form-control item-subtotal" readonly></td>`;
                tbody.appendChild(newRow);
            });

            // Calculate totals
            document.getElementById("billing-form").addEventListener("input", function(event) {
                if (event.target.matches(".item-base-price, .item-commission, .item-tax, .item-quantity")) {
                    var row = event.target.closest("tr");
                    var basePrice = parseFloat(row.querySelector(".item-base-price").value) || 0;
                    var commission = parseFloat(row.querySelector(".item-commission").value) || 0;
                    var taxPercentage = parseFloat(row.querySelector(".item-tax").value) || 0;
                    var quantity = parseFloat(row.querySelector(".item-quantity").value) || 0;

                    // Calculate tax amount based on base price + commission
                    var taxAmount = (basePrice + commission) * (taxPercentage / 100);

                    var subtotal = (basePrice + commission + taxAmount) * quantity;
                    row.querySelector(".item-subtotal").value = subtotal.toFixed(2);
                    calculateGrandTotals();
                }
            });

            // Calculate Grand Totals
            function calculateGrandTotals() {
                var grandTotal = 0;
                var grandNetCommission = 0;
                var netTax = 0;

                document.querySelectorAll("#item-table tbody tr").forEach(function(row) {
                    var basePrice = parseFloat(row.querySelector(".item-base-price").value) || 0;
                    var commission = parseFloat(row.querySelector(".item-commission").value) || 0;
                    var taxPercentage = parseFloat(row.querySelector(".item-tax").value) || 0;
                    var quantity = parseFloat(row.querySelector(".item-quantity").value) || 0;

                    // Calculate tax amount based on base price + commission
                    var taxAmount = (basePrice + commission) * (taxPercentage / 100);

                    var subtotal = (basePrice + commission + taxAmount) * quantity;
                    grandTotal += subtotal;
                    grandNetCommission += commission * quantity;
                    netTax += taxAmount * quantity;
                });

                document.getElementById("grand-total").value = grandTotal.toFixed(2);
                document.getElementById("grand-net-commission").value = grandNetCommission.toFixed(2);
                document.getElementById("net-tax").value = netTax.toFixed(2);
            }

            // Generate Bill
            document.getElementById("generate-bill").addEventListener("click", function() {
                var billData = {
                    "customerName": document.getElementById("customer-name").value,
                    "customerNumber": document.getElementById("customer-number").value,
                    "description": document.getElementById("description").value,
                    "grandTotal": document.getElementById("grand-total").value,
                    "grandNetCommission": document.getElementById("grand-net-commission").value,
                    "netTax": document.getElementById("net-tax").value,
                    "items": []
                };

                document.querySelectorAll("#item-table tbody tr").forEach(function(row) {
                    var item = {
                        "itemName": row.querySelector(".item-name").value,
                        "basePrice": parseFloat(row.querySelector(".item-base-price").value) || 0,
                        "commission": parseFloat(row.querySelector(".item-commission").value) || 0,
                        "tax": parseFloat(row.querySelector(".item-tax").value) || 0,
                        "quantity": parseFloat(row.querySelector(".item-quantity").value) || 0,
                        "subtotal": parseFloat(row.querySelector(".item-subtotal").value) || 0
                    };

                    billData.items.push(item);
                });

                // console.log(billData);
                document.getElementById('bill-form-hidden-input').value = JSON.stringify(billData);
                document.getElementById('bill-submit-form').submit();
            });
        });
    </script>
</body>
</html>
