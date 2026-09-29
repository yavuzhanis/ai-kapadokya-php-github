<?php $eyebrow='3. ULUSAL YAPAY ZEKÂ BULUŞMASI'; $heading=$item['title']; $summary=$item['summary']; $heroImage=$item['image_url']; require BASE_PATH . '/templates/page-hero.php'; ?>
<section class="article-layout section-pad"><div class="container article-grid">
    <article class="prose reveal"><?= clean_html($item['content']) ?></article>
    <aside class="detail-card reveal"><span>Başvuru Bilgileri</span><dl><dt>Hedef Kitle</dt><dd><?= e($item['audience']) ?></dd><dt>Son Başvuru</dt><dd><?= e(format_date_tr($item['deadline'])) ?></dd></dl><?php if ($item['application_url']): ?><a class="button button-lime" target="_blank" rel="noopener" href="<?= e($item['application_url']) ?>">Başvuru formu ↗</a><?php endif; ?><?php if ($item['document_url']): ?><a class="button button-dark" target="_blank" rel="noopener" href="<?= e($item['document_url']) ?>">Şablonu indir ↓</a><?php endif; ?></aside>
</div></section>

