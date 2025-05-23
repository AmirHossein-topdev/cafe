<?php
include "../../include/layout/header.php";

// گرفتن اطلاعات از جدول slides
$slides = $db->query("SELECT * FROM slides");

$invalidInputTitle = '';
$invalidInputImage = '';

if (isset($_POST['addPost'])) {

    // بررسی اینکه عنوان اسلاید خالی نباشد
    if (empty(trim($_POST['slide_name']))) {
        $invalidInputTitle = 'فیلد نام اسلاید الزامیست';
    }

    // بررسی اینکه تصویر اسلاید انتخاب شده باشد
    if (empty(trim($_FILES['image']['name']))) {
        $invalidInputImage = 'فیلد تصویر اسلاید الزامیست';
    }

    // بررسی نوع فایل تصویر (برای جلوگیری از آپلود فایل‌های ناخواسته)
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($imageExtension, $allowedExtensions)) {
        $invalidInputImage = 'فقط فایل‌های تصویری (jpg, jpeg, png, gif) مجاز هستند';
    }

    // بررسی اینکه هر دو فیلد پر باشند
    if (empty($invalidInputTitle) && empty($invalidInputImage)) {
        $slideName = $_POST['slide_name'];  // نام اسلاید
        $categoryId = isset($_POST['categoryId']) ? $_POST['categoryId'] : null; // گرفتن شناسه دسته‌بندی اگر وجود داشته باشد

        // ساخت نام یکتا برای تصویر
        $nameImage = time() . "_" . $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        // بررسی خطای آپلود فایل
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $invalidInputImage = 'خطا در آپلود تصویر';
        } else {
            // آپلود تصویر به پوشه مشخص شده
            if (move_uploaded_file($tmpName, "../../../uploads/slides/$nameImage")) {
                // کوئری برای درج داده‌ها به جدول slides
                $slideInsert = $db->prepare("INSERT INTO slides (slide_name, image) VALUES (:slide_name, :image)");
                $slideInsert->execute(['slide_name' => $slideName, 'image' => $nameImage]);

                // هدایت به صفحه اصلی بعد از موفقیت
                header("Location:index.php");
                exit();
            } else {
                $invalidInputImage = 'خطا در انتقال فایل';
            }
        }
    }
}

?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "../../include/layout/sidebar.php"
        ?>
        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد اسلاید</h1>
            </div>

            <!-- فرم ایجاد اسلاید -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نام اسلاید</label>
                        <input type="text" name="slide_name" class="form-control" value="<?= isset($_POST['slide_name']) ? $_POST['slide_name'] : '' ?>" />
                        <div class="form-text text-danger"><?= $invalidInputTitle ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر اسلاید</label>
                        <input class="form-control" name="image" type="file" autocomplete="off" />
                        <div class="form-text text-danger"><?= $invalidInputImage ?></div>
                    </div>

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

<?php
include "../../include/layout/footer.php"
?>
