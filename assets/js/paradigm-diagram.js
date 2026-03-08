/**
 * Deboart Paradigm Diagram - Interactive Schema
 * Управление интерактивной схемой "Форма → Содержание"
 * Версия: 1.0
 * Дата: январь 2026
 */

document.addEventListener('DOMContentLoaded', function() {
    const diagram = document.getElementById('deboartParadigmDiagram');
    const tooltip = document.getElementById('paradigmTooltip');
    const tooltipTitle = tooltip.querySelector('.tooltip-title');
    const tooltipDescription = tooltip.querySelector('.tooltip-description');
    const tooltipExamples = tooltip.querySelector('.tooltip-examples');

    if (!diagram || !tooltip) return;

    // Данные для подсказок
    const tooltipData = {
        // ФОРМЫ
        text: {
            title: '📖 Текстовые работы',
            description: 'Поэзия, эссе, рассказы, манифесты. Исследование языка как материала.',
            examples: ['Бесконечное зеркало 1', 'Стихотворение "Уж третий час..."']
        },
        image: {
            title: '🖼️ Изобразительные работы',
            description: 'Фотография, цифровая графика, коллажи. Диалог с визуальным пространством.',
            examples: ['Логотип DEBOART', 'Цифровые коллажи']
        },
        video: {
            title: '🎬 Видео работы',
            description: 'Видеоклипы, видеоарт, документация процессов. Время как измерение формы.',
            examples: ['Подниму голову в небеса', 'Пульс города']
        },
        audio: {
            title: '🎵 Аудио работы',
            description: 'Звуковые эксперименты, композиции, полевые записи. Пространство слышимого.',
            examples: ['Звуки тишины', 'Аудиодневник']
        },
        web: {
            title: '🌐 Веб/цифровые работы',
            description: 'Интерактивные инсталляции, генеративное искусство, сетевые проекты.',
            examples: ['Интерактивная поэзия', 'Сайт-как-произведение']
        },
        object: {
            title: '✨ Объекты',
            description: 'Физические артефакты, инсталляции, материальные эксперименты.',
            examples: ['Хрупкая вечность', 'Стеклянные композиции']
        },

        // СОДЕРЖАНИЯ
        silence: {
            title: '😌 Тишина',
            description: 'Работы о созерцании, паузе, внутреннем покое. Пространство между звуками.',
            examples: ['Медитативные тексты', 'Минималистичные композиции']
        },
        energy: {
            title: '⚡ Энергия',
            description: 'Динамика, движение, напряжение. Работы, которые заряжают и трансформируют.',
            examples: ['Эксперименты с ритмом', 'Кинетические объекты']
        },
        thought: {
            title: '🤔 Мысль',
            description: 'Рефлексия, анализ, концептуальные исследования. Искусство как мышление.',
            examples: ['Философские эссе', 'Концептуальные схемы']
        },
        drama: {
            title: '🎭 Драма',
            description: 'Конфликт, напряжение, нарратив. Эмоциональная интенсивность в форме.',
            examples: ['Психологические портреты', 'Драматические видео']
        },
        chaos: {
            title: '🌀 Хаос',
            description: 'Случайность, энтропия, неконтролируемые процессы. Красота в беспорядке.',
            examples: ['Алеаторные композиции', 'Эксперименты со случайностью']
        },
        memory: {
            title: '🕰️ Память',
            description: 'Время, ностальгия, архив. Что остаётся после момента?',
            examples: ['Работы с архивом', 'Исследования времени']
        }
    };

    // ЦВЕТА ДЛЯ БЕЙДЖЕЙ
    const badgeColors = {
        'text': '#1A5FB4',
        'image': '#2A8C6E',
        'video': '#D4B48C',
        'audio': '#6C6C6C',
        'web': '#1A5FB4',
        'object': '#2A8C6E',
        'silence': '#6C6C6C',
        'energy': '#1A5FB4',
        'thought': '#2A8C6E',
        'drama': '#D4B48C',
        'chaos': '#A0AEC0',
        'memory': '#2A2A2A'
    };

    // Элементы схемы
    const formItems = diagram.querySelectorAll('[data-form]');
    const contentItems = diagram.querySelectorAll('[data-content]');
    const allItems = [...formItems, ...contentItems];

    // Активный элемент
    let activeItem = null;

    // ПОКАЗАТЬ ПОДСКАЗКУ
    function showTooltip(item, event) {
        const type = item.dataset.form ? 'form' : 'content';
        const key = item.dataset.form || item.dataset.content;
        const data = tooltipData[key];

        if (!data) return;

        // Обновляем содержимое
        tooltipTitle.textContent = data.title;
        tooltipDescription.textContent = data.description;

        // Очищаем и добавляем примеры
        tooltipExamples.innerHTML = '';
        data.examples.forEach(example => {
            const badge = document.createElement('span');
            badge.className = 'tooltip-badge';
            badge.textContent = example;
            badge.style.backgroundColor = badgeColors[key];
            tooltipExamples.appendChild(badge);
        });

        // Позиционируем подсказку
        const rect = item.getBoundingClientRect();
        const diagramRect = diagram.getBoundingClientRect();

        // Определяем положение (сверху или снизу)
        const spaceAbove = rect.top - diagramRect.top;
        const spaceBelow = diagramRect.bottom - rect.bottom;

        if (spaceBelow > 200 || spaceBelow > spaceAbove) {
            // Показываем снизу
            tooltip.className = 'paradigm-tooltip active bottom';
            tooltip.style.top = (rect.bottom - diagramRect.top + 10) + 'px';
        } else {
            // Показываем сверху
            tooltip.className = 'paradigm-tooltip active top';
            tooltip.style.top = (rect.top - diagramRect.top - tooltip.offsetHeight - 10) + 'px';
        }

        // Центрируем по горизонтали
        tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2 - diagramRect.left) + 'px';

        // Гарантируем, что подсказка не выходит за границы
        const tooltipRect = tooltip.getBoundingClientRect();
        if (tooltipRect.left < diagramRect.left) {
            tooltip.style.left = '0px';
        }
        if (tooltipRect.right > diagramRect.right) {
            tooltip.style.left = (diagramRect.width - tooltip.offsetWidth) + 'px';
        }

        // Активируем элемент
        if (activeItem) activeItem.classList.remove('active');
        item.classList.add('active');
        activeItem = item;
    }

    // СКРЫТЬ ПОДСКАЗКУ
    function hideTooltip() {
        tooltip.classList.remove('active');
        if (activeItem) {
            activeItem.classList.remove('active');
            activeItem = null;
        }
    }

    // ОБРАБОТЧИКИ СОБЫТИЙ
    allItems.forEach(item => {
        // Наведение
        item.addEventListener('mouseenter', function(e) {
            showTooltip(this, e);
        });

        // Клик (для мобильных)
        item.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                if (activeItem === this) {
                    hideTooltip();
                } else {
                    showTooltip(this, e);
                }
            }
        });

        // Касание (для тач-устройств)
        item.addEventListener('touchstart', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                if (activeItem === this) {
                    hideTooltip();
                } else {
                    showTooltip(this, e);
                }
            }
        });
    });

    // Скрываем подсказку при уходе с диаграммы
    diagram.addEventListener('mouseleave', hideTooltip);

    // Скрываем при клике вне диаграммы (для мобильных)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && activeItem && !diagram.contains(e.target)) {
            hideTooltip();
        }
    });

    // АДАПТИВНОЕ ПОВЕДЕНИЕ
    function handleResize() {
        // На мобильных скрываем подсказку при изменении ориентации
        if (window.innerWidth <= 768 && activeItem) {
            hideTooltip();
        }
    }
    
    window.addEventListener('resize', handleResize);

    // АНИМАЦИЯ ПОЯВЛЕНИЯ
    setTimeout(() => {
        diagram.style.opacity = '1';
        diagram.style.transform = 'translateY(0)';
    }, 300);

    // ИНИЦИАЛИЗАЦИЯ
    diagram.style.opacity = '0';
    diagram.style.transform = 'translateY(20px)';
    diagram.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

    console.log('Deboart Paradigm Diagram loaded successfully');
});
