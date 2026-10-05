document.addEventListener('DOMContentLoaded', function () {

  /* Footer year */
  var yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* Auto-scrolling rows (testimonials, services) — duplicate the card set once so
     the scroll position can wrap around seamlessly (real scrollLeft, not a CSS
     transform, so visitors can also drag/swipe the row themselves — see
     setupAutoScroller below). The duplicate is hidden from assistive tech and
     removed from tab order since it's a visual repeat, not new content (relevant
     for the services row, whose cards are real links). */
  function setupMarqueeLoop(trackId) {
    var track = document.getElementById(trackId);
    if (!track) return;
    var originals = Array.prototype.slice.call(track.children);
    originals.forEach(function (item) {
      var clone = item.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      if (clone.matches('a, button, [tabindex]')) clone.setAttribute('tabindex', '-1');
      clone.querySelectorAll('a, button, [tabindex]').forEach(function (el) { el.setAttribute('tabindex', '-1'); });
      track.appendChild(clone);
    });
  }
  setupMarqueeLoop('testimonial-track');
  setupMarqueeLoop('services-track');

  /* Drives the auto-scroll (via real scrollLeft, at speedPxPerSec) and lets the
     visitor take over at any time: touch/trackpad swipe works natively since
     this is a real scroll container, and a plain mouse can grab-drag it too
     (pointerdown/move on a "mouse" pointer only — touch already scrolls on its
     own). Auto-scroll pauses on hover and while the visitor is interacting, and
     resumes shortly after they let go. The scroll position wraps at the halfway
     point of the (duplicated) track so the loop never visibly resets. */
  function setupAutoScroller(marqueeId, speedPxPerSec) {
    var marquee = document.getElementById(marqueeId);
    if (!marquee) return;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var isHovering = false;
    var isDragging = false;
    var resumeTimer = null;
    var lastClientX = 0;
    var lastFrameTime = null;

    function halfScrollWidth() {
      return marquee.scrollWidth / 2;
    }

    function wrapIfNeeded() {
      var half = halfScrollWidth();
      if (marquee.scrollLeft >= half) marquee.scrollLeft -= half;
      else if (marquee.scrollLeft < 0) marquee.scrollLeft += half;
    }

    function tick(timestamp) {
      if (lastFrameTime === null) lastFrameTime = timestamp;
      var deltaSeconds = (timestamp - lastFrameTime) / 1000;
      lastFrameTime = timestamp;
      if (!reduceMotion && !isHovering && !isDragging) {
        marquee.scrollLeft += speedPxPerSec * deltaSeconds;
        wrapIfNeeded();
      }
      requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);

    marquee.addEventListener('scroll', wrapIfNeeded, { passive: true });
    marquee.addEventListener('mouseenter', function () { isHovering = true; });
    marquee.addEventListener('mouseleave', function () { isHovering = false; });

    // Click vs. drag: only commit to "dragging" (and only then block the browser's
    // own default handling — mainly its native drag-out of links/images, which
    // would otherwise steal the pointermove events this needs) once the pointer
    // has actually moved past a small threshold. A plain click never crosses it,
    // so service card links keep navigating normally. Move/up are tracked on the
    // document (not via setPointerCapture, which retargets events in a way that
    // ended up breaking normal link clicks) so the drag keeps tracking even if
    // the pointer briefly leaves the row.
    var isPointerDown = false;
    var dragStartX = 0;
    var DRAG_THRESHOLD = 6;
    // The browser still fires a normal "click" on the underlying link/button
    // after a real drag (preventDefault on pointermove doesn't stop that) —
    // this flag tells a capture-phase click listener to swallow just that one
    // click, without affecting a genuine plain click (no drag) afterwards.
    var suppressNextClick = false;

    marquee.addEventListener('pointerdown', function (e) {
      if (e.pointerType !== 'mouse') return; // touch/pen: let native scrolling handle it
      isPointerDown = true;
      dragStartX = e.clientX;
      lastClientX = e.clientX;
      clearTimeout(resumeTimer);
    });
    document.addEventListener('pointermove', function (e) {
      if (!isPointerDown) return;
      if (!isDragging) {
        if (Math.abs(e.clientX - dragStartX) < DRAG_THRESHOLD) return;
        isDragging = true;
        suppressNextClick = true;
        marquee.classList.add('is-dragging');
      }
      e.preventDefault();
      marquee.scrollLeft -= e.clientX - lastClientX;
      lastClientX = e.clientX;
    });
    document.addEventListener('pointerup', function () {
      isPointerDown = false;
      if (!isDragging) return;
      isDragging = false;
      marquee.classList.remove('is-dragging');
    });
    marquee.addEventListener('click', function (e) {
      if (suppressNextClick) {
        e.preventDefault();
        suppressNextClick = false;
      }
    }, true);

    // On touch, pause the auto-scroll for a moment after the visitor's own swipe
    // so it doesn't fight their gesture; resume automatically after a short pause.
    marquee.addEventListener('touchstart', function () { isDragging = true; clearTimeout(resumeTimer); }, { passive: true });
    marquee.addEventListener('touchend', function () {
      resumeTimer = setTimeout(function () { isDragging = false; }, 1200);
    }, { passive: true });
  }
  setupAutoScroller('services-marquee', 40);
  setupAutoScroller('testimonial-marquee', 65);

  /* Instagram embeds — loaded lazily, only once the section actually scrolls into
     view. The official embed.js script was previously loaded on every page load
     regardless of whether anyone scrolls that far, and being a third-party script
     it delays the window "load" event on a slow connection (a real, measured
     mobile PageSpeed regression) for a section most first-time visitors never
     reach. Instagram's script scans the page for .instagram-media elements as
     soon as it runs, so injecting it late still renders the embeds correctly. */
  var instagramSection = document.getElementById('instagram');
  if (instagramSection) {
    var loadInstagramEmbed = function () {
      var script = document.createElement('script');
      script.async = true;
      script.src = '//www.instagram.com/embed.js';
      document.body.appendChild(script);
    };
    if ('IntersectionObserver' in window) {
      var igObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            loadInstagramEmbed();
            igObserver.disconnect();
          }
        });
      }, { rootMargin: '400px' });
      igObserver.observe(instagramSection);
    } else {
      loadInstagramEmbed();
    }
  }

  /* Track "Appeler" and "WhatsApp" clicks as leads too — previously only the quote
     form counted as a conversion in Google Ads, so a visitor calling or writing on
     WhatsApp directly (very likely now that both are one tap away) never showed up
     as a conversion at all. Delegated on the whole document so it covers every
     tel:/wa.me link on the page (header, hero, floating buttons, footer...),
     present now or added later, without wiring each one by hand. */
  document.addEventListener('click', function (e) {
    var telLink = e.target.closest('a[href^="tel:"]');
    if (telLink) {
      if (typeof window.trackLeadSubmitted === 'function') window.trackLeadSubmitted('phone');
      return;
    }
    var waLink = e.target.closest('a[href*="wa.me"]');
    if (waLink) {
      if (typeof window.trackLeadSubmitted === 'function') window.trackLeadSubmitted('whatsapp');
    }
  });

  /* Visitor gate (particulier / professionnel) — shown once per browser session on
     load. The choice now actually changes the site's wording (hero, "pourquoi nous
     choisir", FAQ, formulaires de devis) toward a B2B register for "professionnel"
     — see applyVisitorType() below. */
  var visitorGate = document.getElementById('visitor-gate');
  var gateWasOpen = !!(visitorGate && !visitorGate.hidden);
  if (visitorGate) {
    var gateParticulier = document.getElementById('gate-particulier');
    var gatePro = document.getElementById('gate-professionnel');
    var chooseVisitor = function (type) {
      try {
        sessionStorage.setItem('mi-visitor-type', type);
        localStorage.setItem('mi-visitor-type', type);
      } catch (err) {}
      visitorGate.hidden = true;
      document.documentElement.style.overflow = '';
      document.dispatchEvent(new Event('mi:visitor-chosen'));
    };
    if (gateParticulier) gateParticulier.addEventListener('click', function () { chooseVisitor('particulier'); });
    if (gatePro) gatePro.addEventListener('click', function () { chooseVisitor('professionnel'); });
  }

  /* Applies the chosen audience everywhere: toggles the "is-pro" class on <html>
     (the static HTML already carries both wordings side by side for the hero,
     reasons, FAQ and bottom contact form — see css/style.css's
     .audience-particulier/.audience-pro rules) and updates the JS-built devis
     pop-up's copy directly, since that one doesn't exist in the static HTML.
     Re-applied on the gate's "mi:visitor-chosen" event, and once eagerly below so
     a visitor who already chose earlier this session (gate won't show again) gets
     the right pop-up copy too — the <html class="is-pro"> part for the static
     content is already set, flash-free, by the blocking script at the top of
     index.html's <body>. */
  function isProVisitor() {
    try { return sessionStorage.getItem('mi-visitor-type') === 'professionnel'; } catch (err) { return false; }
  }
  function applyVisitorType() {
    var isPro = isProVisitor();
    document.documentElement.classList.toggle('is-pro', isPro);
    updatePopupCopyForAudience(isPro);
  }
  document.addEventListener('mi:visitor-chosen', applyVisitorType);

  /* Background videos (gate + hero) — deferred a beat past the very first paint on
     slow connections, so they don't compete for bandwidth with the critical
     CSS/HTML there (the poster image gives an instant visual in the meantime).
     On a decent connection this delay serves no purpose — the video would have
     loaded near-instantly anyway — and only pushes back the LCP timing for
     nothing, so it's skipped there and the video starts right away, like before.
     Deliberately NOT tied to window "load" for the deferred path: that event also
     waits for third-party scripts (e.g. the Instagram embed below) to finish,
     which can hang for a long time on a slow connection and would drag the
     video's start down with it. requestIdleCallback (with a capped timeout)
     fires once the browser is done with the initial render work, independently
     of any third-party network activity. */
  function isSlowConnection() {
    var conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (!conn) return true; // unknown: play it safe and defer
    if (conn.saveData) return true;
    if (conn.effectiveType && conn.effectiveType.indexOf('2g') !== -1) return true;
    if (conn.effectiveType === '3g') return true;
    return false;
  }
  function startVideo(id) {
    var video = document.getElementById(id);
    if (!video) return;
    var source = video.querySelector('source[data-src]');
    if (source) {
      source.src = source.getAttribute('data-src');
      video.load();
    }
    video.play().catch(function () {});
  }
  function whenIdle(fn) {
    if ('requestIdleCallback' in window) {
      requestIdleCallback(fn, { timeout: 1200 });
    } else {
      setTimeout(fn, 200);
    }
  }
  var deferVideos = isSlowConnection();
  function playVideo(id) {
    if (deferVideos) {
      whenIdle(function () { startVideo(id); });
    } else {
      startVideo(id);
    }
  }

  playVideo('gate-video');
  if (gateWasOpen) {
    document.addEventListener('mi:visitor-chosen', function () { playVideo('hero-video'); }, { once: true });
  } else {
    playVideo('hero-video');
  }

  /* Floating quote CTA — appears once the hero is scrolled past */
  var floatingCta = document.getElementById('floating-quote-cta');
  var heroSection = document.getElementById('hero');
  if (floatingCta && heroSection && 'IntersectionObserver' in window) {
    var heroObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        floatingCta.classList.toggle('is-visible', !entry.isIntersecting);
      });
    }, { threshold: 0 });
    heroObserver.observe(heroSection);
  }

  /* Réalisations gallery arrows */
  var galleryScroll = document.getElementById('gallery-grid');
  var galleryPrev = document.getElementById('gallery-prev');
  var galleryNext = document.getElementById('gallery-next');
  if (galleryScroll && galleryPrev && galleryNext) {
    var scrollByCard = function (direction) {
      var card = galleryScroll.querySelector('.gallery-card');
      var step = card ? card.getBoundingClientRect().width + 22 : galleryScroll.clientWidth * 0.8;
      galleryScroll.scrollBy({ left: direction * step, behavior: 'smooth' });
    };
    galleryPrev.addEventListener('click', function () { scrollByCard(-1); });
    galleryNext.addEventListener('click', function () { scrollByCard(1); });

    var updateGalleryArrows = function () {
      var maxScroll = galleryScroll.scrollWidth - galleryScroll.clientWidth - 2;
      galleryPrev.classList.toggle('is-disabled', galleryScroll.scrollLeft <= 0);
      galleryNext.classList.toggle('is-disabled', galleryScroll.scrollLeft >= maxScroll);
    };
    galleryScroll.addEventListener('scroll', updateGalleryArrows, { passive: true });
    updateGalleryArrows();
  }

  /* Hero stat rotator — there are now two (particulier / professionnel), only one
     of which is ever visible at a time (see .audience-particulier-block /
     .audience-pro-block in css/style.css), so each is animated independently. */
  var rotators = document.querySelectorAll('.hero-stat-rotator');
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  rotators.forEach(function (rotator) {
    var rotatorItems = rotator.querySelectorAll('.hero-stat-item');
    if (rotatorItems.length > 1 && !reduceMotion) {
      var rotatorIndex = 0;
      setInterval(function () {
        rotatorItems[rotatorIndex].classList.remove('is-active');
        rotatorIndex = (rotatorIndex + 1) % rotatorItems.length;
        rotatorItems[rotatorIndex].classList.add('is-active');
      }, 2800);
    }
  });

  /* Mobile nav toggle */
  var navToggle = document.getElementById('nav-toggle');
  var mainNav = document.getElementById('main-nav');
  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function () {
      var isOpen = mainNav.classList.toggle('is-open');
      navToggle.classList.toggle('is-active', isOpen);
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    mainNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mainNav.classList.remove('is-open');
        navToggle.classList.remove('is-active');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* FAQ accordion */
  var triggers = document.querySelectorAll('.accordion-trigger');
  triggers.forEach(function (trigger) {
    var panel = trigger.nextElementSibling;
    trigger.addEventListener('click', function () {
      var isOpen = trigger.getAttribute('aria-expanded') === 'true';
      triggers.forEach(function (t) {
        t.setAttribute('aria-expanded', 'false');
        t.nextElementSibling.style.maxHeight = null;
      });
      if (!isOpen) {
        trigger.setAttribute('aria-expanded', 'true');
        panel.style.maxHeight = panel.scrollHeight + 'px';
      }
    });
  });

  /* Sticky header shadow on scroll */
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      header.style.boxShadow = window.scrollY > 8 ? '0 4px 16px rgba(18,33,59,0.08)' : 'none';
    }, { passive: true });
  }

  /* Contact form handling — sends the lead via Web3Forms to Gmail + the Odoo CRM alias.
     Get your two free access keys at https://web3forms.com (one per destination email)
     and paste them below. Until both are filled in, the form falls back to a local-only
     success message so nothing breaks.
     On success, the visitor is redirected to a dedicated "merci" page instead of just
     swapping in an inline message — that gives Google Ads a real URL to watch for
     ("quelqu'un visite cette page" conversion action), which is far more reliable than
     relying on a JS event firing before the tab closes or the visitor navigates away. */
  var WEB3FORMS_KEY_GMAIL = '5ee65a03-76fe-4a42-8b39-2bf36a4d5cb6';
  var WEB3FORMS_KEY_ODOO = 'c3b2d6a0-ad34-460c-9fff-909fa056c85d';
  var THANK_YOU_URL = '/formulaire/merci.html';

  function wireQuoteForm(form, status) {
    if (!form || !status) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      var keysConfigured = WEB3FORMS_KEY_GMAIL.indexOf('YOUR_') !== 0 && WEB3FORMS_KEY_ODOO.indexOf('YOUR_') !== 0;

      function finish(success) {
        if (success) {
          form.reset();
          window.location.href = THANK_YOU_URL;
          return;
        }
        status.textContent = 'Un souci est survenu, merci de réessayer ou de nous appeler directement.';
        status.className = 'form-status error';
      }

      if (!keysConfigured) {
        finish(true);
        return;
      }

      function submitTo(accessKey) {
        var data = new FormData(form);
        data.set('access_key', accessKey);
        data.set('subject', 'Nouvelle demande de devis — Moderne Isolation');
        return fetch('https://api.web3forms.com/submit', {
          method: 'POST',
          headers: { Accept: 'application/json' },
          body: data
        }).then(function (res) { return res.ok; }).catch(function () { return false; });
      }

      Promise.all([submitTo(WEB3FORMS_KEY_GMAIL), submitTo(WEB3FORMS_KEY_ODOO)]).then(function (results) {
        finish(results.indexOf(true) !== -1);
      });
    });
  }

  wireQuoteForm(document.getElementById('hero-form'), document.getElementById('hero-form-status'));

  /* Devis popup — built in JS and appended on every page (rather than duplicated in
     every .html file) so it's available site-wide: clicking any "Devis gratuit" link
     (header, hero, floating button, footer CTA...) opens it in place instead of
     navigating/scrolling to the bottom-of-page form, on every page of the site. Also
     still shows itself once per visit after a delay or scroll depth, like before. */
  function ensurePopup() {
    var existing = document.getElementById('popup-overlay');
    if (existing) return existing;
    var wrap = document.createElement('div');
    wrap.innerHTML =
      '<div class="popup-overlay" id="popup-overlay" hidden>' +
        '<div class="popup-card" role="dialog" aria-modal="true" aria-labelledby="popup-title">' +
          '<button type="button" class="popup-close" id="popup-close" aria-label="Fermer">' +
            '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>' +
          '</button>' +
          '<p class="eyebrow center">Devis gratuit</p>' +
          '<h3 id="popup-title">Combien coûte votre projet ?</h3>' +
          '<p class="popup-lead">Laissez-nous vos coordonnées, on vous répond sous 48h.</p>' +
          '<form class="hero-form popup-form" id="popup-form" novalidate>' +
            '<div class="form-row">' +
              '<label for="pf-name">Nom complet *</label>' +
              '<input type="text" id="pf-name" name="name" required autocomplete="name">' +
            '</div>' +
            '<div class="form-row">' +
              '<label for="pf-phone">Téléphone *</label>' +
              '<input type="tel" id="pf-phone" name="phone" required autocomplete="tel">' +
            '</div>' +
            '<div class="form-row">' +
              '<label for="pf-email">E-mail *</label>' +
              '<input type="email" id="pf-email" name="email" required autocomplete="email">' +
            '</div>' +
            '<button type="submit" class="btn btn-primary btn-lg btn-block">Obtenir mon devis gratuit →</button>' +
            '<p class="form-privacy-note">🔒 En envoyant, vous acceptez d\'être recontacté(e) à ce sujet.</p>' +
            '<p class="form-status" id="popup-form-status" role="status" aria-live="polite"></p>' +
          '</form>' +
        '</div>' +
      '</div>';
    var el = wrap.firstElementChild;
    document.body.appendChild(el);
    return el;
  }

  var popupOverlay = ensurePopup();
  var popupClose = document.getElementById('popup-close');
  var POPUP_SEEN_KEY = 'mi-popup-seen';

  /* Swaps the pop-up's wording for the "professionnel" audience (see
     applyVisitorType() above) — the pop-up is built in JS, so unlike the static
     hero/reasons/FAQ/bottom-form it can't carry both versions in the markup for
     CSS to toggle; its text is set directly here instead. */
  function updatePopupCopyForAudience(isPro) {
    var eyebrow = popupOverlay.querySelector('.popup-card > .eyebrow');
    var title = document.getElementById('popup-title');
    var lead = popupOverlay.querySelector('.popup-lead');
    var submitLabel = popupOverlay.querySelector('#popup-form button[type="submit"]');
    if (eyebrow) eyebrow.textContent = isPro ? 'Espace professionnels' : 'Devis gratuit';
    if (title) title.textContent = isPro ? 'Parlons de votre prochain chantier' : 'Combien coûte votre projet ?';
    if (lead) lead.textContent = isPro
      ? 'Architectes, maîtres d\'œuvre, promoteurs : laissez-nous vos coordonnées, nous étudions votre cahier des charges sous 48h.'
      : 'Laissez-nous vos coordonnées, on vous répond sous 48h.';
    if (submitLabel) submitLabel.textContent = isPro ? 'Transmettre mon projet →' : 'Obtenir mon devis gratuit →';
  }
  applyVisitorType();

  function openPopup() {
    popupOverlay.hidden = false;
    document.body.style.overflow = 'hidden';
    try { sessionStorage.setItem(POPUP_SEEN_KEY, '1'); } catch (err) {}
    var firstField = document.getElementById('pf-name');
    if (firstField) firstField.focus();
  }

  function closePopup() {
    popupOverlay.hidden = true;
    document.body.style.overflow = '';
  }

  /* Automatic trigger (delay / scroll depth) — only once per visit, and skipped
     entirely if the visitor already opened the popup themselves via a CTA click. */
  var popupAutoTriggered = false;
  function autoOpenPopup() {
    if (popupAutoTriggered) return;
    var alreadySeen = false;
    try { alreadySeen = sessionStorage.getItem(POPUP_SEEN_KEY) === '1'; } catch (err) {}
    if (alreadySeen) return;
    popupAutoTriggered = true;
    openPopup();
  }

  function startPopupTriggers() {
    var popupTimer = setTimeout(autoOpenPopup, 22000);
    window.addEventListener('scroll', function () {
      var scrolled = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
      if (scrolled > 0.55) {
        clearTimeout(popupTimer);
        autoOpenPopup();
      }
    }, { passive: true });
  }

  if (gateWasOpen) {
    document.addEventListener('mi:visitor-chosen', startPopupTriggers, { once: true });
  } else {
    startPopupTriggers();
  }

  if (popupClose) popupClose.addEventListener('click', closePopup);
  popupOverlay.addEventListener('click', function (e) {
    if (e.target === popupOverlay) closePopup();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !popupOverlay.hidden) closePopup();
  });

  wireQuoteForm(document.getElementById('popup-form'), document.getElementById('popup-form-status'));

  /* Every "Devis gratuit" CTA on the site (header, hero, floating button, footer,
     service/blog pages...) points at "#devis" — intercept those clicks everywhere
     and open the popup in place instead of letting the browser jump/navigate to the
     bottom-of-page form (on another page, that previously meant a full navigation
     away before landing on the anchor). Matches regardless of path prefix
     ("#devis", "index.html#devis", "../index.html#devis"...). */
  document.addEventListener('click', function (e) {
    var devisLink = e.target.closest('a[href$="#devis"]');
    if (devisLink) {
      e.preventDefault();
      openPopup();
    }
  });

  /* Scroll reveal for cards/sections */
  var revealTargets = document.querySelectorAll('.testimonial-card, .gallery-card, .stat, .service-tile, .reason-card');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    revealTargets.forEach(function (el) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(16px)';
      el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      observer.observe(el);
    });
  }
});
