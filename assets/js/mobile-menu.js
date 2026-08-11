document.addEventListener('DOMContentLoaded', function() {
    
    console.log('Mobile menu script loaded');
    
    // ===== ОТКРЫТИЕ/ЗАКРЫТИЕ МЕНЮ =====
    const toggle = document.querySelector('.mobile-menu-toggle');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const close = document.querySelector('.mobile-menu-close');
    
    if (toggle && overlay && close) {
        toggle.addEventListener('click', function() {
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        close.addEventListener('click', function() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
    
    // ===== ПОДМЕНЮ В МОБИЛЬНОМ МЕНЮ (двойной клик) =====
    const mobileDropdowns = document.querySelectorAll('.mobile-nav .menu-item-has-children');
    
    mobileDropdowns.forEach(function(item) {
        const link = item.querySelector('a');
        if (link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const isParent = href && (href.includes('/works') || href.includes('/research'));
                
                if (isParent) {
                    // Если подменю уже открыто — переходим по ссылке
                    if (item.classList.contains('active')) {
                        // Закрываем подменю перед переходом
                        item.classList.remove('active');
                        window.location.href = href;
                        return;
                    }
                    
                    // Если подменю закрыто — открываем его
                    e.preventDefault();
                    
                    // Закрываем другие открытые подменю
                    mobileDropdowns.forEach(function(other) {
                        if (other !== item) {
                            other.classList.remove('active');
                        }
                    });
                    
                    item.classList.add('active');
                }
            });
        }
    });
    
    // Закрываем подменю при клике вне меню
    document.addEventListener('click', function(e) {
        const isInside = e.target.closest('.mobile-nav');
        if (!isInside && document.querySelector('.mobile-nav .menu-item-has-children.active')) {
            document.querySelectorAll('.mobile-nav .menu-item-has-children.active').forEach(function(item) {
                item.classList.remove('active');
            });
        }
    });
    
});