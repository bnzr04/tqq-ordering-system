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

    .data_container {
        max-height: 150px;
        overflow-y: auto;
    }

    .order_content {
        background-color: #ffffff;
        padding: 0 10px;
        color: black;
        border-bottom: #000 1px solid;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order_box {
        display: flex;
        align-items: center;
        letter-spacing: 2px;
        justify-content: space-between;
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

    #print_button {
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
            <a class="btn btn-dark" href="{{ route('make-order.admin') }}" title="Make New Order">Make Order +</a>
        </div>
        <div class="container-fluid m-0 p-2 border-top border-bottom" style="display: flex;align-items:center">
            <!-- <a href="" class="btn btn-dark mx-1">This Day</a>
            <a href="" class="btn btn-dark mx-1">This Week</a>
            <a href="" class="btn btn-dark mx-1">This Month</a>
            <form class="mx-2 p-1" style="display: flex;align-items:center;">
                <label for="date_from" class="m-0" style="letter-spacing: 2px;">From</label>
                <input type="date" class="form-control m-0 mx-1" id="date_from" required>
                <label for="date_to" class="m-0" style="letter-spacing: 2px;">To</label>
                <input type="date" class="form-control m-0 mx-1" id="date_to" required>
                <button class="p-2 rounded">Filter</button>
            </form> -->
            <div class="container-fluid m-0 p-1">
                <button class="btn btn-dark mx-1" id="all-btn">All</button>
                <button class="btn btn-dark mx-1" id="in-queue-btn">In Queue</button>
                <button class="btn btn-dark mx-1" id="preparing-btn">Preparing</button>
                <button class="btn btn-dark mx-1" id="now-serving-btn">Serving</button>
                <button class="btn btn-dark mx-1" id="completed-btn">Completed</button>
            </div>
            <div class="container-fluid m-0 p-0" style="display: flex;align-items:center;">
                <label for="date_from" class="m-0" style="letter-spacing: 2px;">From</label>
                <input type="date" class="form-control m-0 mx-1" id="date_from" required>
                <label for="date_to" class="m-0" style="letter-spacing: 2px;">To</label>
                <input type="date" class="form-control m-0 mx-1" id="date_to" required>
                <button class="p-2 rounded" id="filter_date_button">Filter</button>
            </div>
        </div>
        <div class="container-fluid m-0 p-2">
            <div class="containter-fluid mx-3 my-1 p-0">
                <h4 class="m-0 p-0 fw-bold" style="letter-spacing: 4px;" id="table-title">Current Orders</h4>
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

                <label for="quantity_input">Quantity</label>
                <input type="number" id="quantity_input" class="form-control border-secondary text-center" value="1">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close_remove_modal">Close</button>
                <button type="button" class="btn btn-dark" id="remove_modal_button">Remove Item</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const tableBody = $('#table-body');
        const tableTitle = $("#table-title");
        const orderContainer = $("#order_container");
        const allBtn = $('#all-btn');
        const inQueueBtn = $('#in-queue-btn');
        const preparingBtn = $('#preparing-btn');
        const nowServingBtn = $('#now-serving-btn');
        const completedBtn = $('#completed-btn');

        function orders() {
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // console.log(data);
                    orderContainer.html('');

                    if (data.length > 0) {
                        data.forEach(function(row) {
                            var itemsCount = row.ordered_items.length;
                            // var data = "";

                            var mainContainer = $("<div class='main'></div>");
                            var dataContainer = $("<div class='data_container hide'></div>");

                            var clickableContainer = $("<div data-order-id='" + row.order_id + "' class='order_box hide border-bottom border-secondary text-white shadow p-1'></div>");

                            var textOrder = $("<div class='text_order_container'></div>");

                            var clickableContent = $("<div style='border-right:white 2px solid;padding: 0 10px'><b>Time: </b>" + row.formatDate + "</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Order ID </b>[" + row.daily_order_id + "]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Table # </b>[" + row.table_number + "]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Items </b>[" + itemsCount + "]</div><div style='border-right:white 2px solid;padding: 0 10px' class='order_box_status'><b>Status </b>[<span>" + row.order_status + "</span>]</div><div style='border-right:white 2px solid;padding: 0 10px'><b>Amount </b>[" + row.total_amount + "]</div>");

                            var addItemButton = $("<button type='button' class='add_order_button' data-bs-toggle='modal' data-order-id='" + row.order_id + "' data-bs-target='#add_item_modal'>ADD</button>")

                            var printBillButton = $("<button type='button' id='print_button'><img src='{{ asset('/icons/icons8-print-32.png')}}'  width='20px'>");

                            var serveAllButton = $("<button type='button'>SERVE</button>");

                            var cancelOrderButton = $("<button type='button'>CANCEL</button>");

                            var arrowContainer = $("<div class='arrow_container border' data-order-id='" + row.order_id + "'><img src='{{ asset('/icons/play.png') }}' width='15px'></div>");

                            mainContainer.append(clickableContainer);
                            mainContainer.append(dataContainer);
                            clickableContainer.append(textOrder);
                            textOrder.append(clickableContent);
                            textOrder.append(addItemButton);

                            if (row.order_status == 'serving') {
                                textOrder.append(serveAllButton);
                            } else if (row.order_status == 'in queue') {
                                textOrder.append(cancelOrderButton);
                            }

                            textOrder.append(printBillButton);

                            clickableContainer.prepend(arrowContainer);
                            // dataContainer.append(data);

                            orderContainer.append(mainContainer);
                            // data.empty();
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

        var url = "{{ route('fetch-orders.admin') }}";
        var title = "All Orders";
        orders();

        allBtn.on('click', function() {
            url = "{{ route('fetch-orders.admin') }}";
            title = "All Orders";
            orders();
        });

        inQueueBtn.on('click', function() {
            url = "{{ route('fetch-orders.admin') }}?in-queue=1";
            title = "In Queue Orders";
            orders();
        });

        preparingBtn.on('click', function() {
            url = "{{ route('fetch-orders.admin') }}?preparing=1";
            title = "Preparing Orders";
            orders();
        });

        nowServingBtn.on('click', function() {
            url = "{{ route('fetch-orders.admin') }}?now-serving=1";
            title = "Serving Orders";
            orders();
        });

        completedBtn.on('click', function() {
            url = "{{ route('fetch-orders.admin') }}?completed=1";
            title = "Completed Orders";
            orders();
        });


        $('#filter_date_button').on('click', function(event) {
            event.preventDefault();

            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();

            if (dateFrom !== '' && dateTo !== '') {
                alert(dateFrom + " " + dateTo);
            } else {
                // $('#date_from').addClass('border');
                // $('#date_from').addClass('border-danger');
                $('#date_from').focus();
                // $('#date_to').addClass('border');
                // $('#date_to').addClass('border-danger');
                $('#date_to').focus();
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

                $.ajax({
                    type: 'GET',
                    url: "{{ route('fetch-ordered-items.admin') }}",
                    data: {
                        order_id: orderId,
                    },
                    success: function(data) {
                        // console.log(data);
                        var row = "";
                        dataContainer.empty();

                        var orderStatus = data.order_status;

                        main.find('.order_box_status').children('span').text(orderStatus.toLowerCase());

                        if (data.ordered_items.length > 0) {
                            data.ordered_items.forEach(function(item) {
                                row = $("<div class='order_content p-0 py-1'><div class='mx-1'>" + item.name + " / " + item.category + " / " + item.quantity + "</div><div class='status_container me-1' style='display:flex;align-items:center;'><p class='p-0 m-0'>" + item.status + "</p></div></div>");

                                dataContainer.append(row);

                                if (item.status === 'serving') {
                                    var button = '<button class="mx-1 done_button" data-item-order-id="' + item.order_item_id + '" >Done</button>';
                                } else if (item.status === 'in queue') {
                                    var button = '<button class="mx-1 remove_item" data-order-id="' + item.order_id + '" data-item-name="' + item.name + '" data-item-order-id="' + item.order_item_id + '" data-item-quantity="' + item.quantity + '" data-bs-toggle="modal" data-bs-target="#remove_item_modal">Remove</button>';
                                }

                                row.find('.status_container').append(button);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                })
            }

        });

        $(document).on('click', '.done_button', function() {
            var itemOrderId = $(this).data('item-order-id');
            var statusContainer = $(this).parent();

            $.ajax({
                type: "POST",
                url: "{{ route('update-item-status-complete.admin') }}",
                data: {
                    item_order_id: itemOrderId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    if (data == true) {
                        // console.log(data)
                        statusContainer.children('p').text('completed');
                        statusContainer.children('button').remove();
                    } else {
                        alert('Failed to update item status.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $(document).on('click', '#close_remove_modal', function() {
            $('#remove_modal_body').children('input').val('1');
        });

        $(document).on('click', '.remove_item', function() {
            var orderId = $(this).data('order-id');
            var itemOrderId = $(this).data('item-order-id');
            var itemName = $(this).data('item-name');
            var itemQuantity = $(this).data('item-quantity');
            var statusContainer = $(this).parent();

            $('#order_id_input').val(orderId);
            $('#item_order_id_input').val(itemOrderId);
            $('#item_name_display').text(itemName);
            $('#quantity_input').attr('max', itemQuantity);

            // $.ajax({
            //     type: "POST",
            //     url: "{{ route('remove-item-to-order.admin') }}",
            //     data: {
            //         order_id: orderId,
            //         item_order_id: itemOrderId,
            //         _token: $('meta[name="csrf-token"]').attr('content'),
            //     },
            //     success: function(data) {
            //         console.log(data)
            //         if (data.in_queue_status == true) {
            //             statusContainer.children('p').text('canceled');
            //             statusContainer.children('button').remove();


            //         } else {
            //             alert('Item failed to cancel, \nmaybe the item is either preparing or serving.');
            //         }
            //     },
            //     error: function(xhr, status, error) {
            //         console.log(xhr.responseText);
            //     }
            // });
        });

        $(document).on('click', '#remove_modal_button', function() {

            var removeModal = $(this).parent().siblings('#remove_modal_body');

            var orderId = parseInt(removeModal.find('#order_id_input').val());
            var itemOrderId = parseInt(removeModal.find('#item_order_id_input').val());
            var removeQuantity = parseInt(removeModal.find('#quantity_input').val());

            // console.log(orderId)
            // console.log(itemOrderId)
            // console.log(removeQuantity)

            if (confirm('Are you sure you want to remove this?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('remove-item-to-order.admin') }}",
                    data: {
                        order_id: orderId,
                        item_order_id: itemOrderId,
                        remove_quantity: removeQuantity,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        // console.log(data)
                        if (data.in_queue_status == true) {
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
                url: "{{ route('search-item-name.admin') }}",
                data: {
                    item_name: searchValue
                },
                success: function(data) {
                    // console.log(data);
                    modalResultContainer.empty();
                    if (data.length > 0) {
                        data.forEach(function(row) {

                            var modalOrderBox = $('<div class="container-fluid border-bottom border-secondary py-1 p-0 modal_order_box" id="modal_order_box"></div>');
                            var modalOrderBoxContent = $('<div class="container-fluid m-0">' + row.name + ' / ' + row.category + ' / ' + row.price + '</div><div class=" d-flex quantity_input_container"><input type="number" id="result_quantity_input" class="result_quantity_input" min="1"><button class="me-1 add_item_button" id="add_item_button" data-item-id="' + row.item_id + '" data-item-name="' + row.name + '" data-category="' + row.category + '" data-item-price="' + row.price + '">ADD</button></div>');

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
        var totalAddedBill = 0;
        var subTotal = 0;

        $('#modal_result_container').on('click', '.add_item_button', function() {
            var itemId = $(this).data('item-id');
            var itemName = $(this).data('item-name');
            var itemCategory = $(this).data('category');
            var itemPrice = parseInt($(this).data('item-price'));
            var orderId = $(this).parents('.modal-body').find('#modal_order_id').val();

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
                    var selectedItem = true;
                }
            }


            if (selectedItem !== true) {
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
                    subtotal: itemSubtotal,
                });

                $('#subtotal_display').text(subTotal);

                modalOrderContainer.append('<div class="container-fluid border-bottom border-secondary p-0 py-1" id="modal_order_box"><div class="container-fluid m-0">' + itemName + ' / ' + itemCategory + ' / ' + itemPrice + '</div><input type="number" min="1" class="added_item_input me-1 text-center" value = "' + quantityValue + '" readonly><button class="me-1 remove_item_button" data-item-id="' + itemId + '" data-sub-total="' + itemSubtotal + '">X</button></div>');
            } else {
                alert('Item is already selected!');
            }
        })

        $('#order_container').on('click', '.add_order_button', function() {
            var orderId = $(this).data('order-id');

            toAddItemOrderId = orderId;
            $('#modal_order_id').val(orderId)
        });

        $('#modal_order_container').on('click', '.remove_item_button', function() { //this event will execute if the class = 'remove_item_button' is clicked, this is the remove item button (X) in modal
            var itemId = $(this).data('item-id'); //get the value of data-item-id in this button
            var subtotal = $(this).data('sub-total');

            subTotal = subTotal - subtotal;
            $('#subtotal_display').text(subTotal);

            addedItemArray = addedItemArray.filter(function(item) {
                return item.item_id !== itemId; //will remove the array with matching itemId
            })

            $(this).parent().remove(); //will remove the id='model_order_box'
        })

        $(document).on('click', '#close_modal_button', function() { //this event will execeute if the close button in the modal is clicked
            addedItemArray = []; //remove all the array inside the addedItemArray 
            totalAddedBill = 0;
            subTotal = 0;
            $('#subtotal_display').text(subTotal);
            $('#search_item_input').val(''); //remove the value inside the id='search_item_input' input
            $('#modal_result_container').empty(); //remove the html content inside the id = 'modal_result_container'
            $('#modal_order_container').empty(); //remove the html content inside the id = 'modal_order_container'
        });

        var toAddItemOrderId = 0 //inititate 0 value

        $(document).on('click', '#add_new_item_button', function() { //if the button id = 'add_new_item_button' is clicked

            $.ajax({
                type: "GET",
                url: "{{ route('add-new-item-to-order.admin') }}",
                data: {
                    subtotal: subTotal,
                    order_id: toAddItemOrderId,
                    added_items: addedItemArray,
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        })
    });
</script>
@endsection