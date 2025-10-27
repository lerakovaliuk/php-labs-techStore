<?php

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$db_name = 'lab_db';

try {
    // Підключення до MySQL (без вибору БД)
    $dsn_server = "mysql:host=$host;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn_server, $user, $pass, $options);
    echo "<p>1. Успішно підключено до сервера MySQL.</p>";

    // Створення Бази Даних
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    $pdo->exec("USE `$db_name`;"); // Вибираємо цю БД для наступних запитів
    echo "<p>2. База даних '$db_name' успішно створена (або вже існувала).</p>";

    // Створення Таблиці
    $sql_table = "
    CREATE TABLE IF NOT EXISTS content (
        element_id VARCHAR(100) NOT NULL PRIMARY KEY,
        content_text TEXT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $pdo->exec($sql_table);
    echo "<p>3. Таблиця 'content' успішно створена (або вже існувала).</p>";

    echo "<h2>УСПІХ!</h2>";
    echo "<p>База даних і таблиця готові. Тепер можете видалити цей файл (setup_db.php) і відкрити <b>index.php</b>.</p>";


} catch (\PDOException $e) {
    // Якщо щось пішло не так на будь-якому кроці
    echo "<h1>ПОМИЛКА!</h1>";
    echo "<p style='color:red;'>Не вдалося налаштувати базу даних.</p>";
    echo "<p><b>Повідомлення про помилку:</b> " . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<p><b>Що перевірити:</b></p>";
    echo "<ul>";
    echo "<li>Чи запущено <b>MySQL</b> у вашій панелі XAMPP?</li>";
    echo "<li>Чи правильний пароль (за замовчуванням він пустий: <code>\$pass = '';</code>)?</li>";
    echo "</ul>";
}