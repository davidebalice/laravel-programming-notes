const site_url = document.getElementById("siteUrl").val();

$("body").on("keyup", "#search", function () {
    let text = $("#search").val();

    if (text.length > 0) {
        $.ajax({
            data: { search: text },
            url: site_url + "search-product",
            method: "post",
            beforSend: function (request) {
                return request.setRequestHeader(
                    "X-CSRF-TOKEN",
                    "meta[name='csrf-token']"
                );
            },

            success: function (result) {
                $("#searchProducts").html(result);
            },
        });
    }

    if (text.length < 1) $("#searchProducts").html("");
});
