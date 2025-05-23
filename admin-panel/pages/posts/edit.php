<?php
include "../../include/layout/header.php";

// Check if post ID is passed in GET request
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $post = $db->prepare('SELECT * FROM posts WHERE id = :id');
    $post->execute(['id' => $postId]);
    $post = $post->fetch(PDO::FETCH_ASSOC);

    $categories = $db->query("SELECT * FROM categories");
}

$invalidInputTitle = '';
$invalidInputPrice = '';
$invalidInputDescription = '';

// Check if form is submitted
if (isset($_POST['editPost'])) {

    // Validate form inputs
    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = 'فیلد عنوان محصول الزامیست';
    }
    if (empty(trim($_POST['description']))) {
        $invalidInputDescription = 'فیلد توضیحات محصول الزامیست';
    }
    if (empty(trim($_POST['price']))) {
        $invalidInputPrice = 'فیلد قیمت محصول الزامیست';
    }

    // If inputs are valid, update the post
    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['price']))) {
        $title = $_POST['title'];
        $price = str_replace(',', '', $_POST['price']);
        $description = $_POST['description'];
        $categoryId = $_POST['categoryId'];
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
        $isStockTracked = isset($_POST['is_stock_tracked']) ? 1 : 0;

        if (!empty(trim($_FILES['image']['name']))) {
            // Handle image upload
            $nameImage = time() . "_" . $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];

            if (move_uploaded_file($tmpName, "../../../uploads/posts/$nameImage")) {
                $postUpdate = $db->prepare("UPDATE posts SET title = :title, price = :price, description = :description, category_id = :categoryId, image = :image, stock = :stock, is_stock_tracked = :isStockTracked WHERE id = :id");
                $postUpdate->execute([
                    'title' => $title,
                    'price' => $price,
                    'description' => $description,
                    'categoryId' => $categoryId,
                    'image' => $nameImage,
                    'stock' => $stock,
                    'isStockTracked' => $isStockTracked,
                    'id' => $postId
                ]);
            } else {
                echo "Upload Error";
            }
        } else {
            // Update post without changing image
            $postUpdate = $db->prepare("UPDATE posts SET title = :title, price = :price, description = :description, category_id = :categoryId, stock = :stock, is_stock_tracked = :isStockTracked WHERE id = :id");
            $postUpdate->execute([
                'title' => $title,
                'price' => $price,
                'description' => $description,
                'categoryId' => $categoryId,
                'stock' => $stock,
                'isStockTracked' => $isStockTracked,
                'id' => $postId
            ]);
        }

        header("Location:index.php");
        exit();
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../include/layout/sidebar.php"; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش محصول</h1>
            </div>

            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <!-- Title -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان محصول</label>
                        <input type="text" name="title" class="form-control" value="<?= $post['title'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>

                    <!-- Description -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">توضیحات محصول</label>
                        <input type="text" name="description" class="form-control"
                            value="<?= $post['description'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputDescription ?></div>
                    </div>

                    <!-- Price -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">قیمت محصول</label>
                        <input type="text" name="price" class="form-control"
                            value="<?= htmlspecialchars(number_format($post['price'])) ?>" />
                        <div class="form-text text-danger"><?= $invalidInputPrice ?></div>
                    </div>

                    <!-- Category -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی محصول</label>
                        <select name="categoryId" class="form-select">
                            <?php if ($categories->rowCount() > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option <?= ($category['id'] == $post['category_id']) ? 'selected' : '' ?>
                                        value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">تعداد موجودی</label>
                        <input type="number" name="stock" class="form-control" value="<?= $post['stock'] ?>" />
                    </div>

                    <!-- Stock Tracking Toggle -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label d-block">وضعیت کنترل موجودی</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_stock_tracked" value="1"
                                id="isStockTracked" <?= $post['is_stock_tracked'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isStockTracked">
                                کنترل موجودی فعال باشد
                            </label>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر محصول</label>
                        <input name="image" class="form-control" type="file" />
                    </div>

                    <!-- Display Current Image -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <img class="rounded" src="../../../uploads/posts/<?= $post['image'] ?>" width="300" />
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button name="editPost" type="submit" class="btn btn-dark">
                            ویرایش
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>