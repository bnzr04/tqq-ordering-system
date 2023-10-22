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

    .filter_container {
        border-radius: 3px;
        display: flex;
        align-items: center;
        background-color: #999999;
        cursor: pointer;
    }

    .filter_container:hover {
        background-color: #808080;
    }

    .filter_container:active {
        background-color: #4d4d4d;
        color: #fff;
    }

    .color_box {
        min-width: 40px;
        width: 40px;
        height: 100%;
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
            <div class="container-fluid m-0 p-2 d-flex" style="gap:10px">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newItemModal" id="modalButton" style="letter-spacing: 2px;">ADD ITEM</button>
                <div class="container-fluid border d-flex" style="align-items: center;letter-spacing:2px;gap:10px;">
                    <div class="p-2 filter_container" id="has_stock_box" data-value="has_stock">
                        <p class="m-0">Has Stock</p>
                    </div>
                    <div class="p-1 filter_container" id="overmax_box" data-value="overmax">
                        <div class="m-1 color_box" style="background-color:#00ffcc">&nbsp;</div>
                        <p class="m-0">Over Max</p>
                    </div>
                    <div class="p-1 filter_container" id="safe_box" data-value="safe">
                        <div class="m-1 color_box" style="background-color:#00ff00">&nbsp;</div>
                        <p class="m-0">Safe</p>
                    </div>
                    <div class="p-1 filter_container" id="warning_box" data-value="warning">
                        <div class="m-1 color_box" style="background-color:#ff9900">&nbsp;</div>
                        <p class="m-0">Warning</p>
                    </div>
                    <div class="p-1 filter_container" id="no_stock_box" data-value="no_stock">
                        <div class="m-1 color_box" style="background-color:#ff0000">&nbsp;</div>
                        <p class="m-0">No Stock</p>
                    </div>
                </div>
                <div class="m-0 p-1 d-flex gap-2 align-items-center">
                    <!-- <input type="date" class="form-control" id="dateForReport"> -->
                    <form action="{{ route('generate-item-stocks-report.cashier') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success" id="downloadReportButton">
                            Download Report
                        </button>
                    </form>
                </div>
            </div>
            <div class="container-fluid m-0 p-1">
                <div class="container-fluid m-0 p-1 mb-2 border shadow d-flex" style="align-items:center;justify-content:space-between;letter-spacing:3px;">
                    <h4 class="m-0 p-0 px-2 fw-bold" id="table_title" style="text-wrap:nowrap"></h4>
                    <div style="width:100%;max-width:50vw;display:flex;gap:10px;">
                        <div class="container-fluid m-0 p-0 d-flex" style="width:100%;min-width:200px;flex-wrap:nowrap;">
                            <label for="menu_category" class="px-1">FILTER CATEGORIES</label>
                            <select name="category" id="menu_category" style="letter-spacing: 3px;" class="form-control">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="container-fluid m-0 p-0 d-flex" style="width: 500px;flex-wrap:nowrap;">
                            <input type="search" class="form-control" id="searchItemInput" placeholder="Search Item...">
                        </div>
                    </div>
                </div>
                <div class="container-fluid m-0 p-0">
                    <table class="table table-dark">
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
                <!-- <div class="container-fluid m-0 p-0">
                    <input type="file" name="insert_item" id="insert_item" class="border p-1 rounded border-secondary">
                </div> -->
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

                <div class="container-fluid m-0 mt-3 p-1">
                    <h5 class="m-0">Threshold</h5>
                    <div class="container-fluid mt-2">
                        <label for="max_input">Max Quantity <span style="opacity: 0.6;">default: [50]</span></label>
                        <input type="number" min="1" class="form-control border border-secondary" id="max_input" value="50">
                        <label for="warning_input">Warning Level <span style="opacity: 0.6;">default: [20]</span></label>
                        <input type="number" min="1" class="form-control border border-secondary" id="warning_input" value="20">
                    </div>
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">EDIT ITEM INFORMATION</h5>
                <button type="button" class="btn-close edit_modal_close_button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_body">
                <div class="container-fluid m-0 mb-2 p-0">
                    <label for="id_input">Item ID</label>
                    <input type="text" id="id_input" class="m-0 form-control text-center border-secondary" disabled>
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
                    <select id="edit_item_modal_category_select" class="form-control text-center border-secondary">

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

                <div class="container-fluid m-0 mt-3 p-1">
                    <h5 class="m-0">Threshold</h5>
                    <div class="container-fluid mt-2">
                        <label for="max_input">Max Quantity</label>
                        <input type="number" min="1" class="form-control border border-secondary" id="max_input">
                        <label for="warning_input">Warning Level</label>
                        <input type="number" min="1" class="form-control border border-secondary" id="warning_input">
                    </div>
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
            const getItemsCategoriesRoute = "{{ route('fetch-categories.cashier') }}";

            $.getScript("{{ asset('js/itemsModule.js') }}", () => {
                getItemsCategories(getItemsCategoriesRoute)
                    .then((data) => {
                        if (data.length > 0) {
                            data.forEach(function(row) {
                                categoryOption.append("<option value='" + row + "'style='text-transform: capitalize;'>" + row + "</option>");
                                addItemModalCategory.append("<option value='" + row + "'style='text-transform: capitalize;'>" + row + "</option>");
                            });
                            addItemModalCategory.append("<option value='other'>Other</option>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        function itemRowDisplay(itemInfo) {

            const {
                item_id,
                name,
                description,
                category,
                price,
                quantity,
                max_level,
                warning_level
            } = itemInfo;

            var tableRow = $("<tr class=''><td class='border'>" + item_id + "</td><td class='border'>" + name + "</td><td class='border'>" + description + "</td><td class='border'>" + category + "</td><td class='border'>" + price + "</td><td class='border quantity_box' data-max='" + max_level + "' data-warning='" + warning_level + "'>" + quantity + "</td>");
            var buttonColumn = $("<td class='border' id='buttons_column'><button data-bs-toggle='modal' class='edit_item_info_button' data-bs-target='#editInfoModal' data-item-id='" + item_id + "' data-item-name='" + name + "' data-item-description='" + description + "' data-item-category='" + category + "' data-item-price='" + price + "' data-item-max='" + max_level + "' data-item-warning='" + warning_level + "'>Edit info</button></tr>");
            var addButtonColumn = "<button class='add_stock_button' data-bs-toggle='modal' data-bs-target='#addStockModal' data-item-id='" + item_id + "' data-item-name='" + name + "' data-item-category='" + category + "' data-item-quantity='" + quantity + "'>Update stock</button>";

            tableBody.append(tableRow);
            tableRow.append(buttonColumn);
            buttonColumn.prepend(addButtonColumn);
        }

        function colorDesignationOfQuantityBox() {
            //This will give colors to each of the .quantity_box depends on the stock quantity of the item
            $(".quantity_box").each(function() {
                var quantity = parseInt($(this).text());
                var max = $(this).data("max");
                var warning = $(this).data("warning");

                var warningQty = max * (warning / 100);
                var rangeColor = "";

                $(this).css("text-align", "center");
                $(this).css("color", "#000");

                if (quantity > max) {
                    rangeColor = "Cyan";
                    $(this).css("background-color", "#00ffcc");
                } else if (quantity > warningQty && quantity <= max) {
                    rangeColor = "Green";
                    $(this).css("background-color", "#00ff00");
                } else if (quantity <= warningQty && quantity !== 0) {
                    rangeColor = "Orange";
                    $(this).css("background-color", "#ff9900");
                } else if (quantity == 0) {
                    rangeColor = "Red";
                    $(this).css("background-color", "#ff0000");
                }
            });
        }

        function fetchItem() {
            const getItemsRoute = "{{ route('fetch-items.cashier') }}";
            $.getScript("{{ asset('js/itemsModule.js') }}", () => {
                getItems(getItemsRoute)
                    .then((data) => {
                        tableTitle.text("Has Stock Items")
                        if (data.length > 0) {
                            data.forEach(function(row) {

                                if (row.quantity == null) {
                                    row.quantity = 0;
                                }

                                var itemInfo = {
                                    item_id: row.item_id,
                                    name: row.name,
                                    description: row.description,
                                    category: row.category,
                                    price: row.price,
                                    quantity: row.quantity,
                                    max_level: row.max_level,
                                    warning_level: row.warning_level
                                }

                                itemRowDisplay(itemInfo);
                            });

                            colorDesignationOfQuantityBox();

                        } else {
                            tableBody.append("<tr><td colspan='7'>No item...</td></tr>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        function filterStock(range) {
            const filterStockByRangeRoute = "{{ route('filter-stock-by-range.cashier') }}";

            $.getScript("{{ asset('js/itemStockModule.js') }}", () => {
                filterStockByRange(filterStockByRangeRoute, range)
                    .then((data) => {
                        tableBody.empty();
                        if (data.length > 0) {
                            data.forEach(function(row) {

                                if (row.quantity == null) {
                                    row.quantity = 0;
                                }

                                var itemInfo = {
                                    item_id: row.item_id,
                                    name: row.name,
                                    description: row.description,
                                    category: row.category,
                                    price: row.price,
                                    quantity: row.quantity,
                                    max_level: row.max_level,
                                    warning_level: row.warning_level
                                }

                                itemRowDisplay(itemInfo);
                            });

                            colorDesignationOfQuantityBox();

                        } else {
                            tableBody.append("<tr><td colspan='7'>No item on the menu...</td></tr>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        }

        function resetModal() {
            $("#name").val("");
            $("#description").val("");
            $("#category").val("");
            $("#price").val("");
        }

        function resetMenuCategory() {
            $("#menu_category").val("all");
            $("#menu_category").prop("selectedIndex", 0);
        }

        $('#menu_category').on('change', function() {
            $("#searchItemInput").val("");
            var category = $(this).val();

            $.getScript("{{ asset('js/itemsModule.js') }}", () => {
                const getItemsByCategoriesRoute = "{{ route('filter-item-by-category.cashier') }}";
                getItemsByCategories(getItemsByCategoriesRoute, category)
                    .then((data) => {
                        tableTitle.text(category == "all" ? "ALL" : category);
                        if (data.length > 0) {
                            tableBody.empty();
                            data.forEach(function(row) {

                                if (row.quantity == null) {
                                    row.quantity = 0;
                                }

                                var itemInfo = {
                                    item_id: row.item_id,
                                    name: row.name,
                                    description: row.description,
                                    category: row.category,
                                    price: row.price,
                                    quantity: row.quantity,
                                    max_level: row.max_level,
                                    warning_level: row.warning_level
                                }

                                itemRowDisplay(itemInfo);
                            });

                            colorDesignationOfQuantityBox();

                        } else {
                            tableBody.append("<tr><td colspan='6'>No item on the menu...</td></tr>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        });

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
            var maxQuantity = $("#max_input").val();
            var warningLevel = $("#warning_input").val();

            if (addItemModalCategoryValue === "other") {
                itemCategory = otherCategoryInput;
            } else {
                itemCategory = addItemModalCategoryValue;
            }

            const createNewItemRoute = "{{ route('save-item.cashier') }}";

            if (itemName != "" && itemDescription != "" && itemCategory != "" && itemPrice != "") {
                $.getScript("{{ asset('js/itemsModule.js') }}", function() {
                    createNewItem(
                            createNewItemRoute,
                            itemName,
                            itemDescription,
                            itemCategory,
                            itemPrice,
                            maxQuantity,
                            warningLevel
                        )
                        .then((response) => {
                            if (response) {
                                alert("Item Added successfully.");
                                $("#newItemModal").modal("hide");
                                $(".modal-backdrop").remove();
                                resetModal();
                                window.location.reload();
                            }
                        })
                        .catch((error) => {
                            console.error(error);
                        });
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

            const editModalOtherCategoryContainer = $("#edit_modal_other_input_container");
            editModalOtherCategoryContainer.css('display', 'none');
        });

        // Click event handler for the "Update Stock" button in the modal
        $("#update_stock_modal_button").on('click', function() {
            if (confirm("Do you want to add this quantity to this item stocks?")) {
                var itemId = $("#item_id_input").val();
                var quantity = $("#stock_quantity_input").val();
                const operation = $("input[name='operation']:checked").val();

                if (quantity !== "") {
                    quantity = parseInt(quantity);

                    $.getScript("{{ asset('js/itemStockModule.js') }}", () => {
                        const updateItemStockRoute = "{{ route('add-item-stock.cashier') }}";
                        updateItemStock(updateItemStockRoute, itemId, quantity, operation)
                            .then((response) => {
                                // console.log(response);
                                switch (response) {
                                    case true:
                                        alert("Item ID [" + itemId + "] stock is successfully updated.");
                                        window.location.reload();
                                        break;
                                    case false:
                                        alert('Oops! Failed to update stock!');
                                        break;
                                    case "no_stock":
                                        alert('Oops! You cannot remove quantity because item has no stocks!');
                                        break;
                                    case "insufficient":
                                        alert('Oops! Current quantity is insufficient to deduct the quantity!');
                                        break;
                                }
                            })
                            .catch((error) => {
                                console.error(error);
                            });
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
            var max = $(this).data("item-max");
            var warning = $(this).data("item-warning");
            const editItemModalCategorySelect = $("#edit_item_modal_category_select");

            editItemModalCategorySelect.empty();
            const editItemInfoModal = $("#editInfoModal");

            editItemInfoModal.find("#id_input").val(itemId);
            editItemInfoModal.find("#item_name_input").val(itemName);
            editItemInfoModal.find("#item_description_input").val(itemDescription);
            editItemModalCategorySelect.append('<option value="' + itemCategory + '">' + itemCategory + '</option>');

            $.getScript("{{ asset('js/itemsModule.js') }}", function() {
                var getItemsCategoriesRoute = "{{ route('fetch-categories.cashier') }}";
                getItemsCategories(getItemsCategoriesRoute)
                    .then((data) => {
                        if (data.length > 0) {
                            const filteredCategories = data.filter(category => category !== itemCategory);

                            filteredCategories.forEach((category) => {
                                editItemModalCategorySelect.append('<option value="' + category + '">' + category + '</option>');
                            });

                            editItemModalCategorySelect.append("<option value='other'>Other</option>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });

            editItemInfoModal.find("#item_price_input").val(itemPrice);
            editItemInfoModal.find("#max_input").val(max);
            editItemInfoModal.find("#warning_input").val(warning);
        });

        $("#edit_item_modal_category_select").on("change", function() {
            var selectValue = $(this).val();

            if (selectValue === "other") {
                $("#edit_modal_other_input_container").css("display", "block");
            } else {
                $("#edit_modal_other_input_container").css("display", "none");
            }
        });

        $("#update_item_info_modal_button").on("click", function() {
            const editItemInfoModal = $("#editInfoModal");
            const itemCategorySelectInput = editItemInfoModal.find("#edit_item_modal_category_select");
            var itemId = editItemInfoModal.find("#id_input").val();
            var itemName = editItemInfoModal.find("#item_name_input").val();
            var itemDescription = editItemInfoModal.find("#item_description_input").val();
            var itemCategory = itemCategorySelectInput.val();
            var OtherCategoryValue = editItemInfoModal.find("#edit_modal_other_category_input").val();
            var itemPrice = editItemInfoModal.find("#item_price_input").val();
            var max = editItemInfoModal.find("#max_input").val();
            var warning = editItemInfoModal.find("#warning_input").val();

            if (itemCategory === "other") {
                itemCategory = OtherCategoryValue;
            }

            $.getScript("{{ asset('js/itemsModule.js') }}", function() {
                const updateItemInformationRoute = "{{ route('update-item-info.cashier') }}";

                updateItemInformation(
                        updateItemInformationRoute,
                        itemId,
                        itemName,
                        itemDescription,
                        itemCategory,
                        itemPrice,
                        max,
                        warning
                    )
                    .then((response) => {
                        if (response) {
                            alert(itemName + " information is successfully updated.");
                            window.location.reload();
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        });

        $("#searchItemInput").on("input", function() {
            resetMenuCategory();
            tableTitle.text("ALL");

            var itemName = $(this).val();
            const searchItemByNameRoute = "{{ route('search-item-name.cashier') }}";
            $.getScript("{{ asset('js/itemsModule.js') }}", () => {
                searchItemByName(itemName, searchItemByNameRoute)
                    .then((data) => {
                        tableBody.empty();
                        if (data.length > 0) {
                            data.forEach(function(row) {

                                if (row.quantity == null) {
                                    row.quantity = 0;
                                }

                                var itemInfo = {
                                    item_id: row.item_id,
                                    name: row.name,
                                    description: row.description,
                                    category: row.category,
                                    price: row.price,
                                    quantity: row.quantity,
                                    max_level: row.max_level,
                                    warning_level: row.warning_level
                                }

                                itemRowDisplay(itemInfo);
                            });

                            colorDesignationOfQuantityBox();

                        } else {
                            tableBody.append("<tr><td colspan='7'>No item on the menu...</td></tr>");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            });
        });


        $("#overmax_box").on("click", function() {
            resetMenuCategory();
            var value = $(this).data('value');

            tableTitle.text("Over Max Items");
            filterStock(value);
        });

        $("#safe_box").on("click", function() {
            resetMenuCategory();
            var value = $(this).data('value');

            tableTitle.text("Safe Items");
            filterStock(value);
        });

        $("#warning_box").on("click", function() {
            resetMenuCategory();
            var value = $(this).data('value');

            tableTitle.text("Warning Items");
            filterStock(value);
        });

        $("#no_stock_box").on("click", function() {
            resetMenuCategory();
            var value = $(this).data('value');

            tableTitle.text("No Stock Items");
            filterStock(value);
        });

        $("#has_stock_box").on("click", function() {
            resetMenuCategory();
            var value = $(this).data('value');

            tableTitle.text("Has Stock Items");
            filterStock(value);
        });

        function generateItemStockReport(route) {
            $.ajax({
                type: "post",
                url: route,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(response) {
                    console.log("downloaded");
                },
                error: function(xhr, status, status) {
                    console.log(xhr.responseText);
                }
            });
        }

        // $("#downloadReportButton").on("click", () => {
        //     const dateForReport = $("#dateForReport");
        //     var message = "";
        //     var hasDate = false;

        //     if (dateForReport.val() !== "") {
        //         message = `Do you want to download ${dateForReport.val()} item stocks report?`;
        //         hasDate = true;
        //     } else {
        //         message = "Do you want to download todays item stocks report?";
        //         hasDate = false;
        //     }

        //     if (confirm(message)) {
        //         var route = "";

        //         if (hasDate) {
        //             var date = dateForReport.val();
        //             route = `{{ route('generate-item-stocks-report.cashier') }}?date=${date}`;
        //         } else {
        //             route = "{{ route('generate-item-stocks-report.cashier') }}";
        //         }

        //         generateItemStockReport(route);
        //     }
        // });

        fetchMenuCategory();
        fetchItem();
    });
</script>
@endsection