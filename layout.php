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

$pageTitleContent = getContent('pageTitle-' . $currentPage, $pageTitle); // Унікальний ID для заголовка кожної сторінки
$xboxContent = getContent('xbox', $x); // Отримуємо $x або значення з БД

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($xboxContent) ?> — <?= htmlspecialchars($pageTitleContent) ?></title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime('style.css') ?>">
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <div class="xbox" id="xbox" data-editable><?= htmlspecialchars(getContent('xbox', $x)) ?></div>
        </div>
        <div class="header-right">
            <h2 id="slogan" data-editable><?= htmlspecialchars(getContent('slogan', 'Number one по домашнім гаджетам!')) ?></h2>
            <p id="welcome" data-editable><?= htmlspecialchars(getContent('welcome', $texts[1])) ?></p>
        </div>
    </div>

    <div class="left">
        <h3 id="annTitle" data-editable><?= htmlspecialchars(getContent('annTitle', 'Оголошення')) ?></h3>
        <p id="announcements" data-editable><?= nl2br(htmlspecialchars(getContent('announcements', $texts[2]))) ?></p>
    </div>

    <div class="menu">
        <h3 id="menuTitle" data-editable style="display:inline; margin-right:10px;"><?= htmlspecialchars(getContent('menuTitle', 'Меню:')) ?></h3>
        <ul class="menu-inline">
            <?php foreach ($menu as $link => $label):
                $active = (basename($_SERVER['PHP_SELF']) === $link) ? 'active' : '';
                ?>
                <li><a class="<?= $active ?>" href="<?= $link ?>"><?= htmlspecialchars($label) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="right">
        <h3 id="scheduleTitle" data-editable><?= htmlspecialchars(getContent('scheduleTitle', 'Графік')) ?></h3>
        <p id="schedule" data-editable><?= nl2br(htmlspecialchars(getContent('schedule', $texts[4]))) ?></p>
    </div>

    <div class="main">
        <h2 id="pageTitle-<?= $currentPage ?>" data-editable><?= htmlspecialchars($pageTitleContent) ?></h2>
        <?php
        // Контент, що змінюється залежно від сторінки:
        switch ($currentPage) {
            case 2:
                echo "<h3 id='ulTitle' data-editable>" . htmlspecialchars(getContent('ulTitle', 'Приклад ненумерованого списку (UL)')) . "</h3><ul>";
                foreach ($lists['ul'] as $i => $li) {
                    // Динамічний ID для кожного елемента списку
                    echo '<li id="ul-item-' . $i . '" data-editable>' . htmlspecialchars(getContent('ul-item-' . $i, $li)) . '</li>';
                }
                echo "</ul>";
                echo "<h3 id='olTitle' data-editable>" . htmlspecialchars(getContent('olTitle', 'Приклад нумерованого списку (OL)')) . "</h3><ol>";
                foreach ($lists['ol'] as $i => $li) {
                    echo '<li id="ol-item-' . $i . '" data-editable>' . htmlspecialchars(getContent('ol-item-' . $i, $li)) . '</li>';
                }
                echo "</ol>";
                break;

            case 3:
                echo "<p id='mapIntro' data-editable>" . htmlspecialchars(getContent('mapIntro', 'Натисни на зони картинки, щоб перейти на відповідні сторінки (приклад MAP):')) . "</p>";
                if ($image_url !== '') {
                    echo '<img src="' . htmlspecialchars($image_url) . '" usemap="#sitemap" alt="Map image">';
                    echo '<map name="sitemap">';
                    foreach ($image_map_areas as $area) {
                        echo '<area shape="' . htmlspecialchars($area['shape'])
                            . '" coords="' . htmlspecialchars($area['coords'])
                            . '" href="' . htmlspecialchars($area['href'])
                            . '" alt="' . htmlspecialchars($area['alt']) . '">';
                    }
                    echo '</map>';
                } else {
                    echo '<p><em>Картинка не задана (змінна $image_url пуста).</em></p>';
                }
                break;

            case 4:
                echo "<h3 id='linksTitle' data-editable>" . htmlspecialchars(getContent('linksTitle', 'Приклади посилань (A)')) . "</h3><p id='innerLinksIntro' data-editable>" . htmlspecialchars(getContent('innerLinksIntro', 'Внутрішні посилання з масиву меню:')) . "</p><ul>";
                foreach ($menu as $link => $label) {
                    // Робимо текст посилань редагованим
                    echo '<li><a href="' . $link . '" id="link-' . $link . '" data-editable>' . htmlspecialchars(getContent('link-' . $link, $label)) . '</a></li>';
                }
                echo "</ul>";
                echo '<p id="outerLink" data-editable>' . htmlspecialchars(getContent('outerLink', 'Зовнішнє посилання: <a href="https://example.com" target="_blank" rel="noopener">Example.com</a>')) . '</p>';
                break;

            case 5:
            default:
                echo "<p id='aboutText' data-editable>" . nl2br(htmlspecialchars(getContent('aboutText', $texts[5]))) . "</p>";
                echo "<p id='extraText' data-editable>" . htmlspecialchars(getContent('extraText', 'Тут ви знайдете товари на будь-який смак, просто приходь і обирай, бо у нас найкращі умови!')) . "</p>";
                break;
        }
        ?>
        <button id="resetBtn">Скинути зміни</button>
    </div>

    <div class="small">
        <h4 id="promo" data-editable><?= htmlspecialchars(getContent('promo', 'Чекаємо вас у наших магазинах!')) ?></h4>
    </div>

    <div class="footer">
        <p id="footerText" data-editable><?= htmlspecialchars(getContent('footerText', $texts[7])) ?></p>
        <div class="ybox" id="ybox" data-editable><?= htmlspecialchars(getContent('ybox', $y)) ?></div>
    </div>
</div>

<?php
// ВИМІРЮВАННЯ ЧАСУ
$page_end_time = microtime(true);
$total_php_time = $page_end_time - $page_start_time;

// Створюємо маленький скрипт, щоб передати змінні у JS
echo "<script>";
echo "var serverTotalTime = " . number_format($total_php_time, 6, '.', '') . ";";
echo "var serverDbTime = " . number_format($db_query_time, 6, '.', '') . ";";
echo "</script>";

?>

<script src="script.js"></script>

</body>
</html>