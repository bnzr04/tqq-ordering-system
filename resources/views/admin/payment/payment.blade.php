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
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0" style="height:100vh;position:relative">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Payment</h3>
        </div>
        <div class="container-fluid m-0 p-2 content">
            <div class="container-fluid m-0 p-0 border border-dark mt-3" style="max-height: 90%;overflow-y:auto">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr style="position: sticky;top:0;">
                            <th scope="col">Order Time</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">Table #</th>
                            <th scope="col">Order Type</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Order Status</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="table_body">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const tableBody = $('#table_body'); //initiate id = table_body

        //this function will retrieve the unpaid orders
        function fetchUnpaidOrders() {
            $.ajax({
                type: "GET",
                url: "{{ route('fetch-unpaid-orders.admin') }}",
                success: function(data) {
                    console.log(data);

                    if (data.length > 0) {
                        data.forEach(function(row) {
                            tableBody.append('<tr><th scope="row">' + row.order_time + '</th><td>' + row.order_id + '</td><td>' + row.table_number + '</td><td>' + row.order_type + '</td><th>' + row.payment_status + '</th><td>' + row.order_status + '</td><td>' + row.total_amount + '</td><td><button type="button">Payment</button></td></tr>')
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        }

        fetchUnpaidOrders()
    });
</script>
@endsection