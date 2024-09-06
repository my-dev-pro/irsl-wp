<?php
/*
 * Plugin Name: Irsl
 * Plugin URI: https://irsl.io
 * Description: Send whatsapp notification to your customers
 * Version: 1.0.0
 * Author: MY-Dev
 * Author URI: https://my-dev.pro
 * Tested up to: 5.4
 * Text Domain: myDev-irsl
 * Domain Path: /languages
 */
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}

include_once "incl/mobile-check.php";
include_once "incl/util.php";

require_once "incl/admin-settings.php";

// register languages
function myDev_irsl_text_domain()
{
    load_plugin_textdomain(
        "myDev-irsl",
        false,
        dirname(plugin_basename(__FILE__)) . DIRECTORY_SEPARATOR . "languages"
    );
}

add_action("plugin_loaded", "myDev_irsl_text_domain");

// woocommerce_order_status_changed
add_action("woocommerce_order_status_changed", "order_status_irsl", 10, 3);
function order_status_irsl($order_id, $old_status, $new_status)
{
    if (
        $new_status == "processing" &&
        carbon_get_theme_option("irsl_show_processing")
    ) {
        $message = carbon_get_theme_option("irsl_processing_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }
    // on hold
    if ($new_status == "on-hold" && carbon_get_theme_option("irsl_show_hold")) {
        $message = carbon_get_theme_option("irsl_hold_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }

    // completed
    if (
        $new_status == "completed" &&
        carbon_get_theme_option("irsl_show_completed")
    ) {
        $message = carbon_get_theme_option("irsl_completed_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }
    // refunded
    if (
        $new_status == "refunded" &&
        carbon_get_theme_option("irsl_show_refunded")
    ) {
        $message = carbon_get_theme_option("irsl_refunded_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }
    // failed
    if (
        $new_status == "failed" &&
        carbon_get_theme_option("irsl_show_failed")
    ) {
        $message = carbon_get_theme_option("irsl_failed_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }
    // pending
    if (
        $new_status == "pending" &&
        carbon_get_theme_option("irsl_show_pending")
    ) {
        $message = carbon_get_theme_option("irsl_pending_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }

    // cancelled
    if (
        $new_status == "cancelled" &&
        carbon_get_theme_option("irsl_show_cancelled")
    ) {
        $message = carbon_get_theme_option("irsl_cancelled_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }
}

// new order
// add_action("woocommerce_new_order","irslneworder",20,1);  // TODO ACTIVE THIS
function irslneworder($order_id)
{
    error_log("neworder triggre!!!");
    if (carbon_get_theme_option("irsl_show_neworder")) {
        $message = carbon_get_theme_option("irsl_neworder_message");
        require "incl/message_attr.php";
        require "incl/apicall.php";
    }
}

add_action("woocommerce_order_status_completed", "order_coupon_irsl", 10, 1);

// order coupon
function order_coupon_irsl($order_id)
{
    if (is_user_logged_in() && carbon_get_theme_option("irsl_show_coupon")) {
        $order = new WC_Order($order_id);
        $order_id = $order->get_id();

        // Orders count
        $customer_orders_count = irsl_bot_get_user_total_completed_orders(
            $order->get_customer_id()
        );

        // First customer order
        $value = $order->get_billing_first_name();

        $hasSent = get_user_meta($order->get_user_id(), "irsl_has_sent", true);

        if (
            $customer_orders_count ==
                carbon_get_theme_option("irsl_coupon_orders") &&
            !$hasSent
        ) {
            update_user_meta($order->get_user_id(), "irsl_has_sent", true);

            $message = carbon_get_theme_option("irsl_coupon_message");
            require "incl/message_attr.php";
            require "incl/apicall.php";
        }
    }

    return;
}

// order notes
function irsl_send_order_notes($email_args)
{
    if (carbon_get_theme_option("irsl_show_note")) {
        $order = wc_get_order($email_args["order_id"]);
        $note = $email_args["customer_note"];
        if ("NULL" === $order->get_billing_phone()) {
            return;
        }
        $final_message = $note;
        require "incl/apicall.php";
    }
}

add_action(
    "woocommerce_new_customer_note_notification",
    "irsl_send_order_notes",
    10,
    1
);
