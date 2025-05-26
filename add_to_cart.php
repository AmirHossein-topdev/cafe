<?php
include "./include/config.php"; // Database configuration
include "./include/db.php"; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id']);
    $tableId = intval($_POST['table_id']);
    $quantity = intval($_POST['quantity']);

    if ($tableId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid table ID']);
        exit;
    }

    // جدول ثابت است
    $cartTable = 'cart';

    // دریافت عنوان، قیمت و موجودی محصول
    $postStmt = $db->prepare("SELECT title, price, stock FROM posts WHERE id = ?");
    $postStmt->execute([$postId]);
    $post = $postStmt->fetch();

    if ($post) {
        // چک موجودی
        if ($post['is_stock_tracked'] == 1 && $post['stock'] <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'محصول موجود نیست']);
            exit;
        }

        // بررسی وجود آیتم در سبد خرید میز مشخص
        $stmt = $db->prepare("SELECT * FROM $cartTable WHERE post_id = ? AND table_id = ?");
        $stmt->execute([$postId, $tableId]);
        $cartItem = $stmt->fetch();

        if ($cartItem) {
            // بروزرسانی تعداد
            $newQuantity = $cartItem['quantity'] + $quantity;
            $updateStmt = $db->prepare("UPDATE $cartTable SET quantity = ?, price = ? WHERE post_id = ? AND table_id = ?");
            $updateStmt->execute([$newQuantity, $post['price'], $postId, $tableId]);
        } else {
            // درج آیتم جدید در cart
            $insertStmt = $db->prepare("INSERT INTO $cartTable (post_id, post_title, quantity, table_id, price) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->execute([$postId, $post['title'], $quantity, $tableId, $post['price']]);
        }

        // 🚨 کاهش موجودی محصول
        $newStock = $post['stock'] - $quantity;
        $stockStmt = $db->prepare("UPDATE posts SET stock = ? WHERE id = ?");
        $stockStmt->execute([$newStock, $postId]);

        echo json_encode(['status' => 'success']);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Post not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
