document.addEventListener('DOMContentLoaded', function () {

  /* Footer year */
  var yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

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

  /* Avant / Après comparison sliders */
  document.querySelectorAll('[data-compare]').forEach(function (slider) {
    var after = slider.querySelector('.compare-after');
    var line = slider.querySelector('.compare-line');
    var handle = slider.querySelector('.compare-handle');
    var range = slider.querySelector('.compare-range');

    function update(value) {
      after.style.clipPath = 'inset(0 0 0 ' + value + '%)';
      line.style.left = value + '%';
      handle.style.left = value + '%';
    }
    range.addEventListener('input', function () { update(range.value); });
    update(range.value);
  });

  /* Gallery filter */
  var filterTabs = document.querySelectorAll('.filter-tab');
  var galleryCards = document.querySelectorAll('.gallery-card');
  filterTabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      filterTabs.forEach(function (t) {
        t.classList.remove('is-active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('is-active');
      tab.setAttribute('aria-selected', 'true');

      var filter = tab.getAttribute('data-filter');
      galleryCards.forEach(function (card) {
        var match = filter === 'all' || card.getAttribute('data-cat') === filter;
        card.classList.toggle('is-hidden', !match);
      });
    });
  });

  /* Sticky header shadow on scroll */
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      header.style.boxShadow = window.scrollY > 8 ? '0 4px 16px rgba(18,33,59,0.08)' : 'none';
    }, { passive: true });
  }

  /* Contact form handling — submits to WordPress via admin-ajax.php (see inc/ajax-contact.php) */
  var form = document.getElementById('hero-form');
  var status = document.getElementById('hero-form-status');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      if (typeof window.miAjax === 'undefined') {
        status.textContent = 'Merci ! Votre demande a bien été envoyée, nous revenons vers vous sous 48h.';
        status.className = 'form-status success';
        form.reset();
        return;
      }

      var submitBtn = form.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      status.textContent = 'Envoi en cours...';
      status.className = 'form-status';

      var data = new FormData(form);
      data.append('action', 'mi_contact_form');
      data.append('nonce', window.miAjax.nonce);

      fetch(window.miAjax.url, { method: 'POST', body: data })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          status.textContent = json.data.message;
          status.className = 'form-status ' + (json.success ? 'success' : 'error');
          if (json.success) {
            if (typeof window.trackLeadSubmitted === 'function') {
              window.trackLeadSubmitted(form.project.value);
            }
            form.reset();
          }
        })
        .catch(function () {
          status.textContent = "Une erreur est survenue. Merci de nous appeler directement.";
          status.className = 'form-status error';
        })
        .finally(function () { submitBtn.disabled = false; });
    });
  }

  /* Scroll reveal for cards/sections */
  var revealTargets = document.querySelectorAll('.testimonial-card, .gallery-card, .stat, .chip, .reason-card, .compare-slider');
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
