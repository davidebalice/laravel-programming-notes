(function ($) {
    "use strict";
    var productDetails = function () {
        $(".product-image-slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: false,
            asNavFor: ".slider-nav-thumbnails",
        });

        $(".slider-nav-thumbnails").slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: ".product-image-slider",
            dots: false,
            focusOnSelect: true,

            prevArrow:
                '<button type="button" class="slick-prev"><i class="fi-rs-arrow-small-left"></i></button>',
            nextArrow:
                '<button type="button" class="slick-next"><i class="fi-rs-arrow-small-right"></i></button>',
        });

        $(".slider-nav-thumbnails .slick-slide").removeClass("slick-active");

        $(".slider-nav-thumbnails .slick-slide").eq(0).addClass("slick-active");

        $(".product-image-slider").on(
            "beforeChange",
            function (event, slick, currentSlide, nextSlide) {
                var mySlideNumber = nextSlide;
                $(".slider-nav-thumbnails .slick-slide").removeClass(
                    "slick-active"
                );
                $(".slider-nav-thumbnails .slick-slide")
                    .eq(mySlideNumber)
                    .addClass("slick-active");
            }
        );

        $(".product-image-slider").on(
            "beforeChange",
            function (event, slick, currentSlide, nextSlide) {
                var img = $(slick.$slides[nextSlide]).find("img");
                $(".zoomWindowContainer,.zoomContainer").remove();
                $(img).elevateZoom({
                    zoomType: "inner",
                    cursor: "crosshair",
                    zoomWindowFadeIn: 500,
                    zoomWindowFadeOut: 750,
                });
            }
        );
        if ($(".product-image-slider").length) {
            $(".product-image-slider .slick-active img").elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 750,
            });
        }
        $(".list-filter").each(function () {
            $(this)
                .find("a")
                .on("click", function (event) {
                    event.preventDefault();
                    $(this).parent().siblings().removeClass("active");
                    $(this).parent().toggleClass("active");
                    $(this)
                        .parents(".attr-detail")
                        .find(".current-size")
                        .text($(this).text());
                    $(this)
                        .parents(".attr-detail")
                        .find(".current-color")
                        .text($(this).attr("data-color"));
                });
        });
        $(".detail-qty").each(function () {
            var qtyval = parseInt($(this).find(".qty-val").val(), 10);

            $(".qty-up").on("click", function (event) {
                event.preventDefault();
                qtyval = qtyval + 1;
                $(this).prev().val(qtyval);
            });

            $(".qty-down").on("click", function (event) {
                event.preventDefault();
                qtyval = qtyval - 1;
                if (qtyval > 1) {
                    $(this).next().val(qtyval);
                } else {
                    qtyval = 1;
                    $(this).next().val(qtyval);
                }
            });
        });

        $(".dropdown-menu .cart_list").on("click", function (event) {
            event.stopPropagation();
        });
    };

    $(document).ready(function () {
        productDetails();
    });
})(jQuery);

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function productView(id) {
    $.ajax({
        type: "GET",
        url: "/product/view/modal/" + id,
        dataType: "json",
        success: function (data) {
            console.log(data);
            $("#pname").text(data.product.name);
            $("#pprice").text(data.product.price);
            $("#pcode").text(data.product.code);
            $("#pcategory").text(data.product.category.name);
            $("#pbrand").text(data.product.brand.name);
            $("#pimage").attr("src", "/" + data.product.image);
            $("#pvendor_id").text(data.product.vendor_id);
            $("#product_id").val(id);
            $("#qty").val(1);

            if (data.product.discount_price == null) {
                $("#pprice").text("");
                $("#oldprice").text("");
                $("#pprice").text(data.product.price);
            } else {
                $("#pprice").text(data.product.price);
                $("#oldprice").text(data.product.price);
            }
            if (data.product.product_qty > 0) {
                $("#aviable").text("");
                $("#stockout").text("");
                $("#aviable").text("aviable");
            } else {
                $("#aviable").text("");
                $("#stockout").text("");
                $("#stockout").text("stockout");
            }

            $('select[name="size"]').empty();
            $.each(data.size, function (key, value) {
                $('select[name="size"]').append(
                    '<option value="' + value + ' ">' + value + "  </option"
                );
                if (data.size == "") {
                    $("#sizeArea").hide();
                } else {
                    $("#sizeArea").show();
                }
            });

            $('select[name="color"]').empty();
            $.each(data.color, function (key, value) {
                $('select[name="color"]').append(
                    '<option value="' + value + ' ">' + value + "  </option"
                );
                if (data.color == "") {
                    $("#colorArea").hide();
                } else {
                    $("#colorArea").show();
                }
            });
        },
    });
}

