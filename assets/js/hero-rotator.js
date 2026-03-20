document.addEventListener('DOMContentLoaded', function() {
    const titles = [
        'ВОПРОС К ФОРМЕ.<br><span style="color: var(--wp--preset--color--accent-blue);">ОТВЕТ — В СОДЕРЖАНИИ.</span>',
        'Я НЕ ПРОДАЮ КАРТИНЫ.<br><span style="color: var(--wp--preset--color--accent-blue);">Я ПРОДАЮ КЛЮЧИ К ТВОЕЙ ВСЕЛЕННОЙ.</span>',
        'МЕНЯ ВСЕГДА МУЧИЛ ОДИН ВОПРОС:<br><span style="color: var(--wp--preset--color--accent-blue);">«ЧТО, ЕСЛИ Я НЕ ЗНАЮ, КТО Я?»</span>',
        'ВАША МНОГОГРАННОСТЬ —<br><span style="color: var(--wp--preset--color--accent-blue);">НЕ РАССЕЯННОСТЬ. ЭТО МЕТОД ИССЛЕДОВАНИЯ МИРА.</span>',
        'ФОРМА — ЭТО ПРОСТО ЯЗЫК.<br><span style="color: var(--wp--preset--color--accent-blue);">СОДЕРЖАНИЕ — ТО, ЧТО ВЫ ХОТИТЕ СКАЗАТЬ.</span>',
        'НЕ ИЩИТЕ ОДНУ СТРАСТЬ.<br><span style="color: var(--wp--preset--color--accent-blue);">ИЩИТЕ СВЯЗИ МЕЖДУ ВСЕМИ, ЧТО У ВАС УЖЕ ЕСТЬ.</span>',
        'КАЖДАЯ МОЯ РАБОТА — НЕ ШЕДЕВР.<br><span style="color: var(--wp--preset--color--accent-blue);">КАЖДАЯ РАБОТА — ЭКСПЕРИМЕНТАЛЬНЫЙ ПРОТОКОЛ.</span>'
    ];
    
    const randomIndex = Math.floor(Math.random() * titles.length);
    const titleElement = document.getElementById('rotating-title');
    
    if (titleElement) {
        titleElement.innerHTML = titles[randomIndex];
    }
});