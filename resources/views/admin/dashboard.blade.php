@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    .boxContainer {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 10px;
    }

    .box {
        width: 15vw;
        min-height: 15vh;
        color: #fff;
        border-radius: 5px;
    }

    .boxTitle {
        padding: 10px;
        letter-spacing: 2px;
        text-align: center;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Dashboard</h3>
        </div>
        <div class="container-fluid m-0 p-3 boxContainer">
            <div class="box" style="background-color: #0d0d0d;">
                <div class="boxTitle">Todays Total Order</div>
                <hr class="m-0">
                <div id="totalOrderDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
            <div class="box" style="background-color: #1a1a1a;">
                <div class="boxTitle">Dine In Orders</div>
                <hr class="m-0">
                <div id="dineInOrderDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
            <div class="box" style="background-color: #262626;">
                <div class="boxTitle">Take Out Orders</div>
                <hr class="m-0">
                <div id="takeOutOrderDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
            <div class="box" style="background-color: #333333;">
                <div class="boxTitle">Items In Menu</div>
                <hr class="m-0">
                <div id="itemsInMenuDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
            <div class="box" style="background-color: #404040;">
                <div class="boxTitle">Items With Stock</div>
                <hr class="m-0">
                <div id="itemsWithStockDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
            <div class="box" style="background-color: #4d4d4d;">
                <div class="boxTitle">In Queue Order</div>
                <hr class="m-0">
                <div id="inQueueOrderDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
            <div class="box" style="background-color: #595959;">
                <div class="boxTitle">Preparing Order</div>
                <hr class="m-0">
                <div id="preparingOrderDisplay" style="font-size:x-large;text-align:center;">-</div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        function displayInfoOutput(data) {
            $("#totalOrderDisplay").text(data.todayTotalOrder);
            $("#dineInOrderDisplay").text(data.dineInOrder);
            $("#takeOutOrderDisplay").text(data.takeOutOrder);
            $("#itemsInMenuDisplay").text(data.itemsInMenu);
            $("#itemsWithStockDisplay").text(data.itemsWithStock);
            $("#inQueueOrderDisplay").text(data.inQueueOrders);
            $("#preparingOrderDisplay").text(data.preparingOrders);
        }

        function fetchAndDisplayData() {
            $.getScript("{{ asset('js/dashboardInfo.js') }}", () => {
                const dashboardInfoRoute = "{{ route('dashboard-info.admin') }}";
                dashboardInfo(dashboardInfoRoute)
                    .then((data) => {
                        displayInfoOutput(data);
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        fetchAndDisplayData();
        setInterval(fetchAndDisplayData, 5000);
    });
</script>
@endsection