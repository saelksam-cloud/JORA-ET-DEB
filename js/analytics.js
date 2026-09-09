/*
 * Google Analytics 4 + Google Ads — chargés uniquement après consentement (RGPD).
 * 1. GA4 : créez une propriété sur https://analytics.google.com, remplacez GA_MEASUREMENT_ID (format G-XXXXXXXXXX)
 * 2. Google Ads : dans votre compte https://ads.google.com > Outils > Conversions > Nouvelle action de conversion
 *    ("Demande de devis" / type Site web), copiez l'ID (format AW-XXXXXXXXX) et l'étiquette de conversion,
 *    remplacez GOOGLE_ADS_ID et GOOGLE_ADS_CONVERSION_LABEL ci-dessous.
 */
var GA_MEASUREMENT_ID = 'G-BQYG8JDEFX';
var GOOGLE_ADS_ID = 'AW-18346441837';
var GOOGLE_ADS_CONVERSION_LABEL = 'S7p8COCZ6NUcEO34oaxE';
var CONSENT_KEY = 'mi-cookie-consent';

function loadGoogleTags() {
  var hasGA = GA_MEASUREMENT_ID && GA_MEASUREMENT_ID.indexOf('XXXXXXXXXX') === -1;
  var hasAds = GOOGLE_ADS_ID && GOOGLE_ADS_ID.indexOf('XXXXXXXXX') === -1;
  if (!hasGA && !hasAds) return;
  if (window.__gaLoaded) return;
  window.__gaLoaded = true;

  var script = document.createElement('script');
  script.async = true;
  script.src = 'https://www.googletagmanager.com/gtag/js?id=' + (hasGA ? GA_MEASUREMENT_ID : GOOGLE_ADS_ID);
  document.head.appendChild(script);

  window.dataLayer = window.dataLayer || [];
  window.gtag = function () { window.dataLayer.push(arguments); };
  window.gtag('js', new Date());
  if (hasGA) window.gtag('config', GA_MEASUREMENT_ID, { anonymize_ip: true });
  if (hasAds) window.gtag('config', GOOGLE_ADS_ID);
}

/* Événement déclenché à chaque signal de contact (voir main.js) : envoi du formulaire,
   mais aussi clic sur un lien "Appeler" ou "WhatsApp" — sinon ces contacts-là ne
   remontent jamais comme conversion côté Google Ads. Envoie un événement GA4
   "generate_lead" (avec la source du contact) + une conversion Google Ads si configurée. */
function trackLeadSubmitted(source) {
  if (typeof window.gtag !== 'function') return;
  window.gtag('event', 'generate_lead', { lead_source: source || 'form' });

  var hasAds = GOOGLE_ADS_ID && GOOGLE_ADS_ID.indexOf('XXXXXXXXX') === -1;
  var hasLabel = GOOGLE_ADS_CONVERSION_LABEL && GOOGLE_ADS_CONVERSION_LABEL.indexOf('XXXXXXXXXX') === -1;
  if (hasAds && hasLabel) {
    window.gtag('event', 'conversion', { send_to: GOOGLE_ADS_ID + '/' + GOOGLE_ADS_CONVERSION_LABEL });
  }
}
window.trackLeadSubmitted = trackLeadSubmitted;

document.addEventListener('DOMContentLoaded', function () {
  var banner = document.getElementById('cookie-banner');
  if (!banner) return;

  var consent = localStorage.getItem(CONSENT_KEY);
  if (consent === 'granted') {
    loadGoogleTags();
  } else if (consent !== 'denied') {
    /* Don't show the cookie banner on top of the mandatory visitor gate
       (particulier/professionnel) — wait until it's been dismissed. */
    var gate = document.getElementById('visitor-gate');
    if (gate && !gate.hidden) {
      document.addEventListener('mi:visitor-chosen', function () { banner.hidden = false; }, { once: true });
    } else {
      banner.hidden = false;
    }
  }

  var acceptBtn = document.getElementById('cookie-accept');
  var declineBtn = document.getElementById('cookie-decline');

  if (acceptBtn) {
    acceptBtn.addEventListener('click', function () {
      localStorage.setItem(CONSENT_KEY, 'granted');
      banner.hidden = true;
      loadGoogleTags();
    });
  }
  if (declineBtn) {
    declineBtn.addEventListener('click', function () {
      localStorage.setItem(CONSENT_KEY, 'denied');
      banner.hidden = true;
    });
  }
});
