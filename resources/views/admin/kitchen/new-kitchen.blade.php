@extends('layouts.app')
@section('content')
<style>
    .content {
        position: absolute;
        height: calc(100% - 46px);
        overflow-y: auto;
        display: flex;
    }


    .container {
        min-width: 20vw;
        max-width: 20vw;
        display: flex;
        flex-direction: column;
        position: relative;
        background-color: #333333;
    }

    .list-container {
        position: relative;
        height: 100%;
        overflow-y: auto;
        text-align: center;
    }

    .container-footer {
        position: relative;
        bottom: 0;
        height: 10%;
    }

    .container-footer button {
        max-width: 100px;
        width: 100px;
        min-width: 70px;
        letter-spacing: 2px;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0" style="height:100vh;position:relative">
        <div class="container-fluid m-0 p-0 d-flex shadow" style="align-items: center;">
            <h3 class="m-0 p-2" style="letter-spacing: 2px;">Kitchen</h3>
            <h3 class="m-0 p-2">(<span id="queue_number">0</span>)</h3>
        </div>
        <div class="container-fluid m-0 p-0 content">



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

        function playNotificationSound() { //this function will play sound notification
            $('#notificationSound')[0].muted = false;
            const audio = $('#notificationSound')[0];
            audio.play();
        }

        var previousCount = 0; //initiate previousCount

        //this function will fetch the orders 
        function fetchOrders() {
            $.ajax({
                method: "GET",
                url: "{{ route('fetch-kitchen-orders.admin') }}",
                dataType: 'json',
                success: function(data) {
                    // console.log(data);

                    $('.content').empty(); //remove elements inside the content class

                    var orderNum = data.order.length //get the order array count

                    var currentCount = orderNum; //set the current count as the length of the order array

                    if (currentCount > previousCount) { //if the currentCount value  than the previousCount means there is new order;
                        playNotificationSound(); //play the sound notification
                    }

                    previousCount = currentCount; //store the currentCount value

                    if (data.order.length > 0) { //if the order array is not empty
                        $('#queue_number').text(orderNum); //change the text display to the current order array count

                        data.order.forEach(function(row) { //each order will create a container and its content

                            //this accept button and done button will initiate the html structure and inserted the order id and order status
                            const acceptButton = "<button class='bg-warning rounded-2' data-order-id='" + row.order_id + "' data-order-status='" + row.order_status + "' id='accept_button'>Accept</button>";
                            const doneButton = "<button class='bg-success rounded-2 text-white' data-order-id='" + row.order_id + "' data-order-status='" + row.order_status + "' id='done_button'>Done</button>";

                            const container = $("<div class='container border p-0 m-0 shadow'></div>");
                            const header = $("<div class='container-fluid m-0 p-2 border-bottom bg-dark text-white'></div>");
                            const itemListContainer = $("<div class='container-fluid p-0 text-white list-container item_list_display'></div>");

                            const footerContainer = $("<div class='container-fluid m-0 p-0 container-footer border-top d-flex' style='justify-content:center'></div>");
                            var controlButton = "";

                            if (row.order_status === 'in queue') { //if the order status is 'in queue'
                                controlButton = acceptButton; //the controlButton value is the accept button
                            } else if (row.order_status === 'preparing') { //else if the order status is 'preparing'
                                controlButton = doneButton; //the controlButton value is the done button
                            }

                            container.append(header); //insert the header html structure inside the container
                            container.append(itemListContainer); //""
                            container.append(footerContainer); //""
                            footerContainer.append(controlButton); //""

                            $('.content').append(container);

                            const currentItemContainer = container.find('.item_list_display'); // Find the item_list_display within the current container

                            currentItemContainer.empty();

                            row.ordered_items.forEach(function(item) {
                                const itemHTML = "<h6 class='m-0 p-2 border-bottom'>" + item.name + " / " + item.category + " / " + item.quantity + "</h6>";
                                currentItemContainer.append(itemHTML);
                            });
                        });

                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        fetchOrders(); //execute the fetchOrder function
        setInterval(fetchOrders, 10000); //the fetchOrders function will execute every 10 seconds

        const containerFooter = $('.container-footer')

        $('.content').on('click', '#accept_button', function() { //if the id accept_button is clicked
            var orderId = $(this).data('order-id'); //get the value of data-order-id in this accept_button

            var container = $(this).closest('.container-footer') //get the class=container-footer of this accept button

            $.ajax({
                type: "POST",
                url: "{{ route('update-status-preparing.admin') }}",
                data: {
                    order_id: orderId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    container.html('');
                    container.append("<button class='bg-success rounded-2 text-white' id='done_button' data-order-id='" + orderId + "'>Done</button>");
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('.content').on('click', '#done_button', function() {
            var orderId = $(this).data('order-id');
            var container = $(this).closest('.container');

            console.log(orderId);

            $.ajax({
                type: "POST",
                url: "{{ route('update-status-now-serving.admin') }}",
                data: {
                    order_id: orderId,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    // console.log(data);
                    $('#queue_number').text(data.order_count);
                    container.remove();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection