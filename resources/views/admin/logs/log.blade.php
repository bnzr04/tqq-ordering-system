@extends('layouts.app')
@include('layouts.header')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    .table-container {
        max-height: 350px;
        overflow-y: auto;
    }

    .page-link {
        color: black;
    }

    .page-link:hover {
        color: white;
        background-color: black;
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
                <button class="btn btn-dark" id="this-day-btn">This day</button>
                <button class="btn btn-dark" id="this-week-btn">This Week</button>
                <button class="btn btn-dark" id="this-month-btn">This Month</button>
            </div>
            <form id="filter-form" class="m-0">
                <div class="container-fluid m-0 py-2" style="display:flex;align-items:center">
                    <p class="m-0 mx-1">From</p>
                    <input type="date" name="date-from" id="date-from" class="form-control" style="width: 200px;" required>

                    <p class="m-0 mx-1">To</p>
                    <input type="date" name="date-to" id="date-to" class="form-control" style="width: 200px;" required>

                    <button class="btn btn-outline-dark mx-1">Filter</button>
                </div>
            </form>
        </div>
        <div class="container-fluid">
            <div class="container-fluid p-0 mt-1">
                <h5 class="m-0 px-2" style="letter-spacing: 2px;" id="table-title"></h5>
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

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    window.APP_URL = "{{ url('') }}";

    const thisDayBtn = document.getElementById('this-day-btn');
    const thisWeekBtn = document.getElementById('this-week-btn');
    const thisMonthBtn = document.getElementById('this-month-btn');

    const filterForm = document.getElementById('filter-form');

    const tableBody = document.getElementById('table-body');
    var tableTitle = document.getElementById('table-title');

    function formatDate(date) {
        const options = {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        };
        return date.toLocaleDateString('en-US', options);
    }

    function fetchLogs() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                tableBody.innerHTML = "";
                tableTitle.innerHTML = title;
                console.log(data);
                if (data.length > 0) {
                    data.forEach(function(row) {
                        tableBody.innerHTML += "<tr><td class='border'>" + row.formatDate + "</td><td class='border'>" + row.user_id + "</td><td class='border'>" + row.user_type + "</td><td class='border'>" + row.activity + "</td><td class='border'>" + row.query + "</td></tr>";
                    });
                } else {
                    tableBody.innerHTML = "<tr><td colspan='5'>No Logs...</td></tr>";
                }
            } else {
                console.log('Error: ' + xhr.status);
            }
        };
        xhr.send();
    }

    var url = '{{ route("logs-view.admin") }}';
    var title = 'This day activities';
    fetchLogs();

    thisDayBtn.addEventListener('click', function() {
        url = '{{ route("logs-view.admin") }}';
        title = 'This day activities';
        fetchLogs();
    });

    thisWeekBtn.addEventListener('click', function() {
        url = '{{ route("logs-view.admin") }}?this-week=1';
        title = 'This week activities';
        fetchLogs();
    });

    thisMonthBtn.addEventListener('click', function() {
        url = '{{ route("logs-view.admin") }}?this-month=1';
        title = 'This month activities';
        fetchLogs();
    });

    filterForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const dateFromInput = document.getElementById('date-from').value;
        const dateToInput = document.getElementById('date-to').value;
        var from = new Date(dateFromInput);
        var to = new Date(dateToInput);

        url = '{{ route("logs-view.admin") }}?filter=1&date-from=' + dateFromInput + '&date-to=' + dateToInput;
        title = 'Activities from ' + formatDate(from) + ' - ' + formatDate(to);
        fetchLogs();
    });
</script>
@endsection