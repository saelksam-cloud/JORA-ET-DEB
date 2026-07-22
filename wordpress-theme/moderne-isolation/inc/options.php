<?php
/**
 * Simple theme options page (Réglages > Moderne Isolation) — no plugin required.
 * All front-end text that isn't a repeatable CPT lives here so the client can edit
 * phone number, addresses, stats, etc. from wp-admin.
 */

function mi_option( $key, $default = '' ) {
	$options = get_option( 'mi_options', array() );
	return isset( $options[ $key ] ) && '' !== $options[ $key ] ? $options[ $key ] : $default;
}

function mi_register_options_page() {
	add_options_page( 'Moderne Isolation', 'Moderne Isolation', 'manage_options', 'mi-options', 'mi_render_options_page' );
}
add_action( 'admin_menu', 'mi_register_options_page' );

function mi_register_settings() {
	register_setting( 'mi_options_group', 'mi_options', 'mi_sanitize_options' );
}
add_action( 'admin_init', 'mi_register_settings' );

function mi_sanitize_options( $input ) {
	$clean = array();
	foreach ( $input as $key => $value ) {
		$clean[ $key ] = 'partners' === $key || 'about_text' === $key || 'detail_text_1' === $key || 'detail_text_2' === $key
			? sanitize_textarea_field( $value )
			: sanitize_text_field( $value );
	}
	return $clean;
}

function mi_options_fields() {
	return array(
		'phone'                => array( 'label' => 'Téléphone (affiché)', 'default' => '06 35 35 63 45' ),
		'phone_tel'             => array( 'label' => 'Téléphone (format tel:, ex. +33635356345)', 'default' => '+33635356345' ),
		'email'                => array( 'label' => 'E-mail', 'default' => 'moderneisolation13@gmail.com' ),
		'address'              => array( 'label' => 'Adresse', 'default' => '520 Chemin du Pas de la Mue, 13170 Les Pennes-Mirabeau' ),
		'siret'                => array( 'label' => 'SIRET', 'default' => '000 000 000 00000' ),
		'hours'                => array( 'label' => 'Horaires', 'default' => 'Lun–Ven : 8h–18h · Sam : 9h–12h (sur RDV)' ),
		'since_year'           => array( 'label' => 'Année de création', 'default' => '2016' ),
		'stat_clients'         => array( 'label' => 'Clients accompagnés par an', 'default' => '+100' ),
		'google_rating'        => array( 'label' => 'Note Google', 'default' => '5' ),
		'google_reviews_count' => array( 'label' => 'Nombre d\'avis Google', 'default' => '24' ),
		'google_reviews_url'   => array( 'label' => 'Lien vers les avis Google', 'default' => '#' ),
		'map_query'            => array( 'label' => 'Zone affichée sur la carte (ex. ville ou département)', 'default' => 'Bouches-du-Rhône' ),
		'facebook_url'         => array( 'label' => 'Lien Facebook', 'default' => '#' ),
		'instagram_url'        => array( 'label' => 'Lien Instagram', 'default' => '#' ),
		'linkedin_url'         => array( 'label' => 'Lien LinkedIn', 'default' => '#' ),
		'partners'             => array( 'label' => 'Partenaires (un par ligne)', 'default' => "Point.P\nLeroy Merlin Pro\nSOCOTEC\nQualibat RGE\nSaint-Gobain Isover\nMaPrimeRénov'", 'textarea' => true ),
		'ga_measurement_id'    => array( 'label' => 'Google Analytics 4 — ID de mesure (G-XXXXXXXXXX)', 'default' => '' ),
	);
}

function mi_render_options_page() {
	?>
	<div class="wrap">
		<h1>Réglages — Moderne Isolation</h1>
		<p>Ces champs alimentent le contenu de la page d'accueil (coordonnées, chiffres clés, réseaux sociaux...). Les services, témoignages, réalisations et la FAQ se gèrent dans leurs menus dédiés dans la barre latérale.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'mi_options_group' ); ?>
			<table class="form-table">
				<?php foreach ( mi_options_fields() as $key => $field ) : ?>
					<tr>
						<th scope="row"><label for="mi_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
						<td>
							<?php if ( ! empty( $field['textarea'] ) ) : ?>
								<textarea id="mi_<?php echo esc_attr( $key ); ?>" name="mi_options[<?php echo esc_attr( $key ); ?>]" rows="6" class="large-text"><?php echo esc_textarea( mi_option( $key, $field['default'] ) ); ?></textarea>
							<?php else : ?>
								<input type="text" id="mi_<?php echo esc_attr( $key ); ?>" name="mi_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( mi_option( $key, $field['default'] ) ); ?>" class="regular-text">
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<?php
}
