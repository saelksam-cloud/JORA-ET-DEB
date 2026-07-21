<?php
/**
 * Front page — assembles all homepage sections from theme options and CPTs.
 */
get_header();

$phone_tel = mi_option( 'phone_tel' );
$phone     = mi_option( 'phone' );
?>
<main id="main">

  <!-- ===== HERO ===== -->
  <section class="hero" id="hero">
    <div class="container hero-inner">
      <div class="hero-content">
        <p class="eyebrow">Rénovation intérieure sur mesure depuis <?php echo esc_html( mi_option( 'since_year', '2016' ) ); ?></p>
        <h1>Rénovation, isolation, peinture et <span class="text-accent">maçonnerie</span></h1>
        <p class="hero-lead">Spécialisée dans la modernisation de maisons et d'appartements, notre entreprise intervient à Aix-en-Provence et dans toutes les <strong>Bouches-du-Rhône</strong>.</p>
        <p class="hero-stat">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="#FBC02D" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l7.1-1.01z"/></svg>
          <?php echo esc_html( mi_option( 'stat_clients', '+100' ) ); ?> clients accompagnés par an
        </p>
        <div class="hero-cta">
          <a href="tel:<?php echo esc_attr( $phone_tel ); ?>" class="btn btn-primary btn-lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <?php echo esc_html( $phone ); ?>
          </a>
          <a href="#devis" class="btn btn-outline btn-lg">Contactez-nous</a>
        </div>

        <div class="hero-trust-row">
          <div class="google-badge">
            <svg width="22" height="22" viewBox="0 0 48 48" aria-hidden="true"><path fill="#4285F4" d="M45.12 24.5c0-1.56-.14-3.06-.4-4.5H24v8.51h11.84c-.51 2.75-2.06 5.08-4.39 6.64v5.52h7.11c4.16-3.83 6.56-9.47 6.56-16.17z"/><path fill="#34A853" d="M24 46c5.94 0 10.92-1.97 14.56-5.33l-7.11-5.52c-1.97 1.32-4.49 2.1-7.45 2.1-5.73 0-10.58-3.87-12.31-9.07H4.34v5.7C7.96 41.07 15.4 46 24 46z"/><path fill="#FBBC05" d="M11.69 28.18C11.25 26.86 11 25.45 11 24s.25-2.86.69-4.18v-5.7H4.34C2.85 17.09 2 20.45 2 24s.85 6.91 2.34 9.88z"/><path fill="#EA4335" d="M24 10.75c3.23 0 6.13 1.11 8.41 3.29l6.31-6.31C34.91 4.18 29.93 2 24 2 15.4 2 7.96 6.93 4.34 14.12l7.35 5.7c1.73-5.2 6.58-9.07 12.31-9.07z"/></svg>
            <div><span class="g-stars">★★★★★ <strong><?php echo esc_html( mi_option( 'google_rating', '5' ) ); ?>/5</strong></span><br><a href="#avis">Lire nos <?php echo esc_html( mi_option( 'google_reviews_count', '24' ) ); ?> avis</a></div>
          </div>

          <div class="stamp-badge">
            <svg viewBox="0 0 120 120" width="78" height="78" aria-hidden="true">
              <circle cx="60" cy="60" r="56" fill="none" stroke="#A31E2B" stroke-width="1.5" stroke-dasharray="2.5 3"/>
              <circle cx="60" cy="60" r="47" fill="none" stroke="#A31E2B" stroke-width="1.2"/>
              <path id="stampTop" d="M 14,60 A 46,46 0 1,1 106,60" fill="none"/>
              <path id="stampBottom" d="M 106,62 A 46,46 0 1,1 14,62" fill="none"/>
              <text font-size="9.5" font-weight="700" fill="#A31E2B" letter-spacing="1.6"><textPath href="#stampTop" startOffset="50%" text-anchor="middle">GARANTIE</textPath></text>
              <text font-size="9.5" font-weight="700" fill="#A31E2B" letter-spacing="1.6"><textPath href="#stampBottom" startOffset="50%" text-anchor="middle">DÉCENNALE</textPath></text>
              <path d="M48 60l8 8 16-17" fill="none" stroke="#A31E2B" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="hero-form-wrap" id="devis">
        <form class="hero-form" id="hero-form" novalidate>
          <p class="hero-form-title">Votre devis gratuit en 1 minute</p>
          <p class="hero-form-subtitle">Sans engagement — réponse sous 48h</p>

          <div class="form-row">
            <label for="hf-name">Nom complet *</label>
            <input type="text" id="hf-name" name="name" required autocomplete="name">
          </div>
          <div class="form-row form-row-2">
            <div>
              <label for="hf-phone">Téléphone *</label>
              <input type="tel" id="hf-phone" name="phone" required autocomplete="tel">
            </div>
            <div>
              <label for="hf-email">E-mail *</label>
              <input type="email" id="hf-email" name="email" required autocomplete="email">
            </div>
          </div>
          <div class="form-row">
            <label for="hf-project">Type de projet</label>
            <select id="hf-project" name="project">
              <option>Isolation</option>
              <option>Peinture</option>
              <option>Plâtrerie / Placo</option>
              <option>Maçonnerie</option>
              <option>Rénovation de maison</option>
              <option>Aménagement intérieur / extérieur</option>
              <option>Autre</option>
            </select>
          </div>
          <div class="form-row form-check">
            <input type="checkbox" id="hf-rgpd" name="rgpd" required>
            <label for="hf-rgpd">J'accepte d'être recontacté(e) au sujet de ma demande. *</label>
          </div>
          <button type="submit" class="btn btn-primary btn-lg btn-block">Recevoir mon devis gratuit</button>
          <p class="form-status" id="hero-form-status" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>
  </section>

  <!-- ===== STATS BAR ===== -->
  <section class="stats-bar" aria-label="Chiffres clés">
    <div class="container stats-grid">
      <div class="stat"><span class="stat-num"><?php echo esc_html( mi_option( 'since_year', '2016' ) ); ?></span><span class="stat-label">Année de création</span></div>
      <div class="stat"><span class="stat-num"><?php echo esc_html( mi_option( 'stat_clients', '+100' ) ); ?></span><span class="stat-label">Clients accompagnés par an</span></div>
      <div class="stat"><span class="stat-num"><?php echo esc_html( mi_option( 'google_rating', '5' ) ); ?>/5</span><span class="stat-label">Note sur <?php echo esc_html( mi_option( 'google_reviews_count', '24' ) ); ?> avis Google</span></div>
      <div class="stat"><span class="stat-num">10 ans</span><span class="stat-label">Garantie décennale</span></div>
    </div>
  </section>

  <!-- ===== MID CTA BAND ===== -->
  <section class="cta-banner">
    <div class="container cta-banner-inner">
      <div>
        <h2>Appelez-nous dès maintenant.</h2>
        <p>Nos devis sont gratuits.</p>
      </div>
      <div class="hero-cta">
        <a href="tel:<?php echo esc_attr( $phone_tel ); ?>" class="btn btn-outline-light btn-lg"><?php echo esc_html( $phone ); ?></a>
        <a href="#devis" class="btn btn-primary btn-lg">Contactez-nous</a>
      </div>
    </div>
  </section>

  <!-- ===== À PROPOS ===== -->
  <section class="section" id="apropos">
    <div class="container about-text">
      <h2 class="section-title">Entreprise de rénovation intérieure à Aix-en-Provence</h2>
      <p>Moderne Isolation est spécialisée dans plusieurs travaux de rénovation intérieure et extérieure. Nous mettons notre savoir-faire au service de vos projets pour transformer votre maison ou appartement, du sol au plafond — à Aix-en-Provence, Marseille et dans tout le département des Bouches-du-Rhône.</p>
    </div>
  </section>

  <!-- ===== SERVICES ===== -->
  <section class="services-band" id="services">
    <div class="container">
      <p class="eyebrow center">Nos savoir-faire</p>
      <h2 class="section-title center">Nos services</h2>
      <p class="section-lead center">Nous sommes un interlocuteur capable de s'occuper de A à Z de vos projets de construction et de rénovation. Si certains domaines d'activité ne sont pas listés ici, nous pouvons nous appuyer sur notre réseau de confiance pour les autres corps de métier.</p>

      <div class="chip-grid">
        <?php
        $services = new WP_Query( array( 'post_type' => 'mi_service', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        while ( $services->have_posts() ) : $services->the_post();
          $emoji = get_post_meta( get_the_ID(), 'mi_emoji', true );
          ?>
          <span class="chip"><span class="chip-emoji"><?php echo esc_html( $emoji ); ?></span> <?php the_title(); ?></span>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <!-- ===== DETAIL SECTION ===== -->
  <section class="section">
    <div class="container detail-grid">
      <div class="detail-content">
        <h2>Solutions de rénovation sur mesure à <span class="text-accent">Aix-en-Provence</span></h2>
        <p>Depuis <?php echo esc_html( mi_option( 'since_year', '2016' ) ); ?>, <strong>notre mission est de donner vie à vos projets de rénovation</strong>. Nous proposons un large éventail de services pour rafraîchir ou transformer intégralement votre logement. Notre équipe prend en charge vos travaux de peinture, redonnant éclat et modernité à vos murs intérieurs.</p>
        <p>Nous sommes également spécialisés dans les petits travaux de maçonnerie, la pose de cloisons et de faux plafonds en placo. Que vous souhaitiez réaménager une pièce ou rénover plusieurs parties de votre bâtiment, nous vous apportons des solutions adaptées. Grâce à la parfaite maîtrise de plusieurs techniques d'isolation, nous proposons plusieurs solutions pour optimiser votre confort thermique.</p>
        <p><strong>Notre savoir-faire nous permet de garantir des finitions soignées pour chaque prestation.</strong> Nous intervenons à Aix-en-Provence, Marseille, et dans tout le département des Bouches-du-Rhône.</p>
      </div>
      <div class="detail-image">
        <div class="photo-placeholder">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
          <span>Photo de chantier à venir</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== AVANT / APRÈS ===== -->
  <section class="section" id="avant-apres">
    <div class="container">
      <p class="eyebrow center">Nos projets</p>
      <h2 class="section-title center">Les photos de nos projets <span class="text-accent">parlent d'elles-mêmes</span></h2>
      <p class="section-lead center">Faites glisser le curseur pour comparer l'avant et l'après.</p>

      <div class="compare-grid">
        <?php
        $compares = new WP_Query( array( 'post_type' => 'mi_avant_apres', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        while ( $compares->have_posts() ) : $compares->the_post();
          $avant_id = get_post_meta( get_the_ID(), 'mi_avant_image_id', true );
          $apres_id = get_post_meta( get_the_ID(), 'mi_apres_image_id', true );
          ?>
          <figure class="compare-figure">
            <div class="compare-slider" data-compare>
              <div class="compare-before">
                <?php if ( $avant_id ) : ?>
                  <?php echo wp_get_attachment_image( $avant_id, 'large', false, array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
                <?php else : ?>
                  <div class="photo-placeholder icon-only"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>
                <?php endif; ?>
              </div>
              <div class="compare-after">
                <?php if ( $apres_id ) : ?>
                  <?php echo wp_get_attachment_image( $apres_id, 'large', false, array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
                <?php else : ?>
                  <div class="photo-placeholder icon-only"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>
                <?php endif; ?>
              </div>
              <span class="compare-line" aria-hidden="true"></span>
              <span class="compare-label compare-label-before">Avant</span>
              <span class="compare-label compare-label-after">Après</span>
              <div class="compare-handle" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7l-5 5 5 5M16 7l5 5-5 5"/></svg></div>
              <input type="range" class="compare-range" min="0" max="100" value="50" aria-label="Comparer avant et après — <?php the_title_attribute(); ?>">
            </div>
            <figcaption><?php the_title(); ?></figcaption>
          </figure>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <p class="gallery-note">📷 Ajoutez vos propres photos avant/après depuis le menu "Avant / Après" de l'admin.</p>
    </div>
  </section>

  <!-- ===== BONNES RAISONS ===== -->
  <section class="section section-alt" id="pourquoi">
    <div class="container">
      <p class="eyebrow center">Confiance &amp; qualité</p>
      <h2 class="section-title center">Bonnes raisons de <span class="text-accent">choisir</span> notre entreprise</h2>
      <p class="section-lead center">L'équipe de <strong>Moderne isolation</strong> vous fait bénéficier de plusieurs atouts :</p>

      <div class="reasons-grid">
        <div class="reason-card">
          <div class="reason-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20c0-3.5 2.5-6 5.5-6s5.5 2.5 5.5 6"/><path d="M17.5 5.5l1 2 2.2.3-1.6 1.5.4 2.2-2-1-2 1 .4-2.2-1.6-1.5 2.2-.3z"/></svg></div>
          <h3>Solide savoir-faire</h3>
          <p>Une expertise acquise depuis <?php echo esc_html( mi_option( 'since_year', '2016' ) ); ?> dans tous les corps de métier.</p>
        </div>
        <div class="reason-card">
          <div class="reason-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="5"/><path d="M8.5 12.5L6 21l6-3 6 3-2.5-8.5"/></svg></div>
          <h3>Équipe qualifiée</h3>
          <p>Des artisans passionnés et compétents pour un résultat impeccable.</p>
        </div>
        <div class="reason-card">
          <div class="reason-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 21l3.5-1 11-11-2.5-2.5-11 11z"/><path d="M14.5 4.5L18 3l3 3-1.5 3.5"/></svg></div>
          <h3>Solutions personnalisées</h3>
          <p>Des prestations entièrement sur mesure, adaptées à vos envies et à votre budget.</p>
        </div>
        <div class="reason-card">
          <div class="reason-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20c0-3.5 2.5-6 5.5-6s5.5 2.5 5.5 6"/><path d="M15.5 9.5l2 2 4-4"/></svg></div>
          <h3>Accompagnement de A à Z</h3>
          <p>Un interlocuteur unique pour un suivi complet de votre projet.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== REALISATIONS ===== -->
  <section class="section" id="realisations">
    <div class="container">
      <p class="eyebrow center">Nos chantiers</p>
      <h2 class="section-title center">Nos réalisations</h2>
      <p class="section-lead center">Un aperçu de nos derniers chantiers de rénovation et d'isolation dans les Bouches-du-Rhône.</p>

      <?php $cats = get_terms( array( 'taxonomy' => 'mi_realisation_cat', 'hide_empty' => true ) ); ?>
      <?php if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) : ?>
      <div class="filter-tabs" role="tablist" aria-label="Filtrer les réalisations">
        <button class="filter-tab is-active" data-filter="all" role="tab" aria-selected="true">Tout</button>
        <?php foreach ( $cats as $cat ) : ?>
          <button class="filter-tab" data-filter="<?php echo esc_attr( $cat->slug ); ?>" role="tab" aria-selected="false"><?php echo esc_html( $cat->name ); ?></button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="grid grid-3 gallery-grid" id="gallery-grid">
        <?php
        $thumb_variants = array( 'thumb-1', 'thumb-2', 'thumb-3', 'thumb-4', 'thumb-5', 'thumb-6' );
        $i = 0;
        $realisations = new WP_Query( array( 'post_type' => 'mi_realisation', 'posts_per_page' => -1 ) );
        while ( $realisations->have_posts() ) : $realisations->the_post();
          $terms   = get_the_terms( get_the_ID(), 'mi_realisation_cat' );
          $cat_slug= ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
          $location= get_post_meta( get_the_ID(), 'mi_location', true );
          ?>
          <figure class="gallery-card" data-cat="<?php echo esc_attr( $cat_slug ); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="gallery-thumb" style="padding:0;"><?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?></div>
            <?php else : ?>
              <div class="gallery-thumb <?php echo esc_attr( $thumb_variants[ $i % count( $thumb_variants ) ] ); ?>"><span><?php the_title(); ?></span></div>
            <?php endif; ?>
            <figcaption><strong><?php the_title(); ?></strong><span><?php echo esc_html( $location ); ?></span></figcaption>
          </figure>
          <?php $i++; endwhile; wp_reset_postdata(); ?>
      </div>
      <p class="gallery-note">📷 Ajoutez vos photos de chantiers depuis le menu "Réalisations" de l'admin.</p>
    </div>
  </section>

  <!-- ===== CTA + PHOTO ===== -->
  <section class="cta-photo">
    <div class="cta-photo-text">
      <h2>Contactez-nous pour discuter de votre projet.</h2>
      <p>Nous vous répondons rapidement pour échanger sur vos besoins.</p>
      <a href="tel:<?php echo esc_attr( $phone_tel ); ?>" class="btn btn-outline-light btn-lg cta-photo-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <?php echo esc_html( $phone ); ?>
      </a>
    </div>
    <div class="cta-photo-image">
      <div class="photo-placeholder">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        <span>Photo de chantier à venir</span>
      </div>
    </div>
  </section>

  <!-- ===== DETAIL SECTION 2 ===== -->
  <section class="section">
    <div class="container detail-grid">
      <div class="detail-image">
        <div class="photo-placeholder">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
          <span>Photo de chantier à venir</span>
        </div>
      </div>
      <div class="detail-content">
        <h2>Votre projet de réaménagement intérieur géré par <span class="text-accent">des professionnels</span></h2>
        <p>La réussite d'une rénovation repose sur l'expertise et le souci du détail. <strong>L'équipe de Moderne isolation vous accompagne de A à Z</strong>. De la phase de conception à la réalisation finale du chantier, nous sommes votre unique interlocuteur.</p>
        <p>Nous étudions avec vous vos besoins pour vous proposer une solution personnalisée qui respecte votre budget. Au-delà des murs, nous sommes compétents pour la rénovation de salle de bain et la pose de revêtement de sol.</p>
        <p>Notre solide expérience dans le bâtiment est le gage d'un travail bien fait, réalisé dans les règles de l'art. Nous intervenons à Aix-en-Provence et dans tout le département des Bouches-du-Rhône.</p>
      </div>
    </div>
  </section>

  <!-- ===== TEMOIGNAGES ===== -->
  <section class="section section-dark" id="avis">
    <div class="container">
      <p class="eyebrow center light">Avis clients</p>
      <h2 class="section-title center light">Ils nous ont fait <span class="text-accent">confiance</span></h2>

      <div class="rating-banner">
        <div class="rating-score"><?php echo esc_html( mi_option( 'google_rating', '5' ) ); ?><span>/5</span></div>
        <div>
          <div class="stars" aria-hidden="true">★★★★★</div>
          <p><?php echo esc_html( mi_option( 'google_reviews_count', '24' ) ); ?> avis vérifiés sur Google — <a href="<?php echo esc_url( mi_option( 'google_reviews_url', '#' ) ); ?>" rel="nofollow">voir tous nos avis</a></p>
        </div>
      </div>

      <div class="grid grid-3 testimonial-grid">
        <?php
        $temoignages = new WP_Query( array( 'post_type' => 'mi_temoignage', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC' ) );
        while ( $temoignages->have_posts() ) : $temoignages->the_post();
          $rating   = (int) ( get_post_meta( get_the_ID(), 'mi_rating', true ) ?: 5 );
          $time_ago = get_post_meta( get_the_ID(), 'mi_time_ago', true );
          $name     = get_the_title();
          $initial  = mb_substr( $name, 0, 1 );
          ?>
          <blockquote class="testimonial-card">
            <div class="testimonial-head">
              <div class="testimonial-avatar" style="background:<?php echo esc_attr( mi_avatar_color( $name ) ); ?>"><?php echo esc_html( $initial ); ?></div>
              <div class="testimonial-name"><strong><?php echo esc_html( $name ); ?></strong><span class="stars" aria-hidden="true"><?php echo esc_html( str_repeat( '★', $rating ) ); ?></span></div>
            </div>
            <p>« <?php the_content(); ?> »</p>
            <?php if ( $time_ago ) : ?><span class="testimonial-time"><?php echo esc_html( $time_ago ); ?></span><?php endif; ?>
          </blockquote>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <!-- ===== PARTENAIRES ===== -->
  <section class="section" id="partenaires">
    <div class="container">
      <p class="eyebrow center">Ils nous font confiance</p>
      <h2 class="section-title center">Nos partenaires &amp; certifications</h2>
      <p class="section-lead center">Nous travaillons avec les plus grandes enseignes et organismes du bâtiment pour vous garantir des matériaux de qualité et des travaux conformes aux normes en vigueur.</p>

      <div class="partners-row">
        <?php foreach ( array_filter( array_map( 'trim', explode( "\n", mi_option( 'partners', '' ) ) ) ) as $partner ) : ?>
          <span class="partner-logo"><?php echo esc_html( $partner ); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ===== ZONE D'INTERVENTION ===== -->
  <section class="section section-alt" id="zone">
    <div class="container">
      <p class="eyebrow center">Où intervenons-nous ?</p>
      <h2 class="section-title center">Zone d'intervention en Bouches-du-Rhône</h2>
      <p class="section-lead center">Nos équipes se déplacent dans tout le département pour vos travaux d'isolation et de rénovation.</p>

      <div class="zone-wrap">
        <ul class="city-chips">
          <li>Marseille</li><li>Aix-en-Provence</li><li>Aubagne</li><li>Salon-de-Provence</li>
          <li>Istres</li><li>Martigues</li><li>Arles</li><li>Vitrolles</li>
          <li>La Ciotat</li><li>Miramas</li><li>Gardanne</li><li>Marignane</li>
        </ul>
        <div class="map-embed">
          <iframe title="Zone d'intervention Moderne Isolation" src="https://maps.google.com/maps?q=<?php echo rawurlencode( mi_option( 'map_query', 'Bouches-du-Rhône' ) ); ?>&t=&z=9&ie=UTF8&iwloc=&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== FAQ ===== -->
  <section class="section" id="faq">
    <div class="container container-narrow">
      <p class="eyebrow center">Questions fréquentes</p>
      <h2 class="section-title center">Vous avez des questions ?</h2>

      <div class="accordion" id="accordion">
        <?php
        $faqs = new WP_Query( array( 'post_type' => 'mi_faq', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        while ( $faqs->have_posts() ) : $faqs->the_post();
          ?>
          <div class="accordion-item">
            <button class="accordion-trigger" aria-expanded="false"><?php the_title(); ?></button>
            <div class="accordion-panel"><p><?php the_content(); ?></p></div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <!-- ===== CTA FINAL ===== -->
  <section class="cta-banner">
    <div class="container cta-banner-inner">
      <div>
        <h2>Un projet de rénovation ou d'isolation ?</h2>
        <p>Obtenez votre devis gratuit et sans engagement dès aujourd'hui.</p>
      </div>
      <div class="hero-cta">
        <a href="#devis" class="btn btn-primary btn-lg">Demander un devis gratuit</a>
        <a href="tel:<?php echo esc_attr( $phone_tel ); ?>" class="btn btn-outline-light btn-lg"><?php echo esc_html( $phone ); ?></a>
      </div>
    </div>
  </section>

  <!-- ===== CONTACT ===== -->
  <section class="section" id="contact">
    <div class="container">
      <p class="eyebrow center">Contact</p>
      <h2 class="section-title center">Nos coordonnées</h2>
      <p class="section-lead center">Une question avant de faire votre demande de devis ? Contactez-nous directement, nous vous répondons sous 48h.</p>

      <div class="contact-grid">
        <div class="contact-info">
          <ul class="contact-list">
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#A31E2B" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span><?php echo esc_html( mi_option( 'address' ) ); ?></span>
            </li>
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#A31E2B" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <a href="tel:<?php echo esc_attr( $phone_tel ); ?>"><?php echo esc_html( $phone ); ?></a>
            </li>
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#A31E2B" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg>
              <a href="mailto:<?php echo esc_attr( mi_option( 'email' ) ); ?>"><?php echo esc_html( mi_option( 'email' ) ); ?></a>
            </li>
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#A31E2B" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              <span><?php echo esc_html( mi_option( 'hours' ) ); ?></span>
            </li>
          </ul>

          <div class="social-row">
            <a href="<?php echo esc_url( mi_option( 'facebook_url', '#' ) ); ?>" aria-label="Facebook" rel="nofollow">FB</a>
            <a href="<?php echo esc_url( mi_option( 'instagram_url', '#' ) ); ?>" aria-label="Instagram" rel="nofollow">IG</a>
            <a href="<?php echo esc_url( mi_option( 'linkedin_url', '#' ) ); ?>" aria-label="LinkedIn" rel="nofollow">IN</a>
          </div>
        </div>

        <div class="callout-card">
          <p class="eyebrow">Devis gratuit</p>
          <h3>Prêt à démarrer votre projet ?</h3>
          <p>Remplissez notre formulaire rapide en haut de page : nom, téléphone et type de projet suffisent pour lancer votre demande.</p>
          <ul class="why-list callout-list">
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E9E6B" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
              <div><span>Réponse sous 48h</span></div>
            </li>
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E9E6B" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
              <div><span>Devis détaillé et sans engagement</span></div>
            </li>
            <li>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E9E6B" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
              <div><span>Accompagnement pour vos aides financières</span></div>
            </li>
          </ul>
          <a href="#devis" class="btn btn-primary btn-lg btn-block">Remplir le formulaire ↑</a>
        </div>
      </div>
    </div>
  </section>

</main>
<?php get_footer(); ?>