function addCart() {
    var name = $("#pname").text();
    var id = $("#product_id").val();
    var color = $("#color option:selected").val();
    var size = $("#size option:selected").val();
    var vendor = $("#pvendor").text();
    var quantity = $("#qty").val();

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {
            color: color,
            size: size,
            quantity: quantity,
            name: name,
            vendor: vendor,
        },
        url: "/cart/data/store/" + id,
        success: function (data) {
            headerCart();
            $("#closeModal").click();
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    title: data.error,
                });
            }
        },
    });
}

function headerCart() {
    $.ajax({
        type: "GET",
        url: "/product/header/cart",
        dataType: "json",
        success: function (response) {
            $('span[id="cartSubTotal"]').text(response.cartTotal);
            $('span[id="cartSubTotalMobile"]').text(response.cartTotal);
            $("#cartQty").text(response.cartQty);
            $("#cartQtyMobile").text(response.cartQty);
            var headerCart = "";
            $.each(response.carts, function (key, value) {
                headerCart += ` <ul>
                <li>
                    <div class="shopping-cart-img">
                        <a href="shop-product-right.html"><img alt="Nest" src="/${value.options.image} " style="width:50px;height:50px;" /></a>
                    </div>
                    <div class="shopping-cart-title" style="margin: -73px 74px 14px; width" 146px;>
                        <h4><a href="shop-product-right.html"> ${value.name} </a></h4>
                        <h4><span>${value.qty} × </span>${value.price}</h4>
                    </div>
                    <div class="shopping-cart-delete" style="margin: -85px 1px 0px;">
                        <a type="submit" id="${value.rowId}" onclick="headerCartRemove(this.id)"  ><i class="fi-rs-cross-small"></i></a>
                    </div>
                </li> 
            </ul>
                   `;
            });
            $("#headerCart").html(headerCart);
            $("#headerCartMobile").html(headerCart);
        },
    });
}

headerCart();

function headerCartRemove(rowId) {
    $.ajax({
        type: "GET",
        url: "/headercart/product/remove/" + rowId,
        dataType: "json",
        success: function (data) {
            headerCart();
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    title: data.error,
                });
            }
        },
    });
}

function cartRemove(rowId) {
    $.ajax({
        type: "GET",
        url: "/headercart/product/remove/" + rowId,
        dataType: "json",
        success: function (data) {
            headerCart();
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    title: data.success,
                });
                window.location.href = "/cart";
            } else {
                Toast.fire({
                    type: "error",
                    title: data.error,
                });
            }
        },
    });
}

function addCartDetails() {
    var name = $("#dpname").text();
    var id = $("#dproduct_id").val();
    var color = $("#dcolor option:selected").val();
    var size = $("#dsize option:selected").val();
    var vendor = $("#pvendor").text();
    var quantity = $("#dqty").val();
    $.ajax({
        type: "POST",
        dataType: "json",
        data: {
            color: color,
            size: size,
            quantity: quantity,
            name: name,
            vendor: vendor,
        },
        url: "/dcart/data/store/" + id,
        success: function (data) {
            headerCart();

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    title: data.error,
                });
            }
        },
    });
}

function addToWishList(product_id) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/add-to-wishlist/" + product_id,
        success: function (data) {
            wishlist();
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    icon: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    icon: "error",
                    title: data.error,
                });
            }
        },
    });
}

