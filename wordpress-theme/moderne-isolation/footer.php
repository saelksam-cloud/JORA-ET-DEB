<!-- ===== FOOTER ===== -->
<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo logo-footer">
        <svg width="30" height="30" viewBox="0 0 100 100" aria-hidden="true"><path d="M20,84 V16 H36 L50,36 L64,16 H80 V84 H68 V38 L50,63 L32,38 V84 Z" fill="#A31E2B"/></svg>
        <span>Moderne <strong>isolation</strong></span>
      </a>
      <p>Rénovation intérieure et extérieure depuis <?php echo esc_html( mi_option( 'since_year', '2016' ) ); ?> : isolation, peinture, placo, maçonnerie à Aix-en-Provence et dans les Bouches-du-Rhône.</p>
    </div>

    <div>
      <h3>Liens rapides</h3>
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/#services' ) ); ?>">Services</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#realisations' ) ); ?>">Réalisations</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#avis' ) ); ?>">Avis</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#partenaires' ) ); ?>">Partenaires</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#zone' ) ); ?>">Zone d'intervention</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#faq' ) ); ?>">FAQ</a></li>
      </ul>
    </div>

    <div>
      <h3>Nos services</h3>
      <ul>
        <?php
        $footer_services = new WP_Query( array( 'post_type' => 'mi_service', 'posts_per_page' => 5, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        while ( $footer_services->have_posts() ) :
          $footer_services->the_post();
          ?>
          <li><?php the_title(); ?></li>
        <?php endwhile; wp_reset_postdata(); ?>
      </ul>
    </div>

    <div>
      <h3>Contact</h3>
      <ul>
        <li><?php echo esc_html( mi_option( 'address' ) ); ?></li>
        <li><a href="tel:<?php echo esc_attr( mi_option( 'phone_tel' ) ); ?>"><?php echo esc_html( mi_option( 'phone' ) ); ?></a></li>
        <li><a href="mailto:<?php echo esc_attr( mi_option( 'email' ) ); ?>"><?php echo esc_html( mi_option( 'email' ) ); ?></a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> — SIRET <?php echo esc_html( mi_option( 'siret' ) ); ?></p>
      <ul>
        <?php
        $mentions = get_page_by_path( 'mentions-legales' );
        $confid   = get_page_by_path( 'politique-confidentialite' );
        ?>
        <li><a href="<?php echo $mentions ? esc_url( get_permalink( $mentions ) ) : '#'; ?>">Mentions légales</a></li>
        <li><a href="<?php echo $confid ? esc_url( get_permalink( $confid ) ) : '#'; ?>">Politique de confidentialité</a></li>
      </ul>
    </div>
  </div>
</footer>

<a href="tel:<?php echo esc_attr( mi_option( 'phone_tel' ) ); ?>" class="mobile-call-fab" aria-label="Appeler <?php bloginfo( 'name' ); ?>">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
</a>

<?php
$confid = get_page_by_path( 'politique-confidentialite' );
?>
<div class="cookie-banner" id="cookie-banner" role="dialog" aria-label="Consentement cookies" hidden>
  <p>Nous utilisons Google Analytics pour mesurer l'audience du site et améliorer votre expérience. Vous pouvez accepter ou refuser ces cookies de mesure d'audience. <a href="<?php echo $confid ? esc_url( get_permalink( $confid ) ) : '#'; ?>">En savoir plus</a>.</p>
  <div class="cookie-banner-actions">
    <button type="button" class="btn btn-outline" id="cookie-decline">Refuser</button>
    <button type="button" class="btn btn-primary" id="cookie-accept">Accepter</button>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
