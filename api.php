<?php

// Файл для зберігання наших даних
$dataFile = __DIR__ . '/toasts.json';

// Отримуємо дію з рядка запиту
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
    // --- Збереження даних (п. 2c) ---

    // Отримуємо JSON-дані з тіла POST-запиту
    $jsonData = file_get_contents('php://input');

    // Перевіряємо, чи це валідний JSON (для безпеки)
    $data = json_decode($jsonData);

    if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
        // Зберігаємо валідні дані у файл
        file_put_contents($dataFile, $jsonData, LOCK_EX);
        echo json_encode(['status' => 'success', 'message' => 'Дані успішно збережено.']);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Отримано невалідні JSON-дані.']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'load') {
    // --- Завантаження даних (п. 2d) ---

    if (file_exists($dataFile)) {
        // Якщо файл існує, читаємо і віддаємо його вміст
        readfile($dataFile);
    } else {
        // Якщо файлу ще немає, віддаємо пустий масив
        echo '[]';
    }

} else {
    // --- Невідома дія ---
    http_response_code(404); // Not Found
    echo json_encode(['status' => 'error', 'message' => 'Невідома дія або метод запиту.']);
}