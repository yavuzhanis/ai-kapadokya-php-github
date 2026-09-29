<section class="hero">
    <div class="hero-glow-layer">
        <canvas id="heroCanvas" class="hero-canvas"></canvas>
        <div class="hero-floating-balloons" aria-hidden="true">
            <div class="cyber-balloon balloon-left">
                <div class="balloon-envelope">
                    <div class="balloon-circuit-glow"></div>
                    <span class="balloon-burner"></span>
                </div>
                <div class="balloon-basket"></div>
            </div>
            <div class="cyber-balloon balloon-right">
                <div class="balloon-envelope">
                    <div class="balloon-circuit-glow"></div>
                    <span class="balloon-burner"></span>
                </div>
                <div class="balloon-basket"></div>
            </div>
            <div class="cyber-balloon balloon-far">
                <div class="balloon-envelope"></div>
                <div class="balloon-basket"></div>
            </div>
        </div>
        <div class="aurora-orb orb-primary"></div>
        <div class="aurora-orb orb-secondary"></div>
    </div>
    <div class="hero-art" style="--hero:url('<?= e($currentEvent['hero_image']) ?>')"><span class="hero-grid"></span></div>
    <div class="container hero-content">
        <div class="hero-copy reveal">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                <span>15–16 EKİM 2026 · III. ULUSAL BULUŞMA · ÜRGÜP</span>
                <span class="hero-live-pill">🔴 RESMİ KAYITLAR AÇIK</span>
            </div>
            <h1 class="hero-heading">
                Geleceğin Zekâsı.<br>
                <em>Kapadokya’da Şekilleniyor.</em>
            </h1>
            <p class="hero-lead">
                <?= e($currentEvent['subtitle']) ?> — Yapay zekâ ajanı demoları, ulusal vizyon oturumları ve geleceğin otonom teknolojileri Kapadokya'nın ilham verici tarihi atmosferinde buluşuyor.
            </p>

            <div class="hero-actions">
                <?php if ($currentEvent['registration_url']): ?>
                    <a class="button button-lime button-glow button-hero-primary" target="_blank" rel="noopener" href="<?= e($currentEvent['registration_url']) ?>">
                        <span>Hemen Kayıt Ol</span>
                        <span class="btn-arrow-pill">↗</span>
                    </a>
                <?php endif; ?>
                <a class="button button-ghost" href="<?= e(url('program')) ?>">Programı Keşfet <span>→</span></a>
                <div class="calendar-dropdown-wrap">
                    <button type="button" class="button button-outline-light button-cal" id="mainCalDropdownBtn" aria-haspopup="true" aria-expanded="false">
                        <span>📅 Takvime Ekle</span>
                        <span class="dropdown-caret">▾</span>
                    </button>
                    <div class="calendar-dropdown-menu" id="mainCalDropdown">
                        <button type="button" data-add-main-cal="google">Google Takvim ↗</button>
                        <button type="button" data-add-main-cal="ics">Apple / Outlook (.ICS) 📥</button>
                    </div>
                </div>
            </div>

            <div class="hero-trust-strip">
                <span><i class="trust-check">✓</i> 40+ Konuşmacı</span>
                <span><i class="trust-check">✓</i> 3 Büyük Yarışma</span>
                <span><i class="trust-check">✓</i> Agentic AI Demo Day</span>
                <span><i class="trust-check">✓</i> Açık & Ücretsiz Katılım</span>
            </div>
        </div>

        <!-- Holographic 3D Event Pass Card -->
        <div class="hero-ticket-card reveal">
            <div class="ticket-glow-aura"></div>
            <div class="ticket-inner">
                <div class="ticket-badge-header">
                    <div class="ticket-brand">
                        <span class="ticket-chip"></span>
                        <div>
                            <b>AI KAPADOKYA 2026</b>
                            <small>RESMİ ZİRVE GİRİŞ KARTI</small>
                        </div>
                    </div>
                    <span class="ticket-status-pill">● CANLI ETKİNLİK</span>
                </div>

                <div class="ticket-main-info">
                    <div class="ticket-info-row">
                        <span class="ticket-label">ZİRVE TARİHİ</span>
                        <strong class="ticket-date-val">15 — 16 EKİM 2026</strong>
                        <span class="ticket-sub-val">2 Gün Boyunca Kesintisiz Akış</span>
                    </div>
                    <div class="ticket-info-row">
                        <span class="ticket-label">LOKASYON & YERLEŞKE</span>
                        <strong class="ticket-venue-val">Kapadokya Üniversitesi</strong>
                        <span class="ticket-sub-val">Tarihi Fabrika Yerleşkesi · Ürgüp / Nevşehir</span>
                        <span class="ticket-coords">📍 38.6300° K, 34.9126° D</span>
                    </div>
                </div>

                <div class="ticket-countdown-box">
                    <span class="ticket-cd-label">⏱ ZİRVEYE KALAN SÜRE</span>
                    <div class="ticket-cd-grid" data-countdown="<?= e($currentEvent['start_date']) ?>T09:30:00+03:00">
                        <div class="t-digit"><b data-days>00</b><small>Gün</small></div>
                        <i>:</i>
                        <div class="t-digit"><b data-hours>00</b><small>Saat</small></div>
                        <i>:</i>
                        <div class="t-digit"><b data-minutes>00</b><small>Dk</small></div>
                        <i>:</i>
                        <div class="t-digit"><b data-seconds>00</b><small>Sn</small></div>
                    </div>
                </div>

                <div class="ticket-footer">
                    <div class="ticket-barcode-wrap">
                        <div class="ticket-barcode"></div>
                        <span class="ticket-id">#AIK-2026-VIP-PASS</span>
                    </div>
                    <?php if (!empty($currentEvent['registration_url'])): ?>
                        <a href="<?= e($currentEvent['registration_url']) ?>" target="_blank" rel="noopener" class="ticket-cta-btn">
                            Kayıt Ol <i>↗</i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-strip">
    <div class="container stats-grid">
        <div class="stat-card reveal">
            <div class="stat-icon-wrap">🎤</div>
            <div class="stat-number-wrap">
                <span class="stat-number">40+</span>
                <span class="stat-plus">Öncü İsim</span>
            </div>
            <span class="stat-label">Konuşmacı & Panelist</span>
            <small class="stat-sub">Akademi, Sanayi ve Girişimcilik Liderleri</small>
        </div>
        <div class="stat-card reveal">
            <div class="stat-icon-wrap">🏆</div>
            <div class="stat-number-wrap">
                <span class="stat-number">₺45.000</span>
                <span class="stat-plus">Büyük Ödül</span>
            </div>
            <span class="stat-label">Ödüllü Yarışmalar</span>
            <small class="stat-sub">Agentic AI Demo Day ve Poster Zirvesi</small>
        </div>
        <div class="stat-card reveal">
            <div class="stat-icon-wrap">⚡</div>
            <div class="stat-number-wrap">
                <span class="stat-number">15+</span>
                <span class="stat-plus">Stratejik Oturum</span>
            </div>
            <span class="stat-label">Panel & Çalıştay</span>
            <small class="stat-sub">Otonom Ajanlar, Savunma, Sağlık ve Gelecek</small>
        </div>
        <div class="stat-card reveal">
            <div class="stat-icon-wrap">🏛️</div>
            <div class="stat-number-wrap">
                <span class="stat-number">1.500+</span>
                <span class="stat-plus">Katılımcı</span>
            </div>
            <span class="stat-label">Kapadokya Yerleşkesi</span>
            <small class="stat-sub">Tarihi Fabrika Yerleşkesi, Ürgüp</small>
        </div>
    </div>
