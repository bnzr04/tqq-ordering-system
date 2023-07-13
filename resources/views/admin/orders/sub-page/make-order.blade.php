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
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Make Order</h3>
        </div>
        <div class="container-fluid m-0 p-2">
            <a href="{{ route('orders.admin') }}" class="btn btn-dark">Back</a>
        </div>
    </div>
</div>
@endsection