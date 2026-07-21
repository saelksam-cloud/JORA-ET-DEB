<?php
/**
 * Custom post types powering the editable homepage sections:
 * Services, Témoignages, Réalisations, Avant/Après, FAQ.
 */

function mi_register_post_types() {

	register_post_type( 'mi_service', array(
		'label'        => 'Services',
		'labels'       => array(
			'name'          => 'Services',
			'singular_name' => 'Service',
			'add_new_item'  => 'Ajouter un service',
			'edit_item'     => 'Modifier le service',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-hammer',
		'supports'     => array( 'title' ),
		'menu_position'=> 20,
	) );

	register_post_type( 'mi_temoignage', array(
		'label'        => 'Témoignages',
		'labels'       => array(
			'name'          => 'Témoignages',
			'singular_name' => 'Témoignage',
			'add_new_item'  => 'Ajouter un témoignage',
			'edit_item'     => 'Modifier le témoignage',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-star-filled',
		'supports'     => array( 'title', 'editor' ),
		'menu_position'=> 21,
	) );

	register_post_type( 'mi_realisation', array(
		'label'        => 'Réalisations',
		'labels'       => array(
			'name'          => 'Réalisations',
			'singular_name' => 'Réalisation',
			'add_new_item'  => 'Ajouter une réalisation',
			'edit_item'     => 'Modifier la réalisation',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-camera',
		'supports'     => array( 'title', 'thumbnail' ),
		'menu_position'=> 22,
	) );

	register_taxonomy( 'mi_realisation_cat', 'mi_realisation', array(
		'label'        => 'Catégories de réalisations',
		'hierarchical' => true,
		'show_ui'      => true,
		'show_in_menu' => true,
		'rewrite'      => false,
	) );

	register_post_type( 'mi_avant_apres', array(
		'label'        => 'Avant / Après',
		'labels'       => array(
			'name'          => 'Avant / Après',
			'singular_name' => 'Avant / Après',
			'add_new_item'  => 'Ajouter un avant/après',
			'edit_item'     => 'Modifier l\'avant/après',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-image-flip-horizontal',
		'supports'     => array( 'title' ),
		'menu_position'=> 23,
	) );

	register_post_type( 'mi_faq', array(
		'label'        => 'FAQ',
		'labels'       => array(
			'name'          => 'FAQ',
			'singular_name' => 'Question',
			'add_new_item'  => 'Ajouter une question',
			'edit_item'     => 'Modifier la question',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-editor-help',
		'supports'     => array( 'title', 'editor' ),
		'menu_position'=> 24,
	) );
}
add_action( 'init', 'mi_register_post_types' );

/* ---------------- Meta boxes ---------------- */

function mi_add_meta_boxes() {
	add_meta_box( 'mi_service_meta', 'Icône (emoji)', 'mi_render_service_meta_box', 'mi_service', 'side', 'default' );
	add_meta_box( 'mi_temoignage_meta', 'Détails de l\'avis', 'mi_render_temoignage_meta_box', 'mi_temoignage', 'side', 'default' );
	add_meta_box( 'mi_realisation_meta', 'Localisation', 'mi_render_realisation_meta_box', 'mi_realisation', 'side', 'default' );
	add_meta_box( 'mi_avant_apres_meta', 'Photos avant / après', 'mi_render_avant_apres_meta_box', 'mi_avant_apres', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mi_add_meta_boxes' );

function mi_render_service_meta_box( $post ) {
	wp_nonce_field( 'mi_save_meta', 'mi_meta_nonce' );
	$emoji = get_post_meta( $post->ID, 'mi_emoji', true );
	echo '<label for="mi_emoji">Emoji affiché devant le nom du service</label>';
	echo '<input type="text" id="mi_emoji" name="mi_emoji" value="' . esc_attr( $emoji ) . '" style="width:100%;font-size:1.4rem;margin-top:6px;" placeholder="🧱">';
}

function mi_render_temoignage_meta_box( $post ) {
	wp_nonce_field( 'mi_save_meta', 'mi_meta_nonce' );
	$rating   = get_post_meta( $post->ID, 'mi_rating', true ) ?: 5;
	$time_ago = get_post_meta( $post->ID, 'mi_time_ago', true );
	echo '<p><label for="mi_rating">Note (1 à 5)</label><br>';
	echo '<input type="number" id="mi_rating" name="mi_rating" min="1" max="5" value="' . esc_attr( $rating ) . '" style="width:100%;"></p>';
	echo '<p><label for="mi_time_ago">Ancienneté affichée</label><br>';
	echo '<input type="text" id="mi_time_ago" name="mi_time_ago" value="' . esc_attr( $time_ago ) . '" style="width:100%;" placeholder="il y a 2 mois"></p>';
	echo '<p class="description">Le titre de l\'avis = nom du client. Le contenu = texte de l\'avis.</p>';
}

function mi_render_realisation_meta_box( $post ) {
	wp_nonce_field( 'mi_save_meta', 'mi_meta_nonce' );
	$location = get_post_meta( $post->ID, 'mi_location', true );
	echo '<label for="mi_location">Ville</label>';
	echo '<input type="text" id="mi_location" name="mi_location" value="' . esc_attr( $location ) . '" style="width:100%;margin-top:6px;" placeholder="Aix-en-Provence">';
}

function mi_render_avant_apres_meta_box( $post ) {
	wp_nonce_field( 'mi_save_meta', 'mi_meta_nonce' );
	$avant_id = get_post_meta( $post->ID, 'mi_avant_image_id', true );
	$apres_id = get_post_meta( $post->ID, 'mi_apres_image_id', true );
	?>
	<div style="display:flex;gap:24px;">
		<div style="flex:1;">
			<p><strong>Photo "Avant"</strong></p>
			<div id="mi_avant_preview">
				<?php echo $avant_id ? wp_get_attachment_image( $avant_id, 'medium' ) : ''; ?>
			</div>
			<input type="hidden" id="mi_avant_image_id" name="mi_avant_image_id" value="<?php echo esc_attr( $avant_id ); ?>">
			<button type="button" class="button mi-upload-btn" data-target="mi_avant_image_id" data-preview="mi_avant_preview">Choisir une image</button>
		</div>
		<div style="flex:1;">
			<p><strong>Photo "Après"</strong></p>
			<div id="mi_apres_preview">
				<?php echo $apres_id ? wp_get_attachment_image( $apres_id, 'medium' ) : ''; ?>
			</div>
			<input type="hidden" id="mi_apres_image_id" name="mi_apres_image_id" value="<?php echo esc_attr( $apres_id ); ?>">
			<button type="button" class="button mi-upload-btn" data-target="mi_apres_image_id" data-preview="mi_apres_preview">Choisir une image</button>
		</div>
	</div>
	<script>
	jQuery(function($){
		$('.mi-upload-btn').on('click', function(e){
			e.preventDefault();
			var btn = $(this), targetField = $('#' + btn.data('target')), preview = $('#' + btn.data('preview'));
			var frame = wp.media({ title: 'Choisir une image', multiple: false, library: { type: 'image' } });
			frame.on('select', function(){
				var att = frame.state().get('selection').first().toJSON();
				targetField.val(att.id);
				preview.html('<img src="' + (att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url) + '" style="max-width:100%;height:auto;">');
			});
			frame.open();
		});
	});
	</script>
	<?php
}

function mi_save_post_meta( $post_id ) {
	if ( ! isset( $_POST['mi_meta_nonce'] ) || ! wp_verify_nonce( $_POST['mi_meta_nonce'], 'mi_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( 'mi_emoji', 'mi_rating', 'mi_time_ago', 'mi_location', 'mi_avant_image_id', 'mi_apres_image_id' );
	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post', 'mi_save_post_meta' );

function mi_enqueue_media_uploader( $hook ) {
	global $post_type;
	if ( 'mi_avant_apres' === $post_type ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'mi_enqueue_media_uploader' );
