<?php
include "../../include/layout/header.php";

$posts = $db->query("SELECT * FROM posts ORDER BY id DESC");

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $db->prepare('DELETE FROM posts WHERE id = :id');
    $query->execute(['id' => $id]);
    header("Location:index.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../include/layout/sidebar.php" ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">محصولات</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="./create.php" class="btn btn-sm btn-dark">ایجاد محصول</a>
                </div>
            </div>

            <div class="mt-4">
                <?php if ($posts->rowCount() > 0): ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان</th>
                                    <th>قیمت</th>
                                    <th>موجودی</th>
                                    <th>توضیحات</th>
                                    <th>تصویر محصول</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; ?>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <th><?= $index++ ?></th>
                                        <td><?= htmlspecialchars($post['title']) ?></td>
                                        <td><?= htmlspecialchars(number_format($post['price'])) ?></td>
                                        <td>
                                            <?php if ($post['is_stock_tracked']): ?>
                                                <?= $post['stock'] > 0 ? $post['stock'] : '<span class="text-danger fw-bold">اتمام موجودی</span>' ?>
                                            <?php else: ?>
                                                <span class="text-success fw-bold">همیشه موجود</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><?= htmlspecialchars(mb_substr($post['description'], 0, 20)) ?>
                                            <?= mb_strlen($post['description']) > 10 ? '...' : '' ?>
                                        </td>

                                        <td>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <?php if (!empty($post['image'])): ?>
                                                    <img class="rounded"
                                                        src="../../../uploads/posts/<?= htmlspecialchars($post['image']) ?>"
                                                        width="60" height="40" />
                                                <?php else: ?>
                                                    <p>تصویر ندارد</p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="./edit.php?id=<?= $post['id'] ?>"
                                                class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?action=delete&id=<?= $post['id'] ?>"
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
                            محصولی یافت نشد ...
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php" ?>