document.addEventListener('DOMContentLoaded', function () {

  /* Footer year */
  var yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

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

  var form = document.getElementById('hero-form');
  var status = document.getElementById('hero-form-status');
  if (form) {
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
            window.trackLeadSubmitted(form.project.value);
          }
          form.reset();
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
