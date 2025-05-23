<?php
include "../../include/layout/header.php";

if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    // Fetch category data
    $category = $db->prepare('SELECT * FROM categories WHERE id = :id');
    $category->execute(['id' => $categoryId]);
    $category = $category->fetch();
}

if (isset($_POST['editCategory'])) {
    $title = trim($_POST['title']);
    $alt = trim($_POST['alt']); // Get the alt text from the form input

    if (!empty($title)) {
        // Update the category title and alt text
        $categoryUpdate = $db->prepare("UPDATE categories SET title = :title, alt = :alt WHERE id = :id");
        $categoryUpdate->execute(['title' => $title, 'alt' => $alt, 'id' => $categoryId]);

        // Check if a new image is uploaded
        if (!empty(trim($_FILES['image']['name']))) {
            $nameImage = time() . "_" . $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];

            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmpName, "../../../uploads/categories/$nameImage")) {
                // Update the category image if uploaded successfully
                $imageUpdate = $db->prepare("UPDATE categories SET image = :image WHERE id = :id");
                $imageUpdate->execute(['image' => $nameImage, 'id' => $categoryId]);
            } else {
                echo "Upload Error";
            }
        }

        // Redirect after updating
        header("Location: index.php");
        exit();
    } else {
        echo "عنوان دسته بندی نمی تواند خالی باشد.";
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php"; ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش دسته بندی</h1>
            </div>

            <!-- Category Edit Form -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان دسته بندی</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($category['title']) ?>" />
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان انگلیسی (Alt)</label>
                        <input type="text" name="alt" class="form-control" value="<?= htmlspecialchars($category['alt']) ?>" />
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر دسته بندی</label>
                        <input name="image" class="form-control" type="file" />
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <?php if (!empty($category['image'])): ?>
                            <img class="rounded" src="../../../uploads/categories/<?= htmlspecialchars($category['image']) ?>" width="100" />
                        <?php else: ?>
                            <p>No image uploaded</p>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <button name="editCategory" type="submit" class="btn btn-dark">
                            ویرایش
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>