function wishlist() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/get-wishlist-product/",
        success: function (response) {
            $("#wishQty").text(response.wishQty);
            $("#wishQtyMobile").text(response.wishQty);
            var rows = "";
            $.each(response.wishlist, function (key, value) {
                rows += `<tr class="pt-30">
                 <td class="custome-checkbox pl-30">
                     
                 </td>
                 <td class="image product-thumbnail pt-40"><img src="/${
                     value.product.image
                 }" alt="#" /></td>
                 <td class="product-des product-name">
                     <h6><a class="product-name mb-10" href="shop-product-right.html">${
                         value.product.name
                     } </a></h6>
                     <div class="product-rate-cover">
                         <div class="product-rate d-inline-block">
                             <div class="product-rating" style="width: 90%"></div>
                         </div>
                         <span class="font-small ml-5 text-muted"> (4.0)</span>
                     </div>
                 </td>
                 <td class="price" data-title="Price">
                 ${
                     value.product.discount_price == null
                         ? `<h3 class="text-brand">$${value.product.price}</h3>`
                         : `<h3 class="text-brand">$${value.product.discount_price}</h3>`
                 }
                     
                 </td>
                 <td class="text-center detail-info" data-title="Stock">
                     ${
                         value.product.qty > 0
                             ? `<span class="stock-status in-stock mb-0"> In Stock </span>`
                             : `<span class="stock-status out-stock mb-0">Stock Out </span>`
                     } 
                    
                 </td>
                
                 <td class="action text-center" data-title="Remove">
                    <a type="submit" class="text-body" id="${
                        value.id
                    }" onclick="wishlistRemove(this.id)" ><i class="fi-rs-trash"></i></a>
                 </td>
             </tr> `;
            });
            $("#wishlist").html(rows);
        },
    });
}

wishlist();

function wishlistRemove(id) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/wishlist-remove/" + id,
        success: function (data) {
            wishlist();
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    icon: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    icon: "error",
                    title: data.error,
                });
            }
        },
    });
}

function addToCompare(product_id) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/add-to-compare/" + product_id,
        success: function (data) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",

                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    icon: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    icon: "error",
                    title: data.error,
                });
            }
        },
    });
}

function compare() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/get-compare-product/",
        success: function (response) {
            var rows = "";
            $.each(response, function (key, value) {
                rows += ` <tr class="pr_image">
                        <td class="text-muted font-sm fw-600 font-heading mw-200">Preview</td>
                        <td class="row_img"><img src="/
                        ${value.product.image} 
                        " style="width:300px; height:300px;"  alt="compare-img" /></td>
                            
                    </tr>
                    <tr class="pr_title">
                            <td class="text-muted font-sm fw-600 font-heading">Name</td>
                            <td class="product_name">
                                <h6><a href="shop-product-full.html" class="text-heading">
                                 ${value.product.name} 
                                </a></h6>
                            </td>
                           
                    </tr>
                    <tr class="pr_price">
                            <td class="text-muted font-sm fw-600 font-heading">Price</td>
                            <td class="product_price">
                                ${
                                    value.product.discount_price == null
                                        ? `<h4 class="price text-brand">$${value.product.price}</h4>`
                                        : `<h4 class="price text-brand">$${value.product.discount_price}</h4>`
                                } 
                            </td>
                        </tr>
                        
                        <tr class="description">
                            <td class="text-muted font-sm fw-600 font-heading">Description</td>
                            <td class="row_text font-xs">
                                <p class="font-sm text-muted"> 
                                    ${value.product.short_desc}
                                </p>
                            </td>
                        </tr>

                        <tr class="pr_stock">
                            <td class="text-muted font-sm fw-600 font-heading">Stock status</td>
                            <td class="row_stock">
                                ${
                                    value.product.qty > 0
                                        ? `<span class="stock-status in-stock mb-0"> In Stock </span>`
                                        : `<span class="stock-status out-stock mb-0">Stock Out </span>`
                                } 
                      </td>
                </tr>
                        
                <tr class="pr_remove text-muted">
                    <td class="text-muted font-md fw-600"></td>
                    <td class="row_remove">
                    <a type="submit" class="text-muted"  id="${
                        value.id
                    }" onclick="compareRemove(this.id)"><i class="fi-rs-trash mr-5"></i><span>Remove</span> </a>
                    </td>
                    
                </tr> `;
            });
            $("#compare").html(rows);
        },
    });
}

compare();

function compareRemove(id) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/compare-remove/" + id,
        success: function (data) {
            compare();
            // Start Message
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",

                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    icon: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    icon: "error",
                    title: data.error,
                });
            }
        },
    });
}

