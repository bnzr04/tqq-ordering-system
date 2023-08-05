@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
        min-height: 100vh;
    }

    #content-container {
        display: grid;
        grid-template-areas:
            'menu menu menu orders '
            'menu menu menu orders '
            'menu menu menu control';
        gap: 10px;
        height: calc(100% - 46px);
        position: relative;
    }

    #menu-container {
        grid-area: menu;
        /* display: flex;
        flex-direction: ; */
    }

    #orders-container {
        grid-area: orders;
        height: 50vh;
    }

    #control-container {
        grid-area: control;
        height: 40vh;
    }

    #sub-menu {
        height: 100%;
        /* flex-wrap: wrap; */
        /* box-sizing: border-box; */
        position: relative;
        display: flex;
        flex-direction: column;
    }

    #menu_result {
        overflow-y: auto;
        height: 100%;
        min-height: 20%;
        max-height: 300px;
        position: relative;
        box-sizing: border-box;
    }

    .layout {
        display: grid;
        grid-template-rows: repeat(3, 1fr);
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        /* overflow-x: auto; */
    }

    .layout button {
        letter-spacing: 2px;
    }

    #menu_category_selector {
        min-height: 30%;
        max-height: 30%;
        overflow-y: auto;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0" style="position:relative;">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Make Order</h3>
        </div>

        <!-- Menu Container -->
        <div class="container-fluid m-0 p-1" id="content-container">
            <div class="container-fluid m-0 p-2 border shadow rounded-3 shadow" id="menu-container">
                <div class="container-fluid p-2 m-0 border border-secondary" id="sub-menu">
                    <div class="container-fluid m-0 p-0 mb-1">
                        <a href="{{ route('orders.admin') }}" class="btn btn-dark">Back</a>
                    </div>
                    <section class="layout border" id="menu_category_selector">
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <button class="rounded-1">&nbsp;</button>
                        <!-- <button class="rounded-1">Dessert</button>
                        <button class="rounded-1">Beverages</button>
                        <button class="rounded-1">Side Dishes</button> -->
                    </section>
                    <div class="container-fluid m-0 p-1 border">
                        <input type="text" name="" id="" placeholder="Search menu..." class="form-control">
                    </div>
                    <div class="container-fluid m-0 border m-0 p-0" id="menu_result">
                        <table class="table table-striped table-dark">
                            <thead id="menu_result_head" style="letter-spacing: 3px;">

                            </thead>
                            <tbody id="menu_result_table" class="menu_result_table">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Container -->
            <div class="container-fluid m-0 p-2 border shadow rounded-3" id="orders-container">
                <div class="container-fluid p-2 m-0 border border-secondary" style="min-width:450px;height:100%;
        overflow-y: auto;" id="orders-box">

                </div>
            </div>

            <!-- Control Container -->
            <div class="container-fluid m-0 p-2 border shadow rounded-3" id="control-container">
                <div class="container-fluid p-2 m-0 border" id="sub-menu">
                    <div class="container-fluid m-0 p-0" style="display: flex;">
                        <div class="container-fluid m-0 p-0">
                            <p class="m-0 p-0">Order ID: <span id="order-id" class="fw-bold"></span></p>
                            <p class="m-0 p-0">Table #: <input type="number" min="1" style="max-width:60px;border-radius:4px;" id="table-number-input"></p>
                        </div>
                        <div class="container-fluid m-0 p-0" id='radio-container'>
                            <div class="container-fluid m-0 p-0">
                                <label for="in">Dine In</label>
                                <input type="radio" name="order_type" id="in" value="dine-in" checked>
                                <label for="out">Take Out</label>
                                <input type="radio" name="order_type" id="out" value="take-out">
                            </div>
                            <div class="container-fluid m-0 p-0">
                                <label for="unpaid">Unpaid</label>
                                <input type="radio" name="payment_status" id="unpaid" value="unpaid" checked>
                                <label for="paid">Paid</label>
                                <input type="radio" name="payment_status" id="paid" value="paid">
                            </div>
                        </div>
                    </div>
                    <h4 class="m-0 p-0" style="letter-spacing: 3px;">Total:</h4>
                    <div class="container-fluid m-0 my-2 p-1 bg-white" style="letter-spacing: 2px;">P <b id="total_amount_input">0.00</b></div>
                    <button class="btn btn-dark m-1" id="make-order-btn">Make Order</button>
                    <button class="btn btn-dark m-1" id="new-order-btn" style="display: none;">Take New Order</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const radioContainer = $("#radio-container");
        const makeOrderBtn = $("#make-order-btn");
        const newOrderBtn = $("#new-order-btn");
        const orderId = $("#order-id");
        const tableNumberInput = $("#table-number-input");
        const ordersBox = $("#orders-box");

        const menuResultTable = $('#menu_result_table');
        const menuResultHead = $("#menu_result_head");

        var totalBill = 0;

        var orderIdValue = 0;

        function showNextOrderId() { //this function will show the next order id
            $.ajax({
                type: "GET",
                url: "{{ route('next-order-id.admin') }}",
                success: function(data) {
                    orderIdValue = data;

                    if (data) {
                        orderId.text(data);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        showNextOrderId();

        newOrderBtn.on('click', function() { //if the new-order-btn is clicked the order page will reload
            location.reload();
        });

        var isSubmitting = false; // Flag variable to track form submission

        //this will execute if the make-order-btn is clicked
        $("#make-order-btn").on("click", function() {
            var tableNumberValue = tableNumberInput.val(); //get the table number input value

            var total = $('#total_amount_input').text();

            // console.log(totalBill);

            console.log(orderedItems)

            if (tableNumberValue !== '') {
                if (orderedItems.length > 0) {
                    if (confirm('Do you want to send the order to kitchen?')) {
                        if (isSubmitting) {
                            return; // If form is already being submitted, ignore subsequent clicks
                        }

                        var orderType = $('input[name="order_type"]:checked').val();
                        var paymentStatus = $('input[name="payment_status"]:checked').val();
                        var tableNumber = $('#table-number-input').val();
                        // console.log(orderType);
                        // console.log(paymentStatus);

                        isSubmitting = true;

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('submit-order.admin') }}",
                            data: {
                                order_id: orderIdValue,
                                order_type: orderType,
                                payment_status: paymentStatus,
                                table_number: tableNumber,
                                order_items: orderedItems,
                                total_bill: totalBill,
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(data) {
                                console.log(data);
                                if (data.response == true) {
                                    orderId.text(data.orderId);
                                    radioContainer.find(":radio").prop("disabled", true);
                                    tableNumberInput.prop('disabled', true);
                                    makeOrderBtn.prop('disabled', true);
                                    makeOrderBtn.text('In Queue');
                                    newOrderBtn.css('display', 'block');
                                    $('#orders-box').find('.remove_item_button').prop('disabled', true);
                                    menuResultTable.find('.add_item_button').prop('disabled', true);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            },
                            complete: function() {
                                makeOrderBtn.prop('disabled', true);
                                isSubmitting = false; // Reset the flag variable
                            }
                        })
                    }
                } else {
                    alert('Please add items!');
                }
            } else {
                alert('Please enter a table number...');
            }

        });


        $(document).on("click", ".menu_button", function() {
            var categoryValue = $(this).data('category');
            menuResultTable.empty();
            menuResultHead.empty();

            $.ajax({
                type: "GET",
                url: "{{ route('filter-menu.admin') }}",
                data: {
                    category_value: categoryValue,
                },
                success: function(data) {
                    // console.log(data);
                    if (data.length > 0) {
                        menuResultHead.append('<tr style="position: sticky;top:0;"><th scope = "col" class = "border">' + categoryValue + '</th><th scope = "col" class = "border"></th></tr>')
                        data.forEach(function(row) {
                            menuResultTable.append("<tr id='item_row'><td class='border'>" + row.name + "</td><td class='border'><input type='number' min='1' id='item_quantity' style='width:70px'><button id='add_item_button' class='add_item_button' data-id='" + row.item_id + "'  data-name='" + row.name + "'  data-description='" + row.description + "' data-category='" + row.category + "' data-price='" + row.price + "' >ADD</button></td></tr>");
                        });
                    } else {
                        menuResultTable.append("<tr><td colspan='1' class='border'>No result...</tr>");
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        const menuCategorySelector = $("#menu_category_selector");

        $.ajax({
            type: "GET",
            url: "{{ route('fetch-menu.admin') }}",
            success: function(data) {
                // console.log(data.category);
                menuCategorySelector.empty();
                if (data.category.length > 0) {
                    data.category.forEach(function(row) {
                        menuCategorySelector.append("<button class='rounded-1 menu_button' data-category='" + row + "'>" + row + "</button>");
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });

        var orderedItems = []; //initiate orderedItems array


        //if add item is clicked
        menuResultTable.on('click', '#add_item_button', function() {
            // console.log(orderIdValue);

            var itemQuantity = $(this).parent().find('#item_quantity').val();

            if (itemQuantity == "") {
                itemQuantity = 1;
            }

            $(this).parent().find('#item_quantity').val("");

            var itemId = $(this).data('id');
            var itemName = $(this).data('name');
            var description = $(this).data('description');
            var category = $(this).data('category');
            var price = $(this).data('price');

            for (let i = 0; i < orderedItems.length; i++) {
                if (orderedItems[i].id === itemId) {
                    alert("Item is already selected!");
                    var selectedItem = true;
                }
            }

            if (!selectedItem) {
                orderedItems.push({
                    id: itemId,
                    name: itemName,
                    description: description,
                    category: category,
                    price: price,
                    quantity: itemQuantity,
                });

                var totalAmount = 0;

                for (var item of orderedItems) {
                    totalAmount += item.quantity * item.price;
                }

                $('#total_amount_input').text(totalAmount);

                totalBill = totalAmount;

                // console.log(orderedItems);
                ordersBox.append('<div class="container-fluid m-0 p-1 border" id="item_box" style="display: flex;align-items:center;"><p class="m-0">' + itemName + ' / ' + category + ' / P ' + price + '</p><div class="m-0 mx-1 p-0 border" id="count-container"><input type="text" min="1" style="width: 60px;text-align:center;" readonly value="' + itemQuantity + '"></div><div class="m-0 p-0 border"><button id="remove_item_button" class="remove_item_button" data-id="' + itemId + '">X</button></div></div>');
            }

        });


        //if the remove item button is clicked
        ordersBox.on('click', '#remove_item_button', function() {
            var itemId = $(this).data('id');

            orderedItems = orderedItems.filter(function(item) {
                return item.id !== itemId;
            });

            $(this).closest('#item_box').remove();

            var totalAmount = 0;

            for (const item of orderedItems) {
                totalAmount += item.quantity * item.price;
            }

            $('#total_amount_input').text(totalAmount);

            totalBill = totalAmount;
        });
    });
</script>
@endsection