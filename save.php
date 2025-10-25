<?php

include 'config.php';

// Отримуємо дані з POST
$element_id = $_POST['element_id'];
$content = $_POST['content'];
$page = $_POST['page'];

// Перевіряємо, чи вже є такий елемент у БД
$sql_check = "SELECT * FROM editable_content WHERE element_id = '$element_id' AND page = '$page'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // Якщо є — оновлюємо
    $sql_update = "UPDATE editable_content SET content = '$content' WHERE element_id = '$element_id' AND page = '$page'";
    $conn->query($sql_update);
} else {
    // Якщо нема — додаємо
    $sql_insert = "INSERT INTO editable_content (element_id, content, page) VALUES ('$element_id', '$content', '$page')";
    $conn->query($sql_insert);
}

$conn->close();
echo "OK";
