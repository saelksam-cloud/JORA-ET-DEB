<?php
/**
 * Handles the quote-request form submission (hero form) via admin-ajax,
 * so it actually emails the site admin instead of only showing a client-side message.
 */

function mi_handle_contact_form() {
	check_ajax_referer( 'mi_contact_form', 'nonce' );

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$project = isset( $_POST['project'] ) ? sanitize_text_field( wp_unslash( $_POST['project'] ) ) : '';

	if ( empty( $name ) || empty( $phone ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Merci de renseigner un nom, un téléphone et un e-mail valide.' ) );
	}

	$to      = mi_option( 'email', get_option( 'admin_email' ) );
	$subject = 'Nouvelle demande de devis — ' . $name;
	$body    = "Nom : $name\nTéléphone : $phone\nE-mail : $email\nType de projet : $project\n";
	$headers = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: $name <$email>" );

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => 'Merci ! Votre demande a bien été envoyée, nous revenons vers vous sous 48h.' ) );
	} else {
		wp_send_json_error( array( 'message' => "Une erreur est survenue lors de l'envoi. Merci de nous appeler directement." ) );
	}
}
add_action( 'wp_ajax_mi_contact_form', 'mi_handle_contact_form' );
add_action( 'wp_ajax_nopriv_mi_contact_form', 'mi_handle_contact_form' );