</section>

<!-- Cappadocia & AI Cinematic Showcase Slider -->
<section class="cappadocia-showcase section-pad" id="cappadociaShowcase">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow dark"><i></i>KAPADOKYA & YAPAY ZEKÂ VİZYONU</span>
                <h2>Kapadokya'nın Büyüsü, Geleceğin Zekâsıyla Buluşuyor</h2>
            </div>
            <p class="section-subhead">Binlerce yıllık peri bacaları ve tüf taşından oyma amfiler; gökyüzünde süzülen otonom balonlar, derin öğrenme modelleri ve Türkiye'nin en vizyoner yapay zekâ buluşmasına ev sahipliği yapıyor.</p>
        </div>

        <div class="showcase-slider-wrapper reveal">
            <!-- Slider Carousel -->
            <div class="showcase-slider" id="showcaseSlider" role="region" aria-label="Kapadokya ve Yapay Zeka Görsel Vitrin">
                <!-- Slide 1 -->
                <div class="showcase-slide active" data-slide-index="0">
                    <div class="slide-media">
                        <img src="<?= e(asset('images/slider/slide-1-balloons.jpg')) ?>" alt="Kapadokya Vadileri ve Otonom Sıcak Hava Balonları" loading="lazy">
                        <div class="slide-overlay"></div>
                    </div>
                    <div class="slide-content">
                        <span class="slide-tag">🎈 OTONOM SEYRÜSEFER & NÖRAL BALONLAR</span>
                        <h3 class="slide-title">Kapadokya Semalarında Otonom Gelecek</h3>
                        <p class="slide-desc">Geleneksel sıcak hava balonları ve hava sahası telemetrisi; uçtan uca yapay zekâ, bilgisayarlı görü ve rota optimizasyon modelleriyle gökyüzünde canlanıyor.</p>
                        <div class="slide-actions">
                            <a href="<?= e(url('program')) ?>" class="button button-lime button-glow">Zirve Programını İncele ↗</a>
                            <a href="<?= e(url('yarismalar')) ?>" class="button button-ghost">Yarışmaları Gör →</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="showcase-slide" data-slide-index="1">
                    <div class="slide-media">
                        <img src="<?= e(asset('images/slider/slide-2-summit.jpg')) ?>" alt="Tarihi Taş Yerleşkede Holo-Sahne ve Yapay Zeka Zirvesi" loading="lazy">
                        <div class="slide-overlay"></div>
                    </div>
                    <div class="slide-content">
                        <span class="slide-tag">🏛️ TARİHİ FABRİKA YERLEŞKESİ & HOLO-SAHNE</span>
                        <h3 class="slide-title">Bin Yıllık Tarihte Holografik Zirve</h3>
                        <p class="slide-desc">Kapadokya Üniversitesi Tarihi Fabrika Yerleşkesi'nde, taş amfilerin benzersiz akustiğinde küresel yapay zekâ mimarları ve ulusal teknoloji öncüleri aynı sahnede buluşuyor.</p>
                        <div class="slide-actions">
                            <a href="<?= e(url('konusmacilar')) ?>" class="button button-lime button-glow">Konuşmacıları Keşfet ↗</a>
                            <a href="<?= e(url('program')) ?>" class="button button-ghost">Oturum Detayları →</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="showcase-slide" data-slide-index="2">
                    <div class="slide-media">
                        <img src="<?= e(asset('images/slider/slide-3-hackathon.jpg')) ?>" alt="Agentic AI Hackathon ve Kodlama Maratonu" loading="lazy">
                        <div class="slide-overlay"></div>
                    </div>
                    <div class="slide-content">
                        <span class="slide-tag">💻 AGENTIC AI & KODLAMA MARATONU</span>
                        <h3 class="slide-title">Tüf Kayalarda Gece Boyu Kod ve İnovasyon</h3>
                        <p class="slide-desc">Genç mühendisler, araştırmacılar ve girişimciler; oyma kaya atölyelerinde Türkiye'nin en iddialı otonom yapay zekâ ajanlarını inşa ederek ₺45.000 ödül havuzunda yarışıyor.</p>
                        <div class="slide-actions">
                            <a href="<?= e(url('yarismalar')) ?>" class="button button-lime button-glow">Hackathon'a Başvur ↗</a>
                            <a href="<?= e(url('haberler')) ?>" class="button button-ghost">Zirve Haberleri →</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="showcase-slide" data-slide-index="3">
                    <div class="slide-media">
                        <img src="<?= e(asset('images/slider/slide-4-drones.jpg')) ?>" alt="Peribacalarının Lidar Taraması ve Otonom Drone Filoları" loading="lazy">
                        <div class="slide-overlay"></div>
                    </div>
                    <div class="slide-content">
                        <span class="slide-tag">🛰️ LİDAR TARAMASI & DİJİTAL İKİZ</span>
                        <h3 class="slide-title">Peribacalarının Siber Haritalaması ve Lidar</h3>
                        <p class="slide-desc">Kapadokya'nın eşsiz coğrafi mirası; otonom drone filoları, derin öğrenme tabanlı 3D nokta bulutu taramaları ve koruma modelleri ile dijital evrene taşınıyor.</p>
                        <div class="slide-actions">
                            <a href="<?= e(url('videolar')) ?>" class="button button-lime button-glow">Teknoloji Videoları ↗</a>
                            <a href="<?= e(url('gecmis')) ?>" class="button button-ghost">Geçmiş Buluşmalar →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Controls & Progress Bar -->
            <div class="showcase-controls">
                <button type="button" class="slider-btn slider-prev" id="showcasePrevBtn" aria-label="Önceki Slayt">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </button>

                <div class="slider-indicators" id="showcaseIndicators">
                    <button type="button" class="indicator-tab active" data-go-to="0">
                        <span class="indicator-num">01</span>
                        <span class="indicator-label">Otonom Balonlar</span>
                        <div class="indicator-progress-bar">
                            <div class="indicator-progress-fill"></div>
                        </div>
                    </button>
                    <button type="button" class="indicator-tab" data-go-to="1">
                        <span class="indicator-num">02</span>
                        <span class="indicator-label">Holo-Sahne</span>
                        <div class="indicator-progress-bar">
                            <div class="indicator-progress-fill"></div>
                        </div>
                    </button>
                    <button type="button" class="indicator-tab" data-go-to="2">
                        <span class="indicator-num">03</span>
                        <span class="indicator-label">Hackathon</span>
                        <div class="indicator-progress-bar">
                            <div class="indicator-progress-fill"></div>
                        </div>
                    </button>
                    <button type="button" class="indicator-tab" data-go-to="3">
                        <span class="indicator-num">04</span>
                        <span class="indicator-label">Lidar & Dijital İkiz</span>
                        <div class="indicator-progress-bar">
                            <div class="indicator-progress-fill"></div>
                        </div>
                    </button>
                </div>

                <button type="button" class="slider-btn slider-next" id="showcaseNextBtn" aria-label="Sonraki Slayt">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="intro section-pad">
    <div class="container split-intro">
        <div class="section-kicker reveal">03 / Ulusal Buluşma</div>
        <div class="reveal">
            <h2>Yapay zekâyı yalnızca konuşmuyor, <span>geleceğin rotasını birlikte çiziyoruz.</span></h2>
            <p><?= e($currentEvent['description']) ?></p>
            <a class="text-link" href="<?= e(url('program')) ?>">Buluşmayı keşfet <span>↗</span></a>
        </div>
    </div>
