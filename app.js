// Глобальна змінна для відстеження даних (для п. 2e)
let lastLoadedData = '';
const POLLING_INTERVAL = 10000; // 10 секунд

// Код виконується, коли DOM-дерево завантажено
document.addEventListener('DOMContentLoaded', () => {

    // --- ЛОГІКА ДЛЯ СТОРІНКИ СТВОРЕННЯ (page_creator.php) ---

    const addBtn = document.getElementById('add-toast-btn');
    const saveBtn = document.getElementById('save-toasts-btn');
    const builderContainer = document.getElementById('toast-builder-container');
    const template = document.getElementById('toast-field-template');

    if (addBtn && saveBtn && builderContainer && template) {

        // п. 2b: Додавання нової форми для toast
        addBtn.addEventListener('click', () => {
            const clone = template.content.cloneNode(true);
            builderContainer.appendChild(clone);
        });

        // Обробка видалення (використовуємо делегування подій)
        builderContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-toast-btn')) {
                e.target.closest('.toast-fieldset').remove();
            }
        });

        // п. 2b: Додаємо Drag-n-Drop для зміни порядку
        let draggingElement = null;

        builderContainer.addEventListener('dragstart', (e) => {
            if (e.target.classList.contains('toast-fieldset')) {
                draggingElement = e.target;
                e.dataTransfer.effectAllowed = 'move';
                e.target.classList.add('dragging');
            }
        });

        builderContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';

            const target = e.target.closest('.toast-fieldset');
            if (target && target !== draggingElement && draggingElement) {
                // Визначаємо, вставити до чи після
                const rect = target.getBoundingClientRect();
                const nextIsAfter = (e.clientY - rect.top) > (rect.height / 2);

                if (nextIsAfter) {
                    target.after(draggingElement);
                } else {
                    target.before(draggingElement);
                }
            }
        });

        builderContainer.addEventListener('dragend', () => {
            if(draggingElement) {
                draggingElement.classList.remove('dragging');
                draggingElement = null;
            }
        });

        // п. 2c: Асинхронне збереження
        saveBtn.addEventListener('click', async () => {
            const toasts = [];
            const saveStatus = document.getElementById('save-status');

            // Збираємо дані з усіх форм
            builderContainer.querySelectorAll('.toast-fieldset').forEach(field => {
                const title = field.querySelector('.toast-title-input').value;
                const body = field.querySelector('.toast-body-input').value;
                toasts.push({ title, body });
            });

            saveStatus.textContent = 'Збереження...';

            try {
                const response = await fetch('api.php?action=save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(toasts)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    saveStatus.textContent = 'Збережено!';
                } else {
                    saveStatus.textContent = 'Помилка збереження.';
                }
                setTimeout(() => saveStatus.textContent = '', 2000);

            } catch (error) {
                console.error('Помилка при збереженні:', error);
                saveStatus.textContent = 'Помилка мережі.';
            }
        });
    }


    // --- ЛОГІКА ДЛЯ СТОРІНКИ ПЕРЕГЛЯДУ (page_viewer.php) ---

    const viewerContainer = document.getElementById('toast-viewer-container');

    if (viewerContainer) {

        // Функція для створення HTML-елемента toast (п. 2d)
        const createToastElement = (title, body) => {
            const toast = document.createElement('div');
            toast.className = 'custom-toast';

            const header = document.createElement('div');
            header.className = 'custom-toast-header';

            const titleEl = document.createElement('strong');
            titleEl.textContent = title || 'Повідомлення'; // Заголовок за замовчуванням

            const closeBtn = document.createElement('button');
            closeBtn.className = 'close-btn';
            closeBtn.innerHTML = '&times;'; // Символ 'x'

            // п. 2d: Функціонал кнопки закриття
            closeBtn.addEventListener('click', () => {
                // Додаємо клас для анімації зникнення
                toast.classList.add('fading-out');
                // Видаляємо елемент з DOM після завершення анімації
                setTimeout(() => {
                    toast.remove();
                }, 300); // 300ms - тривалість анімації з CSS
            });

            header.appendChild(titleEl);
            header.appendChild(closeBtn);

            const bodyEl = document.createElement('div');
            bodyEl.className = 'custom-toast-body';
            bodyEl.textContent = body || ''; // Тіло

            toast.appendChild(header);
            toast.appendChild(bodyEl);

            return toast;
        };

        // п. 2d, 2e: Функція завантаження та рендерингу
        const fetchAndRenderToasts = async () => {
            try {
                const response = await fetch('api.php?action=load');
                const newData = await response.text(); // Отримуємо як текст

                // п. 2e: Оновлюємо, ТІЛЬКИ якщо дані змінилися
                if (newData === lastLoadedData) {
                    // console.log('Дані не змінились, оновлення не потрібне.');
                    return;
                }

                // console.log('Дані оновлено, перемальовуємо...');
                lastLoadedData = newData; // Зберігаємо нові дані
                const toasts = JSON.parse(newData); // Парсимо JSON

                // Очищуємо контейнер
                viewerContainer.innerHTML = '';

                // Рендеримо нові toasts
                toasts.forEach(toastData => {
                    const toastElement = createToastElement(toastData.title, toastData.body);
                    viewerContainer.appendChild(toastElement);
                });

            } catch (error) {
                console.error('Помилка при завантаженні toasts:', error);
            }
        };

        // Перше завантаження при старті сторінки
        fetchAndRenderToasts();

        // п. 2e: Періодичний асинхронний контроль (кожні 10 сек)
        setInterval(fetchAndRenderToasts, POLLING_INTERVAL);
    }
});