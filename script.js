let start = performance.now();

document.addEventListener("DOMContentLoaded", () => {
    let mid = performance.now();

    const editableElements = document.querySelectorAll("[data-editable]");

    editableElements.forEach(el => {

        el.addEventListener("click", () => {
            let input;
            if (el.tagName === 'P' || el.tagName === 'LI') {
                input = document.createElement("textarea");
                input.style.height = "80px"; // Трохи місця
                input.value = el.innerText;
            } else {
                input = document.createElement("input");
                input.type = "text";
                input.value = el.innerText;
            }

            input.style.width = "90%";
            input.style.fontSize = window.getComputedStyle(el).fontSize;
            input.style.fontFamily = window.getComputedStyle(el).fontFamily;

            el.replaceWith(input);
            input.focus();

            // ЗМІНЮЄМО ФУНКЦІЮ ЗБЕРЕЖЕННЯ
            // Робимо її асинхронною (async), щоб використовувати 'await fetch'
            const saveChanges = async () => {
                const newContent = input.value;
                el.innerText = newContent;
                input.replaceWith(el);

                try {
                    const response = await fetch('save.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: el.id,
                            content: newContent
                        })
                    });

                    if (!response.ok) {
                        // Якщо сервер повернув помилку (400, 500)
                        const errorData = await response.json();
                        console.error("Помилка збереження:", errorData.message);
                        alert("Не вдалося зберегти зміни. Див. консоль.");
                    } else {
                        // Все добре
                        console.log(`Збережено ${el.id}: ${newContent.substring(0, 20)}...`);
                    }

                } catch (error) {
                    // Помилка мережі
                    console.error("Помилка мережі:", error);
                    alert("Помилка мережі. Не вдалося зберегти зміни.");
                }
            };

            input.addEventListener("blur", saveChanges);
            input.addEventListener("keydown", e => {
                if (e.key === "Enter" && el.tagName !== 'P' && el.tagName !== 'LI') { // Enter зберігає тільки для input
                    saveChanges();
                }
                if (e.key === "Escape") { // Додамо скасування
                    input.replaceWith(el);
                }
            });
        });
    });

    let end = performance.now();

    console.log("=== ⏱️ ЧАС ВИКОНАННЯ ===");
    console.log("--- КЛІЄНТ (JavaScript) ---");
    console.log("Час до DOMContentLoaded (завантаження/парсинг):", (mid - start).toFixed(2), "мс");
    console.log("Час налаштування JS (event listeners):", (end - mid).toFixed(2), "мс");
    console.log("   Загальний час на клієнті:", (end - start).toFixed(2), "мс");

    // Перевіряємо, чи існують змінні з PHP
    if (typeof serverTotalTime !== 'undefined' && typeof serverDbTime !== 'undefined') {
        console.log("--- СЕРВЕР (PHP + MySQL) ---");
        console.log("Час запиту до БД:  " + (serverDbTime * 1000).toFixed(2) + " мс (" + serverDbTime + " сек)");
        console.log("   Загальний час PHP: " + (serverTotalTime * 1000).toFixed(2) + " мс (" + serverTotalTime + " сек)");
    } else {
        console.error("Помилка: не вдалося завантажити час сервера з PHP.");
    }
    console.log("============================");
});

document.addEventListener("DOMContentLoaded", () => {
    const resetBtn = document.getElementById("resetBtn");

    if (resetBtn) {
        // Робимо обробник асинхронним
        resetBtn.addEventListener("click", async () => {
            if (!confirm("Ви впевнені, що хочете скинути ВЕСЬ контент до значень за замовчуванням?")) {
                return;
            }

            try {
                // Викликаємо reset.php
                const response = await fetch('reset.php', { method: 'POST' });
                if (!response.ok) {
                    alert("Помилка скидання даних.");
                } else {
                    alert("Збережені дані очищені. Сторінка буде перезавантажена.");
                    location.reload();
                }
            } catch (error) {
                alert("Помилка мережі при скиданні.");
            }
        });
    }
});