</section>

<!-- Bento Grid: Zirvenin Odak Noktaları -->
<section class="bento-section section-pad">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow dark"><i></i>DENEYİM VE VİZYON</span>
                <h2>Neden AI Kapadokya 2026?</h2>
            </div>
            <p class="section-subhead">Yapay zekânın teorisinden üretken uygulamalarına, girişimcilik ekosisteminden ulusal stratejilere kadar Türkiye'nin en kapsamlı teknoloji buluşması.</p>
        </div>
        <div class="bento-grid">
            <div class="bento-card bento-wide reveal">
                <div class="bento-glow"></div>
                <span class="bento-badge">⚡ ANA SAHNE & DEMO DAY</span>
                <h3>Agentic AI & Otonom Gelecek</h3>
                <p>Yalnızca prompt yazılan modeller değil; bağımsız kararlar alan, görevleri otomatize eden otonom yapay zekâ ajanları Türkiye'de ilk kez canlı sahnede yarışıyor ve yatırımcılarla buluşuyor.</p>
                <div class="bento-tags">
                    <span>#AutonomousAgents</span>
                    <span>#MultiAgentSystems</span>
                    <span>#DemoDay2026</span>
                </div>
            </div>
            <div class="bento-card reveal">
                <div class="bento-glow"></div>
                <span class="bento-badge">🏛️ MEKÂN & ATMOSFER</span>
                <h3>Tarihi Fabrika Yerleşkesi</h3>
                <p>Kapadokya'nın bin yıllık taş dokusunda, peri bacalarının ilham verici silüetinde en ileri nöral mimariler ve gelecek vizyonu bir araya geliyor.</p>
                <div class="bento-tags">
                    <span>#Kapadokya</span>
                    <span>#Ürgüp</span>
                    <span>#İlhamVerenMiras</span>
                </div>
            </div>
            <div class="bento-card reveal">
                <div class="bento-glow"></div>
                <span class="bento-badge">🎤 LİDERLER VE AKADEMİ</span>
                <h3>40+ Vizyoner İsim</h3>
                <p>Türkiye'nin önde gelen üniversitelerinden rektörler, bakanlık temsilcileri, yapay zekâ laboratuvar yöneticileri ve teknoloji devleri tek bir sahnede.</p>
                <div class="bento-tags">
                    <span>#Strateji</span>
                    <span>#Akademi</span>
                    <span>#İşDünyası</span>
                </div>
            </div>
            <div class="bento-card bento-wide reveal">
                <div class="bento-glow"></div>
                <span class="bento-badge">🤝 EKOSİSTEM VE NETWORKING</span>
                <h3>Girişimcilik ve Yatırımcı Köprüsü</h3>
                <p>Erken aşama yapay zekâ girişimleri, risk sermayesi fonları (VC) ve melek yatırım ağları için doğrudan birebir temas, yeni iş birlikleri ve yatırım fırsatları.</p>
                <div class="bento-tags">
                    <span>#VentureCapital</span>
                    <span>#StartupEcosystem</span>
                    <span>#Networking</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ticker" aria-label="Etkinlik temaları">
    <div>YAPAY ZEKÂ <i>✦</i> EKONOMİ <i>✦</i> İNSAN <i>✦</i> İNOVASYON <i>✦</i> GELECEK <i>✦</i> KAPADOKYA <i>✦</i> DEMO DAY <i>✦</i> GİRİŞİMCİLİK <i>✦</i> YAPAY ZEKÂ <i>✦</i> EKONOMİ <i>✦</i> İNSAN <i>✦</i> İNOVASYON <i>✦</i> GELECEK <i>✦</i> KAPADOKYA <i>✦</i></div>
