function generateReceipt(orderId, cash, change, generateReceiptRoute) {
    var cash = cash.toFixed(2);
    var change = change.toFixed(2);
    $.ajax({
        type: "get",
        url:
            generateReceiptRoute +
            "?order_id=" +
            orderId +
            "&cash=" +
            cash +
            "&change=" +
            change,
        success: function (data) {
            var newTab = window.open(
                generateReceiptRoute +
                    "?order_id=" +
                    orderId +
                    "&cash=" +
                    cash +
                    "&change=" +
                    change,
                "_blank"
            );

            // Function to close the tab
            function closeTab() {
                if (!newTab.closed) {
                    newTab.close();
                }
            }

            newTab.onload = function () {
                newTab.print();

                setTimeout(closeTab, 1000);
            };
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}

function makeOrderAsPaid(orderId, makeOrderAsPaidRoute) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            type: "post",
            url: makeOrderAsPaidRoute,
            data: {
                order_id: orderId,
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
