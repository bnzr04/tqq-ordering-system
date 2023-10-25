@extends('layouts.app')
@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    .hide {
        display: none;
    }

    .show {
        display: block;
    }

    /* .data_container {
        max-height: 150px;
        overflow-y: auto;
    } */

    .order_content {
        background-color: #ffffff;
        padding: 0 10px;
        color: black;
        border-bottom: #000 1px solid;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order_control_container {
        background-color: #ffffff;
        padding: 0 10px;
        color: black;
        border-bottom: #000 1px solid;
        display: flex;
        align-items: center;
    }

    .order_box {
        display: flex;
        align-items: center;
        letter-spacing: 2px;
        cursor: default;
        font-size: 13px;
    }

    .text_order_container {
        display: flex;
        align-items: center;
    }

    .order_box.hide {
        background-color: #595959;
    }

    .order_box.show {
        background-color: #000;
    }

    .arrow_container {
        padding: 0 5px;
        cursor: pointer;
    }

    .arrow_container img {
        filter: brightness(0) invert(1);
        z-index: 0;
    }

    .arrow_container.show img {
        filter: brightness(0) invert(1);
        transform: rotate(90deg);
    }

    #paymentButton {
        background-color: #1a1a1a;
    }

    #modal_label {
        letter-spacing: 2px;
    }

    #modal_order_container {
        height: 200px;
        overflow-y: auto;
    }

    #modal_result_container {
        height: 150px;
        overflow-y: auto;
    }

    #modal_order_box {
        background-color: #333333;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .quantity_input_container input {
        width: 70px;
    }

    .added_item_input {
        width: 70px;
    }

    .paymentModalBodyContainer {
        display: grid;
        grid-template-columns: auto auto;
    }

    #container1,
    #container2 {
        height: 60vh;
    }

    #container1 {
        grid-row: 1/2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        letter-spacing: 3px;
        text-align: center;
    }

    #container1 input {
        text-align: center;
        font-size: 26px;
    }

    #container2 {
        overflow: auto;
    }

    #container2 li {
        border-bottom: #595959 1px solid;
        padding: 5px 0;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Orders</h3>
        </div>
        <div class="container-fluid m-0 mt-2 p-2">
            <a class="btn btn-dark" href="{{ route('make-order.cashier') }}" title="Make New Order">Make Order +</a>
        </div>
        <div class="container-fluid m-0 p-2 border-top border-bottom" style="display: flex;align-items:center">
            <div class="d-flex border m-0 p-1">
                <button class="btn btn-dark mx-1" id="today_btn">Today</button>
                <button class="btn btn-dark mx-1" id="yesterday_btn">Yesterday</button>
                <div class="mx-3 p-0" style="display: flex;align-items:center;">
                    <label for="date_input" class="m-0" style="letter-spacing: 2px;text-wrap:nowrap;">Filter date</label>
                    <input type="date" class="form-control m-0 mx-1" id="date_input" required>
                    <button class="p-2 rounded" id="filter_date_button">Filter</button>
                </div>
            </div>
            <div class="d-flex m-0 p-1">
                <button class="btn btn-dark mx-1" id="unpaid_btn">Unpaid Orders</button>
                <button class="btn btn-dark mx-1" id="paid_btn">Paid Orders</button>
            </div>
            <!-- <div class="d-flex m-0 p-0">
                <button class="btn btn-dark mx-1" id="dine_in_btn">Dine-in</button>
                <button class="btn btn-dark mx-1" id="take_out_btn">Take-out</button>
            </div> -->

        </div>
        <div class="container-fluid m-0 p-2">
            <div class="containter-fluid mx-3 my-1 p-0">
                <h4 class="m-0 p-0 fw-bold" style="letter-spacing: 4px;" id="table-title"></h4>
            </div>
            <div class="container-fluid p-0 border border-secondary" id="order_container" style="height: 65vh;overflow-y:auto">

            </div>
        </div>
    </div>
</div>
<!--Add Item Modal -->
<div class="modal fade" id="add_item_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label">ADD ITEM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close_modal_button"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="modal_order_id" value="1" class="form-control border border-secondary" disabled hidden>

                <div class="container-fluid p-0">
                    <div class="container-fluid p-0 d-flex" style="align-items: center;justify-content:space-between">
                        <label>Additional</label>
                        <h5>Sub total: P <span style="font-weight: bold;" id="subtotal_display">0</span></h5>
                    </div>
                    <div class="container-fluid p-0 border border-secondary rounded-3" id="modal_order_container">

                    </div>
                </div>

                <div class="container-fluid mt-2 p-0">
                    <input type="text" id="search_item_input" value="" placeholder="Search item..." class="form-control border border-secondary">
                    <div class="container-fluid mt-1 p-0 border border-secondary rounded-3" id="modal_result_container">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close_modal_button">Close</button>
                <button type="button" class="btn btn-dark" id="add_new_item_button">Add</button>
            </div>
        </div>
    </div>
</div>

<!--Remove Modal -->
<div class="modal fade" id="remove_item_modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModal" style="letter-spacing: 3px;">REMOVE ITEM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close_remove_modal"></button>
            </div>
            <div class="modal-body" id="remove_modal_body">
                <input type="hidden" class="form-control border-secondary text-center" id="order_id_input">
                <input type="hidden" class="form-control border-secondary text-center" id="item_order_id_input">

                <h5><b id="item_name_display"></b></h5>
                <h6>Current Quantity<br><b id="current_item_quantity_display"></b></h6>

                <label for="quantity_input">Remove Quantity</label>
                <input type="number" id="quantity_input" class="form-control border-secondary text-center" min="1" value="1">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close_remove_modal">Close</button>
                <button type="button" class="btn btn-dark" id="remove_modal_button">Remove Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel" style="letter-spacing: 3px;">PAYMENT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cancelPaymentModal"></button>
            </div>
            <div class="modal-body paymentModalBodyContainer">
                <div class="container-fluid m-0 p-1" id="container1">
                    <div class="container-fluid m-0 p-2">
                        <h3 style="letter-spacing: 3px;">TOTAL AMOUNT</h3>
                        <h1>P <span id="paymentModalTotalAmountDisplay">-</span></h1>
                    </div>
                    <div class="container-fluid p-3">
                        <h4>ENTER CASH</h4>
                        <input type="number" class="form-control border border-secondary" id="cashInput">
                        <button class="btn btn-dark mt-4 proceedButtonPaymentModal" style="width:300px;letter-spacing:3px;">PROCEED</button>
                    </div>
                </div>
                <div class="container-fluid m-0 p-1 border border-secondary rounded-3" id="container2">
                    <ul id="itemsListDisplay">

                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelPaymentModal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        const tableBody = $('#table-body');
        const tableTitle = $("#table-title");
        const orderContainer = $("#order_container");
        const paymentModal = $("#paymentModal");
        var mainFilterDateUrl = "{{ route('fetch-orders-by-date.cashier') }}";
        var paymentStatus;
        var orderType;
        let subTitle;
        var filterDateUrl;
        var dateFilter;
        var dateValue;
        var filterDateTitle;
        var isOrderFilteredByDate = false;


        function updateOrderStatus(orderId, newStatus) {
            var orderElement = $("div[data-order-id='" + orderId + "']");
            var orderStatusElement = orderElement.find('.order_box_status span');
            var textOrder = orderElement.find('.text_order_container');
            orderStatusElement.text(newStatus);

            var serveAllButton = $("<button type='button' id='serve_button'>SERVE</button>");

            var cancelOrderButton = $("<button type='button' id='cancel_button'>CANCEL</button>");

            if (newStatus == 'serving') {
                var serveButton = textOrder.find("[id='serve_button']");
            } else if (newStatus == 'in queue') {
                var cancelButton = textOrder.find("[id='cancel_button']");
            }

        }

        function orderStatusInterval() {
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // console.log(data);
                    if (data.length > 0) {
                        data.forEach(function(row) {
                            // Update order status if needed
                            updateOrderStatus(row.order_id, row.order_status);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function orders() {
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // console.log(data);
                    tableTitle.text("");
                    orderContainer.html('');
                    tableTitle.text(titleText);

                    if (data.length > 0) {

                        data.forEach(function(row) {
                            var itemsCount = row.ordered_items.length;

                            var mainContainer = $("<div class='main'></div>");
                            var dataContainer = $("<div class='data_container hide'></div>");

                            var clickableContainer = $("<div data-order-id='" + row.order_id + "' class='order_box hide border-bottom border-secondary text-white shadow p-1'></div>");

                            var textOrder = $("<div class='text_order_container'></div>");

                            var clickableContent = $("<div style='border-right:white 2px solid;padding: 0 10px'><b>Time: </b>" + row.formatDate + "</div><div style='border-right:white 2px solid;padding: 0 10px' title='" + row.order_id + "'><b>Order ID </b>[" + row.daily_order_id + "]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Table # </b>[" + row.table_number + "]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Items </b>[" + itemsCount + "]</div><div style='border-right:white 2px solid;padding: 0 10px' class='order_box_status'><b>Status </b>[<span>" + row.order_status + "</span>]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Amount </b>[" + row.total_amount + "]</div><div style='border-right:white 2px solid;padding: 0 10px'>" + row.order_type + "</div><div style='border-right:white 2px solid;padding: 0 10px'>" + row.payment_status + "</div>");

                            var addItemButton = $("<button type='button' class='add_order_button' data-bs-toggle='modal' data-order-id='" + row.order_id + "' data-bs-target='#add_item_modal'>ADD</button>")

                            var serveAllButton = $("<button type='button'>SERVE</button>");

                            var cancelOrderButton = $("<button type='button'>CANCEL</button>");

                            var arrowContainer = $("<div class='arrow_container border' data-order-id='" + row.order_id + "'><img src='{{ asset('/icons/play.png') }}' width='15px'></div>");

                            mainContainer.append(clickableContainer);
                            mainContainer.append(dataContainer);
                            clickableContainer.append(textOrder);
                            textOrder.append(clickableContent);

                            clickableContainer.prepend(arrowContainer);

                            orderContainer.append(mainContainer);
                        });
                    } else {
                        orderContainer.append("<div class=' shadow px-2 p-1 my-1'><b>No orders...</b></div>");
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function getItemsOrder(orderId) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('fetch-ordered-items.cashier') }}",
                    data: {
                        order_id: orderId,
                    },
                    success: function(data) {
                        resolve(data);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                })
            });
        }

        var mainUrl = "{{ route('fetch-orders.cashier') }}";
        var url = "{{ route('fetch-orders.cashier') }}";
        let title = "Today Orders";
        var titleText = title;
        orders();

        setInterval(orderStatusInterval, 10000); // 10000 milliseconds = 10 seconds

        const todayBtn = $('#today_btn');
        const yesterdayBtn = $('#yesterday_btn');
        const inQueueBtn = $('#in_queue_button');

        const unpaidOrdersBtn = $('#unpaid_btn');
        const paidOrdersBtn = $('#paid_btn');

        const dineInBtn = $('#dine_in_btn');
        const takeOutBtn = $('#take_out_btn');

        let currentUrl = "{{ route('fetch-orders.cashier') }}";
        var dayFilter = "?today=1";

        todayBtn.on('click', function() {
            isOrderFilteredByDate = false;

            url = mainUrl;
            dayFilter = "?today=1";
            title = "Today Orders";
            titleText = title;
            orders();
            filterDateUrl = undefined;
            dateValue = undefined;
            $('#date_input').val("");
        });

        yesterdayBtn.on('click', function() {
            isOrderFilteredByDate = false;

            dayFilter = "?yesterday=1";
            url = mainUrl + dayFilter;
            title = "Yesterday Orders";
            titleText = title;
            orders();

            filterDateUrl = undefined;
            dateValue = undefined;
            $('#date_input').val("");
        });

        unpaidOrdersBtn.on('click', function() {

            paymentStatus = "&payment-status=unpaid";
            subTitle = " * Unpaid Orders";

            if (isOrderFilteredByDate !== true) {
                url = mainUrl + dayFilter + paymentStatus;
                titleText = title + subTitle;
                orders();
            } else {
                if (dateValue !== undefined) {
                    filterDateUrl = mainFilterDateUrl + "?filter-date=" + dateValue + paymentStatus;
                    fetchFilteredDateOrders(subTitle);
                }
            }
        });

        paidOrdersBtn.on('click', function() {

            paymentStatus = "&payment-status=paid";
            subTitle = " * Paid Orders";

            if (isOrderFilteredByDate !== true) {
                url = mainUrl + dayFilter + paymentStatus;
                titleText = title + subTitle;
                orders();
            } else {
                if (dateValue !== undefined) {
                    filterDateUrl = mainFilterDateUrl + "?filter-date=" + dateValue + paymentStatus;
                    fetchFilteredDateOrders(subTitle);
                }
            }

        });

        dineInBtn.on('click', function() {
            orderType = "&order-type=dine-in";
        });

        takeOutBtn.on('click', function() {
            orderType = "&order-type=take-out";
        });

        function fetchFilteredDateOrders(title) {
            $.ajax({
                type: "get",
                url: filterDateUrl,
                success: function(data) {
                    filterDateTitle = "Orders of " + data.date;

                    if (title == undefined) {
                        title = "";
                    }

                    tableTitle.text(filterDateTitle + title);
                    orderContainer.html('');

                    if (data.orders.length > 0) {
                        data.orders.forEach(function(row) {
                            var itemsCount = row.ordered_items.length;

                            var mainContainer = $("<div class='main'></div>");
                            var dataContainer = $("<div class='data_container hide'></div>");

                            var clickableContainer = $("<div data-order-id='" + row.order_id + "' class='order_box hide border-bottom border-secondary text-white shadow p-1'></div>");

                            var textOrder = $("<div class='text_order_container'></div>");

                            var clickableContent = $("<div style='border-right:white 2px solid;padding: 0 10px'><b>Time: </b>" + row.formatDate + "</div><div style='border-right:white 2px solid;padding: 0 10px' title='" + row.order_id + "'><b>Order ID </b>[" + row.daily_order_id + "]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Table # </b>[" + row.table_number + "]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Items </b>[" + itemsCount + "]</div><div style='border-right:white 2px solid;padding: 0 10px' class='order_box_status'><b>Status </b>[<span>" + row.order_status + "</span>]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Amount </b>[" + row.total_amount + "]</div><div style='border-right:white 2px solid;padding: 0 10px'>" + row.order_type + "</div><div style='border-right:white 2px solid;padding: 0 10px'>" + row.payment_status + "</div>");

                            var addItemButton = $("<button type='button' class='add_order_button' data-bs-toggle='modal' data-order-id='" + row.order_id + "' data-bs-target='#add_item_modal'>ADD</button>")

                            var serveAllButton = $("<button type='button'>SERVE</button>");

                            var cancelOrderButton = $("<button type='button'>CANCEL</button>");

                            var arrowContainer = $("<div class='arrow_container border' data-order-id='" + row.order_id + "'><img src='{{ asset('/icons/play.png') }}' width='15px'></div>");

                            mainContainer.append(clickableContainer);
                            mainContainer.append(dataContainer);
                            clickableContainer.append(textOrder);
                            textOrder.append(clickableContent);

                            clickableContainer.prepend(arrowContainer);

                            orderContainer.append(mainContainer);
                        });
                    } else {
                        orderContainer.append("<div class=' shadow px-2 p-1 my-1'><b>No orders...</b></div>");
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        };

        paymentModal.on("click", ".proceedButtonPaymentModal", function() {
            const cashInput = paymentModal.find("#cashInput");
            var orderId = $(this).attr("data-order-id");
            var totalAmount = parseFloat($(this).attr("data-total-amount"));

            if (cashInput.val() !== "") {
                var cash = parseFloat(cashInput.val());
                var change = cash - totalAmount;

                if (cash >= totalAmount) {
                    var generateReceiptRoute = "{{ route('generate-receipt') }}";
                    var makeOrderAsPaidToute = "{{ route('make-order-paid.cashier') }}";
                    $.getScript("{{ asset('js/payment.js') }}", function() {
                        generateReceipt(orderId, cash, change, generateReceiptRoute);
                        makeOrderAsPaid(orderId, makeOrderAsPaidToute)
                            .then(function(response) {
                                if (response) {
                                    paymentModal.modal("hide");
                                    orders();
                                }
                            })
                            .catch(function(error) {
                                console.error(error);
                            });
                    });

                    alert("CHANGE: " + change);
                } else {
                    alert("Cash not enough!");
                }
            } else {
                cashInput.focus();
            }
        });

        paymentModal.on("click", "#cancelPaymentModal", function() {
            const cashInput = paymentModal.find("#cashInput");
            cashInput.val("");
        });

        function getOrderedItems(orderId) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    type: "get",
                    url: "{{ route('fetch-ordered-items-of-order.cashier') }}",
                    data: {
                        order_id: orderId
                    },
                    success: function(data) {
                        resolve(data); // Resolve the promise with the data
                    },
                    error: function(xhr, status, error) {
                        reject(error); // Reject the promise with an error message
                    }
                });
            });
        }

        $(document).on('click', '#paymentButton', function() {
            var orderId = $(this).data("order-id");
            const amountDisplay = paymentModal.find("#paymentModalTotalAmountDisplay");

            getOrderedItems(orderId)
                .then(function(data) {
                    var itemListDisplay = $("#itemsListDisplay");
                    itemListDisplay.empty();
                    var itemSubTotal = 0.00;
                    var totalAmount = 0.00;

                    if (data.length > 0) {
                        data.forEach(function(item) {
                            var quantity = item.quantity;
                            var price = item.price;
                            itemSubTotal = price * quantity;
                            totalAmount += itemSubTotal;

                            var itemRow = $("<li>").text(item.name + " - " + item.category + " - " + quantity + " - @ " + price + " - [" + itemSubTotal.toFixed(2) + "]");
                            itemListDisplay.append(itemRow);
                        });

                        amountDisplay.text(totalAmount.toFixed(2));
                        paymentModal.find(".proceedButtonPaymentModal").attr("data-total-amount", totalAmount.toFixed(2));
                    } else {
                        var emptyRow = $("<li>").text("No item...");
                        itemListDisplay.append(emptyRow);
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });

            paymentModal.find(".proceedButtonPaymentModal").attr("data-order-id", orderId);
            paymentModal.modal("show");

        });


        $('#filter_date_button').on('click', function(event) { //retrieve orders filtered by date
            event.preventDefault();
            isOrderFilteredByDate = true;

            dateValue = $('#date_input').val();
            dateFilter = `?filter-date=${dateValue}`;
            filterDateUrl = mainFilterDateUrl + dateFilter;

            if (dateValue !== '') {
                fetchFilteredDateOrders();
            } else {
                $('#date_input').focus();
            }

        });

        orderContainer.on('click', '.arrow_container', function() {
            var orderId = $(this).data('order-id');
            var main = $(this).closest('.main');
            var dataContainer = main.find('.data_container');

            if ($('.data_container').hasClass('show')) {
                $('.data_container').removeClass('show').addClass('hide');
                $('.order_box').removeClass('show').addClass('hide');
                $('.arrow_container').removeClass('show');
            }

            // Toggle the visibility of dataContainer
            dataContainer.toggleClass('show hide');

            if (dataContainer.hasClass('show')) {
                dataContainer.toggleClass('show');
                $(this).parent().toggleClass('show');
                $(this).toggleClass('show');

                getItemsOrder(orderId)
                    .then(function(data) {
                        var row = "";
                        var orderControlContainer = "";
                        dataContainer.empty();

                        var orderStatus = data.order_status;
                        var dailyOrderId = data.daily_order_id;

                        main.find('.order_box_status').children('span').text(orderStatus.toLowerCase());

                        if (data.ordered_items.length > 0) {
                            var addItemButton = $("<button type='button' class='add_order_button mx-1' data-bs-toggle='modal' data-order-id='" + data.order_id + "' data-daily-order-id='" + dailyOrderId + "' data-bs-target='#add_item_modal'>ADD ITEM</button>")

                            var paymentButton = $("<button type='button' id='paymentButton' class='text-white' data-order-id='" + data.order_id + "'>PAYMENT</button>");

                            var completeButton = $("<button type='button' class='mx-1' id='completeButton' data-order-id='" + data.order_id + "' data-daily-order-id='" + dailyOrderId + "'>COMPLETE</button>");

                            var cancelOrderButton = $("<button type='button' class='mx-1'>CANCEL</button>");

                            var viewReceiptButton = $("<button type='button' class='bg-dark text-white' id='viewReceiptButton' data-order-id='" + data.order_id + "'>VIEW RECEIPT</button>");

                            orderControlContainer = $("<div class='order_control_container p-1'></div>");

                            dataContainer.prepend(orderControlContainer);

                            if (data.payment_status == "unpaid") {
                                orderControlContainer.append(addItemButton);
                                orderControlContainer.append(paymentButton);
                            } else if (data.payment_status == "paid") {
                                orderControlContainer.append(viewReceiptButton);
                            }

                            if (data.order_status == "Serving") {
                                orderControlContainer.append(completeButton);
                            }
                            //  else if (data.order_status == "In Queue" && data.payment_status !== "paid") {
                            //     orderControlContainer.append(cancelOrderButton);
                            // }

                            data.ordered_items.forEach(function(item) {
                                var itemPrice = parseFloat(item.price);
                                var itemSubTotal = itemPrice * item.quantity;
                                itemSubTotal = itemSubTotal.toFixed(2);

                                row = $("<div class='order_content p-0 py-1'><div class='mx-1'>" + item.name + " / " + item.category + " / " + item.quantity + " / " + item.price + " / [ P " + itemSubTotal + " ]</div><div class='status_container me-1' style='display:flex;align-items:center;'><p class='p-0 m-0'>" + item.status + "</p></div></div>");

                                dataContainer.append(row);

                                // if (item.status === 'serving') {
                                //     var button = '<button class="mx-1 done_button" data-item-order-id="' + item.order_item_id + '" >Done</button>';
                                // }
                                // else 
                                if (item.status === 'in queue' && data.payment_status !== "paid") {
                                    var button = '<button class="mx-1 remove_item" data-order-id="' + item.order_id + '" data-item-name="' + item.name + '" data-item-category="' + item.category + '" data-item-price="' + item.price + '" data-item-order-id="' + item.order_item_id + '" data-item-quantity="' + item.quantity + '" data-bs-toggle="modal" data-bs-target="#remove_item_modal">Remove</button>';
                                }

                                row.find('.status_container').append(button);
                            });
                        }
                    })
                    .catch(function(error) {
                        console.error(error);
                    })

            }

        });

        function printReceipt(orderId) {
            var printReceiptRoute = `{{ route('print-receipt') }}?order_id=${orderId}`;
            $.ajax({
                type: "get",
                url: printReceiptRoute,
                success: function(data) {
                    const receiptTab = window.open(printReceiptRoute);

                    receiptTab.load();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            })
        }

        $(document).on("click", "#viewReceiptButton", function() {
            var orderId = $(this).data("order-id");
            printReceipt(orderId);
        });

        $(document).on('click', '#completeButton', function() {
            var orderId = $(this).data('order-id');
            var dailyOrderId = $(this).data('daily-order-id');

            if (confirm("Do you want to mark as completed this order ID [" + dailyOrderId + "]?")) {
                $.ajax({
                    type: "get",
                    url: "{{ route('update-order-status-complete.cashier') }}",
                    data: {
                        order_id: orderId,
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        if (response) {
                            alert("Order ID [" + dailyOrderId + "] is completed.");
                            window.location.reload();
                        } else {
                            alert("Order ID [" + dailyOrderId + "] is failed to mark as complete.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        $(document).on('click', '.remove_item', function() {
            var orderId = $(this).data('order-id');
            var itemOrderId = $(this).data('item-order-id');
            var itemName = $(this).data('item-name');
            var itemCategory = $(this).data('item-category');
            var itemPrice = $(this).data('item-price');
            var itemCurrentQuantity = $(this).data('item-quantity');

            const removeItemModal = $('#remove_item_modal').children('.modal-dialog').children('.modal-content').find('#remove_modal_body');

            const itemInfoDisplay = removeItemModal.children('h5').find('#item_name_display');
            const currentItemQuantityDisplay = removeItemModal.children('h6').find('#current_item_quantity_display');
            itemInfoDisplay.text(itemName + " / " + itemCategory + " / " + itemPrice);
            currentItemQuantityDisplay.text(itemCurrentQuantity);

            const orderIdInput = removeItemModal.find('#order_id_input');
            const itemOrderIdInput = removeItemModal.find('#item_order_id_input');

            orderIdInput.val(orderId);
            itemOrderIdInput.val(itemOrderId);
        });

        $(document).on('click', '#close_remove_modal', function() {
            $('#remove_modal_body').children('#quantity_input').val('1');
        });

        $(document).on('click', '#remove_modal_button', function() {

            var removeModal = $(this).parent().siblings('#remove_modal_body');

            var orderId = parseInt(removeModal.find('#order_id_input').val());
            var itemOrderId = parseInt(removeModal.find('#item_order_id_input').val());
            var removeQuantity = parseInt(removeModal.find('#quantity_input').val());

            if (confirm('Are you sure you want to remove this?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('remove-item-to-order.cashier') }}",
                    data: {
                        order_id: orderId,
                        item_order_id: itemOrderId,
                        remove_quantity: removeQuantity,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        if (data.in_queue_status == true) {
                            alert("Item order quantity is successfully updated.");
                            window.location.reload();
                        } else {
                            alert('Item failed to cancel, \nmaybe the item is either preparing or serving.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

        });

        $("#search_item_input").on('input', function() {
            var searchValue = $(this).val();
            var modalResultContainer = $('#modal_result_container');

            $.ajax({
                type: "GET",
                url: "{{ route('search-item-name.cashier') }}",
                data: {
                    item_name: searchValue
                },
                success: function(data) {
                    // console.log(data);
                    modalResultContainer.empty();
                    if (data.length > 0) {
                        data.forEach(function(row) {

                            if (row.quantity == null) {
                                row.quantity = 0;
                            }

                            var modalOrderBox = $('<div class="container-fluid border-bottom border-secondary py-1 p-0 modal_order_box" id="modal_order_box"></div>');
                            var modalOrderBoxContent = $('<div class="container-fluid m-0">' + row.name + ' / ' + row.category + ' / ' + row.price + ' / [' + row.quantity + ']</div><div class=" d-flex quantity_input_container"><input type="number" id="result_quantity_input" class="result_quantity_input" min="1"><button class="me-1 add_item_button" id="add_item_button" data-item-id="' + row.item_id + '" data-item-name="' + row.name + '" data-category="' + row.category + '" data-item-price="' + row.price + '" data-item-quantity="' + row.quantity + '">ADD</button></div>');

                            modalResultContainer.append(modalOrderBox);
                            modalOrderBox.append(modalOrderBoxContent);
                        });
                    } else {
                        modalResultContainer.append('<p class="p-1">No matches...</p>')
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        })

        var addedItemArray = [];
        var totalAddedBill = 0.00;
        var subTotal = 0;

        function incrementQuantityOfToAddItem(itemId, addedItemRow, quantityValue) {
            quantityValue = parseInt(quantityValue);
            for (let i = 0; i < addedItemArray.length; i++) {
                if (addedItemArray[i].item_id === itemId) {
                    addedItemArray[i].quantity += quantityValue;

                    addedItemRow.parent().find("[data-item-id='" + addedItemArray[i].item_id + "']").val(addedItemArray[i].quantity);
                    var itemSubtotal = parseFloat(addedItemArray[i].price) * quantityValue;
                    subTotal += parseFloat(addedItemArray[i].price) * quantityValue;
                }
            }

            $('#subtotal_display').text(subTotal.toFixed(2));
        }

        $('#modal_result_container').on('click', '.add_item_button', function() {
            var itemId = $(this).data('item-id');
            var itemName = $(this).data('item-name');
            var itemCategory = $(this).data('category');
            var itemPrice = parseFloat($(this).data('item-price'));
            var orderId = $(this).parents('.modal-body').find('#modal_order_id').val();
            var itemQuantity = $(this).data('item-quantity');

            const modalOrderContainer = $('#modal_order_container');

            var quantityValue = $(this).siblings('input').val();

            $('.result_quantity_input').val('');

            if (quantityValue === '') {
                quantityValue = 1;
            } else {
                quantityValue = parseInt(quantityValue);
            }

            for (let i = 0; i < addedItemArray.length; i++) {
                if (addedItemArray[i].item_id == itemId) {
                    var itemArrayQuantity = addedItemArray[i].quantity;
                    var selectedItem = true;
                }
            }

            if (itemQuantity > 0) {
                if (quantityValue > itemQuantity) {
                    alert("Quantity must be less than or equal to " + itemQuantity + "!")
                } else {
                    if (!selectedItem) {
                        var itemSubtotal = itemPrice * quantityValue;
                        subTotal += itemPrice * quantityValue;

                        addedItemArray.push({
                            order_id: orderId,
                            item_id: itemId,
                            name: itemName,
                            category: itemCategory,
                            price: itemPrice,
                            quantity: quantityValue,
                            price: itemPrice,
                            subtotal: parseFloat(itemSubtotal.toFixed(2)),
                        });

                        $('#subtotal_display').text(subTotal.toFixed(2));

                        modalOrderContainer.append('<div class="container-fluid border-bottom border-secondary p-0 py-1" id="modal_order_box"><div class="container-fluid m-0">' + itemName + ' / ' + itemCategory + ' / ' + itemPrice + '</div><input type="number" data-item-id="' + itemId + '" min="1" id="added_item_input" me-1 text-center" value = "' + quantityValue + '" readonly><button class="me-1 remove_item_button" data-item-id="' + itemId + '" data-item-price="' + itemPrice + '" data-sub-total="' + itemSubtotal + '">X</button></div>');
                    } else {
                        var addedItemRow = $('#modal_order_box');
                        var newQuantity = itemArrayQuantity + quantityValue;

                        if (newQuantity > itemQuantity) {
                            alert(itemName + " / " + itemCategory + " reached the stock quantity limit!");
                        } else {
                            incrementQuantityOfToAddItem(itemId, addedItemRow, quantityValue);
                        }
                    }
                }
            } else {
                alert('Sorry, Item cannot add because the stock is ' + itemQuantity + '.');
            }
        })

        $('#order_container').on('click', '.add_order_button', function() {
            var orderId = $(this).data('order-id');
            var dailyOrderid = $(this).data('daily-order-id');

            toAddItemOrderId = orderId;
            dailyOrderId = dailyOrderid;
            $('#modal_order_id').val(orderId);
        });

        $('#modal_order_container').on('click', '.remove_item_button', function() { //this event will execute if the class = 'remove_item_button' is clicked, this is the remove item button (X) in modal
            var itemId = $(this).data('item-id'); //get the value of data-item-id in this button
            var itemPrice = $(this).data('item-price');
            var itemQuanity = $(this).siblings("#added_item_input").val();

            var itemTotalSubtotal = itemPrice * itemQuanity;

            subTotal = subTotal - itemTotalSubtotal;
            $('#subtotal_display').text(subTotal);

            addedItemArray = addedItemArray.filter(function(item) {
                return item.item_id !== itemId; //will remove the array with matching itemId
            });

            $(this).parent().remove(); //will remove the id='model_order_box'
        })

        $(document).on('click', '#close_modal_button', function() { //this event will execeute if the close button in the modal is clicked
            addedItemArray = []; //set the array to empty
            totalAddedBill = 0; //set the totalAddedBill to 0
            subTotal = 0.00;

            $('#subtotal_display').text(subTotal);
            $('#search_item_input').val(''); //remove the value inside the id='search_item_input' input
            $('#modal_result_container').empty(); //remove the html content inside the id = 'modal_result_container'
            $('#modal_order_container').empty(); //remove the html content inside the id = 'modal_order_container'
        });

        var toAddItemOrderId = 0 //inititate 0 value
        var dailyOrderId;

        //ADD button with id='add_new_item_button' on add item modal will add the items from addedItemArray to the order
        $(document).on('click', '#add_new_item_button', function() {
            subTotal = subTotal.toFixed(2);
            if (addedItemArray.length > 0) {

                if (confirm("Do you want to add these item/s ?")) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('add-new-item-to-order.cashier') }}",
                        data: {
                            subtotal: subTotal,
                            order_id: toAddItemOrderId,
                            added_items: addedItemArray,
                        },
                        success: function(data) {
                            // console.log(data);
                            alert("Item/s successfully added to Order ID [" + dailyOrderId + "]");
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            } else {
                alert("Please add item to proceed.");
            }
        })
    });
</script>
@endsection