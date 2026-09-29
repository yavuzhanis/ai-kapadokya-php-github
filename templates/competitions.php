<?php $eyebrow='AÇIK ÇAĞRILAR & PROJELER'; $heading='Yarışmalar ve Demo Day'; $summary='Öğrenciler ve girişimciler için fikirleri görünür kılan, ödüllü ve yeni iş birliklerinin önünü açan başvuru programları.'; require BASE_PATH . '/templates/page-hero.php'; ?>
<section class="competition-section standalone section-pad">
    <div class="container">
        <div class="competition-grid">
            <?php foreach ($competitions as $item): ?>
                <a class="competition-card reveal" href="<?= e(url('yarismalar/' . $item['slug'])) ?>">
                    <div class="competition-image">
                        <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['title']) ?>">
                    </div>
                    <div class="competition-copy">
                        <div class="comp-top-tags">
                            <?php if (str_contains(strtolower($item['title']), 'demo')): ?>
                                <span class="comp-tag tag-demo">✦ DEMO DAY & GİRİŞİM</span>
                            <?php else: ?>
                                <span class="comp-tag tag-prize">🏆 15.000 TL ÖDÜL</span>
                            <?php endif; ?>
                            <span class="comp-active-pill">Başvurular Açık</span>
                        </div>
                        <h2><?= e($item['title']) ?></h2>
                        <p><?= e($item['summary']) ?></p>

                        <!-- Milestone Roadmap -->
                        <div class="comp-roadmap">
                            <div class="comp-step done">
                                <span class="step-dot"></span>
                                <div><b>1. Başvuru</b><small>Çevrim içi form</small></div>
                            </div>
                            <div class="comp-step active">
                                <span class="step-dot"></span>
                                <div><b>2. Değerlendirme</b><small>Jüri ön elemesi</small></div>
                            </div>
                            <div class="comp-step">
                                <span class="step-dot"></span>
                                <div><b>3. Sahne</b><small>Kapadokya Finali</small></div>
                            </div>
                        </div>

                        <div class="comp-meta-strip">
                            <span class="comp-audience-label">👥 <?= e($item['audience']) ?></span>
                            <span class="comp-deadline-label">⏱ Son: <?= e(format_date_tr($item['deadline'])) ?></span>
                        </div>
                        <div class="comp-card-footer">
                            <b>Başvuru Detaylarını Gör <i>↗</i></b>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>


