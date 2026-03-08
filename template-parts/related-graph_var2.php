<?php
/**
 * Template Part: Related Works Graph
 * Description: Визуальный граф связей между работами в виде карточек с интерактивными врезками
 */

$current_work_id = isset($current_work_id) ? $current_work_id : get_the_ID();

// Функция для получения связанных работ
function get_graph_related_works($work_id, $limit = 8) {
    $current_form_terms = get_the_terms($work_id, 'work_form');
    $current_feeling_terms = get_the_terms($work_id, 'work_feeling');

    if (empty($current_form_terms) && empty($current_feeling_terms)) {
        return array();
    }

    $tax_query = array('relation' => 'OR');
    
    if (!empty($current_form_terms) && !is_wp_error($current_form_terms)) {
        foreach ($current_form_terms as $term) {
            $tax_query[] = array(
                'taxonomy' => 'work_form',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            );
        }
    }
    
    if (!empty($current_feeling_terms) && !is_wp_error($current_feeling_terms)) {
        foreach ($current_feeling_terms as $term) {
            $tax_query[] = array(
                'taxonomy' => 'work_feeling',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            );
        }
    }

    $related_query = new WP_Query(array(
        'post_type'      => 'work',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'post__not_in'   => array($work_id),
        'tax_query'      => $tax_query,
        'orderby'        => 'rand',
        'no_found_rows'  => true,
    ));

    return $related_query->posts;
}

// Получаем связанные работы
$max_works = 8;
$related_nodes = get_graph_related_works($current_work_id, $max_works);
$has_related = !empty($related_nodes) && count($related_nodes) >= 2;


// Собираем данные о связях (общие таксономии с весами)
$connections_data = array();

if ($has_related) {
    $current_form_terms = get_the_terms($current_work_id, 'work_form');
    $current_feeling_terms = get_the_terms($current_work_id, 'work_feeling');
    
    // Создаём карту весов для текущей работы
    $current_term_weights = array();
    
    if ($current_form_terms) {
        foreach ($current_form_terms as $term) {
            $current_term_weights[$term->term_id] = isset($current_term_weights[$term->term_id]) ? 
                $current_term_weights[$term->term_id] + 2 : 2;
        }
    }
    
    if ($current_feeling_terms) {
        foreach ($current_feeling_terms as $term) {
            $current_term_weights[$term->term_id] = isset($current_term_weights[$term->term_id]) ? 
                $current_term_weights[$term->term_id] + 1 : 1;
        }
    }
    
    foreach ($related_nodes as $work) {
        $work_form_terms = get_the_terms($work->ID, 'work_form');
        $work_feeling_terms = get_the_terms($work->ID, 'work_feeling');
        
        $common_terms = array();
        $max_weight = 0;
        $strongest_term = null;
        
        // Ищем общие формы
        if ($current_form_terms && $work_form_terms) {
            foreach ($current_form_terms as $c_term) {
                foreach ($work_form_terms as $w_term) {
                    if ($c_term->term_id == $w_term->term_id) {
                        $weight = isset($current_term_weights[$c_term->term_id]) ? 
                            $current_term_weights[$c_term->term_id] : 2;
                        
                        $term_data = array(
                            'id' => $c_term->term_id,
                            'name' => $c_term->name,
                            'slug' => $c_term->slug,
                            'icon' => deboart_get_form_icon($c_term->slug),
                            'taxonomy' => 'form',
                            'weight' => $weight,
                            'url' => add_query_arg(
                                array('form' => array($c_term->slug)), 
                                get_post_type_archive_link('work')
                            )
                        );
                        
                        $common_terms[] = $term_data;
                        
                        if ($weight > $max_weight) {
                            $max_weight = $weight;
                            $strongest_term = $term_data;
                        }
                    }
                }
            }
        }
        
        // Ищем общие содержания
        if ($current_feeling_terms && $work_feeling_terms) {
            foreach ($current_feeling_terms as $c_term) {
                foreach ($work_feeling_terms as $w_term) {
                    if ($c_term->term_id == $w_term->term_id) {
                        $weight = isset($current_term_weights[$c_term->term_id]) ? 
                            $current_term_weights[$c_term->term_id] : 1;
                        
                        $term_data = array(
                            'id' => $c_term->term_id,
                            'name' => $c_term->name,
                            'slug' => $c_term->slug,
                            'icon' => deboart_get_feeling_icon($c_term->slug),
                            'taxonomy' => 'feeling',
                            'weight' => $weight,
                            'url' => add_query_arg(
                                array('feeling' => array($c_term->slug)), 
                                get_post_type_archive_link('work')
                            )
                        );
                        
                        $common_terms[] = $term_data;
                        
                        if ($weight > $max_weight) {
                            $max_weight = $weight;
                            $strongest_term = $term_data;
                        }
                    }
                }
            }
        }
        
        // Сортируем общие термины по весу (от большего к меньшему)
        usort($common_terms, function($a, $b) {
            return $b['weight'] - $a['weight'];
        });
        
        // Сохраняем все общие термины и самый сильный
        $connections_data[$work->ID] = array(
            'all' => $common_terms,
            'strongest' => $strongest_term,
            'weight' => $max_weight,
            'icons' => array_column($common_terms, 'icon'), // Массив иконок для отображения
            'urls' => array_column($common_terms, 'url')    // Соответствующие URL
        );
    }
}
?>

