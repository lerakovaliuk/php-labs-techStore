<?php

require_once __DIR__ . '/db.php';

// Отримуємо дані, надіслані як JSON
$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$content = $input['content'] ?? null;

// Базова валідація
if (!$id || !isset($content)) {
    // Повертаємо помилку
    header('Content-Type: application/json', true, 400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Відсутній ID або контент.']);
    exit;
}

try {

    $sql = "INSERT INTO content (element_id, content_text) 
            VALUES (:id, :content)
            ON DUPLICATE KEY UPDATE content_text = VALUES(content_text)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'id' => $id,
        'content' => $content
    ]);

    // Повертаємо успішну відповідь
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);

} catch (\PDOException $e) {
    // Повертаємо помилку сервера
    header('Content-Type: application/json', true, 500); // Internal Server Error

    // Надсилаємо саму помилку SQL, щоб її було видно в консолі браузера
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}