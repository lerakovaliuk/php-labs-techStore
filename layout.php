<?php

require_once __DIR__ . '/config.php';
if (!isset($currentPage)) $currentPage = 1;
if (!isset($menu) || !is_array($menu)) $menu = [];
if (!isset($x)) $x = 'Заголовок сайту (X)';
if (!isset($y)) $y = 'Контактна інформація (Y)';
if (!isset($texts) || !is_array($texts)) {
    $texts = [];
    for ($i = 1; $i <= 7; $i++) $texts[$i] = '';
}
if (!isset($lists) || !is_array($lists)) $lists = ['ul' => [], 'ol' => []];
if (!isset($image_url)) $image_url = '';
if (!isset($image_map_areas) || !is_array($image_map_areas)) $image_map_areas = [];

$self = basename($_SERVER['PHP_SELF']);
$pageTitle = isset($menu[$self]) ? $menu[$self] : ("Сторінка $currentPage");
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($x) ?> — <?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime('style.css') ?>">
    <link rel="stylesheet" href="toasts.css"> </head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <div class="xbox"><?= htmlspecialchars($x) ?></div>
        </div>
        <div class="header-right">
            <h2>Number one по домашнім гаджетам!</h2>
            <p><?= htmlspecialchars($texts[1]) ?></p>
        </div>
    </div>

    <div class="left">
        <h3>Оголошення</h3>
        <p><?= htmlspecialchars($texts[2]) ?></p>
    </div>

    <div class="menu">
        <h3 style="display:inline; margin-right:10px;">Меню:</h3>
        <ul class="menu-inline">
            <?php foreach ($menu as $link => $label):
                $active = (basename($_SERVER['PHP_SELF']) === $link) ? 'active' : '';
                ?>
                <li><a class="<?= $active ?>" href="<?= $link ?>"><?= htmlspecialchars($label) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="right">
        <h3>Графік</h3>
        <p><?= htmlspecialchars($texts[4]) ?></p>
    </div>

    <div class="main">
        <h2>TechStore — <?= htmlspecialchars($pageTitle) ?></h2>
        <?php
        // Контент, що змінюється залежно від сторінки:

        // ВАЖЛИВО: Тепер ми не використовуємо $currentPage,
        // а дозволяємо кожному файлу (page_creator.php, page_viewer.php)
        // вставити свій контент в layout.php

        // Перевіряємо, чи був встановлений $pageContent у файлі, що викликає
        if (isset($pageContent) && is_callable($pageContent)) {
            call_user_func($pageContent); // Викликаємо функцію для рендеру контенту
        } else {
            // Старий код для сумісності (якщо потрібно)
            // Або просто виводимо контент за замовчуванням
            echo "<p>" . nl2br(htmlspecialchars($texts[5])) . "</p>";
        }
        ?>
    </div>

    <div class="small">
        <h4>Чекаємо вас у наших магазинах!</h4>
    </div>

    <div class="footer">
        <p><?= htmlspecialchars($texts[7]) ?></p>
        <div class="ybox"><?= htmlspecialchars($y) ?></div>
    </div>
</div>

<script src="app.js"></script> </body>
</html>