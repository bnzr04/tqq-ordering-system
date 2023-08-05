@extends('layouts.app')
@section('content')
<style>
    /* .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    } */

    .content {
        position: absolute;
        height: calc(100% - 46px);
    }

    .rowIsSelected {
        border: rgb(0, 219, 43) 2px solid;
        box-shadow: 0 0 20px rgb(0, 219, 43);
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <!-- <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div> -->
    <div class="container-fluid m-0 p-0" style="height:100vh;position:relative">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Kitchen</h3>
        </div>
        <div class="container-fluid m-0 p-2 border content">
            <div class="container-fluid m-0 p-0 border" style="height: 46vh;overflow-y:auto;">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr style="position: sticky;top:0;">
                            <th scope="col">Time Order</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">Table #</th>
                            <th scope="col">Order Status</th>
                            <th scope="col">Items</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>
            </div>
            <div class="container-fluid m-0 p-1 border" style="height: 44vh;overflow-y:auto;letter-spacing:3px;">
                <div class="container-fluid m-0 p-0 d-flex" style="align-items: center;">
                    <h4 class="m-0">Ordered Items</h4>
                    <div class="m-0 p-1 d-flex">
                        <h5 class="m-0 p-1 mx-4 bg-white">Order ID: <span id="order_id_display"></span></h5>
                        <h5 class="m-0 p-1 mx-4 bg-white">Table #: <span id="table_number_display"></span></h5>
                        <h5 class="m-0 p-1 mx-4 bg-white">Items: <span id="item_number_display"></span></h5>
                        <h5 class="m-0 p-1 mx-4 bg-white">Status: <span id="order_status_display"></span></h5>
                    </div>
                </div>
                <div class="container-fluid p-0 border d-flex" style="height: 70%;overflow-y:auto;">
                    <ol id="items_display">

                    </ol>
                </div>
                <div class="container-fluid p-2" style="display: flex;justify-content:center;" id="button_container">
                    <!-- <button class="bg-warning px-3 mx-1" style="min-width: 200px">Accept</button>
                    <button class="bg-success px-3 mx-1 text-white" style="min-width: 200px">Done</button> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--New Order Sound notificaition -->
<audio id="notificationSound" muted>
    <source src="{{ asset('/sound/ill-make-it-possible-notification.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<script>
    $(document).ready(function() {

        function playNotificationSound() {
            $('#notificationSound')[0].muted = false;
            const audio = $('#notificationSound')[0];
            audio.play();
        }

        var previousCount = 0;

        //this function will fetch the orders 
        function fetchOrders() {
            $.ajax({
                method: "GET",
                url: "{{ route('fetch-kitchen-orders.admin') }}",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#table-body').empty();

                    var currentCount = data.length;

                    if (currentCount > previousCount) {
                        playNotificationSound();
                    }

                    previousCount = currentCount;

                    if (data.length > 0) {
                        data.forEach(function(row) {

                            $('#table-body').append("<tr id='order_row'><td>" + row.order_time + "</td><td>" + row.order_id + "</td><td>" + row.table_number + "</td><td>" + row.order_status + "</td><td>" + row.item_count + "</td><td><button class='px-3' id='view_button' data-order-id='" + row.order_id + "' data-table-number='" + row.table_number + "' data-item-count='" + row.item_count + "'>View</button></td></tr>");
                        });
                    } else {
                        $('#table-body').append('<tr><td colspan="6">No orders at the moment...</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        fetchOrders(); //execute the fetchOrder function
        setInterval(fetchOrders, 10000); //the fetchOrders function will execute every 10 seconds

        var selectedRow = null; // Variable to store the previously selected row

        function rowSelected(orderRow) {
            orderRow.addClass("rowIsSelected");

            if (selectedRow !== orderRow) {
                // Remove the class 'rowIsSelected' from the previously selected row
                if (selectedRow) {
                    selectedRow.removeClass("rowIsSelected");
                }

                // Update the selectedRow variable to the current row
                selectedRow = orderRow;
            }
        }

        $('#table-body').on('click', '#view_button', function() {
            var orderRow = $(this).closest('tr');

            rowSelect = false;

            var orderId = $(this).data('order-id');
            var tableNumber = $(this).data('table-number');
            var itemCount = $(this).data('item-count');
            $("#items_display").text("");

            var clickedButton = $(this);


            $.ajax({
                type: "GET",
                url: "{{ route('fetch-ordered-items.admin') }}",
                data: {
                    order_id: orderId
                },
                success: function(data) {
                    // orderRow.css('border', 'rgb(0, 219, 43) 2px solid');
                    // orderRow.css('box-shadow', '0 0 20px rgb(0, 219, 43)');

                    rowSelected(orderRow);

                    $('#button_container').empty();

                    // console.log(data.order_status);
                    $('#order_status_display').text(data.order_status);

                    $('#order_id_display').text(orderId);
                    $('#table_number_display').text(tableNumber);
                    $('#item_number_display').text(itemCount);

                    if (data.ordered_items.length > 0) {
                        data.ordered_items.forEach(function(row) {
                            $("#items_display").append("<li style='font-size:16px'>" + row.name + " / " + row.category + " / " + row.quantity + " <input type='checkbox' name='done' id=''></li>")
                        });
                    } else {
                        $('#items_display').append('<p >No Items Ordered...</p>');
                    }

                    if (data.order_status == 'In Queue') {
                        $('#button_container').append('<button id="accept_button" class="bg-warning px-3 mx-1" style="min-width: 200px" data-order-id="' + orderId + '">Accept</button>');
                    } else if (data.order_status == 'Preparing') {
                        $('#button_container').append('<button id="done_button" class="bg-success px-3 mx-1 text-white" style="min-width: 200px" data-order-id="' + orderId + '">Done</button>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });


        $("#button_container").on('click', '#accept_button', function() {
            var orderId = $(this).data('order-id');

            $.ajax({
                type: "POST",
                url: "{{ route('update-status-preparing.admin') }}",
                data: {
                    order_id: orderId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    // console.log(data);
                    fetchOrders();
                    $('#order_status_display').text(data.order_status);
                    $('#button_container').empty();
                    $('#button_container').append('<button id="done_button" class="bg-success px-3 mx-1 text-white" style="min-width: 200px" data-order-id="' + orderId + '">Done</button>');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $("#button_container").on('click', '#done_button', function() {
            var orderId = $(this).data('order-id');

            $.ajax({
                type: "POST",
                url: "{{ route('update-status-now-serving.admin') }}",
                data: {
                    order_id: orderId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    // console.log(data);
                    fetchOrders();
                    $('#items_display').empty();
                    $('#order_status_display').empty();
                    $('#order_id_display').empty();
                    $('#table_number_display').empty();
                    $('#item_number_display').empty();
                    $('#button_container').empty();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection