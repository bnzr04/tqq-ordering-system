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
                        <input type="search" id="search_item_input" placeholder="Search menu..." class="form-control">
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

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel" style="letter-spacing: 3px;">PAYMENT</h5>
                <button type="button" class="btn-close" id="resetPaymentModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid m-0 p-1">
                    <div class="container-fluid m-0 p-0" style="letter-spacing: 2px;">
                        <input type="number" id="paymentModalOrderIdInput" class="form-control mb-2 border border-secondary text-center" style="letter-spacing: 2px;" disabled>
                        <h4>Total Amount</h4>
                        <h5 class="text-center">P <span id="amountDisplay">-</span></h5>
                    </div>
                    <div class="container-fluid m-0 mt-4 p-0">
                        <label for="cashInput">
                            <h5>Cash</h5>
                        </label>
                        <input type="number" min="1" id="cashInput" class="form-control border border-secondary text-center" style="letter-spacing: 2px;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="reset">Cancel</button>
                <button type="button" class="btn btn-dark" id="proceedButton">Proceed</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Modal -->
<div class="modal fade" id="changeModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" id="resetPaymentModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid m-0 p-1">
                    <div class="container-fluid m-0 p-0" style="letter-spacing: 2px;">
                        <h4>Change</h4>
                        <h5 class="text-center">P <span id="changeDisplay">-</span></h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
