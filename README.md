# Moderne Isolation — Site vitrine

Landing page pour **Moderne Isolation**, entreprise de rénovation intérieure et extérieure (isolation, peinture, plâtrerie, maçonnerie) à Aix-en-Provence et dans les Bouches-du-Rhône depuis 2016. Site statique (HTML/CSS/JS, aucune dépendance externe), pensé pour la conversion (devis) et le référencement local (SEO).

Charte graphique (bordeaux/noir) et contenu (services, avis clients, accroche, téléphone) inspirés du site réel de l'entreprise, avec une mise en page retravaillée : formulaire de devis visible dès le hero, sections SEO complètes (FAQ, zone d'intervention, données structurées).

## Structure du site

```
index.html                    Page principale (landing page)
mentions-legales.html         Mentions légales
politique-confidentialite.html Politique de confidentialité / RGPD
css/style.css                 Design system + styles
js/main.js                    Menu mobile, accordéon FAQ, formulaire
robots.txt                    Directives pour les moteurs de recherche
sitemap.xml                   Plan du site pour l'indexation
site.webmanifest              Manifeste PWA (icône, couleurs)
```

## ⚠️ À vérifier / compléter avant mise en ligne

**Déjà réel** (repris de votre site actuel / fourni par vous) : nom, positionnement (rénovation intérieure/extérieure depuis 2016), téléphone `06 35 35 63 45`, e-mail `moderneisolation13@gmail.com`, adresse `520 Chemin du Pas de la Mue, 13170 Les Pennes-Mirabeau`, les 13 services, les avis clients (texte, prénoms, note 5/5 sur 24 avis Google), villes d'intervention, 17 vraies photos de chantiers (`images/realisations/`) utilisées dans la galerie "Réalisations" et les sections détail/CTA, les partenaires réels (Chausson Matériaux, Zolpan, Tollens, Leroy Merlin — logos en couleur à venir), 8 vrais posts/reels Instagram intégrés via le widget officiel dans "Nos chantiers en vidéo" (pour en changer un ou en ajouter : dupliquer un bloc `<div class="instagram-embed"><blockquote class="instagram-media" data-instgrm-permalink="...">` dans `index.html`), les liens Facebook et LinkedIn (footer + section contact + haut du hero), le lien Google Maps vers votre fiche (bouton "Lire nos 24 avis" + section avis), le vrai logo "M" (`images/logo-m.png`, sur fond transparent, utilisé dans le header et le footer de toutes les pages), et la bannière "Le mot du gérant" avec la vraie photo de Monday John (`images/team/monday-john.jpg`) et un texte validé par vous.

**Encore des placeholders à remplacer** :
- **Domaine** : `https://www.moderne-isolation.fr/` dans les balises canonical/OG/JSON-LD/sitemap.xml/robots.txt — à remplacer par votre vrai domaine (ex. moderne-isolation-13.fr) avant mise en ligne
- **SIRET / forme juridique / assureur décennale** dans `mentions-legales.html`
- **Logos partenaires** (section "Ils nous font confiance") : les noms sont réels (Chausson Matériaux, Zolpan, Tollens, Leroy Merlin) mais affichés en texte simple — envoyez-moi leurs logos officiels si vous voulez les afficher en couleur
- **Photos manquantes** : les illustrations du blog restent des emplacements — on regarde ensemble des visuels pour ces articles

## Fonctionnalités

