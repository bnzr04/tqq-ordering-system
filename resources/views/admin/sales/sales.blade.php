@extends('layouts.app')
@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    .content {
        position: relative;
        display: flex;
        flex-direction: column;
        height: calc(100% - 46px);
    }

    #table-container {
        height: 100%;
        max-height: 70vh;
        display: flex;
        position: relative;
        flex-direction: column;
    }

    .sales-box {
        min-width: 170px;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0" style="height:100%;position:relative">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Sales</h3>
        </div>
        <div class="container-fluid m-0 p-2 content">
            <div class="container-fluid m-0 p-0 d-flex">
                <div class="d-flex border rounded-1 m-0 mx-1 p-1">
                    <button class="btn btn-dark m-1" id="today_button">Today</button>
                    <button class="btn btn-dark m-1" id="yesterday_button">Yesterday</button>
                    <div class="mx-3 p-0" style="display: flex;align-items:center;">
                        <label for="date_input" class="m-0" style="letter-spacing: 2px;text-wrap:nowrap;">Filter date</label>
                        <input type="date" class="form-control m-0 mx-1" id="date_input" required>
                        <button class="p-2 rounded" id="filter_date_button">Filter</button>
                    </div>
                </div>
                <div class="d-flex border rounded-1 m-0 p-1" style="align-items: center;">
                    <div class="m-0 mx-1">
                        <input type="search" id="search_item_input" class="m-0 form-control" placeholder="Search item name..." style="width:300px">
                    </div>
                    <div class="m-0 mx-1 d-flex" style="align-items: center;letter-spacing:2px;">
                        <label for="select_category">Category</label>
                        <select name="category" id="select_category" class="form-select mx-1" style="width: 200px;">
                            <option value="">All</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="container-fluid d-flex m-0 my-1 p-1 bg-secondary rounded-1 text-white" style="letter-spacing: 3px;font-size:18px;justify-content:center;">
                <div class="p-1">
                    <p class="m-0">Date: <span id='sales_date_display'></span> <span id="day_identifier"></span> <span id="order_type_identifier"></span></p>
                </div>
            </div>

            <div class="container-fluid m-0 p-0" id="table-container">
                <div class="m-0 p-0 border border-secondary rounded-1" style="height: 100%;overflow-y:auto">
                    <table class="table table-dark table-striped" style="letter-spacing: 2px;text-align:center">
                        <thead id="sales_table_head">

                        </thead>
                        <tbody id="sales_table_body">

                        </tbody>
                    </table>
                </div>

                <div class="container-fluid m-0 p-1 d-flex">
                    <div class="mx-1 ms-0 p-2 bg-dark text-white text-center rounded-1 sales-box">
                        <div class="m-0 mb-1 p-0 d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Cash</h5>
                            <button style="width:20px;height:20px;display:flex;align-items:center;justify-content:center;"><img src="{{ asset('/icons/draw.png/') }}" width="11px"></button>
                        </div>
                        <h3 class="m-0"><span id="cash_display">-</span></h3>
                    </div>
                    <div class="mx-1 p-2 bg-primary text-white text-center rounded-1 sales-box">
                        <div class="m-0 mb-1 p-0 d-flex align-items-center justify-content-between">
                            <h5 class="m-0" style="font-size: 16px;">Dine In</h5>
                            <button style="width:20px;height:20px;display:flex;align-items:center;justify-content:center;" id="dine_in_filter_button"><img src="{{ asset('/icons/filter.png/') }}" style="width:10px;height:10px;"></button>
                        </div>
                        <h3 class="m-0"><span id="dine_in_display">-</span></h3>
                    </div>

                    <div class="mx-1 p-2 bg-danger text-white text-center rounded-1 sales-box">
                        <div class="m-0 mb-1 p-0 d-flex align-items-center justify-content-between">
                            <h5 class="m-0" style="font-size: 16px;">Take Out</h5>
                            <button style="width:20px;height:20px;display:flex;align-items:center;justify-content:center;" id="take_out_filter_button"><img src="{{ asset('/icons/filter.png/') }}" style="width:10px;height:10px;"></button>
                        </div>
                        <h3 class="m-0"><span id="take_out_display">-</span></h3>
                    </div>

                    <div class="mx-1 p-2 bg-success text-white text-center rounded-1 sales-box">
                        <div class="m-0 mb-1 p-0 d-flex align-items-center justify-content-between">
                            <h5 class="m-0" style="font-size: 16px;">Total Sales</h5>
                            <button style="width:20px;height:20px;display:flex;align-items:center;justify-content:center;" id="total_sales_filter_button"><img src="{{ asset('/icons/filter.png/') }}" style="width:10px;height:10px;"></button>
                        </div>
                        <h3 class="m-0"><span id="total_sales_display">-</span></h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        const salesDateDisplay = $('#sales_date_display');
        const dayDisplayIdentidier = $('#day_identifier');
        const orderTypeIdentidier = $('#order_type_identifier');
        const soldTableHead = $('#sales_table_head');
        const soldTableBody = $('#sales_table_body');

        const categorySelectDropdown = $('#select_category');
        const searchItemInput = $("#search_item_input");

        function fetchAllCategories() {
            $.ajax({
                type: 'get',
                url: "{{ route('fetch-categories.admin') }}",
                success: function(data) {
                    // console.log(data)

                    if (data.length > 0) {
                        data.forEach(function(option) {
                            categorySelectDropdown.append("<option value='" + option + "'>" + option + "</option>");
                        })
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        }

        fetchAllCategories();

        function fetchSoldItems() {
            $.ajax({
                type: 'get',
                url: mainUrl,
                success: function(data) {
                    // console.log(data)

                    salesDateDisplay.text(data.formatted_date);
                    dayDisplayIdentidier.text(dayIdentifierValue);
                    orderTypeIdentidier.text(orderTypeFilterSubUrl);

                    soldTableHead.empty();
                    soldTableBody.empty();
                    if (data.sold_items.length > 0) {
                        soldTableHead.append('<tr style="position: sticky;top: 0;border-bottom:white 1px solid"><th scope="col">ITEM</th><th scope="col">CATEGORY</th><th scope="col">DESCRIPTION</th><th scope="col">PRICE</th><th scope="col">SOLD</th><th scope="col">SUBTOTAL</th></tr>');
                        data.sold_items.forEach(function(row) {
                            soldTableBody.append("<tr><th class='border'>" + row.name + "</th><td class='border'>" + row.category + "</td><td class='border'>" + row.description + "</td><td class='border'>" + row.price + "</td><td class='border'>" + row.sold_quantity + "</td><td class='border'>" + row.subtotal + "</td></tr>");
                        });
                    } else {
                        soldTableBody.append("<tr><td colspan='5'>No items sold...</td></tr>");
                    }

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        }

        var mainUrl = "{{ route('fetch-sold-items.admin') }}?today=1";
        var fetchSoldItemsUrl = "{{ route('fetch-sold-items.admin') }}?today=1";
        let dayIdentifierValue = "(Today)";
        let orderTypeFilterSubUrl = "";
        fetchSoldItems();

        function displayTotalSales() {
            const cashDisplay = $("#cash_display");
            const dineInDisplay = $("#dine_in_display");
            const takeOutDisplay = $("#take_out_display");
            const totalSalesDisplay = $("#total_sales_display");

            $.ajax({
                type: "get",
                url: totalSalesUrl,
                success: function(data) {

                    var totalDineIn = data[0][0].total_dine_in;
                    var totalTakeOut = data[1][0].total_take_out;
                    var totalSales = data[2][0].total_sales;

                    if (totalDineIn === null) {
                        totalDineIn = "-";
                    }

                    if (totalTakeOut === null) {
                        totalTakeOut = "-";
                    }

                    if (totalSales === null) {
                        totalSales = "-";
                    }

                    dineInDisplay.text(totalDineIn);
                    takeOutDisplay.text(totalTakeOut);
                    totalSalesDisplay.text(totalSales);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        }

        var totalSalesUrl = "{{ route('get-sales-amount.admin') }}?today=1";
        displayTotalSales();

        const todayButton = $("#today_button");
        const yesterdayButton = $("#yesterday_button");
        const dateFilterButton = $("#filter_date_button");

        todayButton.on('click', function() {
            $("#date_input").val('');
            searchItemInput.val('');
            categorySelectDropdown.prop('selectedIndex', 0);

            fetchSoldItemsUrl = "{{ route('fetch-sold-items.admin') }}?today=1";
            dayIdentifierValue = "(Today)";
            orderTypeFilterSubUrl = "";
            mainUrl = fetchSoldItemsUrl;
            fetchSoldItems();

            totalSalesUrl = "{{ route('get-sales-amount.admin') }}?today=1";
            displayTotalSales();
        });

        yesterdayButton.on('click', function() {
            $("#date_input").val('');
            searchItemInput.val('');
            categorySelectDropdown.prop('selectedIndex', 0);

            fetchSoldItemsUrl = "{{ route('fetch-sold-items.admin') }}?yesterday=1";
            dayIdentifierValue = "(Yesterday)";
            orderTypeFilterSubUrl = "";
            mainUrl = fetchSoldItemsUrl;
            fetchSoldItems();

            totalSalesUrl = "{{ route('get-sales-amount.admin') }}?yesterday=1";
            displayTotalSales();
        });

        dateFilterButton.on('click', function() {
            var dateInputValue = $("#date_input").val();
            dayIdentifierValue = "";
            orderTypeFilterSubUrl = "";
            searchItemInput.val('');
            categorySelectDropdown.prop('selectedIndex', 0);

            if (dateInputValue !== "") {
                fetchSoldItemsUrl = "{{ route('fetch-sold-items.admin') }}?filter=" + dateInputValue;
                mainUrl = fetchSoldItemsUrl;
                fetchSoldItems();

                totalSalesUrl = "{{ route('get-sales-amount.admin') }}?filter=" + dateInputValue;
                displayTotalSales();
            } else {
                $("#date_input").focus();
            }

        });

        categorySelectDropdown.on('change', function() {
            var categoryValue = $(this).val();
            searchItemInput.val('');

            categoryValue = "&category=" + categoryValue;
            orderTypeFilterSubUrl = "";

            mainUrl = fetchSoldItemsUrl + categoryValue;
            fetchSoldItems();
        });

        searchItemInput.on('input', function() {
            var searchInputValue = $(this).val();

            searchInputValue = "&search=" + searchInputValue;
            orderTypeFilterSubUrl = "";

            mainUrl = fetchSoldItemsUrl + searchInputValue;
            fetchSoldItems();
        });

        const dineInFilterButton = $("#dine_in_filter_button");
        const takeOutFilterButton = $("#take_out_filter_button");
        const totalSalesFilterButton = $("#total_sales_filter_button");

        dineInFilterButton.on('click', function() {
            categorySelectDropdown.prop('selectedIndex', 0);
            var paymentStatus = "&order-type=dine-in";
            orderTypeFilterSubUrl = "(Dine-in)";

            mainUrl = fetchSoldItemsUrl + paymentStatus;
            fetchSoldItems();
        });

        takeOutFilterButton.on('click', function() {
            categorySelectDropdown.prop('selectedIndex', 0);
            var paymentStatus = "&order-type=take-out";
            orderTypeFilterSubUrl = "(Take-out)";

            mainUrl = fetchSoldItemsUrl + paymentStatus;
            fetchSoldItems();
        });

        totalSalesFilterButton.on('click', function() {
            categorySelectDropdown.prop('selectedIndex', 0);
            orderTypeFilterSubUrl = "";

            mainUrl = fetchSoldItemsUrl;
            fetchSoldItems();
        });


    });
</script>
@endsection