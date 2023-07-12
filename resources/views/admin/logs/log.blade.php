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
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Activity Logs</h3>
        </div>
        <div class="container-fluid mt-1">
            <p class="m-0 p-2" style="letter-spacing: 2px;">Date Control:</p>
            <div class="container-fluid p-0">
                <button class="btn btn-dark" id="filter-btn">This day</button>
                <button class="btn btn-dark" id="filter-btn">This Week</button>
                <button class="btn btn-dark" id="filter-btn">This Month</button>
            </div>
            <div class="container-fluid m-0 py-2" style="display:flex;align-items:center">
                <p class="m-0 mx-1">From</p>
                <input type="date" name="" id="" class="form-control" style="width: 200px;">

                <p class="m-0 mx-1">To</p>
                <input type="date" name="" id="" class="form-control" style="width: 200px;">
            </div>
        </div>
        <div class="container-fluid">
            <div class="container-fluid p-0 mt-1">
                <h5 class="m-0" style="letter-spacing: 2px;">Date: Jan 1 2023 - Feb 29 2023</h5>
            </div>
            <div class="container-fluid p-0 border border-danger">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr class="table-dark">
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection