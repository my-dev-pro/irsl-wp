<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$order = new WC_Order( $order_id );
// get the field value
$irslvalue = $message;
$data_bas = $irslvalue; // Tests
$v1 = $order->get_billing_first_name();
$v2 = $order->get_billing_last_name();
$v3 = $order->get_total();
$v4 = $order->get_id();
$v5 = carbon_get_theme_option( 'irsl_coupon_code' );
if (!isset($new_status)){
  $new_status = "غير معروف";
}
$vars = array(
  '{first_name}'    => $v1,
  '{last_name}'     => $v2 ,
  '{total}'         => $v3,
  '{order_id}'      => $v4 ,
  '{stat}'          => $new_status ,
  '{code}'          => $v5,
);
$final_message = strtr($data_bas, $vars);
$body=$final_message; 