</section>


<section class="program-preview section-pad">
    <div class="container">
        <div class="section-head reveal">
            <div><span class="eyebrow dark"><i></i>ANA OTURUMLAR</span>
                <h2>İki gün.<br>Tek bir gelecek.</h2>
            </div><a class="button button-dark" href="<?= e(url('program')) ?>">Tüm programı gör <span>→</span></a>
        </div>

        <div class="program-controls reveal">
            <div class="day-tabs" data-tabs>
                <?php $days = array_values(array_unique(array_column($sessions, 'day_date')));
                foreach ($days as $i => $day): ?><button class="<?= $i === 0 ? 'active' : '' ?>" data-tab="<?= e($day) ?>"><small>0<?= $i + 1 ?>. GÜN</small><?= e(format_date_tr($day)) ?></button><?php endforeach; ?>
            </div>
            <div class="program-filter-box">
                <div class="session-search-wrapper">
                    <span class="search-ico">⚡</span>
                    <input type="text" id="sessionSearchInput" placeholder="Oturum veya konuşmacı ara..." autocomplete="off">
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

        <div class="session-list" id="sessionListContainer">
            <?php foreach ($sessions as $session): ?>
                <article class="session-row reveal"
                    data-session-title="<?= e($session['title']) ?>"
                    data-session-type="<?= e($session['type']) ?>"
                    data-tab-panel="<?= e($session['day_date']) ?>"
                    data-venue="Ana Salon · Kapadokya Fabrika Yerleşkesi"
                    data-moderator="<?= e($session['moderator'] ?? '') ?>"
                    data-participants="<?= e($session['participants'] ?? '') ?>"
                    data-description="Yapay zekâ ekosisteminin liderleri, geleceğin vizyonunu ve pratik uygulamalarını Kapadokya’da tartışıyor."
                    data-search="<?= e(mb_strtolower($session['title'] . ' ' . $session['moderator'] . ' ' . $session['participants'])) ?>">
                    <time><?= e(substr($session['start_time'], 0, 5)) ?></time>
                    <span class="session-type session-type-<?= e($session['type']) ?>"><?= e($session['type']) ?></span>
                    <div class="session-info">
                        <h3><?= e($session['title']) ?></h3>
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
    </div>