<?php if ($has_related) : ?>
<section class="work-section work-graph">
    <div class="section-header">
        <h2 class="section-title">
            <span class="section-icon">🕸️</span>
            Граф связей
        </h2>
        <div class="section-subtitle">
            Исследования, связанные по форме и содержанию
        </div>
    </div>
    
    <div class="section-content">
        <div class="graph-container">
            <div class="graph-canvas-wrapper" id="graph-wrapper">
                <canvas id="work-graph-canvas"></canvas>
                <!-- Карточки и врезки будут добавляться через JavaScript -->
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.getElementById('graph-wrapper');
    if (!wrapper) return;
    
    // Данные о работах из PHP
    const works = {
        current: {
            id: <?php echo $current_work_id; ?>,
            title: <?php echo json_encode(get_the_title($current_work_id)); ?>,
            url: <?php echo json_encode(get_permalink($current_work_id)); ?>,
            year: <?php echo json_encode(get_post_meta($current_work_id, 'work_date', true)); ?>,
            thumbnail: <?php echo has_post_thumbnail($current_work_id) ? json_encode(get_the_post_thumbnail_url($current_work_id, 'thumbnail')) : 'null'; ?>,
            icons: <?php 
                $current_icons = array();
                $form_terms = get_the_terms($current_work_id, 'work_form');
                $feeling_terms = get_the_terms($current_work_id, 'work_feeling');
                
                if ($form_terms && !is_wp_error($form_terms)) {
                    foreach ($form_terms as $term) {
                        $current_icons[] = deboart_get_form_icon($term->slug);
                    }
                }
                if ($feeling_terms && !is_wp_error($feeling_terms)) {
                    foreach ($feeling_terms as $term) {
                        $current_icons[] = deboart_get_feeling_icon($term->slug);
                    }
                }
                echo json_encode($current_icons);
            ?>
        },
        related: [
            <?php foreach ($related_nodes as $work) : ?>
            {
                id: <?php echo $work->ID; ?>,
                title: <?php echo json_encode(get_the_title($work->ID)); ?>,
                url: <?php echo json_encode(get_permalink($work->ID)); ?>,
                year: <?php echo json_encode(get_post_meta($work->ID, 'work_date', true)); ?>,
                thumbnail: <?php echo has_post_thumbnail($work->ID) ? json_encode(get_the_post_thumbnail_url($work->ID, 'thumbnail')) : 'null'; ?>,
                icons: <?php 
                    $icons = array();
                    $form_terms = get_the_terms($work->ID, 'work_form');
                    $feeling_terms = get_the_terms($work->ID, 'work_feeling');
                    
                    if ($form_terms && !is_wp_error($form_terms)) {
                        foreach ($form_terms as $term) {
                            $icons[] = deboart_get_form_icon($term->slug);
                        }
                    }
                    if ($feeling_terms && !is_wp_error($feeling_terms)) {
                        foreach ($feeling_terms as $term) {
                            $icons[] = deboart_get_feeling_icon($term->slug);
                        }
                    }
                    echo json_encode($icons);
                ?>
            },
            <?php endforeach; ?>
        ]
    };
    
    // Данные о связях (общие таксономии)
    const connectionsData = <?php echo json_encode($connections_data); ?>;
    
    // Функция создания карточки
    function createCard(work, isCurrent = false) {
        const card = document.createElement('div');
        card.className = `graph-card ${isCurrent ? 'current' : 'related'}`;
        card.dataset.id = work.id;
        
        // Иконки
        const iconsHtml = work.icons.slice(0, 3).map(icon => 
            `<span class="graph-card-icon">${icon}</span>`
        ).join('');
        
        const moreHtml = work.icons.length > 3 ? 
            `<span class="graph-card-icon-more">+${work.icons.length - 3}</span>` : '';
        
        // Изображение или плейсхолдер
        let imageHtml = '';
        if (work.thumbnail) {
            imageHtml = `<img src="${work.thumbnail}" alt="${work.title}" class="graph-card-img">`;
        } else {
            imageHtml = `<span class="graph-card-placeholder-icon">${work.icons[0] || '🎨'}</span>`;
        }
        
        card.innerHTML = `
            <a href="${work.url}" class="graph-card-link">
                <div class="graph-card-image">
                    ${imageHtml}
                </div>
                <div class="graph-card-content">
                    <div class="graph-card-icons">
                        ${iconsHtml}
                        ${moreHtml}
                    </div>
                    <h3 class="graph-card-title">${work.title}</h3>
                    ${work.year ? `<span class="graph-card-year">${work.year}</span>` : ''}
                </div>
            </a>
        `;
        
        return card;
    }
    
    // Проверка на мобильное устройство
    function isMobile() {
        return window.innerWidth <= 768;
    }
    
    // Позиционирование карточек
    function positionCards() {
        const wrapperRect = wrapper.getBoundingClientRect();
        
        // Удаляем старые карточки и врезки
        const oldCards = wrapper.querySelectorAll('.graph-card');
        oldCards.forEach(card => card.remove());
        
        const oldConnections = wrapper.querySelectorAll('.graph-connection');
        oldConnections.forEach(conn => conn.remove());
        
        // Создаём или очищаем canvas
        let canvas = wrapper.querySelector('#work-graph-canvas');
        if (!canvas) {
            canvas = document.createElement('canvas');
            canvas.id = 'work-graph-canvas';
            wrapper.appendChild(canvas);
        }
        canvas.width = wrapperRect.width;
        canvas.height = wrapperRect.height;
        
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Центр
        const centerX = wrapperRect.width / 2;
        const centerY = wrapperRect.height / 2;
        
        // Размеры карточек
        const cardWidth = isMobile() ? 100 : 180;
        const cardHeight = isMobile() ? 140 : 200;
        
        // Создаём центральную карточку
        const currentCard = createCard(works.current, true);
        currentCard.style.position = 'absolute';
        currentCard.style.left = centerX + 'px';
        currentCard.style.top = centerY + 'px';
        currentCard.style.transform = 'translate(-50%, -50%)';
        currentCard.style.zIndex = '10';
        wrapper.appendChild(currentCard);
        
        // Если нет связанных работ, выходим
        if (works.related.length === 0) return;
        
        const relatedCount = works.related.length;
        
        // Эллиптическая орбита
        let radiusX = wrapperRect.width * 0.4;
        let radiusY = wrapperRect.height * 0.3;
        
        if (relatedCount >= 6) {
            radiusX = wrapperRect.width * 0.45;
            radiusY = wrapperRect.height * 0.35;
        } else if (relatedCount >= 4) {
            radiusX = wrapperRect.width * 0.42;
            radiusY = wrapperRect.height * 0.32;
        }
        
        const minRadiusX = cardWidth * 1.5 + (relatedCount * 10);
        const minRadiusY = cardHeight * 1.2 + (relatedCount * 5);
        
        radiusX = Math.max(radiusX, minRadiusX);
        radiusY = Math.max(radiusY, minRadiusY);
        
        const maxRadiusX = wrapperRect.width * 0.45 - cardWidth/2;
        const maxRadiusY = wrapperRect.height * 0.45 - cardHeight/2;
        
        radiusX = Math.min(radiusX, maxRadiusX);
        radiusY = Math.min(radiusY, maxRadiusY);
        
        // Позиционируем связанные карточки
        const angleStep = (Math.PI * 2) / relatedCount;
        const startAngle = -Math.PI / 2;
        
        const positions = [];
        
        works.related.forEach((work, index) => {
            const angle = startAngle + (index * angleStep);
            
            let x = centerX + Math.cos(angle) * radiusX;
            let y = centerY + Math.sin(angle) * radiusY;
            
            // Корректируем вертикальное положение
            const verticalDistance = Math.abs(y - centerY);
            if (verticalDistance < cardHeight * 0.8) {
                y = centerY + Math.sin(angle) * radiusY * 1.2;
            }
            
            x = Math.max(cardWidth/2 + 15, Math.min(wrapperRect.width - cardWidth/2 - 15, x));
            y = Math.max(cardHeight/2 + 15, Math.min(wrapperRect.height - cardHeight/2 - 15, y));
            
            positions.push({ x, y, angle });
            
            const card = createCard(work, false);
            card.style.position = 'absolute';
            card.style.left = x + 'px';
            card.style.top = y + 'px';
            card.style.transform = 'translate(-50%, -50%)';
            card.style.zIndex = '5';
            wrapper.appendChild(card);
        });
        
        // Рисуем линии и создаём врезки
        ctx.strokeStyle = 'rgba(26, 95, 180, 0.25)';
        ctx.lineWidth = 1.5;
        ctx.setLineDash([6, 4]);
        
        const connectionElements = [];
        
// Внутри positionCards(), после positions.forEach
positions.forEach((pos, index) => {
    const workId = works.related[index].id;
    const connectionInfo = connectionsData[workId] || null;
    
    // Рисуем линию
    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
    
    // Создаём врезку, если есть общие термины
    if (connectionInfo && connectionInfo.all && connectionInfo.all.length > 0) {
        const terms = connectionInfo.all;
        const icons = connectionInfo.icons || [terms[0].icon];
        const urls = connectionInfo.urls || terms.map(t => t.url);
        
        // Координаты середины линии
        const midX = (centerX + pos.x) / 2;
        const midY = (centerY + pos.y) / 2;
        
        // Размер зависит от количества иконок
        const iconCount = Math.min(icons.length, 4); // Максимум 4 иконки
        const baseSize = 40;
        let width, height;
        
        if (iconCount === 1) {
            width = height = 36;
        } else if (iconCount === 2) {
            width = 60;
            height = 36;
        } else if (iconCount === 3) {
            width = 80;
            height = 36;
        } else {
            width = 96;
            height = 36;
        }
        
        // Создаём контейнер для иконок
        const connection = document.createElement('div');
        connection.className = 'graph-connection multi-icon';
        connection.setAttribute('data-work-id', workId);
        
        connection.style.position = 'absolute';
        connection.style.left = (midX - width/2) + 'px';
        connection.style.top = (midY - height/2) + 'px';
        connection.style.width = width + 'px';
        connection.style.height = height + 'px';
        connection.style.backgroundColor = 'rgba(20, 20, 20, 0.9)';
        connection.style.border = '2px solid rgba(26, 95, 180, 0.6)';
        connection.style.borderRadius = '18px';
        connection.style.display = 'flex';
        connection.style.alignItems = 'center';
        connection.style.justifyContent = 'space-around';
        connection.style.padding = '0 4px';
        connection.style.gap = '2px';
        connection.style.cursor = 'pointer';
        connection.style.zIndex = '20';
        connection.style.transition = 'all 0.2s ease';
        connection.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.3)';
        
        // Заполняем иконками
        icons.slice(0, 4).forEach((icon, i) => {
            const iconEl = document.createElement('span');
            iconEl.innerHTML = icon;
            iconEl.style.fontSize = '18px';
            iconEl.style.lineHeight = '1';
            iconEl.style.cursor = 'pointer';
            iconEl.style.transition = 'transform 0.2s ease';
            iconEl.setAttribute('data-term-index', i);
            
            // Подсказка для каждой иконки
            if (terms[i]) {
                iconEl.title = terms[i].name;
            }
            
            // Клик по конкретной иконке
            iconEl.addEventListener('click', (e) => {
                e.stopPropagation();
                if (urls[i]) {
                    window.location.href = urls[i];
                }
            });
            
            connection.appendChild(iconEl);
        });
        
        // Общий клик по контейнеру (по умолчанию на самую сильную связь)
        connection.addEventListener('click', (e) => {
            // Если клик не по иконке-ребёнку
            if (e.target === connection && urls[0]) {
                window.location.href = urls[0];
            }
        });
        
        // Подсветка при наведении
        connection.addEventListener('mouseenter', () => {
            connection.style.transform = 'scale(1.1)';
            connection.style.borderColor = 'var(--wp--preset--color--accent-blue, #1a5fb4)';
            connection.style.backgroundColor = 'rgba(26, 95, 180, 0.3)';
            connection.style.zIndex = '25';
            
            // Подсвечиваем линию
            ctx.save();
            ctx.strokeStyle = 'rgba(26, 95, 180, 0.8)';
            ctx.lineWidth = 2.5;
            ctx.setLineDash([]);
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            ctx.restore();
        });
        
        connection.addEventListener('mouseleave', () => {
            connection.style.transform = 'scale(1)';
            connection.style.borderColor = 'rgba(26, 95, 180, 0.6)';
            connection.style.backgroundColor = 'rgba(20, 20, 20, 0.9)';
            connection.style.zIndex = '20';
            
            // Перерисовываем все линии
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            ctx.strokeStyle = 'rgba(26, 95, 180, 0.25)';
            ctx.lineWidth = 1.5;
            ctx.setLineDash([6, 4]);
            
            positions.forEach(p => {
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.lineTo(p.x, p.y);
                ctx.stroke();
            });
        });
        
        wrapper.appendChild(connection);
        connectionElements.push(connection);
    }
});
        
        ctx.setLineDash([]);
        
        // Сохраняем позиции для обновления при ресайзе
        window.__graphPositions = positions;
        window.__graphConnections = connectionElements;
    }
    
    // Запускаем
    positionCards();
    
    // Обновляем при ресайзе
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            positionCards();
        }, 100);
    });
});
</script>

