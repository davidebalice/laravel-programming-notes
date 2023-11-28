$(function () {
    $(document).on("click", "[id^='delete']", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        Swal.fire({
            title: "Are you sure?",
            text: "Delete this data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.createElement("form");
                form.action = link;
                form.method = "POST";
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});

$(function () {
    $(document).on("click", "#confirm", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are you sure to Confirm?",
            text: "Once Confirm, You will not be able to pending again?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Confirm!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire("Confirm!", "Confirm Change", "success");
            }
        });
    });
});

$(function () {
    $(document).on("click", "#processing", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are you sure to Processing?",
            text: "Once Processing, You will not be able to pending again?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Processing!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire("Processing!", "Processing Change", "success");
            }
        });
    });
});

$(function () {
    $(document).on("click", "#delivered", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are you sure to Delivered?",
            text: "Once Delivered, You will not be able to pending again?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delivered!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire("Delivered!", "Delivered Change", "success");
            }
        });
    });
});

$(function () {
    $(document).on("click", "#approved", function (e) {
        e.preventDefault();
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are you sure to Approved?",
            text: "Return Order Approved",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Approved!",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
                Swal.fire("Approved!", "Approved Change", "success");
            }
        });
    });
});
