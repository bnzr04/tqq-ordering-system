@extends('layouts.app')
@section('content')
<style>
    .grid-container {
        display: grid;
        grid: 100vh / 200px auto;
    }

    #other_input_container {
        display: none;
    }

    #edit_modal_other_input_container {
        display: none;
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
                <button class="btn btn-dark" id="stocks_button">Stocks</button>
            </div>
            <div class="container-fluid m-0 p-1">
                <div class="container-fluid m-0 p-1 mb-2 border shadow d-flex" style="align-items:center;justify-content:space-between;letter-spacing:3px;">
                    <h4 class="m-0 p-0 px-2 fw-bold" id="table_title"></h4>
                    <div class="container-fluid m-0 p-0 d-flex" style="width: 500px;flex-wrap:nowrap;">
                        <label for="menu_category">FILTER CATEGORIES:</label>
                        <select name="category" id="menu_category" style="letter-spacing: 3px;" class="form-control">
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>
                <div class="container-fluid m-0 p-0">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr style="position: sticky;top:0;letter-spacing:3px;">
                                <th scope="col" class="border">ID</th>
                                <th scope="col" class="border">Name</th>
                                <th scope="col" class="border">Description</th>
                                <th scope="col" class="border">Category</th>
                                <th scope="col" class="border">Price</th>
                                <th scope="col" class="border">Stock</th>
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
<!--Add item Modal -->
<div class="modal fade" id="newItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel" style="letter-spacing: 2px;">ADD NEW ITEM</h5>
                <button type="button" class="btn-close add_item_modal_close_button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid m-0 p-0">
                    <input type="file" name="insert_item" id="insert_item" class="border p-1 rounded border-secondary">
                </div>
                <div class="container-fluid m-0 p-0 my-2 mt-3">
                    <label for="name" style="letter-spacing: 2px;">Name</label>
                    <input type="text" name="name" id="name" class="form-control border-secondary">
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="description" style="letter-spacing: 2px;">Description</label>
                    <input type="text" name="description" id="description" class="form-control border-secondary">
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="category" style="letter-spacing: 2px;">Category</label>
                    <select id="add_item_modal_category" class="form-control text-center border-secondary">
                        <option value="">Select category</option>
                    </select>
                    <div class="container-fluid p-1 px-2" id="other_input_container">
                        <label for="other_category_input">Other</label>
                        <input type="text" id="other_category_input" class="m-0 form-control border-secondary">
                    </div>
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="price" style="letter-spacing: 2px;">Price</label>
                    <input type="text" name="price" id="price" class="form-control border-secondary" placeholder="0.00">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary add_item_modal_close_button" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" id="add_item">Add Item</button>
            </div>
        </div>
    </div>
</div>

<!--Update Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">ADD STOCK</h5>
                <button type="button" class="btn-close close_button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_body">
                <b>
                    <h5 id="item_info_modal_display"></h5>
                </b>
                <div class="container-fluid m-0 p-0">
                    <input type="hidden" id="item_id_input" class="form-control border-secondary">
                    <label for="current_stock_quantity_input">Current Stock Quantity</label>
                    <input type="number" id="current_stock_quantity_input" class="form-control border-secondary text-center" readonly>
                </div>
                <div class="container-fluid m-0 mt-3 p-0">
                    <label for="stock_quantity_input">Quantity</label>
                    <input type="number" min="1" id="stock_quantity_input" class="form-control border-secondary text-center">

                    <div class="m-0 mt-4 p-1">
                        <b class="m-0">Operation:</b>
                        <div class="mt-2">
                            <label for="add">Add to stock</label>
                            <input type="radio" name="operation" id="add" value="add" checked>
                        </div>
                        <div class="">
                            <label for="remove">Remove to stock</label>
                            <input type="radio" name="operation" id="remove" value="remove">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close_button" data-bs-dismiss="modal" id="close_button">Close</button>
                <button type="button" class="btn btn-dark" id="update_stock_modal_button">UPDATE STOCK</button>
            </div>
        </div>
    </div>
