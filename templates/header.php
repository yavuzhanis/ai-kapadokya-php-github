<?php
$siteName = $repo->setting('site_name', 'AI Kapadokya');
$description = isset($metaDescription) ? $metaDescription : $repo->setting('site_tagline');
?>
<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#160d22">
    <title><?= e($pageTitle ?? $siteName) ?> · <?= e($siteName) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('app.css')) ?>?v=<?= @filemtime(__DIR__ . '/../assets/app.css') ?: '2.1.0' ?>">
    <script defer src="<?= e(asset('hero-canvas.js')) ?>?v=<?= @filemtime(__DIR__ . '/../assets/hero-canvas.js') ?: '2.1.0' ?>"></script>
    <script>
        (function() {
            var stored = localStorage.getItem('aik_theme');
            var theme = stored ? stored : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>

<body>
    <div class="scroll-progress" id="scrollProgress"></div>
    <a class="skip-link" href="#main">İçeriğe geç</a>
    <header class="site-header" data-header>
        <div class="header-inner">
            <a class="brand" href="<?= e(url()) ?>" aria-label="AI Kapadokya Ana Sayfa">
                <div class="brand-logo-crest">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="crestGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#d8ff62" />
                                <stop offset="60%" stop-color="#a855f7" />
                                <stop offset="100%" stop-color="#6366f1" />
                            </linearGradient>
                            <linearGradient id="crestBg" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="rgba(168,85,247,0.22)" />
                                <stop offset="100%" stop-color="rgba(216,255,98,0.12)" />
                            </linearGradient>
                        </defs>
                        <rect width="40" height="40" rx="11" fill="url(#crestBg)" stroke="rgba(255,255,255,0.18)" stroke-width="1.2" />
                        <!-- Cappadocia Hot Air Balloon Silhouette -->
                        <path d="M20 7C14.5 7 10 11.5 10 17C10 21 12.3 24 15 26.8L17.8 30.5H22.2L25 26.8C27.7 24 30 21 30 17C30 11.5 25.5 7 20 7Z" fill="url(#crestGrad)" fill-opacity="0.18" stroke="url(#crestGrad)" stroke-width="1.8" stroke-linejoin="round" />
                        <!-- Neural core and satellites -->
                        <circle cx="20" cy="17" r="3.2" fill="#d8ff62" />
                        <circle cx="15.5" cy="14" r="1.8" fill="#a855f7" />
                        <circle cx="24.5" cy="14" r="1.8" fill="#c084fc" />
                        <circle cx="20" cy="22.5" r="1.8" fill="#d8ff62" />
                        <line x1="15.5" y1="14" x2="20" y2="17" stroke="rgba(255,255,255,0.7)" stroke-width="1.2" />
                        <line x1="24.5" y1="14" x2="20" y2="17" stroke="rgba(255,255,255,0.7)" stroke-width="1.2" />
                        <line x1="20" y1="17" x2="20" y2="22.5" stroke="rgba(255,255,255,0.7)" stroke-width="1.2" />
                        <!-- Basket -->
                        <rect x="18.2" y="31.8" width="3.6" height="2.2" rx="0.6" fill="url(#crestGrad)" />
                    </svg>
                </div>
                <span class="brand-copy">
                    <span class="brand-title">AI <b>KAPADOKYA</b></span>
                    <small class="brand-sub">III. ULUSAL BULUŞMA · 2026</small>
                </span>
            </a>
            <div class="header-right-actions">
                <button class="cmd-trigger-btn" type="button" data-cmd-trigger aria-label="Arama ve Komut Paleti (CMD+K)" title="Arama & Hızlı Menü (⌘K)">
                    <span class="cmd-ico">⚡</span>
                    <span class="cmd-kbd"><kbd>⌘</kbd><kbd>K</kbd></span>
                </button>
                <button class="theme-toggle" id="themeToggleBtn" type="button" aria-label="Temayı Değiştir (Koyu / Açık)">
                    <span class="theme-sun">☀️</span>
                    <span class="theme-moon">🌙</span>
                </button>
                <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-menu-toggle><span></span><span></span></button>
            </div>
            <nav id="site-nav" class="site-nav" aria-label="Ana menü" data-nav>
                <div class="nav-item has-sub"><button type="button">Hakkında <i></i></button>
                    <div class="sub-menu">
                        <a href="<?= e(url('duzenleme-kurulu')) ?>">Düzenleme Kurulu</a>
                        <a href="<?= e(url('haberler')) ?>">Haberler</a>
                        <a href="<?= e(url('videolar')) ?>">Videolar</a>
                        <a href="<?= e(url('konaklama')) ?>">Konaklama</a>
                    </div>
                </div>
                <div class="nav-item has-sub"><button type="button">Yarışmalar <i></i></button>
                    <div class="sub-menu">
                        <a href="<?= e(url('yarismalar')) ?>">Tüm Yarışmalar</a>
                        <?php foreach ($repo->competitions((int) $currentEvent['id']) as $competition): ?>
                            <a href="<?= e(url('yarismalar/' . $competition['slug'])) ?>"><?= e($competition['title']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="<?= e(url('yarismalar/agentic-ai-demoday')) ?>">Demo Day</a>
                <div class="nav-item has-sub"><button type="button">Paneller <i></i></button>
                    <div class="sub-menu">
                        <a href="<?= e(url('program')) ?>">Ana Oturumlar</a>
                        <a href="<?= e(url('konusmacilar')) ?>">Konuşmacılar</a>
                        <a href="<?= e(url('gelecek-tasarimi-oturumlari')) ?>">Gelecek Tasarımı Oturumları</a>
                    </div>
                </div>
                <div class="nav-item has-sub"><button type="button">Geçmiş <i></i></button>
                    <div class="sub-menu">
                        <a href="<?= e(url('gecmis')) ?>">Tüm Buluşmalar</a>
                        <?php foreach ($repo->events() as $eventMenu): if ((int) $eventMenu['year'] === (int) $currentEvent['year']) continue; ?>
                            <a href="<?= e(url('gecmis/' . $eventMenu['year'])) ?>"><?= e($eventMenu['year']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a class="nav-contact" href="<?= e(url('iletisim')) ?>">İletişim</a>
                <?php if (!empty($currentEvent['registration_url'])): ?>
                    <a class="header-cta" target="_blank" rel="noopener" href="<?= e($currentEvent['registration_url']) ?>">Kayıt Ol <span>↗</span></a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main id="main">