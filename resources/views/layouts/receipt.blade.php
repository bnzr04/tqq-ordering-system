<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/receipt.css') }}">
    <title>Bill Receipt</title>
</head>

<body>
    <div class="main-container">
        <div class="header">
            <p>TING QUA QUA</p>
            <p>RESTAURANT</p>
        </div>
        <div class="time-container">
            <p id="dateDisplay"></p>
            <p id="timeDisplay"></p>
        </div>
        <div class="order-details">
            <p>Order ID: <span id="orderIdDisplay">{{ $order_id }}</span> [<span id="dailyOrderIdDisplay">{{ $daily_order_id }}</span>]</p>
            <p>For: <span id="orderTypeDisplay">{{ $order_type }}</span></p>
            <p>Table #: <span id="tableNumberDisplay">{{ $table_number }}</span></p>
            <p>Cashier: <span id="cashierNameDisplay">{{ $cashier_name }}</span></p>
        </div>
        <div class="amount-container">
            <p>Cash: <span id="cashDisplay">{{ $cash }}</span></p>
            <p>Change: <span id="changeDisplay">{{ $change }}</span></p>
            <h5>AMOUNT: <span id="amountDisplay">{{ $amount }}</span></h5>
        </div>
        <div class="items-container">
            <p><span id="items-number">{{ $itemCount }}</span> Item(s)</p>
            <table>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }} - {{ $item->category }} - {{ $item->quantity }} - @ {{ $item->price }}</td>
                    <td>{{ $item->subtotal }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    @if($print === false)
    <script src="{{ asset('js/receiptTime.js') }}"></script>
    @endif
</body>

</html>