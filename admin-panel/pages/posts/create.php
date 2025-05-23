<?php
include "../../include/layout/header.php";

// Fetch categories
$categories = $db->query("SELECT * FROM categories");

// Initialize error messages
$invalidInputTitle = '';
$invalidInputPrice = '';
$invalidInputImage = '';
$invalidInputDescription = '';

// Handle form submission
if (isset($_POST['addPost'])) {

    // Validation checks
    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان مقاله الزامیست';
    }
    if (empty(trim($_POST['description']))) {
        $invalidInputDescription = 'فیلد توضیحات الزامیست';
    }
    if (empty(trim($_POST['price']))) {
        $invalidInputPrice = 'فیلد قیمت الزامیست';
    }
    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = 'فیلد تصویر محصول الزامیست';
    }

    // Proceed if all fields are valid
    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['price'])) && !empty(trim($_FILES['image']['name']))) {
        $title = $_POST['title'];
        $price = str_replace(',', '', $_POST['price']);
        $description = $_POST['description'];
        $categoryId = $_POST['categoryId'];
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
        $isStockTracked = isset($_POST['is_stock_tracked']) ? 1 : 0;

        // File upload logic
        $nameImage = time() . "_" . $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        if (move_uploaded_file($tmpName, "../../../uploads/posts/$nameImage")) {

            // Insert with stock and tracking
            $postInsert = $db->prepare("INSERT INTO posts (title, price, description, category_id, image, stock, is_stock_tracked) VALUES (:title, :price, :description, :category_id, :image, :stock, :is_stock_tracked)");
            $postInsert->execute([
                'title' => $title,
                'price' => $price,
                'description' => $description,
                'category_id' => $categoryId,
                'image' => $nameImage,
                'stock' => $stock,
                'is_stock_tracked' => $isStockTracked
            ]);

            header("Location:index.php");
            exit();
        } else {
            echo "Upload Error";
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد محصول</h1>
            </div>

            <!-- Create Post Form -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <!-- Title -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان محصول</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>

                    <!-- Description -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">توضیحات محصول</label>
                        <input type="text" name="description" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputDescription ?></div>
                    </div>

                    <!-- Price -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">قیمت</label>
                        <input type="text" name="price" class="form-control" id="persianInput"
                            oninput="convertPersianToEnglish(this)" />
                        <div class="form-text text-danger"><?= $invalidInputPrice ?></div>
                    </div>

                    <!-- Category -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی محصولات</label>
                        <select name="categoryId" class="form-select">
                            <?php if ($categories->rowCount() > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">تعداد موجودی</label>
                        <input type="number" name="stock" class="form-control" value="0" />
                    </div>

                    <!-- Stock Tracking Toggle -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label d-block">وضعیت کنترل موجودی</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_stock_tracked" value="1"
                                id="isStockTracked" checked>
                            <label class="form-check-label" for="isStockTracked">
                                کنترل موجودی فعال باشد
                            </label>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر محصول</label>
                        <input class="form-control" name="image" type="file" autocomplete="off" />
                        <div class="form-text text-danger"><?= $invalidInputImage ?></div>
                    </div>

                    <!-- Submit -->
                    <div class="col-12">
                        <button type="submit" name="addPost" class="btn btn-dark">
                            ایجاد
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>