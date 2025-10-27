<?php

require_once __DIR__ . '/db.php';

try {
    // TRUNCATE TABLE швидше, ніж DELETE FROM, якщо нам треба очистити все
    $pdo->exec("TRUNCATE TABLE content");

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Дані скинуті.']);

} catch (\PDOException $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}