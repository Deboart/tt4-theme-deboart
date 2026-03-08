document.addEventListener('DOMContentLoaded', function() {
	
	console.log('Mobile menu script loaded');
console.log('Toggle:', document.querySelector('.mobile-menu-toggle'));
console.log('Overlay:', document.querySelector('.mobile-menu-overlay'));
console.log('Close:', document.querySelector('.mobile-menu-close'));

    const toggle = document.querySelector('.mobile-menu-toggle');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const close = document.querySelector('.mobile-menu-close');
    
    if (!toggle || !overlay || !close) return;
    
    toggle.addEventListener('click', function() {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Блокируем скролл
    });
    
    close.addEventListener('click', function() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Закрытие по клику вне меню
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});