</section>

<section class="speakers-section section-pad">
    <div class="container">
        <div class="section-head light reveal">
            <div><span class="eyebrow"><i></i>KONUŞMACILAR</span>
                <h2>Fikirlere yön veren<br>öncü isimler.</h2>
            </div><a class="button button-outline-light" href="<?= e(url('konusmacilar')) ?>">Tümünü gör <span>→</span></a>
        </div>
        <div class="speaker-grid">
            <?php foreach ($speakers as $i => $speaker): ?><a class="speaker-card reveal" href="<?= e($speaker['linkedin_url'] ?: '#') ?>" <?= $speaker['linkedin_url'] ? 'target="_blank" rel="noopener"' : '' ?>>
                    <div class="speaker-photo"><img loading="lazy" src="<?= e($speaker['image_url']) ?>" alt="<?= e($speaker['name']) ?>"><span class="speaker-num">0<?= $i + 1 ?></span></div>
                    <div><small><?= e($speaker['role']) ?></small>
                        <h3><?= e($speaker['name']) ?></h3>
                        <p><?= e($speaker['company']) ?></p>
                    </div><i>↗</i>
                </a><?php endforeach; ?>
        </div>
    </div>
</section>

<section class="competition-section section-pad">
    <div class="container">
        <div class="section-head reveal">
            <div><span class="eyebrow dark"><i></i>AÇIK ÇAĞRILAR & PROJELER</span>
                <h2>Fikrini görünür kıl.</h2>
            </div><a class="text-link" href="<?= e(url('yarismalar')) ?>">Tüm Proje ve Çağrılar <span>↗</span></a>
        </div>
        <div class="competition-grid"><?php foreach ($competitions as $item): ?>
                <a class="competition-card reveal" href="<?= e(url('yarismalar/' . $item['slug'])) ?>">
                    <div class="competition-image">
                        <img loading="lazy" src="<?= e($item['image_url']) ?>" alt="<?= e($item['title']) ?>">
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
                        <h3><?= e($item['title']) ?></h3>
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
                            <b>Detayları Gör & Başvur <i>↗</i></b>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="gallery-section section-pad">
    <div class="container">
        <div class="section-head light reveal">
            <div><span class="eyebrow"><i></i>HAFIZA</span>
                <h2>Kapadokya’da<br>şekillenen anlar.</h2>
            </div>
        </div>
        <div class="gallery-grid">
            <?php foreach ($gallery as $i => $image): ?>
                <button class="gallery-card g<?= $i + 1 ?> reveal" data-lightbox="<?= e($image['image_url']) ?>" aria-label="<?= e($image['title']) ?> görselini büyüt">
                    <span class="gallery-badge"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            <line x1="11" y1="8" x2="11" y2="14" />
                            <line x1="8" y1="11" x2="14" y2="11" />
                        </svg> Büyüt</span>
                    <img loading="lazy" src="<?= e($image['image_url']) ?>" alt="<?= e($image['title']) ?>">
                    <span><?= e($image['category']) ?><b><?= e($image['title']) ?></b></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="news-section section-pad">
    <div class="container">
        <div class="section-head reveal">
            <div><span class="eyebrow dark"><i></i>GÜNCEL</span>
                <h2>Haberler.</h2>
            </div><a class="text-link" href="<?= e(url('haberler')) ?>">Tüm haberler <span>→</span></a>
        </div>
        <div class="news-grid"><?php foreach ($newsItems as $item): ?><article class="news-card reveal"><a href="<?= e(url('haberler/' . $item['slug'])) ?>"><img loading="lazy" src="<?= e($item['image_url']) ?>" alt=""><small><?= e(format_date_tr($item['published_at'])) ?></small>
                        <h3><?= e($item['title']) ?></h3>
                        <p><?= e($item['summary']) ?></p><span>Haberi oku ↗</span>
                    </a></article><?php endforeach; ?></div>
    </div>
