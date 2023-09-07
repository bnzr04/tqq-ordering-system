@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        /* grid: 100vh / 200px auto; */
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
                        <a href="{{ route('payment.admin') }}" class="btn btn-secondary">Payment</a>
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
                    </section>
                    <div class="container-fluid m-0 p-1 border">
                        <input type="text" id="search_item_input" placeholder="Search menu..." class="form-control">
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

        const searchItemInput = $('#search_item_input');

        const menuResultTable = $('#menu_result_table');
        const menuResultHead = $("#menu_result_head");

        const menuCategorySelector = $('#menu_category_selector');

        var totalAmount = 0.00;

        var orderIdValue = 0;

        var nextDailyOrderId = 0

        function showNextOrderId() { //this function will show the next order id
            $.ajax({
                type: "GET",
                url: "{{ route('next-order-id.admin') }}",
                success: function(data) {
                    // console.log(data);

                    orderIdValue = data.next_order_id; //store the data value which container the next order id

                    orderId.text(data.daily_order_id); //display the orderIdValue
                    nextDailyOrderId = data.daily_order_id; //set the value of orderIdValue
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        showNextOrderId(); //excecute the function

        searchItemInput.on('input', function() { //this function will search the item by name typing
            var inputValue = $(this).val();

            $.ajax({
                type: 'GET',
                url: "{{ route('search-item-name.admin') }}",
                data: {
                    item_name: inputValue,
                },
                success: function(data) {
                    // console.log(data);
                    menuResultTable.html('');

                    if (data.length > 0) {
                        menuResultHead.html('');
                        data.forEach(function(row) {
                            if (row.quantity == null) {
                                row.quantity = 0;
                            }

                            menuResultTable.append("<tr id='item_row'><td class='border'>" + row.name + "  /  " + row.category + "  /  " + row.price + "  /  [" + row.quantity + "]</td><td class='border'><input type='number' min='1' id='item_quantity' style='width:70px'><button id='add_item_button' class='add_item_button' data-id='" + row.item_id + "'  data-name='" + row.name + "'  data-description='" + row.description + "' data-category='" + row.category + "' data-price='" + row.price + "' data-quantity='" + row.quantity + "'>ADD</button></td></tr>");
                        });
                    } else {
                        menuResultTable.append("<tr><td colspan='2'>No result...</td></tr>")
                    }
                },
                error: function(xhr, status, error) {
                    console.log(chr.responseText);
                }
            });
        });

        newOrderBtn.on('click', function() { //if the new-order-btn is clicked the order page will reload
            location.reload();
        });

        var isSubmitting = false; // Flag variable to track form submission

        //this will execute if the make-order-btn is clicked
        $("#make-order-btn").on("click", function() {
            // console.log(totalAmount);

            var dailyOrderId = parseInt(orderId.text())

            // console.log(dailyOrderId)
            var tableNumberValue = tableNumberInput.val(); //get the table number input value

            var total = $('#total_amount_input').text();

            if (tableNumberValue !== '') {
                if (orderedItems.length > 0) {
                    if (confirm('Do you want to send the order to kitchen?')) {
                        if (isSubmitting) {
                            return; // If form is already being submitted, ignore subsequent clicks
                        }

                        var orderType = $('input[name="order_type"]:checked').val();
                        var paymentStatus = $('input[name="payment_status"]:checked').val();
                        var tableNumber = $('#table-number-input').val();

                        isSubmitting = true;

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('submit-order.admin') }}",
                            data: {
                                order_id: orderIdValue,
                                daily_order_id: dailyOrderId,
                                order_type: orderType,
                                payment_status: paymentStatus,
                                table_number: tableNumber,
                                order_items: orderedItems,
                                total_bill: totalAmount,
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(data) {
                                if (data.response == true) {
                                    orderId.text(dailyOrderId);
                                    radioContainer.find(":radio").prop("disabled", true);
                                    tableNumberInput.prop('disabled', true);
                                    makeOrderBtn.prop('disabled', true);
                                    makeOrderBtn.text('In Queue');
                                    newOrderBtn.css('display', 'block');
                                    $('#orders-box').find('.remove_item_button').prop('disabled', true);
                                    menuResultTable.find('.add_item_button').prop('disabled', true);
                                    menuResultTable.find('input').prop('disabled', true);
                                    menuCategorySelector.find('button').prop('disabled', true);
                                    searchItemInput.prop('disabled', true);
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
            $("#search_item_input").val("");

            $.ajax({
                type: "GET",
                url: "{{ route('filter-menu.admin') }}",
                data: {
                    category_value: categoryValue,
                },
                success: function(data) {
                    if (data.length > 0) {
                        menuResultHead.append('<tr style="position: sticky;top:0;"><th scope = "col" class = "border text-center">' + categoryValue + '</th><th scope = "col" class = "border"></th></tr>')
                        data.forEach(function(row) {
                            if (row.quantity == null) {
                                row.quantity = 0;
                            }

                            menuResultTable.append("<tr id='item_row'><td class='border'>" + row.name + "  /  " + row.category + "  /  " + row.price + " / [" + row.quantity + "]</td><td class='border'><input type='number' min='1' id='item_quantity' style='width:70px'><button id='add_item_button' class='add_item_button' data-id='" + row.item_id + "'  data-name='" + row.name + "'  data-description='" + row.description + "' data-category='" + row.category + "' data-price='" + row.price + "' data-quantity='" + row.quantity + "' >ADD</button></td></tr>");
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

        $.ajax({
            type: "GET",
            url: "{{ route('fetch-menu.admin') }}",
            success: function(data) {
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

        function incrementQuantityOfItemOrder(itemId, itemRow, itemQuantity) {
            itemQuantity = parseInt(itemQuantity);
            for (let i = 0; i < orderedItems.length; i++) {
                if (orderedItems[i].id === itemId) {
                    orderedItems[i].quantity += itemQuantity;

                    itemRow.parent().find("[data-id='" + orderedItems[i].id + "']").val(orderedItems[i].quantity);
                    totalAmount += parseFloat(orderedItems[i].price) * itemQuantity;
                }
            }

            $('#total_amount_input').text(totalAmount.toFixed(2));
        }

        //if add item is clicked
        menuResultTable.on('click', '.add_item_button', function() {

            var itemQuantity = $(this).parent().find('#item_quantity').val();

            if (itemQuantity == "") {
                itemQuantity = 1;
            }

            itemQuantity = parseInt(itemQuantity);

            $(this).parent().find('#item_quantity').val("");

            var itemId = $(this).data('id');
            var itemName = $(this).data('name');
            var description = $(this).data('description');
            var category = $(this).data('category');
            var price = $(this).data('price');
            var stockQuantity = $(this).data('quantity');

            for (let i = 0; i < orderedItems.length; i++) {
                if (orderedItems[i].id === itemId) {
                    var quantity = orderedItems[i].quantity;
                    var selectedItem = true;
                }
            }

            if (stockQuantity != 0) {
                if (itemQuantity > stockQuantity) {
                    alert("Quantity must be less than or equal to " + stockQuantity + "!")
                } else {
                    if (!selectedItem) {
                        orderedItems.push({
                            id: itemId,
                            name: itemName,
                            description: description,
                            category: category,
                            price: price,
                            quantity: itemQuantity,
                        });

                        if (itemQuantity !== '') {
                            totalAmount += price * itemQuantity;
                        } else {
                            totalAmount += price;
                        }

                        $('#total_amount_input').text(totalAmount.toFixed(2));

                        ordersBox.append('<div class="container-fluid m-0 p-1 border" id="item_box" style="display: flex;align-items:center;"><p class="m-0">' + itemName + ' / ' + category + ' / P ' + price + '</p><div class="m-0 mx-1 p-0 border" id="count-container"><input id="ordered_item_quantity" data-id="' + itemId + '" type="text" min="1" style="width: 60px;text-align:center;" readonly value="' + itemQuantity + '"></div><div class="m-0 p-0 border"><button>-</button><button>+</button><button id="remove_item_button" class="remove_item_button ms-1" data-id="' + itemId + '">X</button></div></div>');

                    } else {
                        var itemRow = $('#item_box');

                        var newQuantity = quantity + itemQuantity;

                        if (newQuantity > stockQuantity) {
                            alert(itemName + " / " + category + " reached the stock quantity limit!");
                        } else {
                            incrementQuantityOfItemOrder(itemId, itemRow, itemQuantity);
                        }
                    }
                }
            } else {
                alert('Sorry, Item cannot add because the stock is ' + stockQuantity + '.');
            }

        });


        //if the remove item button is clicked
        ordersBox.on('click', '#remove_item_button', function() {
            var itemId = $(this).data('id'); //store the value of data-id from this button

            for (let i = 0; i < orderedItems.length; i++) {
                if (orderedItems[i].id === itemId) { //if the itemId is match
                    var toSubtract = orderedItems[i].price * orderedItems[i].quantity; //multiply the ordered item price to the quantity and store the product value
                }
            }



            totalAmount -= toSubtract; //subtract the value of toSubtract to the totalAmount value

            $('#total_amount_input').text(totalAmount); //display the totalAmount value to the id = "total_amount_input" input


            orderedItems = orderedItems.filter(function(item) {
                return item.id !== itemId;
            });

            $(this).closest('#item_box').remove();
        });
    });
</script>
@endsection