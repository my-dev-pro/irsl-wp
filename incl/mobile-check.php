<?php
function is_mobile_valid($number)
{
	$token = carbon_get_theme_option('irsl_token');
	$instance_id = carbon_get_theme_option('irsl_instance_id');
	$host = carbon_get_theme_option('irsl_host');

	if (! empty($token) && ! empty($instance_id) && ! empty($host)) {
		$apiUrl = "https://$host.irsl.io/$instance_id/$token/ExistsOnWhatsApp/$number";
		$response = wp_remote_post(
			$apiUrl,
			array(
				'method' => 'Get',
			)
		);
		$response_body = json_decode(wp_remote_retrieve_body($response), true);
		if (is_wp_error($response)) {
			wp_send_json_error(wp_remote_retrieve_body($response));
		} else {
			if (!$response_body["ok"]) {
				return false;
			}
			return true;
		}
	}
}