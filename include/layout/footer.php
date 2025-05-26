<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Link to Swiper.js (optional) -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://unpkg.com/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<!-- add to chart -->
<script>
    $(document).ready(function () {
        $('.add-to-cart').on('click', function () {
            var postId = $(this).data('post-id');
            var stock = parseInt($(this).data('stock'));
            var is_stock_tracked = parseInt($(this).data('is-stock-tracked'));
			
            if (is_stock_tracked === 1 && stock === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'ناموجود',
                    text: 'این محصول در حال حاضر موجود نیست.'
                });
                return; // از ادامه اجرای کد جلوگیری می‌کنیم
            }

            // ادامه فرآیند افزودن به سبد
            if (!sessionStorage.getItem('cart')) {
                sessionStorage.setItem('cart', JSON.stringify([]));
            }

            var cart = JSON.parse(sessionStorage.getItem('cart'));
            var productFound = false;

            for (var i = 0; i < cart.length; i++) {
                if (cart[i].postId === postId) {
                    cart[i].quantity += 1;
                    productFound = true;
                    break;
                }
            }

            if (!productFound) {
                cart.push({
                    postId: postId,
                    quantity: 1
                });
            }

            sessionStorage.setItem('cart', JSON.stringify(cart));
            $('#iconNumber').text(cart.length);

            Swal.fire({
                position: "top",
                icon: "success",
                title: "سفارش با موفقیت به سبد خرید اضافه شد",
                showConfirmButton: false,
                timer: 1500
            });
        });

        // نمایش تعداد محصولات در بارگذاری اولیه
        if (sessionStorage.getItem('cart')) {
            var cart = JSON.parse(sessionStorage.getItem('cart'));
            $('#iconNumber').text(cart.length);
        }
    });
	

</script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 3,
        spaceBetween: 30,
        freeMode: true,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        easing: 'ease-in',
        breakpoints: {
            300: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }

    });
</script>

<!-- Initialize categories Swiper -->
<script>
    var swiper = new Swiper(".mySwiper1", {
        slidesPerView: 3,
        spaceBetween: 10,
        freeMode: true,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        easing: 'linear',


    });
</script>

<!-- go to diffrent cart -->
<script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener("DOMContentLoaded", function () {
        var tableId = document.getElementById('table_id').addEventListener('change', function () {
            let inputValue = this.value;
            console.log(inputValue);

            // Find the cart link element
            var cartLink = document.getElementById('cart-link');

            // Modify the href dynamically
            cartLink.href = './cart.php?table_id=' + inputValue;
        });


    });
</script>

<!-- // Smooth scroll to the category section -->
<script>
    document.querySelectorAll('a[href^="#category-"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Highlight active category in the sticky bar as you scroll
    const categoryLinks = document.querySelectorAll('.category-link');
    const categorySections = document.querySelectorAll('.category-section');

    window.addEventListener('scroll', () => {
        let currentCategory = '';

        categorySections.forEach(section => {
            const sectionTop = section.offsetTop - 100; // Adjust for sticky bar height
            if (window.scrollY >= sectionTop) {
                currentCategory = section.getAttribute('id');
            }
        });

        categoryLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').substring(1) === currentCategory) {
                link.classList.add('active');
            }
        });
    });
</script>

<!-- category header Box -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sections = document.querySelectorAll(".category-section");
        const categoryBox = document.getElementById("dynamic-category-box");
        const categoryIcon = document.getElementById("dynamic-category-icon");
        const categoryTitle = document.getElementById("dynamic-category-title");
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const categoryId = entry.target.getAttribute("data-category-id");
                    const categoryImage = entry.target.querySelector("img").src; // تصویر دسته‌بندی
                    const categoryName = entry.target.querySelector("h2")
                        .textContent; // نام دسته‌بندی

                    // به‌روزرسانی باکس دینامیک
                    categoryIcon.innerHTML =
                        `<img src="${categoryImage}" class="rounded-circle object-fit-contain" style="width: 50px; height: 50px;">`;
                    categoryTitle.textContent = categoryName;

                }
            });
        }, {
            threshold: 0.5
        }); // وقتی 50% از دسته در ویو است

        sections.forEach(section => observer.observe(section));
    });
</script>

<!-- empty table Check -->
<script>
    document.getElementById('cartButton').addEventListener('click', function (event) {
        var tableIdInput = document.getElementById('table_id').value;

        // Check if the table_id input is empty
        if (tableIdInput === '') {
            // Prevent the default action (opening the cart modal)
            event.preventDefault();

            // Show SweetAlert with the error message
            Swal.fire({
                title: 'خطا!',
                text: 'شماره میز وارد نشده است',
                icon: 'error',
                confirmButtonText: 'باشه'
            });
        }
    });
</script>


<!-- caching -->
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then((registration) => {
                console.log('Service Worker registered with scope: ', registration.scope);
            })
            .catch((error) => {
                console.log('Service Worker registration failed: ', error);
            });
    }
</script>
</body>

</html>