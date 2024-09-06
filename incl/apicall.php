<?php

// Load WordPress

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$phoneNumber = ltrim($order->get_billing_phone(), '+');
if ( empty($order->get_billing_phone()) || empty($phoneNumber)  ) return;

	$headers = [
		'Content-Type' => 'application/json',
	];

	$body_encoded = json_encode([
		'recipient' => $phoneNumber,
		'content' => [
			'text' => $final_message,
		],
	]);

   $token = carbon_get_theme_option( 'irsl_token' );
   $instance_id = carbon_get_theme_option( 'irsl_instance_id' );
   $host = carbon_get_theme_option('irsl_host');

   $apiUrl = "https://$host.irsl.io/$instance_id/$token/sendMessage";
   
	$response = wp_remote_post( $apiUrl, array(
			'method'      => 'POST',
			'header'		=> $headers,
			'body'        => $body_encoded,
		)
	);

	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
	}