function cart() {
    $.ajax({
        type: "GET",
        url: "/get-cart-product",
        dataType: "json",
        success: function (response) {
            var rows = "";
            var cartQty = response.cartQty;

            if (cartQty == 0) {
                rows =
                    '<tr><td scope="col" colspan="10" class="emptyCart"><br /><br /><br />Cart is empty<br /><br /><br /></td></tr>';
            }

            $.each(response.carts, function (key, value) {
                rows += `<tr class="pt-30">
            <td class="custome-checkbox pl-30">
                 
            </td>
            <td class="image product-thumbnail pt-40"><img src="/${
                value.options.image
            } " alt="#"></td>
            <td class="product-des product-name">
                <h6 class="mb-5"><a class="product-name mb-10 text-heading" href="shop-product-right.html">${
                    value.name
                } </a></h6>
                
            </td>
            <td class="price" data-title="Price">
                <h4 class="text-body">$${value.price} </h4>
            </td>
              <td class="price" data-title="Price">
              ${
                  value.options.color == null
                      ? `<span></span>`
                      : `<h6 class="text-body">${value.options.color} </h6>`
              } 
            </td>
               <td class="price" data-title="Price">
              ${
                  value.options.size == null
                      ? `<span></span>`
                      : `<h6 class="text-body">${value.options.size} </h6>`
              } 
            </td>
            <td class="text-center detail-info" data-title="Stock">
                <div class="detail-extralink mr-15">
                    <div class="detail-qty border radius">
                        <a type="submit" class="qty-down" id="${
                            value.rowId
                        }" onclick="cartDecrement(this.id)">
                            <i class="fi-rs-angle-small-down"></i>
                        </a>
                        <input type="text" name="quantity" class="qty-val" value="${
                            value.qty
                        }" min="1">
                        <a  type="submit" class="qty-up" id="${
                            value.rowId
                        }" onclick="cartIncrement(this.id)">
                            <i class="fi-rs-angle-small-up"></i>
                        </a>
                    </div>
                </div>
            </td>
            <td class="price" data-title="Price">
                <h4 class="text-brand">$${value.subtotal} </h4>
            </td>
            <td class="action text-center" data-title="Remove">
                <a href="#" onclick="cartRemove('${
                    value.rowId
                }')" class="text-body"><i class="fi-rs-trash trash"></i></a>
            </td>
        </tr>`;
            });
            $("#cartPage").html(rows);
        },
    });
}

cart();

function cartDecrement(rowId) {
    $.ajax({
        type: "GET",
        url: "/cart-decrement/" + rowId,
        dataType: "json",
        success: function (data) {
            couponCalculation();
            cart();
            headerCart();
        },
    });
}

function cartIncrement(rowId) {
    $.ajax({
        type: "GET",
        url: "/cart-increment/" + rowId,
        dataType: "json",
        success: function (data) {
            couponCalculation();
            cart();
            headerCart();
        },
    });
}

function applyCoupon() {
    var name = $("#name").val();
    $.ajax({
        type: "POST",
        dataType: "json",
        data: { name: name },
        url: "/coupon-apply",
        success: function (data) {
            couponCalculation();
            if (data.validity == true) {
                $("#couponField").hide();
            }
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    icon: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    icon: "error",
                    title: data.error,
                });
            }
        },
    });
}

function couponCalculation() {
    $.ajax({
        type: "GET",
        url: "/coupon-calculation",
        dataType: "json",
        success: function (data) {
            if (data.total) {
                $("#couponCalField").html(
                    ` <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Subtotal</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">$${data.total}</h4>
                    </td>
                </tr>
                 
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Grand Total</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">$${data.total}</h4>
                    </td>
                </tr>
                `
                );
            } else {
                $("#couponCalField").html(
                    `<tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Subtotal</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">$${data.subtotal}</h4>
                    </td>
                </tr>
                 
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Coupon </h6>
                    </td>
                    <td class="cart_total_amount">
                    <h6 class="text-brand text-end">${data.name} <a type="submit" onclick="couponRemove()"><i class="fi-rs-trash"></i> </a> </h6>
                    </td>
                </tr>
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Discount Amount  </h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">$${data.discount_amount}</h4>
                    </td>
                </tr>
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Grand Total </h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">$${data.total_amount}</h4>
                    </td>
                </tr> `
                );
            }
        },
    });
}

couponCalculation();

function couponRemove() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/coupon-remove",
        success: function (data) {
            couponCalculation();
            $("#couponField").show();
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",

                showConfirmButton: false,
                timer: 3000,
            });
            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: "success",
                    icon: "success",
                    title: data.success,
                });
            } else {
                Toast.fire({
                    type: "error",
                    icon: "error",
                    title: data.error,
                });
            }
        },
    });
}
