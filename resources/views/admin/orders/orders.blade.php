@extends('layouts.app')
@include('layouts.header')
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
        <div class="container-fluid m-0 p-2">
            <div class="containter-fluid m-0 p-0">
                <h5 class="m-0 p-0">Current Orders</h5>
            </div>
            <div class="container-fluid p-0 border table-container">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr class="table-dark" style="position: sticky;top:0;">
                            <th scope="col" class="border">Date & Time</th>
                            <th scope="col" class="border">User ID</th>
                            <th scope="col" class="border">User Type</th>
                            <th scope="col" class="border">Activity</th>
                            <th scope="col" class="border">Query</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td class="border">Date</td>
                            <td class="border">Date</td>
                            <td class="border">Date</td>
                            <td class="border">Date</td>
                            <td class="border">Date</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection