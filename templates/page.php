<?php $eyebrow=$page['eyebrow']; $heading=$page['title']; $summary=$page['summary']; $heroImage=$page['hero_image']; require BASE_PATH . '/templates/page-hero.php'; ?>
<section class="article-layout section-pad"><div class="container"><article class="prose wide reveal"><?= clean_html($page['content']) ?></article></div></section>