</section>

<section class="archive-strip">
    <div class="container"><span>GEÇMİŞ BULUŞMALAR</span><?php foreach ($events as $event): ?><a href="<?= e(url('gecmis/' . $event['year'])) ?>"><b><?= e($event['year']) ?></b><small><?= e($event['title']) ?></small><i>↗</i></a><?php endforeach; ?></div>
</section>

<section class="sponsor-section section-pad">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <span class="eyebrow dark"><i></i>İŞ BİRLİĞİ & EKOSİSTEM</span>
                <h2>Destekleyenler ve Paydaşlar</h2>
            </div>
            <p class="section-subhead">Türkiye'nin yapay zekâ geleceğine güç veren öncü kurumlar ve stratejik ortaklar.</p>
        </div>
        <div class="sponsor-pavilion">
            <?php foreach (['main' => 'Ana Sponsor', 'support' => 'Destek Sponsorları', 'organizer' => 'Düzenleyiciler'] as $tier => $label):
                $tierItems = array_filter($sponsors, fn($s) => $s['tier'] === $tier);
                if (!$tierItems) continue;
            ?>
                <div class="sponsor-tier-block tier-<?= e($tier) ?> reveal">
                    <div class="sponsor-tier-badge">
                        <span class="tier-dot"></span>
                        <span><?= e($label) ?></span>
                    </div>
                    <div class="sponsor-logo-grid">
                        <?php foreach ($tierItems as $sponsor): ?>
                            <a class="sponsor-card" href="<?= e($sponsor['website'] ?: '#') ?>" <?= $sponsor['website'] ? 'target="_blank" rel="noopener"' : '' ?> title="<?= e($sponsor['name']) ?>">
                                <img loading="lazy" src="<?= e($sponsor['logo_url']) ?>" alt="<?= e($sponsor['name']) ?>">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="lightbox" data-lightbox-modal aria-hidden="true">
    <button class="lightbox-close" aria-label="Kapat">✕</button>
    <div class="lightbox-dialog">
        <img alt="Büyütülmüş Görsel">
        <div class="lightbox-caption"></div>
    </div>
</div>