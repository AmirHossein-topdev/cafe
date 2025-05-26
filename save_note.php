<?php
include "./include/config.php";
include "./include/db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_id = isset($_POST['table_id']) ? intval($_POST['table_id']) : 0;
    $note_text = isset($_POST['note_text']) ? trim($_POST['note_text']) : '';

    if ($table_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'شماره میز معتبر نیست']);
        exit;
    }

    if (empty($note_text)) {
        echo json_encode(['status' => 'error', 'message' => 'متن یادداشت نمی‌تواند خالی باشد']);
        exit;
    }

    // استفاده از INSERT ... ON DUPLICATE KEY UPDATE برای درج یا به‌روزرسانی یادداشت
    $stmt = $db->prepare("
        INSERT INTO note (table_id, note_text) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE note_text = VALUES(note_text)
    ");

    $result = $stmt->execute([$table_id, $note_text]);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'خطا در ثبت یادداشت در دیتابیس']);
    }
}
