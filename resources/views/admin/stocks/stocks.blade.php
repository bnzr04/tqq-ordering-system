@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    .content {
        position: absolute;
        height: calc(100% - 46px);
    }

    #stock_container {
        height: 80vh;
        overflow-y: auto;
    }

    #stock_container table thead {
        position: sticky;
        top: 0;
    }

    #addStockModalLabel {
        letter-spacing: 3px;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0" style="height:100vh;position:relative">
        <div class="container-fluid m-0 p-0">
            <h4 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Stocks</h4>
        </div>
        <div class="container-fluid m-0 p-1 content">
            <div class="container-fluid m-0 p-1 d-flex">
                <div class="container-fluid m-0 p-0">
                    <button class="btn btn-dark" id="back_button">Back</button>
                </div>
                <div class="container-fluid m-0 p-0 d-flex">
                    <select name="" id="" class="form-select border-secondary mx-2">
                        <option value="">All</option>
                    </select>
                    <input type="text" name="" id="" class="form-control border-secondary" placeholder="Search item...">
                </div>
            </div>

            <div class="container-fluid m-0 p-0 border" id="stock_container">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Stock</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">ADD STOCK</h5>
                <button type="button" class="btn-close close_button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_body">
                <b>
                    <h5 id="item_info_modal_display"></h5>
                </b>
                <div class="container-fluid m-0 p-0">
                    <input type="hidden" id="item_id_input" class="form-control border-secondary">
                    <label for="current_stock_quantity_input">Current Stock Quantity</label>
                    <input type="number" id="current_stock_quantity_input" class="form-control border-secondary text-center" readonly>
                </div>
                <div class="container-fluid m-0 mt-3 p-0">
                    <label for="stock_quantity_input">Quantity</label>
                    <input type="number" min="1" id="stock_quantity_input" class="form-control border-secondary text-center">

                    <div class="m-0 mt-4 p-1">
                        <b class="m-0">Operation:</b>
                        <div class="mt-2">
                            <label for="add">Add to stock</label>
                            <input type="radio" name="operation" id="add" value="add" checked>
                        </div>
                        <div class="">
                            <label for="remove">Remove to stock</label>
                            <input type="radio" name="operation" id="remove" value="remove">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close_button" data-bs-dismiss="modal" id="close_button">Close</button>
                <button type="button" class="btn btn-dark" id="update_stock_modal_button">UPDATE STOCK</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#back_button").on("click", function() {
            // Redirect to the admin menu route when back button is clicked
            window.location.href = "{{ route('menu.admin') }}";
        });

        // Select the table body within the element with the ID "stock_container"
        const tableBody = $("#stock_container").find('table').children('tbody');

        // Function to fetch items and stocks using AJAX
        function fetchItemsAndStocks() {
            $.ajax({
                type: "get",
                url: "{{ route('fetch-items-and-stocks.admin') }}",
                success: function(data) {
                    // console.log(data);

                    if (data.length > 0) {
                        data.forEach(function(item) {
                            var row = $("<tr>");
                            var addButtonColumn = "<td><button class='add_stock_button' data-bs-toggle='modal' data-bs-target='#addStockModal' data-item-id='" + item.item_id + "' data-item-name='" + item.name + "' data-item-category='" + item.category + "' data-item-quantity='" + item.quantity + "'>UPDATE STOCK</button></td>";
                            tableBody.append(row);

                            if (item.quantity == null) {
                                item.quantity = 0;
                            }
                            row.append("<th>" + item.item_id + "</th><td>" + item.name + "</td><td>" + item.category + "</td><td>" + item.description + "</td><td>" + item.price + "</td><td>" + item.quantity + "</td>");
                            row.append(addButtonColumn);
                        });
                    } else {
                        tableBody.append("<tr><td colspan='7'>No item...</td></tr>");
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        fetchItemsAndStocks();

        // Click event handler for the "Add Stock" button in the modal
        tableBody.on("click", ".add_stock_button", function() {
            var itemId = $(this).data('item-id');
            var itemName = $(this).data('item-name');
            var itemCategory = $(this).data('item-category');
            var itemQuantity = $(this).data('item-quantity');

            if (itemQuantity == null) {
                itemQuantity = 0;
            }

            $("#item_id_input").val(itemId);
            $("#current_stock_quantity_input").val(itemQuantity);
            $('#item_info_modal_display').text(itemName + " / " + itemCategory);
        });

        // Click event handler for the modal's close button
        $(".close_button").on('click', function() {
            const allModalInput = $("#modal_body input");

            const addRadio = $("#add");
            const removeRadio = $("#remove");

            // Toggle the checked status of radio buttons
            if (removeRadio.prop('checked')) {
                addRadio.prop('checked', true);
                removeRadio.prop('checked', false);
            } else {
                addRadio.prop('checked', true);
                removeRadio.prop('checked', false);
            }

            // Clear values of all input elements in the modal
            allModalInput.each(function() {
                $(this).val("");
            });
        });

        // Click event handler for the "Update Stock" button in the modal
        $("#update_stock_modal_button").on('click', function() {
            var itemIdValue = $("#item_id_input").val();
            var quantityValue = $("#stock_quantity_input").val();
            const operationValue = $("input[name='operation']:checked").val();

            if (quantityValue !== "") {
                quantityValue = parseInt(quantityValue);

                // AJAX call to update item stock
                $.ajax({
                    type: "post",
                    url: "{{ route('add-item-stock.admin') }}",
                    data: {
                        item_id_value: itemIdValue,
                        quantity_value: quantityValue,
                        operation_value: operationValue,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        // Handle different success outcomes
                        if (data == true) {
                            window.location.reload();
                        } else if (data == false) {
                            alert('Oops! Failed to update stock!');
                            window.location.reload();
                        } else if (data == "no stock") {
                            alert('Oops! You cannot remove quantity because item has no stocks!');
                        } else {
                            alert('Oops! Current quantity is insufficient to deduct the quantity!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                alert("Please enter quantity!");
            }
        });

    });
</script>
@endsection