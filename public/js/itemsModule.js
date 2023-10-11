function getItems(getItemsRoute) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: getItemsRoute,
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}

function getItemsCategories(getItemsCategoriesRoute) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: getItemsCategoriesRoute,
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}

function getItemsByCategories(getItemsByCategoriesRoute, category) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: getItemsByCategoriesRoute,
            data: {
                category: category,
            },
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}

function searchItemByName(itemName, searchItemByNameRoute) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: searchItemByNameRoute + `?item_name=${itemName}`,
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}

function createNewItem(
    createNewItemRoute,
    itemName,
    itemDescription,
    itemCategory,
    itemPrice,
    maxQuantity,
    warningLevel
) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: createNewItemRoute,
            data: {
                item_name: itemName,
                item_description: itemDescription,
                item_category: itemCategory,
                item_price: itemPrice,
                max_quantity: maxQuantity,
                warning_level: warningLevel,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                reject(error);
            },
        });
    });
}

function updateItemInformation(
    updateItemInformationRoute,
    itemId,
    itemName,
    itemDescription,
    itemCategory,
    itemPrice,
    max,
    warning
) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "post",
            url: updateItemInformationRoute,
            data: {
                item_id: itemId,
                item_name: itemName,
                item_description: itemDescription,
                item_category: itemCategory,
                item_price: itemPrice,
                max_level: max,
                warning_level: warning,
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: (response) => {
                resolve(response);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}

function deleteItemInformation(deleteItemInformationRoute, itemId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "post",
            url: deleteItemInformationRoute,
            data: {
                item_id: itemId,
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: (response) => {
                resolve(response);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}
