/**
 * Hero 3D Grid - интерактивная сетка
 * Реагирует на движение мыши
 */

document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.querySelector('.section-hero');
    if (!heroSection) return;
    
    // Проверяем, не добавлена ли уже сетка
    if (document.querySelector('.hero-grid-container')) return;
    
    // Создаем контейнер для сетки
    const container = document.createElement('div');
    container.className = 'hero-grid-container';
    
    // Создаем сетку
    const grid = document.createElement('div');
    grid.className = 'hero-grid-3d';
    
    container.appendChild(grid);
    heroSection.appendChild(container);
    
    // Переменные для плавности
    let mouseX = 0;
    let mouseY = 0;
    let currentRotateY = 0;
    let currentRotateX = 60; // Базовый наклон
    let currentTranslateX = 0;
    let currentTranslateY = 0;
    
    // Целевые значения
    let targetRotateY = 0;
    let targetRotateX = 60;
    let targetTranslateX = 0;
    let targetTranslateY = 0;
    
    // Отслеживание мыши
    document.addEventListener('mousemove', function(e) {
        // Нормализуем координаты от -1 до 1
        mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
        mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
        
        // Рассчитываем целевые значения
        targetRotateY = mouseX * 15; // ±15 градусов
        targetRotateX = 60 + mouseY * 8; // 52-68 градусов
        targetTranslateX = mouseX * 30; // Сдвиг по X
        targetTranslateY = mouseY * 20; // Сдвиг по Y
    });
    
    // Анимация с плавностью
    function animate() {
        // Плавно приближаемся к целевым значениям
        currentRotateY += (targetRotateY - currentRotateY) * 0.05;
        currentRotateX += (targetRotateX - currentRotateX) * 0.05;
        currentTranslateX += (targetTranslateX - currentTranslateX) * 0.05;
        currentTranslateY += (targetTranslateY - currentTranslateY) * 0.05;
        
        // Применяем трансформацию
        grid.style.transform = `rotateX(${currentRotateX}deg) rotateY(${currentRotateY}deg) rotateZ(0deg)`;
        grid.style.top = `calc(-50% + ${currentTranslateY}px)`;
        grid.style.left = `calc(-50% + ${currentTranslateX}px)`;
        
        requestAnimationFrame(animate);
    }
    
    animate();
    
    // Сброс при уходе мыши с окна
    document.addEventListener('mouseleave', function() {
        targetRotateY = 0;
        targetRotateX = 60;
        targetTranslateX = 0;
        targetTranslateY = 0;
    });
    
    // Адаптация при ресайзе
    window.addEventListener('resize', function() {
        // Ничего не делаем, сетка подстроится автоматически
    });
});

/**
 * Соединение сеток hero и method
 */
document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.querySelector('.section-hero');
    const methodSection = document.querySelector('.deboart-method-section');
    
    if (!heroSection || !methodSection) return;
    
    // При скролле меняем наклон hero сетки
    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        const heroBottom = heroSection.offsetTop + heroSection.offsetHeight;
        const methodTop = methodSection.offsetTop;
        
        // Когда скроллим от hero к method
        if (scrollY > heroSection.offsetTop && scrollY < methodTop) {
            const progress = (scrollY - heroSection.offsetTop) / (methodTop - heroSection.offsetTop);
            
            // Постепенно уменьшаем наклон сетки
            const grid = document.querySelector('.hero-grid-3d');
            if (grid) {
                const rotateX = 65 - (progress * 65); // От 65° до 0°
                const translateY = progress * 50; // Сдвигаем вверх
                grid.style.transform = `rotateX(${rotateX}deg) rotateY(0deg) translateY(${translateY}px)`;
                grid.style.opacity = 1 - (progress * 0.3); // Слегка затухает
            }
        }
    });
    
    // Параллакс при движении мыши (для hero)
    document.addEventListener('mousemove', function(e) {
        const mouseY = e.clientY / window.innerHeight;
        
        // Эффект "водопада" - линии текут вниз при движении мыши вниз
        const heroGrid = document.querySelector('.hero-grid-3d');
        const methodGrid = document.querySelector('.deboart-method-section');
        
        if (heroGrid && mouseY > 0.5) {
            const flow = (mouseY - 0.5) * 20;
            heroGrid.style.backgroundPositionY = `${flow}px`;
        }
        
        if (methodGrid && mouseY > 0.3) {
            const flow = (mouseY - 0.3) * 30;
            methodGrid.style.backgroundPositionY = `${flow}px`;
        }
    });
});