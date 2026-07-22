// PaperNest — premium prototype interactions
document.addEventListener('DOMContentLoaded', function () {

  /* Sticky header state */
  var header = document.querySelector('.site-header');
  var onScroll = function () {
    if (!header) return;
    if (window.scrollY > 12) header.classList.add('is-scrolled');
    else header.classList.remove('is-scrolled');

    var toTop = document.querySelector('.to-top');
    if (toTop) toTop.classList.toggle('is-visible', window.scrollY > 500);
  };
  document.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* Mobile nav */
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.querySelector('.main-nav');
  var scrim = document.querySelector('.nav-scrim');
  function setNavState(open) {
    if (!toggle || !nav) return;
    toggle.classList.toggle('is-active', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Zamknij menu' : 'Otwórz menu');
    nav.classList.toggle('is-open', open);
    if (scrim) scrim.classList.toggle('is-open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  }
  function closeNav() { setNavState(false); }
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      setNavState(!nav.classList.contains('is-open'));
    });
    if (scrim) scrim.addEventListener('click', closeNav);
    nav.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeNav); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) { closeNav(); toggle.focus(); }
    });
  }

  /* Scroll reveal — z siatką bezpieczeństwa: treść nigdy nie może zostać trwale ukryta,
     nawet jeśli IntersectionObserver nie zdąży zareagować (bardzo szybkie przewijanie,
     nietypowe przeglądarki, itp.). */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.01, rootMargin: '0px 0px -10px 0px' });
    revealEls.forEach(function (el, i) {
      el.style.setProperty('--i', i % 8);
      io.observe(el);
    });
    /* Niezależne od zdarzenia 'load' (które czeka na wolne zasoby, np. fonty) —
       gwarantuje szybkie ujawnienie treści nawet gdy obserwator nie zdąży
       zareagować. Bez opóźnienia stagger — to awaryjna ścieżka, treść ma
       pojawić się od razu, nie kaskadowo. */
    setTimeout(function () {
      revealEls.forEach(function (el) {
        el.style.transitionDelay = '0s';
        el.classList.add('is-visible');
      });
    }, 350);
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* Back to top */
  var toTopBtn = document.querySelector('.to-top');
  if (toTopBtn) {
    toTopBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* Variant chip selection (product pages) */
  document.querySelectorAll('.variant-options').forEach(function (group) {
    group.addEventListener('click', function (e) {
      var chip = e.target.closest('.variant-chip');
      if (!chip) return;
      group.querySelectorAll('.variant-chip').forEach(function (c) { c.classList.remove('active'); });
      chip.classList.add('active');
      var priceNow = chip.getAttribute('data-price');
      var priceOld = chip.getAttribute('data-old-price');
      var card = chip.closest('.pd-info');
      if (card) {
        var now = card.querySelector('.price-now');
        var old = card.querySelector('.price-old');
        var save = card.querySelector('.save-badge');
        if (now && priceNow) now.textContent = priceNow + ' zł';
        if (old) {
          if (priceOld) { old.textContent = priceOld + ' zł'; old.style.display = ''; }
          else { old.style.display = 'none'; }
        }
        if (save) {
          if (priceOld && priceNow) {
            var pct = Math.round((1 - parseFloat(priceNow.replace(',', '.')) / parseFloat(priceOld.replace(',', '.'))) * 100);
            if (pct > 0) { save.textContent = '-' + pct + '%'; save.style.display = ''; }
            else { save.style.display = 'none'; }
          } else { save.style.display = 'none'; }
        }
      }
    });
  });

  /* Quantity steppers */
  document.querySelectorAll('.qty-input').forEach(function (box) {
    var input = box.querySelector('input');
    box.querySelectorAll('button').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var val = parseInt(input.value || '1', 10);
        val = btn.classList.contains('minus') ? Math.max(1, val - 1) : val + 1;
        input.value = val;
      });
    });
  });

  /* Tabs (product detail: description / specs / shipping) */
  document.querySelectorAll('.tabs').forEach(function (tabs) {
    var panels = tabs.parentElement.querySelectorAll('.tab-panel');
    tabs.querySelectorAll('.tab-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        tabs.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.remove('active'); });
        panels.forEach(function (p) { p.classList.remove('active'); });
        btn.classList.add('active');
        var target = tabs.parentElement.querySelector('.tab-panel[data-tab="' + btn.dataset.tab + '"]');
        if (target) target.classList.add('active');
      });
    });
  });

  /* Shop filter chips (visual only) */
  document.querySelectorAll('.shop-filters').forEach(function (group) {
    group.addEventListener('click', function (e) {
      var chip = e.target.closest('.filter-chip');
      if (!chip) return;
      group.querySelectorAll('.filter-chip').forEach(function (c) { c.classList.remove('active'); });
      chip.classList.add('active');
      var target = chip.getAttribute('data-filter');
      document.querySelectorAll('[data-family]').forEach(function (card) {
        var show = target === 'all' || card.getAttribute('data-family') === target;
        card.style.display = show ? '' : 'none';
      });
    });
  });

  /* Testimonials — samoczynnie przepływający pasek (czysty CSS), z przyciskiem
     pauzy/wznowienia wymaganym przez WCAG 2.2.2 dla automatycznie poruszającej
     się treści. */
  document.querySelectorAll('[data-testi-slider]').forEach(function (slider) {
    var toggle = slider.querySelector('[data-testi-toggle]');
    if (!toggle) return;
    var pauseIcon = toggle.innerHTML;
    var playIcon = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M7 4.8v14.4c0 .9 1 1.5 1.8 1L19 13.4c.7-.5.7-1.5 0-2L8.8 3.8c-.8-.5-1.8.1-1.8 1Z"/></svg>';
    toggle.addEventListener('click', function () {
      var paused = slider.classList.toggle('is-paused');
      toggle.setAttribute('aria-pressed', paused ? 'true' : 'false');
      toggle.setAttribute('aria-label', paused ? 'Wznów automatyczne przewijanie opinii' : 'Zatrzymaj automatyczne przewijanie opinii');
      toggle.innerHTML = paused ? playIcon : pauseIcon;
    });
  });

  /* =====================================================================
     System zgód cookie (RODO) — dwuwarstwowy: baner + panel preferencji.
     Zgoda jest granularna (niezbędne / funkcjonalne / analityczne /
     marketingowe), zapisywana lokalnie, i równie łatwa do wycofania co do
     wyrażenia (link "Zarządzaj zgodami" w stopce dostępny zawsze).
     ===================================================================== */
  var CONSENT_KEY = 'papernest_consent_v1';
  var cookieBanner = document.querySelector('.cookie-banner');
  var consentModal = document.querySelector('.consent-modal');
  var consentScrim = document.querySelector('[data-consent-scrim]');
  var consentToggles = document.querySelectorAll('[data-consent-cat]');
  var lastFocusedEl = null;

  function readConsent() {
    try {
      var raw = localStorage.getItem(CONSENT_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch (e) { return null; }
  }

  function writeConsent(prefs) {
    prefs.necessary = true;
    prefs.decided = true;
    prefs.timestamp = new Date().toISOString();
    try { localStorage.setItem(CONSENT_KEY, JSON.stringify(prefs)); } catch (e) {}
    // Miejsce na realne wdrożenie: tu odpalałyby się/wyłączały skrypty
    // analityczne i marketingowe w zależności od prefs.analytics / prefs.marketing.
    document.dispatchEvent(new CustomEvent('papernest:consent-updated', { detail: prefs }));
  }

  function applyTogglesFromConsent(prefs) {
    consentToggles.forEach(function (input) {
      var cat = input.getAttribute('data-consent-cat');
      input.checked = !!(prefs && prefs[cat]);
    });
  }

  function hideBanner() {
    if (cookieBanner) {
      cookieBanner.classList.remove('is-visible');
      cookieBanner.setAttribute('aria-hidden', 'true');
    }
  }
  function showBanner() {
    if (cookieBanner) {
      cookieBanner.classList.add('is-visible');
      cookieBanner.setAttribute('aria-hidden', 'false');
    }
  }

  function trapFocus(e) {
    if (!consentModal || !consentModal.classList.contains('is-visible')) return;
    if (e.key === 'Escape') { closeConsentModal(); return; }
    if (e.key !== 'Tab') return;
    var focusables = consentModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (!focusables.length) return;
    var first = focusables[0], last = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  }

  function openConsentModal() {
    if (!consentModal) return;
    lastFocusedEl = document.activeElement;
    var existing = readConsent();
    applyTogglesFromConsent(existing || { functional: false, analytics: false, marketing: false });
    hideBanner();
    if (consentScrim) { consentScrim.hidden = false; requestAnimationFrame(function () { consentScrim.classList.add('is-visible'); }); }
    consentModal.hidden = false;
    requestAnimationFrame(function () { consentModal.classList.add('is-visible'); });
    document.body.style.overflow = 'hidden';
    document.addEventListener('keydown', trapFocus);
    var closeBtn = consentModal.querySelector('[data-close-consent]');
    if (closeBtn) closeBtn.focus();
  }

  function closeConsentModal() {
    if (!consentModal) return;
    consentModal.classList.remove('is-visible');
    if (consentScrim) consentScrim.classList.remove('is-visible');
    document.removeEventListener('keydown', trapFocus);
    setTimeout(function () {
      consentModal.hidden = true;
      if (consentScrim) consentScrim.hidden = true;
    }, 350);
    document.body.style.overflow = '';
    if (lastFocusedEl && typeof lastFocusedEl.focus === 'function') lastFocusedEl.focus();
    if (!readConsent()) showBanner();
  }

  // Otwieranie panelu: przycisk "Dostosuj ustawienia" w banerze + link
  // "Zarządzaj zgodami" w stopce (dostępny zawsze, na każdej podstronie).
  document.querySelectorAll('[data-open-consent]').forEach(function (btn) {
    btn.addEventListener('click', openConsentModal);
  });
  document.querySelectorAll('[data-close-consent]').forEach(function (btn) {
    btn.addEventListener('click', closeConsentModal);
  });
  if (consentScrim) consentScrim.addEventListener('click', closeConsentModal);

  // Akcje: akceptuj wszystkie / odrzuć opcjonalne / zapisz wybrane w panelu.
  document.querySelectorAll('[data-cookie-action]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var action = btn.getAttribute('data-cookie-action');
      var prefs;
      if (action === 'accept-all') {
        prefs = { functional: true, analytics: true, marketing: true };
      } else if (action === 'reject') {
        prefs = { functional: false, analytics: false, marketing: false };
      } else { // 'save' — z panelu preferencji, bierze aktualny stan przełączników
        prefs = {};
        consentToggles.forEach(function (input) {
          prefs[input.getAttribute('data-consent-cat')] = input.checked;
        });
      }
      writeConsent(prefs);
      applyTogglesFromConsent(prefs);
      hideBanner();
      if (consentModal && consentModal.classList.contains('is-visible')) closeConsentModal();
    });
  });

  // Pierwsza wizyta: pokaż baner z opóźnieniem (nie blokuje pierwszego renderu).
  var existingConsent = readConsent();
  if (!existingConsent) {
    setTimeout(showBanner, 700);
  }
});
