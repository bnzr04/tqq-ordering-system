@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
        min-height: 100vh;
    }

    #content-container {
        display: grid;
        grid-template-areas:
            'menu menu menu orders '
            'menu menu menu orders '
            'menu menu menu control';
        gap: 10px;
        min-height: 80vh;
    }

    #menu-container {
        grid-area: menu;
    }

    #orders-container {
        grid-area: orders;
    }

    #control-container {
        grid-area: control;
    }

    #sub-menu {
        height: 100%;
        flex-wrap: wrap;
    }

    .layout {
        display: grid;
        grid-template-rows: repeat(3, 1fr);
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        overflow-x: auto;
    }

    .layout button {
        letter-spacing: 2px;
    }
</style>
<div class="container-fluid p-0 m-0 grid-container">
    <div class="container-fluid m-0 p-0">
        @include('layouts.sidebar')
    </div>
    <div class="container-fluid m-0 p-0">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Make Order</h3>
        </div>
        <div class="container-fluid m-0 p-2">
            <a href="{{ route('orders.admin') }}" class="btn btn-dark">Back</a>
        </div>
        <div class="container-fluid m-0 p-2" id="content-container">
            <div class="container-fluid m-0 p-2 border shadow rounded-3 shadow" id="menu-container">
                <div class="container-fluid p-2 m-0 border border-secondary" id="sub-menu">
                    <section class="layout">
                        <button class="rounded-1">Rice Toppings</button>
                        <button class="rounded-1">Guisado</button>
                        <button class="rounded-1">Pancit Special</button>
                        <button class="rounded-1">Pancit sa Bilao</button>
                        <button class="rounded-1">Mami & Selfmade Siopao</button>
                        <button class="rounded-1">Chicken</button>
                        <button class="rounded-1">Fried</button>
                        <button class="rounded-1">Soup</button>
                        <button class="rounded-1">Roast & Sizzling</button>
                        <button class="rounded-1">Dessert</button>
                        <button class="rounded-1">Beverages</button>
                        <button class="rounded-1">Side Dishes</button>
                    </section>
                    <div class="container-fluid m-0 p-1 border">
                        <input type="text" name="" id="" placeholder="Search menu..." class="form-control">
                    </div>
                </div>
            </div>
            <div class="container-fluid m-0 p-2 border shadow rounded-3" id="orders-container">
                <div class="container-fluid p-2 m-0 border" id="sub-menu">
                    <label for="menu">Sweet & Sour Pork</label>
                    <input type="number" name="" id="menu" class="form-control" min="1" style="width: 60px;">
                </div>

            </div>
            <div class="container-fluid m-0 p-2 border shadow rounded-3" id="control-container">
                <div class="container-fluid p-2 m-0 border" id="sub-menu">
                    <h4>Total: 1,500.00</h4>
                    <button class="btn btn-dark">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection