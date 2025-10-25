document.addEventListener("DOMContentLoaded", function () {
    const editableElements = document.querySelectorAll("[data-editable]");
    const pageId = document.body.getAttribute("data-page") || 1; // номер сторінки, якщо є

    editableElements.forEach((el) => {
        el.addEventListener("click", function () {
            const oldText = el.innerText;
            const input = document.createElement("textarea");
            input.value = oldText;
            input.style.width = "100%";
            input.style.height = "auto";
            el.innerHTML = "";
            el.appendChild(input);
            input.focus();

            // При втраті фокусу або натисканні Enter — зберігаємо
            input.addEventListener("blur", saveContent);
            input.addEventListener("keydown", function (e) {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault();
                    saveContent();
                }
            });

            function saveContent() {
                const newText = input.value.trim();
                el.innerText = newText;

                // Відправляємо дані на сервер
                fetch("save.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `element_id=${encodeURIComponent(el.id)}&content=${encodeURIComponent(newText)}&page=${encodeURIComponent(pageId)}`
                })
                    .then((res) => res.text())
                    .then((data) => console.log("✅ Збережено:", data))
                    .catch((err) => console.error("❌ Помилка:", err));
            }
        });
    });

    // Для заміру часу відображення
    const start = performance.now();
    window.addEventListener("load", () => {
        const end = performance.now();
        console.log(`⏱ Час завантаження сторінки (JS): ${(end - start).toFixed(2)} мс`);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const resetBtn = document.getElementById("resetBtn");

    if (resetBtn) {
        resetBtn.addEventListener("click", () => {
            localStorage.clear();
            alert("Збережені дані очищені. Сторінка буде перезавантажена.");
            location.reload();
        });
    }
});