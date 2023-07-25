@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
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
                <button class="btn btn-dark mx-1" id="now-serving-btn">Now Serving</button>
                <button class="btn btn-dark mx-1" id="completed-btn">Completed</button>
            </div>
            <div class="container-fluid m-0 p-0">
                <form style="display: flex;align-items:center;">
                    <label for="date_from" class="m-0" style="letter-spacing: 2px;">From</label>
                    <input type="date" class="form-control m-0 mx-1" id="date_from" required>
                    <label for="date_to" class="m-0" style="letter-spacing: 2px;">To</label>
                    <input type="date" class="form-control m-0 mx-1" id="date_to" required>
                    <button class="p-2 rounded">Filter</button>
                </form>
            </div>
        </div>
        <div class="container-fluid m-0 p-2">
            <div class="containter-fluid mx-3 my-1 p-0">
                <h4 class="m-0 p-0 fw-bold" style="letter-spacing: 4px;" id="table-title">Current Orders</h4>
            </div>
            <div class="container-fluid p-0 border table-container" style="max-height: 65vh;overflow-y:auto">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr class="table-dark" style="position: sticky;top:0;">
                            <th scope="col" class="border">Time Order</th>
                            <th scope="col" class="border">Order ID</th>
                            <th scope="col" class="border">Table #</th>
                            <th scope="col" class="border">Order Type</th>
                            <th scope="col" class="border">Payment Status</th>
                            <th scope="col" class="border">Order Status</th>
                            <th scope="col" class="border">Total Amount</th>
                            <th scope="col" class="border"></th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    const tableBody = document.getElementById('table-body');
    const tableTitle = document.getElementById("table-title");

    const allBtn = document.getElementById('all-btn');
    const inQueueBtn = document.getElementById('in-queue-btn');
    const preparingBtn = document.getElementById('preparing-btn');
    const nowServingBtn = document.getElementById('now-serving-btn');
    const completedBtn = document.getElementById('completed-btn');

    function orders() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                // console.log(data);
                tableBody.innerHTML = "";
                tableTitle.innerHTML = title;

                if (data.length > 0) {
                    data.forEach(function(row) {
                        tableBody.innerHTML += "<tr><td class='border'>" + row.formatDate + "</td><td class='border'>" + row.order_id + "</td><td class='border'>" + row.table_number + "</td><td class='border'>" + row.order_type + "</td><td class='border'>" + row.payment_status + "</td><td class='border'>" + row.order_status + "</td><td class='border'>" + row.total_amount + "</td><td class='border'><button>VIEW</button></td></tr>";
                    });
                } else {
                    tableBody.innerHTML = "<tr><td colspan='8'>No orders...</td></tr>";
                }

            }
        };
        xhr.send();
    }

    var url = "{{ route('fetch-orders.admin') }}";
    var title = "All Orders";
    orders();

    allBtn.addEventListener('click', function() {
        url = "{{ route('fetch-orders.admin') }}";
        title = "All Orders";
        orders();
    });

    inQueueBtn.addEventListener('click', function() {
        url = "{{ route('fetch-orders.admin') }}?in-queue=1";
        title = "In Queue Orders";
        orders();
    });

    preparingBtn.addEventListener('click', function() {
        url = "{{ route('fetch-orders.admin') }}?preparing=1";
        title = "Preparing Orders";
        orders();
    });

    nowServingBtn.addEventListener('click', function() {
        url = "{{ route('fetch-orders.admin') }}?now-serving=1";
        title = "Serving Orders";
        orders();
    });

    completedBtn.addEventListener('click', function() {
        url = "{{ route('fetch-orders.admin') }}?completed=1";
        title = "Completed Orders";
        orders();
    });
</script>
@endsection