</main>
<footer class="site-footer">
    <div class="footer-orbit"></div>
    <div class="container footer-grid">
        <div>
            <a class="brand brand-light" href="<?= e(url()) ?>" aria-label="AI Kapadokya">
                <div class="brand-logo-crest">
                    <svg width="38" height="38" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="11" fill="rgba(168,85,247,0.2)" stroke="rgba(255,255,255,0.18)" stroke-width="1.2"/>
                        <path d="M20 7C14.5 7 10 11.5 10 17C10 21 12.3 24 15 26.8L17.8 30.5H22.2L25 26.8C27.7 24 30 21 30 17C30 11.5 25.5 7 20 7Z" fill="#a855f7" fill-opacity="0.2" stroke="#d8ff62" stroke-width="1.8" stroke-linejoin="round"/>
                        <circle cx="20" cy="17" r="3.2" fill="#d8ff62"/>
                        <rect x="18.2" y="31.8" width="3.6" height="2.2" rx="0.6" fill="#d8ff62"/>
                    </svg>
                </div>
                <span class="brand-copy">
                    <span class="brand-title">AI <b>KAPADOKYA</b></span>
                    <small class="brand-sub">III. ULUSAL BULUŞMA · 2026</small>
                </span>
            </a>
            <p><?= e($repo->setting('site_tagline')) ?></p>
        </div>
        <div><h3>Etkinlik</h3><a href="<?= e(url('program')) ?>">Program</a><a href="<?= e(url('konusmacilar')) ?>">Konuşmacılar</a><a href="<?= e(url('yarismalar')) ?>">Yarışmalar</a><a href="<?= e(url('videolar')) ?>">Videolar</a><a href="<?= e(url('gecmis')) ?>">Geçmiş Buluşmalar</a></div>
        <div><h3>İletişim</h3><a href="mailto:<?= e($repo->setting('contact_email')) ?>"><?= e($repo->setting('contact_email')) ?></a><a href="tel:<?= e(preg_replace('/\D+/', '', $repo->setting('contact_phone'))) ?>"><?= e($repo->setting('contact_phone')) ?></a><p><?= e($repo->setting('address')) ?></p></div>
        <div><h3>Sosyal</h3><a target="_blank" rel="noopener" href="<?= e($repo->setting('instagram_url')) ?>">Instagram ↗</a><a target="_blank" rel="noopener" href="<?= e($repo->setting('linkedin_url')) ?>">LinkedIn ↗</a><a target="_blank" rel="noopener" href="<?= e($repo->setting('youtube_url')) ?>">YouTube ↗</a></div>
    </div>
    <div class="container footer-bottom"><span>© <?= date('Y') ?> <?= e($repo->setting('footer_text')) ?></span><span>Kapadokya Üniversitesi</span></div>
</footer>

<div class="floating-quick-dock" id="quickDock">
    <?php if (!empty($currentEvent['registration_url'])): ?>
        <a class="dock-cta" target="_blank" rel="noopener" href="<?= e($currentEvent['registration_url']) ?>" aria-label="Zirveye Kayıt Ol">
            <span class="dock-pulse"></span>
            <b>Kayıt Ol</b>
            <i>↗</i>
        </a>
    <?php endif; ?>
    <button type="button" class="dock-top-btn" id="scrollTopBtn" aria-label="Sayfa Başına Çık">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
    </button>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="toast-container" aria-live="polite"></div>

<!-- Command Palette Modal (CMD+K) -->
<div class="glass-modal-backdrop" id="cmdPaletteModal" aria-hidden="true" role="dialog" aria-label="Komut Paleti">
    <div class="cmd-palette-box">
        <div class="cmd-input-bar">
            <span class="cmd-search-ico">⚡</span>
            <input type="text" id="cmdPaletteInput" placeholder="Oturum, konuşmacı, yarışma veya eylem ara..." autocomplete="off">
            <span class="cmd-esc-hint">ESC</span>
        </div>
        <div class="cmd-results" id="cmdPaletteResults"></div>
        <div class="cmd-footer">
            <span><kbd>↑</kbd><kbd>↓</kbd> Gezin</span>
            <span><kbd>↵</kbd> Seç</span>
            <span><kbd>ESC</kbd> Kapat</span>
        </div>
    </div>
</div>

<!-- Session Detail Modal -->
<div class="glass-modal-backdrop" id="sessionModal" aria-hidden="true" role="dialog" aria-label="Oturum Detayı">
    <div class="glass-modal-card session-modal-card">
        <button type="button" class="modal-close-btn" aria-label="Kapat">✕</button>
        <div class="modal-session-top">
            <span id="modalSessionType" class="session-type">PANEL</span>
            <span id="modalSessionTime" class="modal-session-meta">10:00 · Ana Salon</span>
        </div>
        <h2 id="modalSessionTitle" class="modal-title">Oturum Başlığı</h2>
        <p id="modalSessionDesc" class="modal-desc">Açıklama</p>
        
        <div id="modalSessionModWrap" class="modal-speaker-row">
            <small>MODERATÖR</small>
            <div id="modalSessionMod" class="modal-speaker-name"></div>
        </div>

        <div id="modalSessionPartWrap" class="modal-speaker-row">
            <small>KATILIMCILAR / KONUŞMACILAR</small>
            <div id="modalSessionPart" class="modal-speaker-name"></div>
        </div>

        <div class="modal-action-bar">
            <button type="button" class="button button-lime button-sm" id="modalAddGoogleCal">Google Takvime Ekle 📅</button>
            <button type="button" class="button button-ghost button-sm" id="modalAddIcsCal">.ICS İndir 📥</button>
            <button type="button" class="button button-ghost button-sm" id="modalCopyLink">Bağlantıyı Kopyala 🔗</button>
            <button type="button" class="button button-fav-toggle button-sm" id="modalFavToggle">☆ Kişisel Programıma Ekle</button>
        </div>
    </div>
</div>

<!-- Mobile Bottom Dock -->
<nav class="mobile-bottom-dock" aria-label="Hızlı Gezinti">
    <a href="<?= e(url()) ?>" class="dock-item">
        <span>🏠</span>
        <small>Zirve</small>
    </a>
    <a href="<?= e(url('program')) ?>" class="dock-item">
        <span>🗓️</span>
        <small>Program</small>
    </a>
    <a href="<?= e(url('yarismalar')) ?>" class="dock-item">
        <span>🏆</span>
        <small>Yarışmalar</small>
    </a>
    <a href="<?= e(url('konusmacilar')) ?>" class="dock-item">
        <span>🎤</span>
        <small>Konuşmacılar</small>
    </a>
    <button type="button" class="dock-item" data-cmd-trigger aria-label="Komut Paleti">
        <span>⚡</span>
        <small>Ara (⌘K)</small>
    </button>
</nav>

<script src="<?= e(asset('app.js')) ?>?v=<?= @filemtime(__DIR__ . '/../assets/app.js') ?: '2.1.0' ?>" defer></script>
</body>
</html>
