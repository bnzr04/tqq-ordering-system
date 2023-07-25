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
    <div class="container-fluid m-0 p-0" style="height:100vh;position:relative">
        <div class="container-fluid m-0 p-0">
            <h3 class="m-0 p-2 shadow" style="letter-spacing: 2px;">Menu</h3>
        </div>
        <div class="container-fluid m-0 p-0" style="height: 93vh;">
            <div class="container-fluid m-0 p-1">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newItemModal" id="modalButton">Add item</button>
            </div>
            <div class="container-fluid m-0 p-1">
                <div class="container-fluid m-0 p-1 mb-2 border shadow d-flex" style="align-items:center;justify-content:space-between;letter-spacing:3px;">
                    <h4 class="m-0 p-0 px-2 fw-bold" id="table_title"></h4>
                    <div class="container-fluid m-0 p-0 d-flex" style="width: 500px;flex-wrap:nowrap;">
                        <label for="menu_category">FILTER CATEGORIES:</label>
                        <select name="category" id="menu_category" style="letter-spacing: 3px;" class="form-control">
                            <option value="">All</option>
                        </select>
                    </div>
                </div>
                <div class="container-fluid m-0 p-0">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr style="position: sticky;top:0;letter-spacing:3px;">
                                <th scope="col" class="border">Menu ID</th>
                                <th scope="col" class="border">Name</th>
                                <th scope="col" class="border">Description</th>
                                <th scope="col" class="border">Category</th>
                                <th scope="col" class="border">Price</th>
                                <th scope="col" class="border"></th>
                            </tr>
                        </thead>
                        <tbody id="table_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="newItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel" style="letter-spacing: 2px;">ADD NEW ITEM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid m-0 p-0">
                    <input type="file" name="insert_item" id="insert_item" class="border p-1 rounded">
                </div>
                <div class="container-fluid m-0 p-0 my-2 mt-3">
                    <label for="name" style="letter-spacing: 2px;">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="description" style="letter-spacing: 2px;">Description</label>
                    <input type="text" name="description" id="description" class="form-control">
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="category" style="letter-spacing: 2px;">Category</label>
                    <input type="text" name="category" id="category" class="form-control">
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="price" style="letter-spacing: 2px;">Price</label>
                    <input type="text" name="price" id="price" class="form-control" placeholder="0.00">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" id="add_item">Add Item</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var tableBody = $("#table_body");
        var tableTitle = $('#table_title');
        var categoryOption = $("#menu_category");

        function fetchMenuCategory() {
            $.ajax({
                type: "GET",
                url: "{{ route('fetch-menu.admin') }}",
                success: function(data) {
                    // console.log(data.category);
                    if (data.category.length > 0) {
                        data.category.forEach(function(row) {
                            categoryOption.append("<option value='" + row + "'style='text-transform: capitalize;'>" + row + "</option>");
                        });
                    }
                    // categoryOption.append
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        fetchMenuCategory();

        function fetchMenu() {
            $.ajax({
                type: "GET",
                url: "{{ route('fetch-menu.admin') }}",
                success: function(data) {
                    // console.log(data);
                    tableTitle.text("ON THE MENU")
                    if (data.menu.length > 0) {
                        data.menu.forEach(function(row) {
                            tableBody.append("<tr><td class='border'>" + row.item_id + "</td><td class='border'>" + row.name + "</td><td class='border'>" + row.description + "</td><td class='border'>" + row.category + "</td><td class='border'>" + row.price + "</td><td class='border'><button>Edit</button><button>Remove</button></td></tr>");
                        });
                    } else {
                        tableBody.append("<tr><td colspan='6'>No item on the menu...</td></tr>");
                    }
                    // tableBody.append("<tr><td>" + data[0].item_id + "</td></tr>")
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                },
            });
        }

        fetchMenu();

        function resetModal() {
            $("#name").val("");
            $("#description").val("");
            $("#category").val("");
            $("#price").val("");
        }

        $('#newItemModal').on('shown.bs.modal', function() {
            $("#newItemModal").css('display', 'block');
        });

        //when the add item button in modal is clicked it will save to the database
        $("#add_item").on('click', function() {
            var itemName = $("#name").val();
            var itemDescription = $("#description").val();
            var itemCategory = $("#category").val();
            var itemPrice = $("#price").val();

            $.ajax({
                type: "POST",
                url: "{{ route('save-item.admin') }}",
                data: {
                    item_name: itemName,
                    item_description: itemDescription,
                    item_category: itemCategory,
                    item_price: itemPrice,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(data) {
                    alert("Added successfully.");
                    $("#newItemModal").modal("hide");
                    $(".modal-backdrop").remove();
                    resetModal();
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('#menu_category').on('change', function() {
            var categoryValue = $(this).val();

            $.ajax({
                type: "GET",
                url: "{{ route('filter-menu.admin') }}",
                data: {
                    category_value: categoryValue,
                },
                success: function(data) {
                    console.log(data);
                    tableBody.empty();

                    categoryValue = categoryValue.toUpperCase();
                    tableTitle.text(categoryValue + " ON THE MENU");

                    if (data.length > 0) {
                        data.forEach(function(row) {
                            tableBody.append("<tr><td class='border'>" + row.item_id + "</td><td class='border'>" + row.name + "</td><td class='border'>" + row.description + "</td><td class='border'>" + row.category + "</td><td class='border'>" + row.price + "</td><td class='border'><button>Edit</button><button>Remove</button></td></tr>");
                        });
                    } else {
                        tableBody.append("<tr><td colspan='6'>No item in this category...</td></tr>");
                    }

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection