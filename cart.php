<?php
include "./include/config.php";
include "./include/db.php";
// محاسبه مجموع قیمت سبد خرید
$totalPrice = 0;
// بررسی اینکه آیا درخواست از نوع AJAX است
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartItems'])) {
    // اطلاعات ارسال شده از طریق AJAX
    $cartItems = json_decode($_POST['cartItems'], true);

    // بررسی اینکه آیا cartItems داده‌ای دارد
    if (!empty($cartItems)) {
        $productIds = array_map(function ($item) {
            return $item['postId'];
        }, $cartItems);

        // آماده‌سازی کوئری برای دریافت اطلاعات محصولات از دیتابیس
        $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM posts WHERE id IN ($placeholders)");
        $stmt->execute($productIds);

        // دریافت اطلاعات محصولات
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ارسال اطلاعات محصولات به صورت JSON به کلاینت
        echo json_encode($products);
    } else {
        echo json_encode([]);
    }
    exit; // قطع اجرای اسکریپت پس از ارسال پاسخ
}
?>

<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>سبد خرید</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        @font-face {
            font-family: "Vazir";
            src: url("./assets/fonts/Vazir.eot");
            /* IE9 Compat Modes */
            src: url("./assets/fonts/Vazir.eot?#iefix") format("embedded-opentype"),
                url("./assets/fonts/Vazir.woff2") format("woff2"),
                url("./assets/fonts/Vazir.woff") format("woff"),
                url("./assets/fonts/Vazir.ttf") format("truetype");
            /* Safari, Android, iOS */
        }

        body {
            background-color: #37494E;
            font-family: "Vazir", sans-serif !important;
        }
    </style>

<body>

    <div class="container mt-4">
        <div class="row d-flex justify-content-between">
            <a class="col-6" href="./main.php">
                <i class="bi bi-x-octagon-fill text-danger-emphasis fs-3"></i>
            </a>
            <div class="col-6 text-start">
                <a href="main.php" class="fs-4 fw-bold link-body-emphasis text-decoration-none ">
                    Cafe Name
                </a>
            </div>
        </div>
        <hr class="text-white border-5 rounded">
        <div class="text-center text-white fs-2 fw-bold my-4">
            <h3>سبد خرید</h3>
        </div>
        <section class="text-center mb-4">
            <h6 class="text-white-50">
                <?php if ($totalPrice > 0): ?>
                    مجموع کل سفارش: <span id="total-price"><?= number_format($totalPrice) ?> تومان</span>
                <?php else: ?>
                    <span id="total-price">سبد خرید شما خالی است</span>
                <?php endif; ?>
            </h6>

            <!-- Input for Table Number -->
            <div class="sticky-bottom d-flex  gap-3 ">
                <div class="input-group flex-nowrap">
                    <input type="number" class="form-control text-center rounded-5" id="table_id"
                        placeholder="شماره میز" aria-describedby="addon-wrapping" required>
                </div>
                <button type="button" class="btn btn-dark">تایید</button>
            </div>
        </section>


        <div id="cart-items-container" class="row">
            <!-- محصولات سبد خرید در اینجا بارگذاری خواهند شد -->
        </div>
        <a href="./main.php" class="btn btn-secondary">بازگشت</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- showing items & deleting -->
    <script>
        // دریافت داده‌های سبد خرید از sessionStorage
        var cartItems = JSON.parse(sessionStorage.getItem('cart')) || [];

        // نمایش محصولات سبد خرید
        if (cartItems.length > 0) {
            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cartItems=' + encodeURIComponent(JSON.stringify(cartItems))
            })
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var totalPrice = 0;
                        var cartItemsContainer = document.getElementById('cart-items-container');
                        cartItemsContainer.innerHTML = ''; // پاک کردن محتوای قبلی

                        // افزودن هر محصول به صفحه
                        data.forEach(product => {
                            var cartItem = cartItems.find(item => item.postId === product.id);
                            var quantity = cartItem ? cartItem.quantity : 1;
                            var totalProductPrice = product.price * quantity;
                            totalPrice += totalProductPrice;

                            var productHtml = `
                    <div class="col-12 mb-4" id="cart-item-${product.id}">
                        <div class="card shadow-lg" style="background-color:#53656B;">
                            <div class="d-flex g-0">
                                <div class="col-4">
                                    <img src="./uploads/posts/${product.image}"
                                        class="img-fluid rounded-5 object-fit-fill p-3 h-100" loading="lazy"
                                        alt="${product.title}">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold fs-3">${product.title}</h5>
                                        <h6 class="card-text">
                                            تعداد: 
                                            <button class="btn btn-sm btn-danger change-quantity" data-post-id="${product.id}" data-action="decrease">-</button>
                                            <span class="fs-5 " id="quantity-${product.id}">${quantity}</span>
                                            <button class="btn btn-sm btn-success change-quantity" data-post-id="${product.id}" data-action="increase">+</button>
                                        </h6>
                                        <h6 class="" id="total-product-price-${product.id}">${totalProductPrice.toLocaleString()} تومان</h6>
                                        <button class="btn btn-sm remove-from-cart text-white" style="background-color:#3F545A;" data-post-id="${product.id}">
                                            حذف
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                            cartItemsContainer.innerHTML += productHtml;
                        });

                        // نمایش مجموع قیمت
                        document.getElementById('total-price').innerText = 'مجموع: ' + totalPrice.toLocaleString() + ' تومان';
                    } else {
                        document.getElementById('cart-items-container').innerHTML =
                            '<p class="text-center text-muted">سبد خرید شما خالی است.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            document.getElementById('cart-items-container').innerHTML =
                '<p class="text-center text-muted">سبد خرید شما خالی است.</p>';
        }

        // افزایش و کاهش تعداد محصول
        document.getElementById('cart-items-container').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('change-quantity')) {
                var postId = e.target.getAttribute('data-post-id'); // شناسه محصول
                var action = e.target.getAttribute('data-action'); // افزایش یا کاهش

                // پیدا کردن محصول در سبد خرید
                var cartItem = cartItems.find(item => item.postId === parseInt(postId));
                if (!cartItem) return;

                // افزایش یا کاهش تعداد
                if (action === 'increase') {
                    cartItem.quantity += 1;
                    location.reload();
                } else if (action === 'decrease' && cartItem.quantity > 1) {
                    cartItem.quantity -= 1;
                    location.reload();

                }

                // ذخیره‌سازی به‌روزرسانی شده در sessionStorage
                sessionStorage.setItem('cart', JSON.stringify(cartItems));

                // به روز رسانی تعداد و قیمت محصول
                document.getElementById('quantity-' + postId).innerText = cartItem.quantity;
                var product = cartItems.find(item => item.postId === parseInt(postId));
                var totalProductPrice = product.price * cartItem.quantity;
                document.getElementById('total-product-price-' + postId).innerText = totalProductPrice.toLocaleString() + ' تومان';

                // به روز رسانی مجموع قیمت
                updateTotalPrice();
            }
        });

        // حذف محصول از سبد خرید
        document.getElementById('cart-items-container').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-from-cart')) {
                var postId = e.target.getAttribute('data-post-id'); // شناسه محصول
                // حذف محصول از sessionStorage
                cartItems = cartItems.filter(item => item.postId !== parseInt(postId));
                sessionStorage.setItem('cart', JSON.stringify(cartItems));

                // حذف محصول از صفحه
                var cartItemElement = document.getElementById('cart-item-' + postId);
                if (cartItemElement) {
                    cartItemElement.remove();
                    // رفرش صفحه
                    location.reload(); // رفرش کردن صفحه
                }

                // به روز رسانی مجموع قیمت
                updateTotalPrice();
            }
        });

        // به روز رسانی مجموع قیمت بعد از تغییرات
        function updateTotalPrice() {
            var totalPrice = 0;
            cartItems.forEach(item => {
                var product = item.product; // فرض می‌کنیم محصول در cartItems ذخیره شده است
                totalPrice += product.price * item.quantity;
            });
            document.getElementById('total-price').innerText = 'مجموع: ' + totalPrice.toLocaleString() + ' تومان';
        }

    </script>
    <!-- add to cart -->
    <script>
        // add to cqrt
        document.querySelector('button[type="button"]').addEventListener('click', function () {
            var tableId = document.getElementById('table_id').value; // گرفتن شماره میز
            if (tableId <= 0) {
                alert('لطفاً شماره میز را وارد کنید');
                return;
            }

            // دریافت داده‌های سبد خرید از sessionStorage
            var cartItems = JSON.parse(sessionStorage.getItem('cart')) || [];

            // بررسی اینکه سبد خرید خالی نباشد
            if (cartItems.length > 0) {
                var allItemsAdded = true;

                // ارسال هر آیتم به سرور با تعداد جدید
                cartItems.forEach(function (item) {
                    fetch('add_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'post_id=' + encodeURIComponent(item.postId) +
                            '&table_id=' + encodeURIComponent(tableId) +
                            '&quantity=' + encodeURIComponent(item.quantity) // ارسال تعداد محصول
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status !== 'success') {
                                allItemsAdded = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            allItemsAdded = false;
                        });
                });



                // نمایش SweetAlert بعد از ارسال اطلاعات
                setTimeout(function () {
                    if (allItemsAdded) {
                        Swal.fire({
                            icon: 'success',
                            title: 'محصولات با موفقیت به سبد خرید اضافه شدند',
                            showConfirmButton: false,
                            timer: 1500 // 1.5 ثانیه برای نمایش
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطا در اضافه کردن محصولات',
                            text: 'لطفاً دوباره تلاش کنید.',
                        });
                    }
                }, 500); // فاصله زمانی کوتاه قبل از نمایش پیام
            } else {
                alert('سبد خرید خالی است');
            }
        });

    </script>


</body>

</html>