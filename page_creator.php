<?php

// Функція, що містить HTML для центрального блоку
$pageContent = function() {
    ?>
    <h3>Конструктор Toast-повідомлень</h3>
    <p>Додайте одне або декілька повідомлень. Користувач визначає контент (заголовок, текст) та порядок (перетягуванням).</p>

    <div id="toast-builder-container">
    </div>

    <div class_ ="toast-controls">
        <button type="button" id="add-toast-btn">Додати повідомлення</button>
        <button type="button" id="save-toasts-btn">Зберегти на сервері</button>
        <span id="save-status" style="margin-left: 10px;"></span>
    </div>

    <template id="toast-field-template">
        <fieldset class="toast-fieldset" draggable="true">
            <legend>Повідомлення (перетягніть, щоб змінити порядок)</legend>
            <button type="button" class="remove-toast-btn" title="Видалити">×</button>
            <div>
                <label>Заголовок:</label>
                <input type="text" class="toast-title-input" placeholder="Наприклад, 'Нова акція!'">
            </div>
            <div>
                <label>Текст:</label>
                <textarea class="toast-body-input" placeholder="Текст вашого повідомлення..."></textarea>
            </div>
        </fieldset>
    </template>
    <?php
};

// Підключаємо загальний шаблон
include 'layout.php';