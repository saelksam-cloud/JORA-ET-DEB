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
     load; both choices lead to the same site for now, this just remembers the pick
     (for a future dedicated B2B experience) and unlocks the page. */
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

  /* Hero stat rotator */
  var rotator = document.getElementById('hero-stat-rotator');
  if (rotator) {
    var rotatorItems = rotator.querySelectorAll('.hero-stat-item');
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (rotatorItems.length > 1 && !reduceMotion) {
      var rotatorIndex = 0;
      setInterval(function () {
        rotatorItems[rotatorIndex].classList.remove('is-active');
        rotatorIndex = (rotatorIndex + 1) % rotatorItems.length;
        rotatorItems[rotatorIndex].classList.add('is-active');
      }, 2800);
    }
  }

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
     success message so nothing breaks. */
  var WEB3FORMS_KEY_GMAIL = '5ee65a03-76fe-4a42-8b39-2bf36a4d5cb6';
  var WEB3FORMS_KEY_ODOO = 'c3b2d6a0-ad34-460c-9fff-909fa056c85d';

  function wireQuoteForm(form, status, onSuccess) {
    if (!form || !status) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      var keysConfigured = WEB3FORMS_KEY_GMAIL.indexOf('YOUR_') !== 0 && WEB3FORMS_KEY_ODOO.indexOf('YOUR_') !== 0;

      function finish(success) {
        status.textContent = success
          ? 'Merci ! Votre demande a bien été envoyée, nous revenons vers vous sous 48h.'
          : 'Un souci est survenu, merci de réessayer ou de nous appeler directement.';
        status.className = 'form-status ' + (success ? 'success' : 'error');
        if (success) {
          if (typeof window.trackLeadSubmitted === 'function') {
            window.trackLeadSubmitted();
          }
          form.reset();
          if (typeof onSuccess === 'function') onSuccess();
        }
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

  /* Capture pop-up — a short duplicate form shown once per visit, after a delay or scroll depth */
  var popupOverlay = document.getElementById('popup-overlay');
  var popupClose = document.getElementById('popup-close');
  var POPUP_SEEN_KEY = 'mi-popup-seen';

  if (popupOverlay) {
    var popupShown = false;

    function showPopup() {
      if (popupShown) return;
      var alreadySeen = false;
      try { alreadySeen = sessionStorage.getItem(POPUP_SEEN_KEY) === '1'; } catch (err) {}
      if (alreadySeen) return;
      popupShown = true;
      popupOverlay.hidden = false;
      document.body.style.overflow = 'hidden';
      try { sessionStorage.setItem(POPUP_SEEN_KEY, '1'); } catch (err) {}
    }

    function hidePopup() {
      popupOverlay.hidden = true;
      document.body.style.overflow = '';
    }

    function startPopupTriggers() {
      var popupTimer = setTimeout(showPopup, 22000);
      window.addEventListener('scroll', function () {
        var scrolled = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
        if (scrolled > 0.55) {
          clearTimeout(popupTimer);
          showPopup();
        }
      }, { passive: true });
    }

    if (gateWasOpen) {
      document.addEventListener('mi:visitor-chosen', startPopupTriggers, { once: true });
    } else {
      startPopupTriggers();
    }

    if (popupClose) popupClose.addEventListener('click', hidePopup);
    popupOverlay.addEventListener('click', function (e) {
      if (e.target === popupOverlay) hidePopup();
    });

    wireQuoteForm(document.getElementById('popup-form'), document.getElementById('popup-form-status'), function () {
      setTimeout(hidePopup, 1800);
    });
  }

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
