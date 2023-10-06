function updateItemStock(updateItemStockRoute, itemId, quantity, operation) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "post",
            url: updateItemStockRoute,
            data: {
                item_id: itemId,
                quantity: quantity,
                operation: operation,
                _token: $('meta[name="csrf-token"]').attr("content"),
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

function filterStockByRange(filterStockByRangeRoute, range) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "get",
            url: filterStockByRangeRoute,
            data: {
                range: range,
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