- Header sticky avec menu mobile (hamburger), logo "M"
- Hero avec formulaire de devis rapide visible dès la première page (nom, téléphone, e-mail, type de projet), badge d'avis Google et tampon "Garantie décennale"
- Bandeau CTA "Appelez-nous dès maintenant"
- Section "À propos" + bandeau chiffres clés (2016, +100 clients/an, 5/5, garantie décennale)
- Bandeau de services (13 prestations) façon chips, fidèle au site actuel
- Section détaillée texte + photo sur le savoir-faire (x2)
- Section "Bonnes raisons de choisir notre entreprise" (4 cartes)
- Galerie de réalisations en carrousel horizontal (17 photos)
- Bandeau CTA avec photo ("Contactez-nous pour discuter de votre projet")
- Témoignages clients réels (avatars colorés, note Google agrégée 5/5 sur 24 avis)
- Section partenaires / certifications
- Zone d'intervention avec carte Google Maps intégrée et liste de villes
- FAQ en accordéon (avec données structurées FAQPage)
- Section contact avec coordonnées, horaires et un rappel/CTA vers le formulaire du hero (pas de formulaire dupliqué)
- Bouton d'appel flottant sur mobile
- Footer complet avec liens légaux

## SEO — ce qui a été mis en place

- **Balises meta** : title et description uniques et optimisés, mots-clés locaux (Bouches-du-Rhône, Marseille, Aix-en-Provence, isolation)
- **Open Graph / Twitter Cards** pour un bon rendu au partage sur les réseaux
- **Données structurées JSON-LD** :
  - `HomeAndConstructionBusiness` (nom, adresse, géolocalisation, zone d'intervention, horaires, note moyenne, avis) → éligible aux Rich Results Google (étoiles, fiche locale)
  - `FAQPage` → éligible à l'affichage des questions/réponses directement dans les résultats Google
- **HTML sémantique** : un seul `<h1>`, hiérarchie `<h2>`/`<h3>` cohérente, `<header>`/`<main>`/`<footer>`/`<section>`
- **robots.txt** + **sitemap.xml** pour guider l'indexation
- **Performance** : pas de framework JS lourd, pas de police externe (aucune requête tierce), icônes en SVG inline, chargement de la carte en `lazy`
- **Mobile-first / responsive** : testé de 390px à 1440px
- **Accessibilité** : lien d'évitement, attributs `aria-*`, contrastes suffisants, `alt`/labels sur les champs

## Analytics (Google Analytics 4)

Le site inclut une bannière de consentement cookies (RGPD) et un chargement conditionnel de GA4 dans `js/analytics.js` :

1. Créez une propriété GA4 sur [analytics.google.com](https://analytics.google.com)
2. Remplacez `G-XXXXXXXXXX` dans `js/analytics.js` (variable `GA_MEASUREMENT_ID`) par votre identifiant de mesure
3. GA4 ne se charge qu'après acceptation du bandeau — tant que l'ID n'est pas renseigné, rien ne se charge
4. Chaque envoi réussi du formulaire de devis déclenche un événement `generate_lead` (avec le type de projet choisi), utile pour suivre vos conversions dans GA4

## Prochaines étapes recommandées pour le référencement

1. **Créer/optimiser votre fiche Google Business Profile** (avis Google = premier levier de confiance en local SEO) et lier la vraie note/nombre d'avis dans le JSON-LD.
2. **Héberger le site sur le vrai domaine** et mettre à jour `canonical`, `og:url`, `sitemap.xml`, `robots.txt` en conséquence.
3. **Ajouter de vraies photos** (chantiers, équipe, avant/après) compressées en WebP pour rester rapide.
4. **Créer des pages dédiées par ville ou par service** (ex. `/isolation-combles-aix-en-provence/`) une fois le trafic établi, pour capter davantage de recherches locales longue traîne.
5. **Obtenir des backlinks locaux** : annuaires du bâtiment, Chambre de Métiers, partenaires fournisseurs, presse locale.
6. **Brancher le formulaire de contact à un vrai service d'envoi** (Formspree, Netlify Forms, ou un petit backend) — actuellement il valide côté client mais n'envoie nulle part.
7. **Soumettre le sitemap** dans Google Search Console et Bing Webmaster Tools.
8. **Suivre les Core Web Vitals** via PageSpeed Insights une fois en ligne.

## Développement local

Aucune installation nécessaire, c'est du HTML/CSS/JS pur :

```bash
python3 -m http.server 8000
# puis ouvrir http://localhost:8000
```