<script type="module" src="{{ asset('js/payment.js') }}"></script>
<script type="module" src="{{ asset('js/itemsModule.js') }}"></script>
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
        var orderedItems = [];
        var isSubmitting = false;
        const paymentModal = $("#paymentModal");
        const changeModal = $("#changeModal");

        function getNextDailyOrderId() {
            $.ajax({
                type: "GET",
                url: "{{ route('next-order-id.admin') }}",
                success: function(data) {
                    orderIdValue = data.next_order_id;
                    orderId.text(data.daily_order_id);
                    nextDailyOrderId = data.daily_order_id;
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function displaySearchedItemByName(itemName) {
            const searchItemByNameRoute = "{{ route('search-item-name.admin') }}";;
            $.getScript("{{ asset('js/itemsModule.js') }}", () => {
                searchItemByName(itemName, searchItemByNameRoute)
                    .then((data) => {
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
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        function postOrder(dailyOrderId, orderType, tableNumber, paymentStatus) {
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
                        $(".decrement_added_button").prop('disabled', true);
                        $(".increment_added_button").prop('disabled', true);

                        if (paymentModal.is(":visible")) {
                            paymentModal.modal("hide");
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                },
                complete: function() {
                    makeOrderBtn.prop('disabled', true);
                    isSubmitting = false;
                }
            })
        }

        function showPaymentModal(dailyOrderId, orderType, tableNumber) {
            var paymentStatus = $('input[name="payment_status"]:checked').val();
            const cashInput = paymentModal.find("#cashInput");
            const orderIdInput = paymentModal.find("#paymentModalOrderIdInput");
            const proceedButton = paymentModal.find("#proceedButton");

            paymentModal.modal('show');
            paymentModal.find("#amountDisplay").text(totalAmount.toFixed(2));
            orderIdInput.val(orderIdValue);

            var cash = 0;

            if (cashInput.val() == "") {
                proceedButton.prop('disabled', true);
            }

            cashInput.on("input", function() {
                cash = parseFloat($(this).val());

                if (cash === "") {
                    proceedButton.prop('disabled', true);
                } else {
                    proceedButton.prop('disabled', false);
                }

            });

            proceedButton.on('click', function() {
                var change = cash - totalAmount;
                if (cash < totalAmount) {
                    alert("Cash not enough!");
                } else {
                    if (confirm('Do you want to send the order to kitchen?')) {
                        var generateReceiptRoute = "{{ route('generate-receipt') }}";
                        var orderId = orderIdValue;
                        alert("CHANGE: " + change);
                        postOrder(dailyOrderId, orderType, tableNumber, paymentStatus);
                        $.getScript("{{ asset('js/payment.js') }}", function() {
                            generateReceipt(orderId, cash, change, generateReceiptRoute);
                        });
                        // showChangeModal(cash);
                    }
                }
            });
        }

        function showChangeModal(cash) {
            paymentModal.modal('hide');
            changeModal.modal('show');

            const changeDisplay = $('#changeDisplay');
            const doneButton = changeModal.find('#doneButton');

            var change = cash - totalAmount;

            changeDisplay.text(change.toFixed(2));
        }

        function generateMenuButtons() {
            $.ajax({
                type: "GET",
                url: "{{ route('fetch-categories.admin') }}",
                success: function(data) {
                    menuCategorySelector.empty();
                    if (data.length > 0) {
                        data.forEach(function(row) {
                            menuCategorySelector.append("<button class='rounded-1 menu_button' data-category='" + row + "'>" + row + "</button>");
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

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

        /////////////////////////
        /////Buttons events//////
        /////////////////////////

        makeOrderBtn.on("click", function() {
            var dailyOrderId = parseInt(orderId.text());
            var orderType = $('input[name="order_type"]:checked').val();
            var paymentStatus = $('input[name="payment_status"]:checked').val();
            var tableNumberValue = tableNumberInput.val();
            var total = $('#total_amount_input').text();
            var tableNumber = null;
            var isPaid = false;

            if (orderedItems.length > 0) {
                if (orderType == 'dine-in') {
                    if (tableNumberValue == '') {
                        alert("Please enter table number...");
                    } else {
                        tableNumber = tableNumberValue;
                    }
                }

                if (paymentStatus == "paid") {
                    isPaid = true;
                } else {
                    isPaid = false;
                }

                if (orderType == 'dine-in' && tableNumberValue !== "" || orderType == 'take-out') {
                    if (isPaid) {
                        showPaymentModal(dailyOrderId, orderType, tableNumber);
                    } else {
                        if (confirm('Do you want to send the order to kitchen?')) {
                            postOrder(dailyOrderId, orderType, tableNumber, paymentStatus);
                        }
                    }
                }
            } else {
                alert("No ordered items...");
            }
        });

        searchItemInput.on('input', function() {
            var inputValue = $(this).val();

            displaySearchedItemByName(inputValue);
        });

        newOrderBtn.on('click', function() {
            location.reload();
        });

        paymentModal.on('click', '#reset', function() {
            var cashInput = $(this).parent().siblings(".modal-body").find("#cashInput");
            cashInput.val('');
        });

        $(document).on("click", ".menu_button", function() {
            var category = $(this).data('category');
            menuResultTable.empty();
            menuResultHead.empty();
            $("#search_item_input").val("");

            $.getScript("{{ asset('js/itemsModule.js') }}", () => {
                const getItemsByCategoriesRoute = "{{ route('filter-item-by-category.admin') }}";
                getItemsByCategories(getItemsByCategoriesRoute, category)
                    .then((data) => {
                        if (data.length > 0) {
                            menuResultHead.append('<tr style="position: sticky;top:0;"><th scope = "col" class = "border text-center">' + category + '</th><th scope = "col" class = "border"></th></tr>')
                            data.forEach(function(row) {
                                if (row.quantity == null) {
                                    row.quantity = 0;
                                }

                                menuResultTable.append("<tr id='item_row'><td class='border'>" + row.name + "  /  " + row.category + "  /  " + row.price + " / [" + row.quantity + "]</td><td class='border'><input type='number' min='1' id='item_quantity' style='width:70px'><button id='add_item_button' class='add_item_button' data-id='" + row.item_id + "'  data-name='" + row.name + "'  data-description='" + row.description + "' data-category='" + row.category + "' data-price='" + row.price + "' data-quantity='" + row.quantity + "' >ADD</button></td></tr>");
                            });
                        } else {
                            menuResultTable.append("<tr><td colspan='1' class='border'>No result...</tr>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    })
            });
        });

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

                        ordersBox.append('<div class="container-fluid m-0 p-1 border" id="item_box" style="display: flex;align-items:center;"><p class="m-0">' + itemName + ' / ' + category + ' / P ' + price + '</p><div class="m-0 mx-1 p-0 border" id="count-container"><input id="ordered_item_quantity" data-id="' + itemId + '" type="text" min="1" style="width: 60px;text-align:center;" readonly value="' + itemQuantity + '"></div><div class="m-0 p-0 border"><button class="decrement_added_button" data-item-id="' + itemId + '">-</button><button class="increment_added_button" data-item-id="' + itemId + '">+</button><button id="remove_item_button" class="remove_item_button ms-1" data-id="' + itemId + '">X</button></div></div>');

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

        ordersBox.on('click', '.decrement_added_button', function() {
            const itemBox = $(this).parent().parent();
            const addedQtyInput = $(this).parent().siblings('#count-container').find('#ordered_item_quantity');

            var itemIdOfAddedItem = $(this).data('item-id');

            var addedQty = parseInt(addedQtyInput.val());

            var newQty = addedQty - 1;

            addedQtyInput.val(newQty);

            for (let i = 0; i < orderedItems.length; i++) {
                if (orderedItems[i].id === itemIdOfAddedItem) {
                    var itemPrice = orderedItems[i].price;
                    var newQty = addedQty - 1;

                    orderedItems[i].quantity = newQty;

                    if (newQty < 1) {
                        orderedItems = orderedItems.filter(function(item) {
                            return item.id !== itemIdOfAddedItem;
                        });

                        itemBox.remove();
                    }

                    totalAmount -= parseFloat(itemPrice);

                    addedQtyInput.val(newQty);
                }
            }

            $('#total_amount_input').text(totalAmount.toFixed(2));
        });

        ordersBox.on('click', '.increment_added_button', function() {
            const itemBox = $(this).parent().parent();
            const addedQtyInput = $(this).parent().siblings('#count-container').find('#ordered_item_quantity');

            var itemIdOfAddedItem = $(this).data('item-id');

            var addedQty = parseInt(addedQtyInput.val());

            for (let i = 0; i < orderedItems.length; i++) {
                if (orderedItems[i].id === itemIdOfAddedItem) {
                    var itemPrice = orderedItems[i].price;
                    var newQty = addedQty + 1;

                    orderedItems[i].quantity = newQty;

                    totalAmount += parseFloat(itemPrice);

                    addedQtyInput.val(newQty);
                }
            }
            $('#total_amount_input').text(totalAmount.toFixed(2));
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

            $('#total_amount_input').text(totalAmount.toFixed(2)); //display the totalAmount value to the id = "total_amount_input" input

            orderedItems = orderedItems.filter(function(item) {
                return item.id !== itemId;
            });

            $(this).closest('#item_box').remove();
        });

        getNextDailyOrderId();
        generateMenuButtons();
    });
</script>
@endsection