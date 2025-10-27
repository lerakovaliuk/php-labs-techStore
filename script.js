let start = performance.now();

document.addEventListener("DOMContentLoaded", () => {
    let mid = performance.now();

    const editableElements = document.querySelectorAll("[data-editable]");

    editableElements.forEach(el => {
        // Підтягування з localStorage
        let saved = localStorage.getItem(el.id);
        if (saved) {
            el.innerText = saved;
        }

        // Обробка кліку
        el.addEventListener("click", () => {
            let input = document.createElement("input");
            input.type = "text";
            input.value = el.innerText;
            input.style.width = "90%";

            el.replaceWith(input);
            input.focus();

            const saveChanges = () => {
                el.innerText = input.value;
                localStorage.setItem(el.id, input.value);
                input.replaceWith(el);
            };

            input.addEventListener("blur", saveChanges);
            input.addEventListener("keydown", e => {
                if (e.key === "Enter") saveChanges();
            });
        });
    });

    let end = performance.now();

    console.log("⏱ Час генерації сторінки:", (mid - start).toFixed(2), "мс");
    console.log("⏱ Час підтягування з localStorage:", (end - mid).toFixed(2), "мс");
    console.log("⏱ Загальний час:", (end - start).toFixed(2), "мс");
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