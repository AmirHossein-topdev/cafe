<?php
include "../../include/layout/header.php";

$slides = $db->query("SELECT * FROM slides ORDER BY id DESC");

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $db->prepare('DELETE FROM slides WHERE id = :id');

    $query->execute(['id' => $id]);

    header("Location:index.php");
    exit();
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
                <h1 class="fs-3 fw-bold">اسلاید ها</h1>

                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="./create.php" class="btn btn-sm btn-dark">
                        ایجاد اسلاید
                    </a>
                </div>
            </div>

            <!-- slides -->
            <div class="mt-4 container">
                <div class="row justify-content-center">
                    <?php if ($slides->rowCount() > 0) : ?>
                        <?php foreach ($slides as $slide) : ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                                <div class="card text-center" style="height:16rem;">
                                    <img src="../../../uploads/slides/<?= htmlspecialchars($slide['image']) ?>" class="card-img-top" style="height: 10rem;" alt="slide-img">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $slide['slide_name'] ?></h5>
                                        <a href="index.php?action=delete&id=<?= $slide['id'] ?>" class="btn btn-sm btn-outline-danger">حذف</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
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
include "../../include/layout/footer.php"
?>