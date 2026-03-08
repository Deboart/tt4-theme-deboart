/**
 * Соединение сеток между секциями
 * Обеспечивает плавный переход линий
 */

document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.querySelector('.section-hero');
    const methodSection = document.querySelector('.deboart-method-section');
    
    if (!heroSection || !methodSection) return;
    
    const heroGrid = document.querySelector('.hero-grid-3d');
    const methodGrid = methodSection; // Используем псевдо-элемент через CSS
    
    // Функция для синхронизации позиций сеток
    function syncGrids() {
        const heroRect = heroSection.getBoundingClientRect();
        const methodRect = methodSection.getBoundingClientRect();
        const scrollY = window.scrollY;
        
        // Расстояние между секциями
        const distance = methodRect.top - heroRect.bottom;
        
        // Если секции перекрываются или рядом
        if (Math.abs(distance) < 100) {
            // Добавляем класс для плавного перехода
            methodSection.classList.add('grid-connected');
            
            // Вычисляем прогресс перехода
            const progress = Math.min(1, Math.max(0, 
                (scrollY + heroRect.height - heroRect.top) / 200
            ));
            
            // Плавно меняем наклон hero сетки
            if (heroGrid) {
                const rotateX = 65 - (progress * 65);
                heroGrid.style.transform = `rotateX(${rotateX}deg) rotateY(0deg)`;
            }
        } else {
            methodSection.classList.remove('grid-connected');
        }
    }
    
    // Запускаем при скролле
    window.addEventListener('scroll', syncGrids);
    syncGrids(); // Запускаем сразу
    
    // При ресайзе тоже обновляем
    window.addEventListener('resize', syncGrids);
});