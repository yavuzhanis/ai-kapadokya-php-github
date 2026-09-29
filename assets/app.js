/**
 * AI Kapadokya · Ultra-Premium Frontend & Interactivity Engine (9/10+)
 * Features: Command Palette (CMD+K), My Schedule (Bookmarks), Session & Speaker Modals,
 * 1-Click Calendar Sync (Google & Apple iCal), Toast Notifications, Glassmorphism 2.0.
 */
(() => {
  // Elements
  const header = document.querySelector('[data-header]');
  const toggle = document.querySelector('[data-menu-toggle]');
  const nav = document.querySelector('[data-nav]');
  const scrollProgress = document.getElementById('scrollProgress');
  const scrollTopBtn = document.getElementById('scrollTopBtn');
  const quickDock = document.getElementById('quickDock');
  const themeToggleBtn = document.getElementById('themeToggleBtn');
  const cmdPaletteModal = document.getElementById('cmdPaletteModal');
  const cmdPaletteInput = document.getElementById('cmdPaletteInput');
  const cmdPaletteResults = document.getElementById('cmdPaletteResults');
  const cmdPaletteTrigger = document.querySelectorAll('[data-cmd-trigger]');
  const sessionModal = document.getElementById('sessionModal');
  const speakerModal = document.getElementById('speakerModal');
  const toastContainer = document.getElementById('toastContainer');

  // =========================================================================
  // 1. Toast Notification System
  // =========================================================================
  window.showToast = (msg, icon = '✦') => {
    if (!toastContainer) return;
    const toast = document.createElement('div');
    toast.className = 'glass-toast';
    toast.innerHTML = `<span class="toast-ico">${icon}</span><span class="toast-msg">${msg}</span>`;
    toastContainer.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 350);
    }, 3200);
  };

  // =========================================================================
  // 2. Theme Switching (Dark & Light Mode)
  // =========================================================================
  const setTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('aik_theme', theme);
    showToast(theme === 'dark' ? 'Koyu mod aktif' : 'Açık mod aktif', theme === 'dark' ? '🌙' : '☀️');
  };

  themeToggleBtn?.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    setTheme(current === 'dark' ? 'light' : 'dark');
  });

  // =========================================================================
  // 3. Header Scroll & Quick Dock
  // =========================================================================
  const onScroll = () => {
    const scrollY = window.scrollY;
    header?.classList.toggle('scrolled', scrollY > 20);

    if (scrollProgress) {
      const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
      const pct = maxScroll > 0 ? (scrollY / maxScroll) * 100 : 0;
      scrollProgress.style.width = pct + '%';
    }

    if (quickDock) {
      quickDock.classList.toggle('visible', scrollY > 280);
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  scrollTopBtn?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    nav?.classList.toggle('open', open);
    document.body.classList.toggle('menu-open', open);
  });

  document.querySelectorAll('.has-sub > button').forEach(button => button.addEventListener('click', () => {
    if (window.innerWidth <= 860) button.parentElement.classList.toggle('open');
  }));

  // =========================================================================
  // 4. Countdown Timer
  // =========================================================================
  const countdown = document.querySelector('[data-countdown]');
  if (countdown) {
    const target = new Date(countdown.dataset.countdown).getTime();
    const render = () => {
      const distance = Math.max(0, target - Date.now());
      const values = {
        days: Math.floor(distance / 86400000),
        hours: Math.floor((distance / 3600000) % 24),
        minutes: Math.floor((distance / 60000) % 60),
        seconds: Math.floor((distance / 1000) % 60),
      };
      Object.entries(values).forEach(([key, value]) => {
        const node = countdown.querySelector(`[data-${key}]`);
        if (node) node.textContent = String(value).padStart(2, '0');
      });
    };
    render();
    setInterval(render, 1000);
  }

  // =========================================================================
  // 5. Calendar Integration (.ICS & Google Calendar)
  // =========================================================================
  const generateICS = (title, description, location, dtStart, dtEnd) => {
    const formatDate = d => d.toISOString().replace(/-|:|\.\d+/g, '');
    const icsContent = [
      'BEGIN:VCALENDAR',
      'VERSION:2.0',
      'PRODID:-//AI Kapadokya//Etkinlik Takvimi//TR',
      'CALSCALE:GREGORIAN',
      'BEGIN:VEVENT',
      `SUMMARY:${title}`,
      `DESCRIPTION:${description}`,
      `LOCATION:${location}`,
      `DTSTART:${formatDate(dtStart)}`,
      `DTEND:${formatDate(dtEnd)}`,
      'STATUS:CONFIRMED',
      'END:VEVENT',
      'END:VCALENDAR'
    ].join('\r\n');

    const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `${title.toLowerCase().replace(/[^a-z0-9]/g, '_')}.ics`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('Takvim dosyası (.ics) indirildi', '📅');
  };

  const openGoogleCalendar = (title, description, location, dtStart, dtEnd) => {
    const fmt = d => d.toISOString().replace(/-|:|\.\d+/g, '');
    const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${fmt(dtStart)}/${fmt(dtEnd)}&details=${encodeURIComponent(description)}&location=${encodeURIComponent(location)}`;
    window.open(url, '_blank', 'noopener,noreferrer');
  };

  // Bind Main Summit Calendar Buttons
  document.querySelectorAll('[data-add-main-cal]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const type = btn.dataset.addMainCal;
      const start = new Date('2026-09-17T10:00:00+03:00');
      const end = new Date('2026-09-18T18:30:00+03:00');
      const title = 'III. Ulusal Yapay Zekâ Buluşması · Kapadokya';
      const desc = 'Yapay zekânın geleceği Kapadokya’da buluşuyor. Paneller, açık çağrılar ve Demo Day. Detaylar: https://aikapadokya.com';
      const loc = 'Kapadokya Üniversitesi Ürgüp Fabrika Yerleşkesi, Nevşehir';

      if (type === 'google') {
        openGoogleCalendar(title, desc, loc, start, end);
      } else {
        generateICS(title, desc, loc, start, end);
      }
    });
  });

  // =========================================================================
  // 6. Favorites & "My Schedule" (Kişisel Programım)
  // =========================================================================
  const getFavorites = () => {
    try {
      return JSON.parse(localStorage.getItem('aik_fav_sessions') || '[]');
    } catch {
      return [];
    }
  };

  const saveFavorites = (favs) => {
    localStorage.setItem('aik_fav_sessions', JSON.stringify(favs));
    updateFavUI();
  };

  const toggleFavorite = (sessionTitle) => {
    let favs = getFavorites();
    const idx = favs.indexOf(sessionTitle);
    if (idx > -1) {
      favs.splice(idx, 1);
      showToast('Oturum kişisel takvimden çıkarıldı', '⭐');
    } else {
      favs.push(sessionTitle);
      showToast('Oturum kişisel takviminize eklendi!', '⭐');
    }
    saveFavorites(favs);
  };

  const updateFavUI = () => {
    const favs = getFavorites();
    const countBadge = document.getElementById('favBadgeCount');
    if (countBadge) countBadge.textContent = favs.length;

    document.querySelectorAll('.session-row').forEach(row => {
      const title = row.dataset.sessionTitle;
      const isFav = favs.includes(title);
      const starBtn = row.querySelector('.session-star-btn');
      if (starBtn) {
        starBtn.classList.toggle('starred', isFav);
        starBtn.setAttribute('aria-label', isFav ? 'Favorilerden çıkar' : 'Favorilere ekle');
        starBtn.innerHTML = isFav ? '★' : '☆';
      }
    });

    if (currentActiveType === 'favorites') {
      applySessionFilters();
    }
  };

  // =========================================================================
  // 7. Interactive Program, Tabs, Search & Filters
  // =========================================================================
  let currentActiveTab = '';
  let currentActiveType = 'all';
  let currentSearchQuery = '';

  const dayTabs = document.querySelector('[data-tabs]');
  const sessionRows = [...document.querySelectorAll('.session-list .session-row')];
  const emptyMsg = document.getElementById('emptySessionMsg');
  const searchInput = document.getElementById('sessionSearchInput');
  const typeChips = document.querySelectorAll('#sessionTypeChips [data-type]');

  const applySessionFilters = () => {
    if (!sessionRows.length) return;
    const favs = getFavorites();
    let visibleCount = 0;

    sessionRows.forEach(row => {
      const matchesTab = currentActiveType === 'favorites' || !currentActiveTab || row.dataset.tabPanel === currentActiveTab;
      let matchesType = true;

      if (currentActiveType === 'favorites') {
        matchesType = favs.includes(row.dataset.sessionTitle);
      } else if (currentActiveType !== 'all') {
        matchesType = row.dataset.sessionType === currentActiveType;
      }

      const rowSearchText = (row.dataset.search || '').toLowerCase();
      const matchesSearch = !currentSearchQuery || rowSearchText.includes(currentSearchQuery);

      if (matchesTab && matchesType && matchesSearch) {
        row.hidden = false;
        visibleCount++;
      } else {
        row.hidden = true;
      }
    });

    if (emptyMsg) {
      if (currentActiveType === 'favorites' && visibleCount === 0) {
        emptyMsg.innerHTML = '<p>Henüz favorilere eklediğiniz bir oturum yok.<br><small style="color:var(--muted)">Oturumların yanındaki yıldız (☆) simgesine tıklayarak kişisel programınızı oluşturabilirsiniz.</small></p>';
        emptyMsg.style.display = 'block';
      } else {
        emptyMsg.innerHTML = '<p>Aradığınız kriterlere uygun oturum bulunamadı.</p>';
        emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    }
  };

  if (dayTabs) {
    const buttons = [...dayTabs.querySelectorAll('[data-tab]')];
    const selectTab = tabValue => {
      currentActiveTab = tabValue;
      buttons.forEach(btn => btn.classList.toggle('active', btn.dataset.tab === tabValue));
      applySessionFilters();
    };

    buttons.forEach(btn => btn.addEventListener('click', () => selectTab(btn.dataset.tab)));
    if (buttons[0]) {
      selectTab(buttons[0].dataset.tab);
    }
  }

  searchInput?.addEventListener('input', e => {
    currentSearchQuery = (e.target.value || '').trim().toLowerCase();
    applySessionFilters();
  });

  typeChips.forEach(chip => {
    chip.addEventListener('click', () => {
      typeChips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      currentActiveType = chip.dataset.type || 'all';
      applySessionFilters();
    });
  });

  // Bind Session Star Buttons & Click to Open Detail Modal
  sessionRows.forEach(row => {
    const starBtn = row.querySelector('.session-star-btn');
    starBtn?.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleFavorite(row.dataset.sessionTitle);
    });

    row.addEventListener('click', () => {
      openSessionModal({
        title: row.dataset.sessionTitle,
        type: row.dataset.sessionType,
        time: row.querySelector('time')?.textContent || '',
        day: row.dataset.tabPanel || '',
        venue: row.dataset.venue || 'Ana Salon · Fabrika Yerleşkesi',
        moderator: row.dataset.moderator || '',
        participants: row.dataset.participants || '',
        description: row.dataset.description || 'Yapay zekâ ekosisteminin liderleri, geleceğin vizyonunu ve pratik uygulamalarını Kapadokya’da tartışıyor.'
      });
    });
  });

  updateFavUI();

  // =========================================================================
  // 8. Session & Speaker Modals
  // =========================================================================
  const openSessionModal = (data) => {
    if (!sessionModal) return;
    sessionModal.querySelector('#modalSessionType').textContent = data.type.toUpperCase();
    sessionModal.querySelector('#modalSessionType').className = `session-type session-type-${data.type}`;
    sessionModal.querySelector('#modalSessionTitle').textContent = data.title;
    sessionModal.querySelector('#modalSessionTime').textContent = `${data.time} · ${data.venue}`;
    sessionModal.querySelector('#modalSessionDesc').textContent = data.description;

    const modWrap = sessionModal.querySelector('#modalSessionModWrap');
    const modEl = sessionModal.querySelector('#modalSessionMod');
    if (data.moderator) {
      modEl.textContent = data.moderator;
      modWrap.style.display = 'block';
    } else {
      modWrap.style.display = 'none';
    }

    const partWrap = sessionModal.querySelector('#modalSessionPartWrap');
    const partEl = sessionModal.querySelector('#modalSessionPart');
    if (data.participants) {
      partEl.textContent = data.participants;
      partWrap.style.display = 'block';
    } else {
      partWrap.style.display = 'none';
    }

    // Modal action buttons
    const googleBtn = sessionModal.querySelector('#modalAddGoogleCal');
    const icsBtn = sessionModal.querySelector('#modalAddIcsCal');
    const copyBtn = sessionModal.querySelector('#modalCopyLink');
    const favBtn = sessionModal.querySelector('#modalFavToggle');

    const dtStart = new Date(`${data.day || '2026-09-17'}T${data.time || '10:00'}:00+03:00`);
    const dtEnd = new Date(dtStart.getTime() + 50 * 60000);

    googleBtn.onclick = () => openGoogleCalendar(data.title, data.description, data.venue, dtStart, dtEnd);
    icsBtn.onclick = () => generateICS(data.title, data.description, data.venue, dtStart, dtEnd);

    copyBtn.onclick = async () => {
      const url = `${window.location.origin}${window.location.pathname}#session-${encodeURIComponent(data.title.slice(0, 20))}`;
      await navigator.clipboard.writeText(url);
      showToast('Oturum bağlantısı panoya kopyalandı', '🔗');
    };

    const updateModalFavBtn = () => {
      const favs = getFavorites();
      const isFav = favs.includes(data.title);
      favBtn.innerHTML = isFav ? '★ Favorilerimde' : '☆ Kişisel Programıma Ekle';
      favBtn.classList.toggle('active', isFav);
    };
    updateModalFavBtn();

    favBtn.onclick = () => {
      toggleFavorite(data.title);
      updateModalFavBtn();
    };

    sessionModal.classList.add('open');
    sessionModal.setAttribute('aria-hidden', 'false');
  };

  const closeModals = () => {
    document.querySelectorAll('.glass-modal-backdrop').forEach(m => {
      m.classList.remove('open');
      m.setAttribute('aria-hidden', 'true');
    });
  };

  document.querySelectorAll('.modal-close-btn, .glass-modal-backdrop').forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (e.target === btn || btn.classList.contains('modal-close-btn')) {
        closeModals();
      }
    });
  });

  // =========================================================================
  // 9. Command Palette (CMD + K / Spotlight)
  // =========================================================================
  const searchableIndex = [
    // Actions
    { title: 'Koyu Temaya Geç', category: 'Eylemler', icon: '🌙', action: () => setTheme('dark') },
    { title: 'Açık Temaya Geç', category: 'Eylemler', icon: '☀️', action: () => setTheme('light') },
    { title: 'Zirveyi Google Takvime Ekle', category: 'Eylemler', icon: '📅', action: () => document.querySelector('[data-add-main-cal="google"]')?.click() },
    { title: 'Zirve Takvim Dosyasını İndir (.ICS)', category: 'Eylemler', icon: '📥', action: () => document.querySelector('[data-add-main-cal="ics"]')?.click() },
    { title: 'Kişisel Programımı Görüntüle', category: 'Eylemler', icon: '⭐', action: () => document.querySelector('#sessionTypeChips [data-type="favorites"]')?.click() },
    // Pages
    { title: 'Tüm Yarışmalar & Açık Çağrılar', category: 'Sayfalar', icon: '🏆', url: '/yarismalar' },
    { title: 'Agentic AI Demo Day 2026', category: 'Sayfalar', icon: '⚡', url: '/yarismalar/agentic-ai-demoday' },
    { title: 'Konuşmacılar & Panelistler', category: 'Sayfalar', icon: '🎤', url: '/konusmacilar' },
    { title: 'Zirve Programı & Akış', category: 'Sayfalar', icon: '🗓️', url: '/program' },
    { title: 'Konaklama ve Ulaşım Rehberi', category: 'Sayfalar', icon: '🏨', url: '/konaklama' },
    { title: 'İletişim & Katılım', category: 'Sayfalar', icon: '✉️', url: '/iletisim' }
  ];

  // Index sessions from DOM
  sessionRows.forEach(row => {
    searchableIndex.push({
      title: row.dataset.sessionTitle,
      category: 'Oturumlar',
      icon: '✦',
      action: () => row.click()
    });
  });

  // Index speakers from DOM
  document.querySelectorAll('.speaker-card').forEach(spk => {
    const name = spk.querySelector('h3')?.textContent;
    const role = spk.querySelector('small')?.textContent;
    if (name) {
      searchableIndex.push({
        title: `${name} — ${role}`,
        category: 'Konuşmacılar',
        icon: '👤',
        url: spk.getAttribute('href') || '/konusmacilar'
      });
    }
  });

  const renderCmdResults = (query = '') => {
    if (!cmdPaletteResults) return;
    const q = query.toLowerCase().trim();
    const filtered = q ? searchableIndex.filter(item => item.title.toLowerCase().includes(q) || item.category.toLowerCase().includes(q)) : searchableIndex.slice(0, 8);

    if (filtered.length === 0) {
      cmdPaletteResults.innerHTML = '<div class="cmd-empty">Sonuç bulunamadı.</div>';
      return;
    }

    cmdPaletteResults.innerHTML = filtered.map((item, i) => `
      <div class="cmd-item ${i === 0 ? 'selected' : ''}" data-cmd-index="${i}">
        <span class="cmd-ico">${item.icon}</span>
        <div class="cmd-text">
          <b>${item.title}</b>
          <small>${item.category}</small>
        </div>
        <span class="cmd-key">↵</span>
      </div>
    `).join('');

    cmdPaletteResults.querySelectorAll('.cmd-item').forEach((el, idx) => {
      el.addEventListener('click', () => {
        executeCmdItem(filtered[idx]);
      });
    });
  };

  const executeCmdItem = (item) => {
    closeCmdPalette();
    if (item.action) {
      item.action();
    } else if (item.url) {
      window.location.href = item.url;
    }
  };

  const openCmdPalette = () => {
    if (!cmdPaletteModal) return;
    cmdPaletteModal.classList.add('open');
    cmdPaletteModal.setAttribute('aria-hidden', 'false');
    if (cmdPaletteInput) {
      cmdPaletteInput.value = '';
      cmdPaletteInput.focus();
      renderCmdResults();
    }
  };

  const closeCmdPalette = () => {
    if (!cmdPaletteModal) return;
    cmdPaletteModal.classList.remove('open');
    cmdPaletteModal.setAttribute('aria-hidden', 'true');
  };

  cmdPaletteTrigger.forEach(t => t.addEventListener('click', openCmdPalette));

  cmdPaletteInput?.addEventListener('input', (e) => {
    renderCmdResults(e.target.value);
  });

  // =========================================================================
  // 10. Lightbox Controller (Hafıza / Galeri)
  // =========================================================================
  const lightboxModal = document.querySelector('[data-lightbox-modal]');
  if (lightboxModal) {
    const closeLightbox = () => {
      lightboxModal.classList.remove('open');
      lightboxModal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    };

    const openLightbox = (src, title, category) => {
      const img = lightboxModal.querySelector('img');
      let caption = lightboxModal.querySelector('.lightbox-caption');
      if (!caption) {
        caption = document.createElement('div');
        caption.className = 'lightbox-caption';
        lightboxModal.appendChild(caption);
      }

      if (img && src) {
        img.src = src;
        img.alt = title || 'AI Kapadokya Galeri';
      }
      caption.innerHTML = `${category ? `<small>${category}</small>` : ''}<h4>${title || ''}</h4>`;

      lightboxModal.classList.add('open');
      lightboxModal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    };

    document.querySelectorAll('[data-lightbox]').forEach(card => {
      card.addEventListener('click', (e) => {
        e.preventDefault();
        const src = card.dataset.lightbox || card.querySelector('img')?.src;
        const title = card.querySelector('b')?.textContent || card.getAttribute('aria-label') || '';
        const category = card.querySelector('span')?.childNodes[0]?.textContent?.trim() || '';
        if (src) {
          openLightbox(src, title, category);
        }
      });
    });

    lightboxModal.querySelector('button')?.addEventListener('click', closeLightbox);
    lightboxModal.addEventListener('click', (e) => {
      if (e.target === lightboxModal) {
        closeLightbox();
      }
    });
  }

  // Global Keybindings: CMD+K or CTRL+K and Escape
  window.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      if (cmdPaletteModal?.classList.contains('open')) {
        closeCmdPalette();
      } else {
        openCmdPalette();
      }
    } else if (e.key === 'Escape') {
      closeCmdPalette();
      closeModals();
      if (lightboxModal?.classList.contains('open')) {
        lightboxModal.classList.remove('open');
        lightboxModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    }
  });

  // =========================================================================
  // 12. Cappadocia & AI Cinematic Showcase Slider
  // =========================================================================
  const initShowcaseSlider = () => {
    const sliderWrap = document.getElementById('cappadociaShowcase');
    const slider = document.getElementById('showcaseSlider');
    if (!slider || !sliderWrap) return;

    const slides = Array.from(slider.querySelectorAll('.showcase-slide'));
    const prevBtn = document.getElementById('showcasePrevBtn');
    const nextBtn = document.getElementById('showcaseNextBtn');
    const indicatorTabs = Array.from(document.querySelectorAll('#showcaseIndicators .indicator-tab'));

    if (slides.length === 0) return;

    let currentIndex = 0;
    let timer = null;
    const duration = 5500;

    const goToSlide = (index) => {
      currentIndex = (index + slides.length) % slides.length;

      slides.forEach((s, idx) => {
        s.classList.toggle('active', idx === currentIndex);
      });

      indicatorTabs.forEach((tab, idx) => {
        tab.classList.toggle('active', idx === currentIndex);
        const fill = tab.querySelector('.indicator-progress-fill');
        if (fill) {
          if (idx === currentIndex) {
            fill.style.transition = 'none';
            fill.style.width = '0%';
            // Force reflow
            void fill.offsetWidth;
            fill.style.transition = `width ${duration}ms linear`;
            fill.style.width = '100%';
          } else {
            fill.style.transition = 'none';
            fill.style.width = '0%';
          }
        }
      });
    };

    const nextSlide = () => goToSlide(currentIndex + 1);
    const prevSlide = () => goToSlide(currentIndex - 1);

    const startTimer = () => {
      stopTimer();
      timer = setInterval(nextSlide, duration);
      // Start initial fill transition for active tab
      const activeFill = indicatorTabs[currentIndex]?.querySelector('.indicator-progress-fill');
      if (activeFill) {
        activeFill.style.transition = `width ${duration}ms linear`;
        activeFill.style.width = '100%';
      }
    };

    const stopTimer = () => {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    };

    // Button events
    prevBtn?.addEventListener('click', () => {
      prevSlide();
      startTimer();
    });

    nextBtn?.addEventListener('click', () => {
      nextSlide();
      startTimer();
    });

    // Indicator tab events
    indicatorTabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        const targetIdx = parseInt(tab.getAttribute('data-go-to'), 10);
        if (!isNaN(targetIdx)) {
          goToSlide(targetIdx);
          startTimer();
        }
      });
    });

    // Pause on hover
    sliderWrap.addEventListener('mouseenter', stopTimer);
    sliderWrap.addEventListener('mouseleave', startTimer);

    // Touch Swipe support
    let touchStartX = 0;
    slider.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    slider.addEventListener('touchend', (e) => {
      const touchEndX = e.changedTouches[0].screenX;
      const diff = touchEndX - touchStartX;
      if (Math.abs(diff) > 45) {
        if (diff < 0) nextSlide();
        else prevSlide();
        startTimer();
      }
    }, { passive: true });

    // Keyboard Arrow navigation when mouse is inside slider
    sliderWrap.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight') { nextSlide(); startTimer(); }
      if (e.key === 'ArrowLeft') { prevSlide(); startTimer(); }
    });

    // Start initial state
    goToSlide(0);
    startTimer();
  };

  initShowcaseSlider();

  // =========================================================================
  // 13. 3D Holographic Perspective Tilt on Hero Event Pass Card
  // =========================================================================
  const init3DCardTilt = () => {
    const card = document.querySelector('.hero-ticket-card');
    if (!card) return;

    const inner = card.querySelector('.ticket-inner');
    const aura = card.querySelector('.ticket-glow-aura');
    if (!inner) return;

    let bounds;
    const onMouseEnter = () => {
      bounds = card.getBoundingClientRect();
      inner.style.transition = 'transform 0.12s ease-out';
    };

    const onMouseMove = (e) => {
      if (!bounds) bounds = card.getBoundingClientRect();
      const mouseX = e.clientX - bounds.left;
      const mouseY = e.clientY - bounds.top;
      const xPct = (mouseX / bounds.width) - 0.5;
      const yPct = (mouseY / bounds.height) - 0.5;

      const rotateY = xPct * 16;
      const rotateX = -yPct * 16;

      inner.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`;

      if (aura) {
        aura.style.opacity = '0.9';
        aura.style.background = `radial-gradient(circle at ${(mouseX / bounds.width * 100).toFixed(1)}% ${(mouseY / bounds.height * 100).toFixed(1)}%, rgba(216, 255, 98, 0.45) 0%, rgba(124, 58, 237, 0.4) 40%, transparent 70%)`;
      }
    };

    const onMouseLeave = () => {
      inner.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
      inner.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
      if (aura) {
        aura.style.opacity = '0.6';
        aura.style.background = '';
      }
    };

    card.addEventListener('mouseenter', onMouseEnter);
    card.addEventListener('mousemove', onMouseMove);
    card.addEventListener('mouseleave', onMouseLeave);
  };

  init3DCardTilt();

  // =========================================================================
  // 14. Animated Number Counters on Scroll
  // =========================================================================
  const initAnimatedCounters = () => {
    const statNumbers = document.querySelectorAll('.stat-number');
    if (!statNumbers.length) return;

    const animateNumber = (el) => {
      const raw = el.innerText.trim();
      const isLira = raw.startsWith('₺');
      const hasPlus = raw.endsWith('+');
      // Clean digits
      const cleaned = raw.replace(/[^\d]/g, '');
      const target = parseInt(cleaned, 10);
      if (isNaN(target)) return;

      const duration = 1800;
      const startTime = performance.now();

      const easeOutQuad = (t) => t * (2 - t);

      const update = (now) => {
        const elapsed = now - startTime;
        const progress = Math.min(1, elapsed / duration);
        const current = Math.floor(easeOutQuad(progress) * target);

        let formatted = target >= 1000 ? current.toLocaleString('tr-TR') : current.toString();
        if (isLira) formatted = '₺' + formatted;
        if (hasPlus) formatted = formatted + '+';

        el.innerText = formatted;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          // Ensure exact final text
          el.innerText = raw;
        }
      };

      requestAnimationFrame(update);
    };

    const counterObserver = 'IntersectionObserver' in window ? new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateNumber(entry.target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.25 }) : null;

    statNumbers.forEach(num => {
      if (counterObserver) counterObserver.observe(num);
    });
  };

  initAnimatedCounters();

  // Reveal Animations on scroll
  const observer = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 }) : null;

  document.querySelectorAll('.reveal').forEach(node => {
    if (observer) {
      observer.observe(node);
    } else {
      node.classList.add('visible');
    }
  });
})();

