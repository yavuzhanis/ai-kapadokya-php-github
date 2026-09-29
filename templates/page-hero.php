<section class="inner-hero <?= !empty($heroImage) ? 'has-image' : '' ?>" <?= !empty($heroImage) ? 'style="--inner-image:url(\'' . e($heroImage) . '\')"' : '' ?>>
    <div class="container reveal"><span class="eyebrow"><i></i><?= e($eyebrow ?? 'AI KAPADOKYA') ?></span><h1><?= e($heading ?? $pageTitle) ?></h1><?php if (!empty($summary)): ?><p><?= e($summary) ?></p><?php endif; ?></div>
</section>

