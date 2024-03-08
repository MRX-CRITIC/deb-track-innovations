$('.add-operation').on('mousedown', function(event) {
    event.preventDefault(); // Предотвратите действие по умолчанию (переход по ссылке)
    // Начинаем отсчет времени удержания мыши
    timeoutId = setTimeout(function() {
        // Если мышь удерживается более 1 секунды, показываем подсказку
        alert('Добавить операцию');
    }, 1000); // Установка задержки в 1 секунду
}).on('mouseup mouseleave', function() {
    clearTimeout(timeoutId);
});
