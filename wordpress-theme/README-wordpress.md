# Thème WordPress — Moderne Isolation

Thème sur mesure convertissant fidèlement la landing page statique en site WordPress, avec tout le contenu éditable depuis l'admin (pas besoin de toucher au code pour changer un texte, ajouter un service ou une photo).

## Installation

**Prérequis** : hébergement WordPress avec PHP 7.4+ et MySQL/MariaDB (les hébergeurs mutualisés français comme OVH, o2switch, Hostinger conviennent).

1. Installez WordPress chez votre hébergeur (souvent en 1 clic depuis leur panneau d'administration)
2. Dans l'admin WordPress : **Apparence > Thèmes > Ajouter un thème > Téléverser un thème**
3. Sélectionnez `moderne-isolation.zip` (fourni à côté de ce fichier) et cliquez sur **Installer**, puis **Activer**
4. Alternative par FTP : décompressez le zip et copiez le dossier `moderne-isolation` dans `wp-content/themes/` de votre hébergement, puis activez-le dans Apparence > Thèmes

## Configuration après activation

### 1. Réglages généraux
Allez dans **Réglages > Moderne Isolation** et renseignez : téléphone, e-mail, adresse, SIRET, horaires, année de création, note/nombre d'avis Google, liens réseaux sociaux, liste des partenaires, et l'ID Google Analytics 4 (`G-XXXXXXXXXX`) si vous en avez un.

### 2. Menu de navigation
Un menu par défaut est déjà créé et assigné. Pour le modifier : **Apparence > Menus**.

### 3. Contenu éditable (menus dans la barre latérale)
- **Services** : vos prestations, chacune avec un emoji (icône)
- **Témoignages** : vos avis clients (titre = nom du client, contenu = texte de l'avis, + note et ancienneté dans l'encadré à droite)
- **Réalisations** : vos chantiers, avec catégorie et photo à la une
- **Avant / Après** : vos comparatifs avant/après (2 photos par fiche, via le sélecteur de médias)
- **FAQ** : vos questions/réponses

Le contenu de démonstration (13 services, 9 témoignages réels, 6 réalisations, 3 avant/après, 6 questions FAQ) a été pré-rempli à partir de votre site actuel — modifiez ou remplacez-le librement.

### 4. Pages légales
Les pages **Mentions légales** et **Politique de confidentialité** sont des pages WordPress classiques, éditables normalement dans **Pages**.

### 5. Formulaire de devis → e-mail
Le formulaire du hero envoie déjà un e-mail via `wp_mail()` à l'adresse renseignée dans Réglages > Moderne Isolation. **Sur beaucoup d'hébergements mutualisés, la fonction mail() native de PHP est peu fiable** (mails filtrés en spam ou non envoyés). Il est recommandé d'installer le plugin gratuit **WP Mail SMTP** et de le configurer avec votre adresse e-mail (Gmail, Outlook, ou celle de votre hébergeur) pour un envoi fiable.

## Ce qui a été repris du site statique

Toute la mise en page, la charte graphique (bordeaux/noir), le slider avant/après interactif, la bannière de consentement cookies RGPD + Google Analytics 4, le SEO (données structurées JSON-LD générées dynamiquement depuis vos avis/FAQ, meta description) sont identiques à la version testée précédemment — seul le contenu est maintenant piloté depuis l'admin WordPress au lieu d'être écrit en dur dans le HTML.

## Limites actuelles / pistes d'amélioration

- Les photos sont encore des emplacements à remplacer (chargez vos vraies photos via les fiches "Réalisations" et "Avant / Après")
- Pas de page dédiée pour "Actualités" (blog) — les articles WordPress standards (Pages > Articles) fonctionneront avec le thème par défaut (`index.php`), mais un template dédié plus soigné peut être ajouté si vous voulez développer cette rubrique
- Le design a été testé en local (PHP + MariaDB) dans cet environnement ; une vérification rapide sur votre hébergement réel après mise en ligne est recommandée
