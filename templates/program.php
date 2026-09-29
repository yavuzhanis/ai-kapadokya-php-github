<?php $eyebrow='15—16 EKİM 2026'; $heading='Ana Oturumlar'; $summary='Türkiye’nin yapay zekâ ekosistemini uygulama, insan kaynağı ve ekonomik değer üretimi düzeyinde değerlendiren iki günlük program.'; require BASE_PATH . '/templates/page-hero.php'; ?>
<section class="inner-content program-page section-pad"><div class="container">
    <?php $days = array_values(array_unique(array_column($sessions, 'day_date'))); ?>
    <div class="day-tabs large" data-tabs><?php foreach ($days as $i => $day): ?><button class="<?= $i === 0 ? 'active' : '' ?>" data-tab="<?= e($day) ?>"><small>0<?= $i + 1 ?>. GÜN</small><?= e(format_date_tr($day)) ?></button><?php endforeach; ?></div>
    
    <div class="program-controls" style="margin-top:20px;">
        <div class="program-filter-box" style="width:100%;">
            <div class="session-search-wrapper" style="flex:1;">
                <span class="search-ico">⚡</span>
                <input type="text" id="sessionSearchInput" placeholder="Oturum başlığı, konuşmacı veya moderatör ara..." autocomplete="off">
            </div>
            <div class="session-chips" id="sessionTypeChips">
                <button type="button" class="chip-btn active" data-type="all">Tümü</button>
                <button type="button" class="chip-btn" data-type="panel">Paneller</button>
                <button type="button" class="chip-btn" data-type="keynote">Keynote</button>
                <button type="button" class="chip-btn" data-type="demoday">Demo Day</button>
                <button type="button" class="chip-btn chip-favs" data-type="favorites">⭐ Kişisel Programım (<span id="favBadgeCount">0</span>)</button>
            </div>
        </div>
    </div>

    <div class="program-toolbar">
        <span><?= count($sessions) ?> oturum listeleniyor</span>
        <div style="display:flex;gap:16px;">
            <button type="button" onclick="window.print()">Programı yazdır ↓</button>
            <button type="button" data-add-main-cal="google">Google Takvime Ekle ↗</button>
            <button type="button" data-add-main-cal="ics">.ICS İndir 📥</button>
        </div>
    </div>
    <div class="session-list full"><?php foreach ($sessions as $session): ?>
        <article class="session-row reveal"
            data-session-title="<?= e($session['title']) ?>"
            data-session-type="<?= e($session['type']) ?>"
            data-tab-panel="<?= e($session['day_date']) ?>"
            data-venue="<?= e($session['venue'] ?? 'Ana Sahne · Kapadokya Fabrika Yerleşkesi') ?>"
            data-moderator="<?= e($session['moderator'] ?? '') ?>"
            data-participants="<?= e($session['participants'] ?? '') ?>"
            data-description="Yapay zekâ ekosisteminin liderleri, geleceğin vizyonunu ve pratik uygulamalarını Kapadokya’da tartışıyor."
            data-search="<?= e(mb_strtolower($session['title'] . ' ' . $session['moderator'] . ' ' . $session['participants'])) ?>">
            <time><?= e(substr($session['start_time'],0,5)) ?><small><?= $session['end_time'] ? e(substr($session['end_time'],0,5)) : '' ?></small></time>
            <span class="session-type session-type-<?= e($session['type']) ?>"><?= e($session['type']) ?></span>
            <div class="session-info">
                <h3><?= e($session['title']) ?></h3>
                <?php if ($session['venue']): ?><small class="venue"><?= e($session['venue']) ?></small><?php endif; ?>
                <?php if ($session['moderator']): ?><p><b>Moderatör:</b> <?= e($session['moderator']) ?></p><?php endif; ?>
                <?php if ($session['participants']): ?><p><?= e($session['participants']) ?></p><?php endif; ?>
            </div>
            <div class="session-row-actions">
                <button type="button" class="session-star-btn" aria-label="Favorilere ekle" title="Kişisel takvime ekle">☆</button>
                <span class="session-arrow" title="Detayları Gör">↗</span>
            </div>
        </article>
    <?php endforeach; ?>
    <div class="empty-session-search" id="emptySessionMsg" style="display:none;">
        <p>Aradığınız kriterlere uygun oturum bulunamadı.</p>
    </div>
    </div>
</div></section>