<style>
.work-graph .graph-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 40px 20px;
    position: relative;
    overflow: visible;
}

.graph-canvas-wrapper {
    position: relative;
    width: 100%;
    min-height: 700px;
    height: 700px;
}

@media (min-width: 1600px) {
    .graph-canvas-wrapper {
        min-height: 800px;
        height: 800px;
    }
}

@media (max-width: 1024px) {
    .graph-canvas-wrapper {
        min-height: 600px;
        height: 600px;
    }
}

@media (max-width: 768px) {
    .graph-canvas-wrapper {
        min-height: 500px;
        height: 500px;
    }
}

@media (max-width: 480px) {
    .graph-canvas-wrapper {
        min-height: 400px;
        height: 400px;
    }
}

#work-graph-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    pointer-events: none;
    z-index: 1;
}

.graph-card {
    width: 180px;
    background: rgba(20, 20, 20, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    cursor: pointer;
    position: absolute;
    z-index: 5;
}

.graph-card.current {
    border-color: var(--wp--preset--color--accent-blue, #1a5fb4);
    box-shadow: 0 0 20px rgba(26, 95, 180, 0.3);
    z-index: 10;
    width: 200px;
}

.graph-card.related:hover {
    transform: translate(-50%, -50%) scale(1.05) !important;
    border-color: var(--wp--preset--color--accent-blue, #1a5fb4);
    z-index: 15;
}

.graph-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.graph-card-image {
    width: 100%;
    height: 100px;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.graph-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.graph-card:hover .graph-card-image img {
    transform: scale(1.05);
}

.graph-card-placeholder-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 2rem;
    background: rgba(13, 27, 42, 0.5);
}

.graph-card-content {
    padding: 10px;
}

.graph-card-icons {
    display: flex;
    gap: 4px;
    margin-bottom: 6px;
    flex-wrap: wrap;
}

.graph-card-icon {
    font-size: 14px;
    opacity: 0.8;
}

.graph-card-icon-more {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.5);
    font-family: var(--wp--preset--font-family--jetbrains-mono, monospace);
    align-self: center;
}

.graph-card-title {
    font-family: var(--wp--preset--font-family--spectral, serif);
    font-size: 13px;
    font-weight: 500;
    margin: 0 0 4px 0;
    color: var(--wp--preset--color--primary-white, #f8f8f8);
    line-height: 1.3;
    word-break: break-word;
}

.graph-card-year {
    font-family: var(--wp--preset--font-family--jetbrains-mono, monospace);
    font-size: 10px;
    color: rgba(255, 255, 255, 0.4);
}

/* Стили для врезок */
.graph-connection {
    transition: all 0.2s ease;
    user-select: none;
    font-family: var(--wp--preset--font-family--jetbrains-mono, monospace);
}

.graph-connection:hover {
    transform: scale(1.2);
    border-color: var(--wp--preset--color--accent-blue, #1a5fb4) !important;
    background-color: rgba(26, 95, 180, 0.3) !important;
}

/* Десктоп */
@media (min-width: 1200px) {
    .graph-card {
        width: 200px;
    }
    
    .graph-card.current {
        width: 220px;
    }
    
    .graph-card-image {
        height: 120px;
    }
}

/* Планшеты */
@media (max-width: 1024px) {
    .graph-canvas-wrapper {
        min-height: 500px;
        height: 500px;
    }
    
    .graph-card {
        width: 160px;
    }
    
    .graph-card.current {
        width: 180px;
    }
}

@media (max-width: 768px) {
    .graph-canvas-wrapper {
        min-height: 450px;
        height: 450px;
    }
    
    .graph-card {
        width: 140px;
    }
    
    .graph-card.current {
        width: 160px;
    }
    
    .graph-card-image {
        height: 80px;
    }
    
    .graph-card-title {
        font-size: 12px;
    }
    
    .graph-connection {
        width: 30px !important;
        height: 30px !important;
        font-size: 16px !important;
        left: calc(50% - 15px) !important;
    }
}

@media (max-width: 600px) {
    .graph-canvas-wrapper {
        min-height: 400px;
        height: 400px;
    }
    
    .graph-card {
        width: 120px;
    }
    
    .graph-card.current {
        width: 140px;
    }
    
    .graph-card-image {
        height: 70px;
    }
    
    .graph-card-title {
        font-size: 11px;
    }
}

@media (max-width: 480px) {
    .graph-canvas-wrapper {
        min-height: 350px;
        height: 350px;
    }
    
    .graph-card {
        width: 100px;
    }
    
    .graph-card.current {
        width: 120px;
    }
    
    .graph-card-image {
        height: 60px;
    }
    
    .graph-card-icon {
        font-size: 12px;
    }
    
    .graph-card-title {
        font-size: 10px;
    }
    
    .graph-connection {
        width: 26px !important;
        height: 26px !important;
        font-size: 14px !important;
    }
}

/* Множественные иконки на врезках */
.graph-connection.multi-icon {
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 0 4px;
}

.graph-connection.multi-icon span {
    display: inline-block;
    transition: transform 0.2s ease;
}

.graph-connection.multi-icon span:hover {
    transform: scale(1.3);
    filter: drop-shadow(0 0 4px rgba(26, 95, 180, 0.8));
}

/* Адаптивность для мобильных */
@media (max-width: 768px) {
    .graph-connection.multi-icon {
        height: 30px !important;
    }
    
    .graph-connection.multi-icon span {
        font-size: 16px !important;
    }
}

@media (max-width: 480px) {
    .graph-connection.multi-icon span {
        font-size: 14px !important;
    }
}
</style>
<?php endif; ?>