# Moderne Isolation — Site vitrine

Landing page pour **Moderne Isolation**, entreprise d'isolation thermique et de rénovation BTP dans les Bouches-du-Rhône. Site statique (HTML/CSS/JS, aucune dépendance externe), pensé pour la conversion (devis) et le référencement local (SEO).

## Structure du site

```
index.html                    Page principale (landing page)
mentions-legales.html         Mentions légales
politique-confidentialite.html Politique de confidentialité / RGPD
css/style.css                 Design system + styles
js/main.js                    Menu mobile, accordéon FAQ, filtre galerie, formulaire
robots.txt                    Directives pour les moteurs de recherche
sitemap.xml                   Plan du site pour l'indexation
site.webmanifest              Manifeste PWA (icône, couleurs)
```

## ⚠️ À personnaliser avant mise en ligne (obligatoire)

Toutes les coordonnées ci-dessous sont des **placeholders fictifs** à remplacer :

- **Téléphone** : `04 42 00 00 00` (recherchez/remplacez `+33442000000` et `04 42 00 00 00` dans tous les fichiers)
- **E-mail** : `contact@moderne-isolation.fr`
- **Adresse** : `12 Avenue des Artisans, 13000 Marseille`
- **Domaine** : `https://www.moderne-isolation.fr/` (balises canonical, Open Graph, JSON-LD, sitemap.xml, robots.txt)
- **SIRET / infos légales** dans `mentions-legales.html`
- **Logos partenaires** (section "Ils nous font confiance") : logos d'exemple (Point.P, Leroy Merlin Pro, SOCOTEC, Qualibat, Saint-Gobain, MaPrimeRénov') à remplacer par vos vrais partenaires
- **Témoignages clients** : avis fictifs à remplacer par de vrais avis (idéalement copiés depuis votre fiche Google Business Profile, avec autorisation des clients)
- **Photos de chantiers** : la section Réalisations utilise des blocs colorés en attendant vos vraies photos avant/après
- **Réseaux sociaux** : liens `#` à remplacer par vos vraies pages (Facebook, Instagram, LinkedIn)

## Fonctionnalités

- Header sticky avec menu mobile (hamburger)
- Hero avec formulaire de devis rapide visible dès la première page (nom, téléphone, e-mail, type de projet) + CTA d'appel direct — pas besoin de scroller pour laisser ses coordonnées
- Bandeau de chiffres clés (chantiers, expérience, satisfaction, note)
- 6 services avec icônes
- Section réassurance ("Pourquoi nous choisir")
- Galerie de réalisations filtrable par catégorie
- Témoignages clients + note Google agrégée
- Section partenaires / certifications
- Zone d'intervention avec carte Google Maps intégrée et liste de villes
- FAQ en accordéon (avec données structurées FAQPage)
- Section contact avec coordonnées, horaires et un rappel/CTA vers le formulaire du hero (pas de formulaire dupliqué)
- Bouton d'appel flottant sur mobile
- Footer complet avec liens légaux

## SEO — ce qui a été mis en place

- **Balises meta** : title et description uniques et optimisés, mots-clés locaux (Bouches-du-Rhône, Marseille, Aix-en-Provence, isolation, RGE Qualibat)
- **Open Graph / Twitter Cards** pour un bon rendu au partage sur les réseaux
- **Données structurées JSON-LD** :
  - `HomeAndConstructionBusiness` (nom, adresse, géolocalisation, zone d'intervention, horaires, note moyenne, avis) → éligible aux Rich Results Google (étoiles, fiche locale)
  - `FAQPage` → éligible à l'affichage des questions/réponses directement dans les résultats Google
- **HTML sémantique** : un seul `<h1>`, hiérarchie `<h2>`/`<h3>` cohérente, `<header>`/`<main>`/`<footer>`/`<section>`
- **robots.txt** + **sitemap.xml** pour guider l'indexation
- **Performance** : pas de framework JS lourd, pas de police externe (aucune requête tierce), icônes en SVG inline, chargement de la carte en `lazy`
- **Mobile-first / responsive** : testé de 390px à 1440px
- **Accessibilité** : lien d'évitement, attributs `aria-*`, contrastes suffisants, `alt`/labels sur les champs

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
