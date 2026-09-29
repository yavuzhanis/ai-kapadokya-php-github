<?php $eyebrow='BİLGİ · DENEYİM · VİZYON'; $heading='Konuşmacılar'; $summary='Akademi, teknoloji ve iş dünyasının öncü isimleri Kapadokya’da aynı sahneyi paylaşıyor.'; require BASE_PATH . '/templates/page-hero.php'; ?>
<section class="speakers-section standalone section-pad"><div class="container">
    <div class="speaker-filter"><button class="active" data-filter="all">Tümü</button><button data-filter="Moderatör">Moderatörler</button><button data-filter="Konuşmacı">Konuşmacılar</button><button data-filter="Keynote">Keynote</button></div>
    <div class="speaker-grid" data-filter-grid><?php foreach ($speakers as $i => $speaker): ?><a class="speaker-card reveal" data-category="<?= e($speaker['role']) ?>" href="<?= e($speaker['linkedin_url'] ?: '#') ?>" <?= $speaker['linkedin_url'] ? 'target="_blank" rel="noopener"' : '' ?>>
        <div class="speaker-photo"><img loading="lazy" src="<?= e($speaker['image_url']) ?>" alt="<?= e($speaker['name']) ?>"><span><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span></div><div><small><?= e($speaker['role']) ?></small><h3><?= e($speaker['name']) ?></h3><p><?= e($speaker['company']) ?></p></div><i>↗</i>
    </a><?php endforeach; ?></div>
</div></section>

