/*
 * Google Analytics 4 — chargé uniquement après consentement (RGPD).
 * 1. Créez une propriété GA4 sur https://analytics.google.com
 * 2. Remplacez GA_MEASUREMENT_ID ci-dessous par votre ID (format G-XXXXXXXXXX)
 */
var GA_MEASUREMENT_ID = 'G-XXXXXXXXXX';
var CONSENT_KEY = 'mi-cookie-consent';

function loadGoogleAnalytics() {
  if (!GA_MEASUREMENT_ID || GA_MEASUREMENT_ID.indexOf('XXXXXXXXXX') !== -1) return;
  if (window.__gaLoaded) return;
  window.__gaLoaded = true;

  var script = document.createElement('script');
  script.async = true;
  script.src = 'https://www.googletagmanager.com/gtag/js?id=' + GA_MEASUREMENT_ID;
  document.head.appendChild(script);

  window.dataLayer = window.dataLayer || [];
  window.gtag = function () { window.dataLayer.push(arguments); };
  window.gtag('js', new Date());
  window.gtag('config', GA_MEASUREMENT_ID, { anonymize_ip: true });
}

/* Événement GA4 à déclencher après un envoi de formulaire réussi (voir main.js) */
function trackLeadSubmitted(projectType) {
  if (typeof window.gtag === 'function') {
    window.gtag('event', 'generate_lead', { project_type: projectType || 'non précisé' });
  }
}
window.trackLeadSubmitted = trackLeadSubmitted;

document.addEventListener('DOMContentLoaded', function () {
  var banner = document.getElementById('cookie-banner');
  if (!banner) return;

  var consent = localStorage.getItem(CONSENT_KEY);
  if (consent === 'granted') {
    loadGoogleAnalytics();
  } else if (consent !== 'denied') {
    banner.hidden = false;
  }

  var acceptBtn = document.getElementById('cookie-accept');
  var declineBtn = document.getElementById('cookie-decline');

  if (acceptBtn) {
    acceptBtn.addEventListener('click', function () {
      localStorage.setItem(CONSENT_KEY, 'granted');
      banner.hidden = true;
      loadGoogleAnalytics();
    });
  }
  if (declineBtn) {
    declineBtn.addEventListener('click', function () {
      localStorage.setItem(CONSENT_KEY, 'denied');
      banner.hidden = true;
    });
  }
});
