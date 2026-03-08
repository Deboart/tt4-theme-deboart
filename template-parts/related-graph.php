<?php
/**
 * Template Part: Related Works Graph
 * Description: Визуальный граф связей между работами с поддержкой ручных и автоматических связей
 * Version: 2.0
 */

$current_work_id = isset($current_work_id) ? $current_work_id : get_the_ID();

// Функция для получения автоматических связей (через таксономии)
function get_auto_related_works($work_id, $limit = 8) {
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

// Функция для получения ручных связей (из Pods)
function get_manual_related_works($work_id) {
    $manual_ids = get_post_meta($work_id, 'manual_connections', true);
    
    if (empty($manual_ids)) {
        return array();
    }
    
    // Преобразуем в массив, если это строка
    if (is_string($manual_ids)) {
        $manual_ids = explode(',', $manual_ids);
    }
    
    // Получаем описания связей (если есть)
    $descriptions = get_post_meta($work_id, 'connection_description', true);
    if (!is_array($descriptions)) {
        $descriptions = array();
    }
    
    $manual_works = array();
    foreach ($manual_ids as $index => $id) {
        $work = get_post($id);
        if ($work && $work->post_status === 'publish') {
            $manual_works[$id] = array(
                'work' => $work,
                'description' => isset($descriptions[$index]) ? $descriptions[$index] : 'Ручная связь'
            );
        }
    }
    
    return $manual_works;
}

// Получаем автоматические связи
$auto_related = get_auto_related_works($current_work_id, 12); // Получаем больше для отбора

// Получаем ручные связи
$manual_related = get_manual_related_works($current_work_id);

// Объединяем и сортируем
$all_related = array();
$connections_data = array();

// Функция для вычисления веса автоматической связи
function calculate_connection_weight($work_id, $related_id) {
    $weight = 0;
    
    $current_form_terms = get_the_terms($work_id, 'work_form');
    $current_feeling_terms = get_the_terms($work_id, 'work_feeling');
    $related_form_terms = get_the_terms($related_id, 'work_form');
    $related_feeling_terms = get_the_terms($related_id, 'work_feeling');
    
    // Считаем общие формы (вес 2)
    if ($current_form_terms && $related_form_terms) {
        foreach ($current_form_terms as $c_term) {
            foreach ($related_form_terms as $r_term) {
                if ($c_term->term_id == $r_term->term_id) {
                    $weight += 2;
                }
            }
        }
    }
    
    // Считаем общие содержания (вес 1)
    if ($current_feeling_terms && $related_feeling_terms) {
        foreach ($current_feeling_terms as $c_term) {
            foreach ($related_feeling_terms as $r_term) {
                if ($c_term->term_id == $r_term->term_id) {
                    $weight += 1;
                }
            }
        }
    }
    
    return $weight;
}

// Сначала добавляем ручные связи (с высоким весом)
foreach ($manual_related as $id => $data) {
    $work = $data['work'];
    $auto_weight = calculate_connection_weight($current_work_id, $id);
    
    $all_related[$id] = array(
        'work' => $work,
        'type' => 'manual',
        'weight' => 3 + $auto_weight, // Базовый вес 3 + автоматические
        'description' => $data['description']
    );
}

// Добавляем автоматические связи, если их ещё нет
foreach ($auto_related as $work) {
    $id = $work->ID;
    if (!isset($all_related[$id])) {
        $weight = calculate_connection_weight($current_work_id, $id);
        if ($weight > 0) {
            $all_related[$id] = array(
                'work' => $work,
                'type' => 'auto',
                'weight' => $weight
            );
        }
    } else {
        // Если уже есть ручная связь, обновляем тип и вес
        $all_related[$id]['type'] = 'both';
        $all_related[$id]['weight'] += calculate_connection_weight($current_work_id, $id);
    }
}

// Сортируем по весу (от большего к меньшему)
uasort($all_related, function($a, $b) {
    return $b['weight'] - $a['weight'];
});

// Ограничиваем до 8 работ
$all_related = array_slice($all_related, 0, 8, true);
$has_related = !empty($all_related);

// Собираем данные о связях для отображения
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
    
    foreach ($all_related as $id => $data) {
        $work = $data['work'];
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
        
        // Сортируем общие термины по весу
        usort($common_terms, function($a, $b) {
            return $b['weight'] - $a['weight'];
        });
        
        // Сохраняем данные о связях
        $connections_data[$work->ID] = array(
            'all' => $common_terms,
            'strongest' => $strongest_term,
            'weight' => $data['weight'],
            'type' => $data['type'],
            'description' => isset($data['description']) ? $data['description'] : '',
            'icons' => array_column($common_terms, 'icon'),
            'urls' => array_column($common_terms, 'url')
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
            Исследования, связанные по форме, содержанию и авторскому выбору
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
            <?php foreach ($all_related as $id => $data) : 
                $work = $data['work'];
            ?>
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
                ?>,
                type: <?php echo json_encode($data['type']); ?>,
                description: <?php echo json_encode(isset($data['description']) ? $data['description'] : ''); ?>
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
        if (work.type) {
            card.dataset.connectionType = work.type;
        }
        
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
        
        // Бейдж типа связи для ручных
        const typeBadge = work.type === 'manual' ? '<span class="graph-card-badge manual" title="Ручная связь">👆</span>' : 
                         (work.type === 'both' ? '<span class="graph-card-badge both" title="Комбинированная связь">🔗</span>' : '');
        
        card.innerHTML = `
            <a href="${work.url}" class="graph-card-link">
                <div class="graph-card-image">
                    ${imageHtml}
                    ${typeBadge}
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
		
		 // ЕСЛИ МОБИЛЬНОЕ УСТРОЙСТВО - НЕ РИСУЕМ ГРАФ
    if (isMobile()) {
        // На мобильных граф не рисуем, показываем карусель (она уже в HTML)
        return;
    }
    
		
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
        const connectionElements = [];
        
        positions.forEach((pos, index) => {
            const workId = works.related[index].id;
            const connectionInfo = connectionsData[workId] || null;
            const workData = works.related[index];
            
            // Определяем цвет линии по типу связи
            let lineColor = 'rgba(26, 95, 180, 0.25)'; // синий для авто
            if (workData.type === 'manual') {
                lineColor = 'rgba(212, 175, 55, 0.4)'; // золотой для ручных
            } else if (workData.type === 'both') {
                // Градиент для комбинированных
                lineColor = 'rgba(26, 95, 180, 0.4)';
            }
            
            ctx.strokeStyle = lineColor;
            ctx.lineWidth = workData.type === 'both' ? 2 : 1.5;
            ctx.setLineDash([6, 4]);
            
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
                const iconCount = Math.min(icons.length, 4);
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
                connection.style.border = `2px solid ${workData.type === 'manual' ? 'rgba(212, 175, 55, 0.6)' : 'rgba(26, 95, 180, 0.6)'}`;
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
                
                // Добавляем описание для ручных связей
                if (workData.type === 'manual' && workData.description) {
                    connection.setAttribute('title', workData.description);
                }
                
                // Заполняем иконками
                icons.slice(0, 4).forEach((icon, i) => {
                    const iconEl = document.createElement('span');
                    iconEl.innerHTML = icon;
                    iconEl.style.fontSize = '18px';
                    iconEl.style.lineHeight = '1';
                    iconEl.style.cursor = 'pointer';
                    iconEl.style.transition = 'transform 0.2s ease';
                    iconEl.setAttribute('data-term-index', i);
                    
                    if (terms[i]) {
                        iconEl.title = terms[i].name;
                    }
                    
                    iconEl.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (urls[i]) {
                            window.location.href = urls[i];
                        }
                    });
                    
                    connection.appendChild(iconEl);
                });
                
                connection.addEventListener('click', (e) => {
                    if (e.target === connection && urls[0]) {
                        window.location.href = urls[0];
                    }
                });
                
                connection.addEventListener('mouseenter', () => {
                    connection.style.transform = 'scale(1.1)';
                    connection.style.borderColor = workData.type === 'manual' ? 'rgba(212, 175, 55, 0.9)' : 'var(--wp--preset--color--accent-blue, #1a5fb4)';
                    connection.style.backgroundColor = workData.type === 'manual' ? 'rgba(212, 175, 55, 0.2)' : 'rgba(26, 95, 180, 0.3)';
                    connection.style.zIndex = '25';
                    
                    // Подсвечиваем линию
                    ctx.save();
                    ctx.strokeStyle = workData.type === 'manual' ? 'rgba(212, 175, 55, 0.8)' : 'rgba(26, 95, 180, 0.8)';
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
                    connection.style.borderColor = workData.type === 'manual' ? 'rgba(212, 175, 55, 0.6)' : 'rgba(26, 95, 180, 0.6)';
                    connection.style.backgroundColor = 'rgba(20, 20, 20, 0.9)';
                    connection.style.zIndex = '20';
                    
                    // Перерисовываем все линии
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    
                    positions.forEach((p, i) => {
                        const wData = works.related[i];
                        let lineClr = 'rgba(26, 95, 180, 0.25)';
                        if (wData.type === 'manual') {
                            lineClr = 'rgba(212, 175, 55, 0.4)';
                        }
                        
                        ctx.strokeStyle = lineClr;
                        ctx.lineWidth = wData.type === 'both' ? 2 : 1.5;
                        ctx.setLineDash([6, 4]);
                        
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
        
        window.__graphPositions = positions;
        window.__graphConnections = connectionElements;
    }
    
    positionCards();
    
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            positionCards();
        }, 100);
    });
});
</script>

<!-- Мобильная карусель (показывается вместо графа) -->
<div class="mobile-carousel">
    <?php foreach ($all_related as $id => $data) : 
        $work = $data['work'];
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
        
        $year = get_post_meta($work->ID, 'work_date', true);
        $type = $data['type'];
    ?>
    <div class="carousel-item">
        <?php if ($type === 'manual' || $type === 'both') : ?>
            <div class="carousel-badge <?php echo $type; ?>" 
                 title="<?php echo $type === 'manual' ? 'Ручная связь' : 'Комбинированная связь'; ?>">
                <?php echo $type === 'manual' ? '👆' : '🔗'; ?>
            </div>
        <?php endif; ?>
        
        <a href="<?php echo get_permalink($work->ID); ?>" class="carousel-link">
            <?php if (has_post_thumbnail($work->ID)) : ?>
                <div class="carousel-image">
                    <?php echo get_the_post_thumbnail($work->ID, 'medium', array('class' => 'carousel-img')); ?>
                </div>
            <?php else : ?>
                <div class="carousel-placeholder">
                    <?php echo !empty($icons) ? $icons[0] : '🎨'; ?>
                </div>
            <?php endif; ?>
            
            <div class="carousel-content">
                <div class="carousel-icons">
                    <?php foreach (array_slice($icons, 0, 3) as $icon) : ?>
                        <span class="carousel-icon"><?php echo $icon; ?></span>
                    <?php endforeach; ?>
                    <?php if (count($icons) > 3) : ?>
                        <span class="carousel-icon">+<?php echo count($icons) - 3; ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="carousel-title"><?php echo get_the_title($work->ID); ?></h3>
                <?php if ($year) : ?>
                    <span class="carousel-year"><?php echo $year; ?></span>
                <?php endif; ?>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

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

.graph-card-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(20, 20, 20, 0.9);
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    z-index: 2;
}

.graph-card-badge.manual {
    border-color: rgba(212, 175, 55, 0.8);
    color: #d4af37;
}

.graph-card-badge.both {
    border-color: var(--wp--preset--color--accent-blue, #1a5fb4);
    color: #1a5fb4;
}

.graph-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
    position: relative;
}

.graph-card-image {
    width: 100%;
    height: 100px;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    position: relative;
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
    filter: drop-shadow(0 0 4px currentColor);
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
    
    .graph-connection.multi-icon {
        height: 30px !important;
    }
    
    .graph-connection.multi-icon span {
        font-size: 16px !important;
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
    
    .graph-connection.multi-icon span {
        font-size: 14px !important;
    }
}

/* Адаптивность для очень маленьких экранов */
@media (max-width: 360px) {
    .graph-canvas-wrapper {
        min-height: 300px;
        height: 300px;
    }
    
    .graph-card {
        width: 90px;
    }
    
    .graph-card.current {
        width: 110px;
    }
    
    .graph-card-image {
        height: 55px;
    }
    
    .graph-card-title {
        font-size: 9px;
    }
}

/* ===== МОБИЛЬНАЯ КАРУСЕЛЬ ===== */
.mobile-carousel {
    display: none;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
    padding: 10px 5px 20px;
    gap: 15px;
    scrollbar-width: thin;
    scrollbar-color: var(--wp--preset--color--accent-blue, #1a5fb4) rgba(255, 255, 255, 0.1);
}

.mobile-carousel::-webkit-scrollbar {
    height: 4px;
}

.mobile-carousel::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
}

.mobile-carousel::-webkit-scrollbar-thumb {
    background: var(--wp--preset--color--accent-blue, #1a5fb4);
    border-radius: 2px;
}

.mobile-carousel .carousel-item {
    display: inline-block;
    width: 240px;
    margin-right: 15px;
    scroll-snap-align: start;
    vertical-align: top;
    white-space: normal;
    background: rgba(20, 20, 20, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.2s ease;
    flex-shrink: 0;
}

.mobile-carousel .carousel-item:last-child {
    margin-right: 0;
}

.mobile-carousel .carousel-item:hover {
    transform: translateY(-4px);
    border-color: var(--wp--preset--color--accent-blue, #1a5fb4);
}

.mobile-carousel .carousel-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.mobile-carousel .carousel-image {
    width: 100%;
    height: 140px;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.mobile-carousel .carousel-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.mobile-carousel .carousel-item:hover .carousel-image img {
    transform: scale(1.05);
}

.mobile-carousel .carousel-placeholder {
    width: 100%;
    height: 140px;
    background: rgba(13, 27, 42, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.mobile-carousel .carousel-content {
    padding: 12px;
}

.mobile-carousel .carousel-icons {
    display: flex;
    gap: 6px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.mobile-carousel .carousel-icon {
    font-size: 16px;
    opacity: 0.9;
}

.mobile-carousel .carousel-title {
    font-family: var(--wp--preset--font-family--spectral, serif);
    font-size: 15px;
    font-weight: 500;
    margin: 0 0 4px 0;
    color: var(--wp--preset--color--primary-white, #f8f8f8);
    line-height: 1.3;
}

.mobile-carousel .carousel-year {
    font-family: var(--wp--preset--font-family--jetbrains-mono, monospace);
    font-size: 11px;
    color: rgba(255, 255, 255, 0.5);
}

.mobile-carousel .carousel-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(20, 20, 20, 0.9);
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    z-index: 2;
}

.mobile-carousel .carousel-badge.manual {
    border-color: rgba(212, 175, 55, 0.8);
    color: #d4af37;
}

.mobile-carousel .carousel-badge.both {
    border-color: var(--wp--preset--color--accent-blue, #1a5fb4);
    color: #1a5fb4;
}

@media (max-width: 768px) {
    .graph-container {
        display: none; /* Прячем граф на мобильных */
    }
    
    .mobile-carousel {
        display: flex;
    }
}

@media (max-width: 480px) {
    .mobile-carousel .carousel-item {
        width: 200px;
    }
    
    .mobile-carousel .carousel-image,
    .mobile-carousel .carousel-placeholder {
        height: 120px;
    }
    
    .mobile-carousel .carousel-title {
        font-size: 14px;
    }
}
</style>
<?php endif; ?>