</div>

<!--Edit Info Modal -->
<div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">EDIT ITEM INFORMATION</h5>
                <button type="button" class="btn-close edit_modal_close_button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="container-fluid m-0 mb-2 p-0">
                    <label for="id_input">Item ID</label>
                    <input type="text" id="id_input" class="m-0 form-control text-center border-secondary" readonly>
                </div>
                <div class="container-fluid m-0 mb-2 p-0">
                    <label for="item_name_input">Name</label>
                    <input type="text" id="item_name_input" class="m-0 form-control text-center border-secondary">
                </div>
                <div class="container-fluid m-0 mb-2 p-0">
                    <label for="item_description_input">Description</label>
                    <input type="text" id="item_description_input" class="m-0 form-control text-center border-secondary">
                </div>
                <div class="container-fluid m-0 p-0 my-2">
                    <label for="category" style="letter-spacing: 2px;">Category</label>
                    <select id="edit_item_modal_category" class="form-control text-center border-secondary">

                    </select>
                    <div class="container-fluid p-1 px-2" id="edit_modal_other_input_container">
                        <label for="edit_modal_other_category_input">Other</label>
                        <input type="text" id="edit_modal_other_category_input" class="m-0 form-control text-center border-secondary">
                    </div>
                </div>
                <div class="container-fluid m-0 mb-2 p-0">
                    <label for="item_price_input">Price</label>
                    <input type="text" id="item_price_input" class="m-0 form-control text-center border-secondary">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close_button" data-bs-dismiss="modal" id="edit_modal_close_button">Close</button>
                <button type="button" class="btn btn-dark" id="update_item_info_modal_button">UPDATE INFO</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var tableBody = $("#table_body");
        var tableTitle = $('#table_title');
        var categoryOption = $("#menu_category");

        var addItemModalCategory = $("#add_item_modal_category");

        function fetchMenuCategory() {
            $.ajax({
                type: "GET",
                url: "{{ route('fetch-menu.admin') }}",
                success: function(data) {

                    if (data.category.length > 0) {
                        data.category.forEach(function(row) {
                            categoryOption.append("<option value='" + row + "'style='text-transform: capitalize;'>" + row + "</option>");
                            addItemModalCategory.append("<option value='" + row + "'style='text-transform: capitalize;'>" + row + "</option>");
                        });
                        addItemModalCategory.append("<option value='other'>Other</option>");
                    }
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

                    tableTitle.text("ON THE MENU")
                    if (data.menu.length > 0) {
                        data.menu.forEach(function(row) {

                            if (row.quantity == null) {
                                row.quantity = 0;
                            }

                            var tableRow = $("<tr class=''><td class='border'>" + row.item_id + "</td><td class='border'>" + row.name + "</td><td class='border'>" + row.description + "</td><td class='border'>" + row.category + "</td><td class='border'>" + row.price + "</td><td class='border'>" + row.quantity + "</td>");
                            var buttonColumn = $("<td class='border' id='buttons_column'><button data-bs-toggle='modal' class='edit_item_info_button' data-bs-target='#editInfoModal' data-item-id='" + row.item_id + "' data-item-name='" + row.name + "' data-item-description='" + row.description + "' data-item-category='" + row.category + "' data-item-price='" + row.price + "'>Edit info</button><button class='remove_item_button' data-item-id='" + row.item_id + "'>Remove item</button></td></tr>");
                            var addButtonColumn = "<button class='add_stock_button' data-bs-toggle='modal' data-bs-target='#addStockModal' data-item-id='" + row.item_id + "' data-item-name='" + row.name + "' data-item-category='" + row.category + "' data-item-quantity='" + row.quantity + "'>Update stock</button>";

                            tableBody.append(tableRow);
                            tableRow.append(buttonColumn);
                            buttonColumn.prepend(addButtonColumn);
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
            var itemCategory = "";
            var itemPrice = $("#price").val();
            var addItemModalCategoryValue = $("#add_item_modal_category").val();
            var otherCategoryInput = $("#other_category_input").val();

            if (addItemModalCategoryValue === "other") {
                itemCategory = otherCategoryInput;
            } else {
                itemCategory = addItemModalCategoryValue;
            }

            if (itemName != "" && itemDescription != "" && itemCategory != "" && itemPrice != "") {
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
                        alert("Item Added successfully.");
                        $("#newItemModal").modal("hide");
                        $(".modal-backdrop").remove();
                        resetModal();
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                alert("Please fill all the inputs.");
            }

        });

        $(".add_item_modal_close_button").on("click", function() {
            $("#insert_item").val("");
            $("#add_item_modal_category").val("");
            $("#other_category_input").val("");
            $("#other_input_container").css("display", "none");
            resetModal();
        });

        $("#add_item_modal_category").on("change", function() {
            var categoryValue = $(this).val();

            if (categoryValue === 'other') {
                $("#other_input_container").css('display', 'block');
            } else {
                $("#other_input_container").css('display', 'none');
            }
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
                            if (row.quantity == null) {
                                row.quantity = 0;
                            }

                            var tableRow = $("<tr class=''><td class='border'>" + row.item_id + "</td><td class='border'>" + row.name + "</td><td class='border'>" + row.description + "</td><td class='border'>" + row.category + "</td><td class='border'>" + row.price + "</td><td class='border'>" + row.quantity + "</td>");
                            var buttonColumn = $("<td class='border' id='buttons_column'><button data-bs-toggle='modal'  class='edit_item_info_button' data-bs-target='#editInfoModal' data-item-id='" + row.item_id + "' data-item-name='" + row.name + "' data-item-description='" + row.description + "' data-item-category='" + row.category + "' data-item-price='" + row.price + "'>Edit info</button><button class='remove_item_button' data-item-id='" + row.item_id + "'>Remove item</button></td></tr>");
                            var addButtonColumn = "<button class='add_stock_button' data-bs-toggle='modal' data-bs-target='#addStockModal' data-item-id='" + row.item_id + "' data-item-name='" + row.name + "' data-item-category='" + row.category + "' data-item-quantity='" + row.quantity + "'>Update stock</button>";

                            tableBody.append(tableRow);
                            tableRow.append(buttonColumn);
                            buttonColumn.prepend(addButtonColumn);
                        });
                    } else {
                        tableBody.append("<tr><td colspan='7'>No item in this category...</td></tr>");
                    }

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $("#stocks_button").on("click", function() { //redirect to stocks view 
            window.location.href = "{{ route('stocks.admin') }}";
        });

        // Click event handler for the "Add Stock" button in the modal
        tableBody.on("click", ".add_stock_button", function() {
            var itemId = $(this).data('item-id');
            var itemName = $(this).data('item-name');
            var itemCategory = $(this).data('item-category');
            var itemQuantity = $(this).data('item-quantity');

            if (itemQuantity == null) {
                itemQuantity = 0;
            }

            $("#item_id_input").val(itemId);
            $("#current_stock_quantity_input").val(itemQuantity);
            $('#item_info_modal_display').text(itemName + " / " + itemCategory);
        });

        // Click event handler for the modal's close button
        $(".close_button").on('click', function() {
            const allModalInput = $("#modal_body input");

            const addRadio = $("#add");
            const removeRadio = $("#remove");

            // Toggle the checked status of radio buttons
            if (removeRadio.prop('checked')) {
                addRadio.prop('checked', true);
                removeRadio.prop('checked', false);
            } else {
                addRadio.prop('checked', true);
                removeRadio.prop('checked', false);
            }

            // Clear values of all input elements in the modal
            allModalInput.each(function() {
                $(this).val("");
            });
        });

        // Click event handler for the "Update Stock" button in the modal
        $("#update_stock_modal_button").on('click', function() {
            if (confirm("Do you want to add this quantity to this item stocks?")) {
                var itemIdValue = $("#item_id_input").val();
                var quantityValue = $("#stock_quantity_input").val();
                const operationValue = $("input[name='operation']:checked").val();

                if (quantityValue !== "") {
                    quantityValue = parseInt(quantityValue);

                    // AJAX call to update item stock
                    $.ajax({
                        type: "post",
                        url: "{{ route('add-item-stock.admin') }}",
                        data: {
                            item_id_value: itemIdValue,
                            quantity_value: quantityValue,
                            operation_value: operationValue,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(data) {
                            // Handle different success outcomes
                            if (data == true) {
                                window.location.reload();
                            } else if (data == false) {
                                alert('Oops! Failed to update stock!');
                                window.location.reload();
                            } else if (data == "no stock") {
                                alert('Oops! You cannot remove quantity because item has no stocks!');
                            } else {
                                alert('Oops! Current quantity is insufficient to deduct the quantity!');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    alert("Please enter quantity!");
                }
            }
        });

        $(document).on("click", ".edit_item_info_button", function() {
            var itemId = $(this).data("item-id");
            var itemName = $(this).data("item-name");
            var itemDescription = $(this).data("item-description");
            var itemCategory = $(this).data("item-category");
            var itemPrice = $(this).data("item-price");

            $("#edit_item_modal_category").empty();

            $("#id_input").val(itemId);
            $("#item_name_input").val(itemName);
            $("#item_description_input").val(itemDescription);
            $("#edit_item_modal_category").append('<option value="">' + itemCategory + '</option>');
            $("#item_price_input").val(itemPrice);

            $.ajax({
                type: "GET",
                url: "{{ route('fetch-menu.admin') }}",
                success: function(data) {
                    var editItemModalCategorySelect = $("#edit_item_modal_category")
                    if (data.category.length > 0) {
                        data.category.forEach(function(row) {
                            editItemModalCategorySelect.append("<option value='" + row + "' >" + row + "</option>");
                        });
                        editItemModalCategorySelect.find("option[value='" + itemCategory + "']").remove();
                        editItemModalCategorySelect.append("<option value='other'>Other</option>");
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $("#edit_item_modal_category").on("change", function() {
            var selectValue = $(this).val();

            if (selectValue === "other") {
                $("#edit_modal_other_input_container").css("display", "block");
            } else {
                $("#edit_modal_other_input_container").css("display", "none");
            }
        });

        $("#update_item_info_modal_button").on("click", function() {
            var itemId = $("#id_input").val();
            var itemName = $("#item_name_input").val();
            var itemDescription = $("#item_description_input").val();
            var itemCategory = $("#edit_item_modal_category").val();
            var editModalOtherCategoryInput = $("#edit_modal_other_category_input").val();
            var itemPrice = $("#item_price_input").val();

            if (itemCategory === "other") {
                itemCategory = editModalOtherCategoryInput;
            }

            $.ajax({
                type: "post",
                url: "{{ route('update-item-info.admin') }}",
                data: {
                    item_id: itemId,
                    item_name: itemName,
                    item_description: itemDescription,
                    item_category: itemCategory,
                    item_price: itemPrice,
                    _token: $("meta[name='csrf-token']").attr("content"),
                },
                success: function(data) {
                    if (data === true) {
                        alert("Item id [" + itemId + "] is successfully updated!");
                        window.location.reload();
                    } else {
                        alert("Sorry, failed to update item id [" + itemId + "] information.");
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $(document).on("click", ".remove_item_button", function() {
            var itemId = $(this).data("item-id");

            if (confirm("Do you want to delete this item id [" + itemId + "]?\nThis will delete permanently in database.")) {
                $.ajax({
                    type: "post",
                    url: "{{ route('delete-item-info.admin') }}",
                    data: {
                        item_id: itemId,
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(data) {
                        if (data === true) {
                            alert("Item successfully deleted to database");
                            window.location.reload();
                        } else {
                            alert("Sorry, failed to delete the item id [" + itemId + "]");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection