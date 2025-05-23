<?php
include "../../include/layout/header.php";

$invalidInputTitle = '';
$invalidInputImage = '';
$invalidInputAlt = '';

if (isset($_POST['addCategory'])) {
    // Check if the title is empty
    if (empty(trim($_POST['title']))) {
        $invalidInputTitle = "فیلد عنوان ضروری هست";
    }

    // Check if an image is uploaded
    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = "فیلد تصویر الزامیست";
    }

    // Check if alt text is provided
    if (empty(trim($_POST['alt']))) {
        $invalidInputAlt = "فیلد alt الزامیست";
    }

    // If both title and image are valid, proceed to insert the category
    if (empty($invalidInputTitle) && empty($invalidInputImage) && empty($invalidInputAlt)) {
        $title = $_POST['title'];
        $alt = $_POST['alt'];
        $nameImage = time() . "_" . basename($_FILES['image']['name']); // Use basename to get the actual file name
        $tmpName = $_FILES['image']['tmp_name'];
        $imagePath = "../../../uploads/categories/" . $nameImage;

        // Check if the image was uploaded successfully
        if (move_uploaded_file($tmpName, $imagePath)) {
            // Insert category into the database
            $categoryInsert = $db->prepare("INSERT INTO categories (title, alt, image) VALUES (:title, :alt, :image)");
            $categoryInsert->execute(['title' => $title, "alt" => $alt, 'image' => $nameImage]);

            // Redirect after success
            header("Location: index.php");
            exit();
        } else {
            $invalidInputImage = "خطا در آپلود تصویر";
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
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد دسته بندی</h1>
            </div>

            <!-- Category Form -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان دسته بندی</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان دسته بندی به انگلیسی</label>
                        <input type="text" name="alt" class="form-control" oninput="convertSpaceToHyphen(this)"/>
                        <div class="form-text text-danger"><?= $invalidInputAlt ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر دسته بندی</label>
                        <input class="form-control" name="image" type="file" autocomplete="off" />
                        <div class="form-text text-danger"><?= $invalidInputImage ?></div>
                    </div>

                    <div class="col-12">
                        <button name="addCategory" type="submit" class="btn btn-dark">
                            ایجاد
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>