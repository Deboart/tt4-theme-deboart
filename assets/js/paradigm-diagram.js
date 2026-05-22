/**
 * Deboart Paradigm Diagram - Interactive Schema
 * Версия: 2.5 (финал — кликабельные ссылки)
 */

document.addEventListener('DOMContentLoaded', function() {
    const diagram = document.getElementById('deboartParadigmDiagram');
    const tooltip = document.getElementById('paradigmTooltip');
    
    if (!diagram || !tooltip) return;

    const tooltipTitle = tooltip.querySelector('.tooltip-title');
    const tooltipExamples = tooltip.querySelector('.tooltip-examples');
    const tooltipDescription = tooltip.querySelector('.tooltip-description');
    const hasDescription = !!tooltipDescription;

    const badgeColors = {
        'text': '#1A5FB4', 'image': '#2A8C6E', 'video': '#D4B48C',
        'audio': '#6C6C6C', 'web': '#1A5FB4', 'object': '#2A8C6E',
        'tishina': '#6C6C6C', 'energy': '#1A5FB4', 'thought': '#2A8C6E',
        'drama': '#D4B48C', 'chaos': '#A0AEC0', 'memory': '#2A2A2A'
    };

    const feelingDescriptions = {
        'tishina': 'Тишина и созерцание',
        'energy': 'Энергия и движение',
        'thought': 'Мысль и рефлексия',
        'drama': 'Драма и напряжение',
        'chaos': 'Хаос и случайность',
        'memory': 'Память и время'
    };

    const fallbackExamples = {
        'text': [{title: 'Бесконечное зеркало', url: '/works/infinite-mirror'}],
        'image': [{title: 'Логотип DEBOART', url: '/works/deboart-logo'}],
        'video': [{title: 'Подниму голову в небеса', url: '/works/video-clip'}],
        'audio': [{title: 'Звуки тишины', url: '/works/sounds-of-silence'}],
        'web': [{title: 'Сайт DEBOART', url: '/'}],
        'object': [{title: 'Бесконечное зеркало 2', url: '/works/infinite-mirror-2'}],
        'tishina': [{title: 'Медитация', url: '/works/meditation'}],
        'energy': [{title: 'Пульс города', url: '/works/city-pulse'}],
        'thought': [{title: 'Стихотворение', url: '/works/poem'}],
        'drama': [{title: 'Конфликт', url: '/works/conflict'}],
        'chaos': [{title: 'Случайный коллаж', url: '/works/random-collage'}],
        'memory': [{title: 'Воспоминание', url: '/works/memory'}]
    };

    const formItems = diagram.querySelectorAll('[data-form]');
    const contentItems = diagram.querySelectorAll('[data-content]');
    const allItems = [...formItems, ...contentItems];

    let activeItem = null;
    let hideTimeout = null;

    function getItemTitle(item) {
        const iconSpan = item.querySelector('.paradigm-item-icon');
        let icon = '';
        if (iconSpan) {
            icon = iconSpan.textContent || iconSpan.innerText || '';
            if (!icon.trim() && iconSpan.querySelector('img')) {
                const img = iconSpan.querySelector('img');
                icon = img.getAttribute('alt') || '🎨';
            }
        }
        icon = icon.trim() || '🎨';
        
        const labelSpan = item.querySelector('.paradigm-item-label');
        const label = labelSpan ? (labelSpan.textContent || labelSpan.innerText || '').trim() : '';
        
        return `${icon} ${label}`;
    }

    function getItemDescription(item) {
        if (item.dataset.content) {
            return item.dataset.description || feelingDescriptions[item.dataset.content] || '';
        }
        const form = item.dataset.form;
        const descriptions = {
            'text': 'Исследование языка как материала. Поэзия, эссе, манифесты.',
            'image': 'Диалог с визуальным пространством. Фотография, графика, коллажи.',
            'video': 'Время как измерение формы. Видеоарт, клипы, документация.',
            'audio': 'Пространство слышимого. Звуковые эксперименты, композиции.',
            'web': 'Интерактивные инсталляции, генеративное искусство, сетевые проекты.',
            'object': 'Физические артефакты, инсталляции, материальные эксперименты.'
        };
        return descriptions[form] || '';
    }

    function getExamples(item) {
        const key = item.dataset.form || item.dataset.content;
        
        if (item.dataset.examples && item.dataset.examples !== '[]') {
            try {
                const examples = JSON.parse(item.dataset.examples);
                if (Array.isArray(examples) && examples.length > 0) {
                    return examples;
                }
            } catch(e) {
                console.warn('Ошибка парсинга examples:', e);
            }
        }
        
        if (key && fallbackExamples[key]) {
            return fallbackExamples[key];
        }
        return [];
    }

    function showTooltip(item, event) {
        // Отменяем скрытие, если было запланировано
        if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
        }
        
        const iconSpan = item.querySelector('.paradigm-item-icon');
        let icon = '';
        if (iconSpan) {
            icon = iconSpan.textContent || iconSpan.innerText || '';
            if (!icon.trim() && iconSpan.querySelector('img')) {
                const img = iconSpan.querySelector('img');
                icon = img.getAttribute('alt') || '🎨';
            }
        }
        icon = icon.trim() || '🎨';
        
        const labelSpan = item.querySelector('.paradigm-item-label');
        const label = labelSpan ? (labelSpan.textContent || labelSpan.innerText || '').trim() : '';
        const title = `${icon} ${label}`;
        
        const description = getItemDescription(item);
        const examples = getExamples(item);

        const cleanTitle = title.replace(/<[^>]*>/g, '');
        tooltipTitle.textContent = cleanTitle;
        
        if (hasDescription && tooltipDescription) {
            if (description) {
                tooltipDescription.style.display = 'block';
                tooltipDescription.textContent = description;
            } else {
                tooltipDescription.style.display = 'none';
            }
        }

        tooltipExamples.innerHTML = '';
        
        if (examples.length > 0) {
            const examplesTitle = document.createElement('div');
            examplesTitle.className = 'tooltip-examples-title';
            examplesTitle.textContent = 'Примеры работ:';
            tooltipExamples.appendChild(examplesTitle);
            
            const examplesList = document.createElement('div');
            examplesList.className = 'tooltip-examples-list';
            
            examples.forEach(example => {
                const link = document.createElement('a');
                link.className = 'tooltip-badge';
                link.textContent = example.title;
                link.href = example.url;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                
                // Останавливаем всплытие, чтобы не скрывать тултип
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                
                // При наведении на ссылку — отменяем скрытие
                link.addEventListener('mouseenter', function() {
                    if (hideTimeout) {
                        clearTimeout(hideTimeout);
                        hideTimeout = null;
                    }
                });
                
                const key = item.dataset.form || item.dataset.content;
                if (key && badgeColors[key]) {
                    link.style.backgroundColor = badgeColors[key];
                }
                
                examplesList.appendChild(link);
            });
            
            tooltipExamples.appendChild(examplesList);
        } else {
            const noExamples = document.createElement('div');
            noExamples.className = 'tooltip-no-examples';
            noExamples.textContent = 'Примеры работ появятся позже';
            tooltipExamples.appendChild(noExamples);
        }

        // Позиционирование
        const rect = item.getBoundingClientRect();
        const diagramRect = diagram.getBoundingClientRect();

        tooltip.style.position = 'absolute';
        
        if (rect.bottom + 250 < window.innerHeight) {
            tooltip.className = 'paradigm-tooltip active bottom';
            tooltip.style.top = (rect.bottom - diagramRect.top + 10) + 'px';
        } else {
            tooltip.className = 'paradigm-tooltip active top';
            tooltip.style.top = (rect.top - diagramRect.top - tooltip.offsetHeight - 10) + 'px';
        }

        tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2) + 'px';
        tooltip.style.right = 'auto';

        const tooltipRect = tooltip.getBoundingClientRect();
        if (tooltipRect.left < diagramRect.left) {
            tooltip.style.left = '0px';
        }
        if (tooltipRect.right > diagramRect.right) {
            tooltip.style.left = (diagramRect.width - tooltip.offsetWidth) + 'px';
        }

        if (activeItem) activeItem.classList.remove('active');
        item.classList.add('active');
        activeItem = item;
    }

    function scheduleHideTooltip() {
        if (hideTimeout) clearTimeout(hideTimeout);
        hideTimeout = setTimeout(function() {
            tooltip.classList.remove('active');
            if (activeItem) {
                activeItem.classList.remove('active');
                activeItem = null;
            }
        }, 400);
    }

    function hideTooltipNow() {
        if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
        }
        tooltip.classList.remove('active');
        if (activeItem) {
            activeItem.classList.remove('active');
            activeItem = null;
        }
    }

    // Обработчики
    allItems.forEach(item => {
        item.addEventListener('mouseenter', function(e) {
            showTooltip(this, e);
        });
        
        item.addEventListener('mouseleave', function(e) {
            scheduleHideTooltip();
        });
    });
    
    // Тултип: при наведении — отменяем скрытие
    tooltip.addEventListener('mouseenter', function() {
        if (hideTimeout) {
            clearTimeout(hideTimeout);
            hideTimeout = null;
        }
    });
    
    tooltip.addEventListener('mouseleave', function() {
        scheduleHideTooltip();
    });
    
    diagram.addEventListener('mouseleave', function() {
        scheduleHideTooltip();
    });

    // Мобильные устройства
    diagram.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const target = e.target.closest('.paradigm-item');
            if (!target) {
                hideTooltipNow();
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && activeItem && !diagram.contains(e.target)) {
            hideTooltipNow();
        }
    });

    function handleResize() {
        if (window.innerWidth <= 768 && activeItem) {
            hideTooltipNow();
        }
    }
    window.addEventListener('resize', handleResize);

    // Анимация
    diagram.style.opacity = '0';
    diagram.style.transform = 'translateY(20px)';
    diagram.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    
    setTimeout(() => {
        diagram.style.opacity = '1';
        diagram.style.transform = 'translateY(0)';
    }, 300);

    console.log('Deboart Paradigm Diagram v2.5 loaded');
});