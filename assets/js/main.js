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
  function closeNav() {
    if (!toggle || !nav) return;
    toggle.classList.remove('is-active');
    nav.classList.remove('is-open');
    if (scrim) scrim.classList.remove('is-open');
    document.body.style.overflow = '';
  }
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.classList.toggle('is-active', open);
      if (scrim) scrim.classList.toggle('is-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });
    if (scrim) scrim.addEventListener('click', closeNav);
    nav.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeNav); });
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

  /* Cookie banner (funkcja zachowana z oryginału, premium restyle) */
  var cookieBanner = document.querySelector('.cookie-banner');
  if (cookieBanner) {
    var stored = null;
    try { stored = localStorage.getItem('papernest_cookie_choice'); } catch (e) {}
    if (!stored) {
      setTimeout(function () { cookieBanner.classList.add('is-visible'); }, 600);
    }
    cookieBanner.querySelectorAll('[data-cookie-action]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        try { localStorage.setItem('papernest_cookie_choice', btn.getAttribute('data-cookie-action')); } catch (e) {}
        cookieBanner.classList.remove('is-visible');
      });
    });
  }
});
