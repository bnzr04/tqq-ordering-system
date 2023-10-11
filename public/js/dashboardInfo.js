function dashboardInfo(dashboardInfoRoute) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "GET",
            url: dashboardInfoRoute,
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            },
        });
    });
}
