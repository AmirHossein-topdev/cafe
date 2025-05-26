<?php
include "./include/layout/header.php";

if (isset($_GET['entity']) && isset($_GET['action']) && isset($_GET['id'])) {
    $entity = $_GET['entity'];
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == "delete") {
        switch ($entity) {
            case "post":
                $query = $db->prepare('DELETE FROM posts WHERE id = :id');
                break;
            case "slides":
                $query = $db->prepare('DELETE FROM slides WHERE id = :id');
                break;
            case "category":
                $query = $db->prepare('DELETE FROM categories WHERE id = :id');
                break;
        }
    } elseif ($action == "approve") {
        $query = $db->prepare("UPDATE comments SET status = '1' WHERE id = :id");
    }

    $query->execute(['id' => $id]);
}

$posts = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT 5");
$categories = $db->query("SELECT * FROM categories ORDER BY id DESC");
$slides = $db->query("SELECT * FROM slides ORDER BY id DESC");

?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "./include/layout/sidebar.php"
            ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">داشبورد</h1>
            </div>

            <!-- Recently Posts -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">محصولات اخیر</h4>
                <?php if ($posts->rowCount() > 0): ?>
                    <?php $index = 1; ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>عنوان</th>
                                    <th>قیمت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <th><?= $index++ ?></th>
                                        <td><?= $post['title'] ?></td>
                                        <td><?= $post['price'] ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?entity=post&action=delete&id=<?= $post['id'] ?>"
                                                class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            محصول ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- Categories -->
            <div class="mt-4">
                <h4 class="text-secondary fw-bold">دسته بندی</h4>
                <?php if ($categories->rowCount() > 0): ?>
                    <?php $index = 1; ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>عنوان</th>
                                    <th>عنوان انگلیسی</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <th><?= $index++ ?></th>
                                        <td><?= $category['title'] ?></td>
                                        <td><?= $category['alt'] ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?entity=category&action=delete&id=<?= $category['id'] ?>"
                                                class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            دسته بندی یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- slides -->
            <div class="mt-4 container">
                <h4 class="text-secondary fw-bold mb-3">اسلاید ها</h4>
                <div class="row justify-content-center">
                    <?php if ($slides->rowCount() > 0): ?>
                        <?php foreach ($slides as $slide): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                                <div class="card text-center" style="height:16rem;">
                                    <img src="../uploads/slides/<?= htmlspecialchars($slide['image']) ?>" class="card-img-top"
                                        style="height: 10rem;" alt="slide-img">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $slide['slide_name'] ?></h5>
                                        <a href="index.php?action=delete&id=<?= $slide['id'] ?>"
                                            class="btn btn-sm btn-outline-danger">حذف</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col">
                            <div class="alert alert-danger">
                                اسلایدی یافت نشد ....
                            </div>
                        </div>
                    <?php endif ?>
                </div>
        </main>
    </div>
</div>

<?php
include "./include/layout/footer.php"
    ?>