<?php
include "./include/layout/header.php";


// Check if a category is provided in the URL, if not default to category 16
$categoryId = isset($_GET['category']) ? $_GET['category'] : 16;

// Prepare the SQL query to fetch posts based on the category
$posts = $db->prepare("SELECT * FROM posts WHERE category_id = :id ORDER BY id DESC");
$posts->execute(['id' => $categoryId]);

// Fetch categories from the database
$categories = $db->query("SELECT * FROM categories")->fetchAll();

// Handle cart functionality
$tableId = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;
$cartItems = [];
$totalPrice = 0;

if ($tableId > 0) {
    $cartTable = 'cart_' . $tableId;
    try {
        $query = "
            SELECT c.id AS cart_id, c.quantity, p.title, p.price, p.image
            FROM $cartTable c
            JOIN posts p ON c.post_id = p.id
        ";
        $cartItems = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cartItems as $item) {
            $totalPrice += (float) $item['price'] * (int) $item['quantity'];
        }
    } catch (PDOException $e) {
        echo 'Database Error: ' . $e->getMessage();
    }
}
?>
<!-- ********************************************** -->
<!-- ********************************************** -->
<!-- ********************************************** -->
<!-- ********************************************** -->
<!-- Dynamic Category Box -->
<section id="dynamic-category-box"
    class="m-2 sticky-top border border-danger-subtle border-2 rounded-5 row align-items-center p-3 row justify-content-between glass-bg"
    style="direction:rtl; background-color: #f8f9fa; top: .5rem;">
    <div class="col-10 d-flex align-items-center">
        <div class="col-3" id="dynamic-category-icon"></div>
        <div class="col-9" id="dynamic-category-title"></div>
    </div>
    <div class="col-2 align-items-center rounded-5" style=" border-right: 3px solid #B69F7C !important;">
        <a href="index.php" class="text-decoration-none text-black ">
            <i class="bi bi-grid fs-1"></i>
        </a>
    </div>

</section>


<!-- Display Products by Category -->
<main class="container p-0 mt-4">
    <?php foreach ($categories as $category): ?>
        <section id="category-<?= $category['id'] ?>" class="mb-5 category-section"
            data-category-id="<?= $category['id'] ?>">
            <h2 class="text-center py-2 rounded" style="color: #B2BBBD;">
                <?= htmlspecialchars($category['title']) ?>
            </h2>
            <img src="./uploads/categories/<?= htmlspecialchars($category['image']) ?>" loading="lazy" alt=""
                class="d-none">
            <?php
            $categoryPosts = $db->prepare("SELECT * FROM posts WHERE category_id = :id ORDER BY id DESC");
            $categoryPosts->execute(['id' => $category['id']]);
            ?>
            <div class="row">
                <?php if ($categoryPosts->rowCount() > 0): ?>
                    <?php foreach ($categoryPosts as $post): ?>
                        <div class="col-12 mb-4">
                            <div class="card shadow-lg" style="background-color:#53656B;">
                                <div class="d-flex g-0">
                                    <div class="col-4 position-relative">
                                        <img src="./uploads/posts/<?= htmlspecialchars($post['image']) ?>"
                                            class="img-fluid rounded-5 object-fit-fill p-3 position-absolute"
                                            style="width:100% !important; height:70% !important;" loading="lazy"
                                            alt="<?= htmlspecialchars($post['title']) ?>">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold fs-3"><?= htmlspecialchars($post['title']) ?></h5>
                                            <h6 class="card-title fw-medium  text-primary-emphasis"
                                                style="font-size: .85rem;cursor: pointer;" onclick="Swal.fire({
                                                    title: '<?= htmlspecialchars($post['title']) ?>',
                                                    text: '<?= htmlspecialchars($post['description']) ?>',
                                                    imageUrl: './uploads/posts/<?= htmlspecialchars($post['image']) ?>',
                                                    imageWidth: 200,
                                                    imageHeight: 200,
                                                    customClass: {
                                                        popup: 'custom-popup', // کلاس برای پنجره اصلی
                                                        image: 'custom-image'  // کلاس برای عکس
                                                    }
                                                    });
                                                    ">
                                                مشاهده بیشتر>
                                            </h6>

                                            <p class="card-text">
                                                <?= htmlspecialchars(number_format($post['price'])) ?> تومان
                                            </p>
                                            <button class="btn btn-sm add-to-cart text-white" id="playButton"
                                                style="background-color:#3F545A;" data-post-id="<?= $post['id'] ?>"
                                                data-stock="<?= $post['stock'] ?>">
                                                افزودن به سبد
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="col">
                        <p class="text-center">محصولی یافت نشد.</p>
                    </div>
                <?php endif ?>
            </div>
        </section>
    <?php endforeach ?>
</main>

<footer class="sticky-bottom d-flex align-items-center">

    <!-- cart button -->
    <button type="button" class="btn btn-light sticky-bottom px-2 py-0 fs-1 mb-2" style="right:85vw;" id="cartButton"
        data-bs-toggle="modal" data-bs-target="#cartModal">
        <a id="cart-link" href="./cart.php">
            <i class="bi bi-journal-text position-relative text-black">
                <span class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.6rem;">
                    <span id="iconNumber">0</span>
                </span>
            </i>
        </a>
    </button>
</footer>


<?php include "./include/layout/footer.php"; ?>