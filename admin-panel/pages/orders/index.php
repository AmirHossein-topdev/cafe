<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

include "../../include/layout/header.php";

function getParam($key)
{
    return isset($_GET[$key]) ? $_GET[$key] : null;
}

// حذف سفارش (تک سفارش)
if (getParam('action') === 'delete' && getParam('id') && getParam('table') && getParam('table_id')) {
    $postId = (int) getParam('id');
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', getParam('table'));
    $tableId = (int) getParam('table_id');

    try {
        // حذف سفارش
        $query = $db->prepare("DELETE FROM $table WHERE id = :id");
        $query->bindParam(':id', $postId, PDO::PARAM_INT);
        $query->execute();

        // بررسی اینکه آیا سفارش دیگری برای میز وجود دارد؟
        $stmtCheck = $db->prepare("SELECT COUNT(*) FROM $table WHERE table_id = :table_id");
        $stmtCheck->bindParam(':table_id', $tableId, PDO::PARAM_INT);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count == 0) {
            // حذف یادداشت میز چون سفارش‌ها تمام شده
            $stmtDeleteNote = $db->prepare("DELETE FROM note WHERE table_id = :table_id");
            $stmtDeleteNote->bindParam(':table_id', $tableId, PDO::PARAM_INT);
            $stmtDeleteNote->execute();
        }

        header("Location: ./index.php");
        exit;
    } catch (Exception $e) {
        echo 'خطا در حذف: ' . $e->getMessage();
    }
}


// تغییر وضعیت سفارش
if (getParam('status') !== null && getParam('id') && getParam('table')) {
    $postId = (int) getParam('id');
    $newStatus = getParam('status') == '1' ? 1 : 0;
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', getParam('table'));

    try {
        $query = $db->prepare("UPDATE $table SET status = :status WHERE id = :id");
        $query->bindParam(':status', $newStatus, PDO::PARAM_INT);
        $query->bindParam(':id', $postId, PDO::PARAM_INT);
        $query->execute();

        header("Location: ./index.php");
        exit;
    } catch (Exception $e) {
        echo 'خطا در به‌روزرسانی وضعیت: ' . $e->getMessage();
    }
}

// تغییر وضعیت همه اقلام
if (getParam('action') === 'update_all_status' && getParam('table') && getParam('status') !== null && getParam('table_id')) {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', getParam('table'));
    $status = getParam('status') == '1' ? 1 : 0;
    $tableId = (int) getParam('table_id');

    try {
        $query = $db->prepare("UPDATE $table SET status = :status WHERE table_id = :table_id");
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':table_id', $tableId, PDO::PARAM_INT);
        $query->execute();

        header("Location: ./index.php");
        exit;
    } catch (Exception $e) {
        echo 'خطا در به‌روزرسانی وضعیت همه اقلام: ' . $e->getMessage();
    }
}

// حذف همه اقلام (و یادداشت مربوط به میز)
if (getParam('action') === 'delete_all' && getParam('table') && getParam('table_id')) {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', getParam('table'));
    $tableId = (int) getParam('table_id');

    try {
        // حذف سفارش‌ها
        $query = $db->prepare("DELETE FROM $table WHERE table_id = :table_id");
        $query->bindParam(':table_id', $tableId, PDO::PARAM_INT);
        $query->execute();

        // حذف یادداشت میز از جدول note
        $queryNote = $db->prepare("DELETE FROM note WHERE table_id = :table_id");
        $queryNote->bindParam(':table_id', $tableId, PDO::PARAM_INT);
        $queryNote->execute();

        header("Location: ./index.php");
        exit;
    } catch (Exception $e) {
        echo 'خطا در حذف همه اقلام و یادداشت: ' . $e->getMessage();
    }
}

// دریافت سفارش‌ها بر اساس table_id
$cartTables = [];
$totalRowCount = 0;

for ($i = 1; $i <= 100; $i++) {
    $stmt = $db->prepare("SELECT * FROM cart WHERE table_id = ? ORDER BY id DESC");
    $stmt->execute([$i]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($items) > 0) {
        $cartTables[$i] = $items;
        $totalRowCount += count($items);
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../include/layout/sidebar.php"; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3">
                <h1 class="fs-3 fw-bold">سفارشات</h1>
                <h4 class="text-success">تعداد کل سفارشات: <?= $totalRowCount ?> سفارش</h4>

                <?php foreach ($cartTables as $tableId => $orders): ?>
                    <div class="mt-4 border-bottom border-4 border-primary rounded py-2">
                        <h5>میز <?= $tableId ?></h5>
                        <div class="table-responsive small">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>عنوان</th>
                                        <th>تعداد</th>
                                        <th>قیمت فی</th>
                                        <th>قیمت کل ایتم</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cartTotal = 0;
                                    foreach ($orders as $order):
                                        $price = (float) trim($order['price']);
                                        $quantity = (int) $order['quantity'];
                                        $itemTotal = $price * $quantity;
                                        $cartTotal += $itemTotal;
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($order['post_title']) ?></td>
                                            <td><?= $quantity ?></td>
                                            <td><?= number_format($price) ?> تومان</td>
                                            <td><?= number_format($itemTotal) ?> تومان</td>
                                            <td class="d-flex align-items-center">
                                                <?php if ((int) $order['status'] === 0): ?>
                                                    <a href="./index.php?status=1&id=<?= $order['id'] ?>&table=cart&table_id=<?= $tableId ?>"
                                                        class="text-danger">
                                                        <i class="bi bi-clipboard-check mx-2 fs-4"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="./index.php?status=0&id=<?= $order['id'] ?>&table=cart&table_id=<?= $tableId ?>"
                                                        class="text-primary">
                                                        <i class="bi bi-clipboard-check-fill mx-2 fs-4"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="./index.php?action=delete&id=<?= $order['id'] ?>&table=cart&table_id=<?= $tableId ?>"
                                                    class="btn btn-sm btn-outline-danger fs-7 ms-2">حذف</a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php
                            // گرفتن یادداشت میز از جدول note
                            $stmtNote = $db->prepare("SELECT note_text FROM note WHERE table_id = ?");
                            $stmtNote->execute([$tableId]);
                            $noteRow = $stmtNote->fetch(PDO::FETCH_ASSOC);
                            if ($noteRow && !empty(trim($noteRow['note_text']))):
                                ?>
                                <div class="alert alert-info">
                                    <strong>یادداشت میز:</strong> <?= htmlspecialchars($noteRow['note_text']) ?>
                                </div>
                            <?php endif; ?>

                            <h6 class="text-danger">مجموع: <?= number_format($cartTotal) ?> تومان</h6>

                            <div class="d-flex">
                                <a href="./index.php?action=update_all_status&status=1&table=cart&table_id=<?= $tableId ?>"
                                    class="btn btn-sm btn-success ms-2">تایید تمام اقلام</a>
                                <a href="./index.php?action=delete_all&table=cart&table_id=<?= $tableId ?>"
                                    class="btn btn-sm btn-danger ms-2">حذف تمام اقلام</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>

<audio src="./Hotel-bell-2.mp3" id="music" preload="auto"></audio>

<script>
    setInterval(() => location.reload(), 10000);
</script>

<?php include "../../include/layout/footer.php"; ?>