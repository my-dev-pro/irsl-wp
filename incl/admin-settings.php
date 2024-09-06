<?php
if (!defined("ABSPATH")) {
    exit(); // Exit if accessed directly.
}
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action("plugins_loaded", "irsl_load");
function irsl_load()
{
    require_once "vendor/autoload.php";
    \Carbon_Fields\Carbon_Fields::boot();
}

// working with settings page //
add_action("carbon_fields_register_fields", "irsl_options");
function irsl_options()
{
    Container::make("theme_options", __("Irsl"))
        ->set_icon("dashicons-whatsapp")
        // wlc tab //
        // general tab //
        ->add_tab(__("General settings", "myDev-irsl"), [
            Field::make("html", "irsl_logo")->set_html(
                '<a class="whatboicon" href="https://dash.irsl.io/"  target="_blank"><img src="' .
                    plugin_dir_url(__FILE__) .
                    'irsl.png" ></a>
        <style>


        .whatboicon img{
            width:20%
        }
        .whatboicon{
        display: flex;
        justify-content: center
        }
     </style>'
            ),
            Field::make("html", "irsl_info")->set_html(
                "<h1>" .
                    __(
                        "Use the following to display the order information in the message",
                        "myDev-irsl"
                    ) .
                    '</h1>
        <p>' .
                    __("Order ID", "myDev-irsl") .
                    __(" add", "myDev-irsl") .
                    '<span id="irsl-varb"> {order_id} </span> </p>
        <p>' .
                    __("Client First Name", "myDev-irsl") .
                    __(" add", "myDev-irsl") .
                    '<span id="irsl-varb"> {first_name} </span> </p>
        <p>' .
                    __("Client Last Name", "myDev-irsl") .
                    __(" add", "myDev-irsl") .
                    '<span id="irsl-varb"> {last_name} </span> </p>
        <p>' .
                    __("Order Status") .
                    __(" add", "myDev-irsl") .
                    '<span id="irsl-varb"> {stat} </span> </p>
        <p>' .
                    __("Order Total", "myDev-irsl") .
                    __(" add", "myDev-irsl") .
                    '<span id="irsl-varb"> {total} </span> </p>
        <p>' .
                    __("Coupon Code", "myDev-irsl") .
                    __(" add", "myDev-irsl") .
                    '<span id="irsl-varb"> {code} </span> </p>

        <style> #irsl-varb{ font-weight:700;}  </style> '
            ),
            Field::make("html", "irsl_info_ultra")->set_html(
                '<a href="https://dash.irsl.io/" target="_blank"> ' .
                    __(
                        "Subscribe to the WhatsApp service from here.",
                        "myDev-irsl"
                    ) .
                    " </a>"
            ),
            Field::make(
                "text",
                "irsl_host",
                __("Irsl Host", "myDev-irsl")
            )->set_attribute("placeholder", "Your Host"),
            Field::make(
                "text",
                "irsl_token",
                __("Irsl Token", "myDev-irsl")
            )->set_attribute("placeholder", "Your Token"),
            Field::make(
                "text",
                "irsl_instance_id",
                __("Irsl Instance ID", "myDev-irsl")
            )->set_attribute("placeholder", "Instance ID"),
        ])

        // new order tab //

        ->add_tab(__("New Order", "myDev-irsl"), [
            // TODO Edit THIS

            Field::make("html", "irsl_neworder_head")->set_html(
                "<h1>" . __("Set new order message", "myDev-irsl") . "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_neworder",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),

            Field::make("html", "irsl_disabled_notice")
                ->set_html(
                    "<h1>" .
                        __(
                            "Unfortunately, this feature is temporarily disabled due to some problems with the WooCommerce add-on. It will be activated once the problem is fixed by the WooCommerce team.",
                            "myDev-irsl"
                        ) .
                        "</h1>"
                )
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_neworder",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ])

        // order note //

        ->add_tab(__("Order Note", "myDev-irsl"), [
            Field::make("html", "irsl_note_head")->set_html(
                "<h1>" . __("Set order note", "myDev-irsl") . "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_note",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
        ])
        // completed tab //
        ->add_tab(__("Completed Order", "myDev-irsl"), [
            Field::make("html", "irsl_completed_head")->set_html(
                "<h1>" .
                    __("Set completed order message", "myDev-irsl") .
                    "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_completed",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "textarea",
                "irsl_completed_message",
                __("Message", "myDev-irsl")
            )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_completed",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ])
        // processing tab //

        ->add_tab(__("Processing Order", "myDev-irsl"), [
            Field::make("html", "irsl_processing_head")->set_html(
                "<h1>" .
                    __("Set order processing message", "myDev-irsl") .
                    "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_processing",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "textarea",
                "irsl_processing_message",
                __("Message", "myDev-irsl")
            )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_processing",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ])
        // on hold tab //

        ->add_tab(__("On Hold Order", "myDev-irsl"), [
            Field::make("html", "irsl_hold_head")->set_html(
                "<h1>" . __("Set order on hold message", "myDev-irsl") . "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_hold",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "textarea",
                "irsl_hold_message",
                __("Message", "myDev-irsl")
            )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_hold",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ])
        // refunded tab //

        ->add_tab(__("Refunded Order", "myDev-irsl"), [
            Field::make("html", "irsl_refunded_head")->set_html(
                "<h1>" .
                    __("Set order refunded message", "myDev-irsl") .
                    "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_refunded",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "textarea",
                "irsl_refunded_message",
                __("Message", "myDev-irsl")
            )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_refunded",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ])

        // failed tab //

        ->add_tab(__("Failed Order", "myDev-irsl"), [
            Field::make("html", "irsl_failed_head")->set_html(
                "<h1>" . __("Set order failed message", "myDev-irsl") . "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_failed",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "textarea",
                "irsl_failed_message",
                __("Message", "myDev-irsl")
            )

                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_failed",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ])
                ->set_required(true),
        ])

        // pending tab //

        ->add_tab(
            __("Pending Order (Waiting payment confirmation)", "myDev-irsl"),
            [
                Field::make("html", "irsl_pending_head")->set_html(
                    "<h1>" .
                        __("Set order pending message", "myDev-irsl") .
                        "</h1>"
                ),
                Field::make(
                    "checkbox",
                    "irsl_show_pending",
                    __("Enable", "myDev-irsl")
                )->set_option_value("no"),
                Field::make(
                    "textarea",
                    "irsl_pending_message",
                    __("Message", "myDev-irsl")
                )
                    ->set_required(true)
                    ->set_conditional_logic([
                        "relation" => "AND", // Optional, defaults to "AND"
                        [
                            "field" => "irsl_show_pending",
                            "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        ],
                    ]),
            ]
        )

        // cancelled tab //

        ->add_tab(__("Cancelled Order", "myDev-irsl"), [
            Field::make("html", "irsl_cancelled_head")->set_html(
                "<h1>" .
                    __("Set order cancelled message", "myDev-irsl") .
                    "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_cancelled",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "textarea",
                "irsl_cancelled_message",
                __("Message", "myDev-irsl")
            )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_cancelled",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ])

        // coupon tab //

        ->add_tab(__("Coupons", "myDev-irsl"), [
            Field::make("html", "irsl_coupon_head")->set_html(
                "<h1>" . __("Set coupons message", "myDev-irsl") . "</h1>"
            ),
            Field::make(
                "checkbox",
                "irsl_show_coupon",
                __("Enable", "myDev-irsl")
            )->set_option_value("no"),
            Field::make(
                "text",
                "irsl_coupon_code",
                __("Coupon code", "myDev-irsl")
            )
                ->set_attribute(
                    "placeholder",
                    __("Enter Coupon code", "myDev-irsl")
                )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_coupon",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
            Field::make(
                "text",
                "irsl_coupon_orders",
                __("Number of orders", "myDev-irsl")
            )
                ->set_attribute(
                    "placeholder",
                    __(
                        "What is the number of orders based on which the coupon will be sent to the customer, ex. if sending is upon the first order, enter number 1",
                        "myDev-irsl"
                    )
                )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_coupon",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
            Field::make(
                "textarea",
                "irsl_coupon_message",
                __("Message", "myDev-irsl")
            )
                ->set_required(true)
                ->set_conditional_logic([
                    "relation" => "AND", // Optional, defaults to "AND"
                    [
                        "field" => "irsl_show_coupon",
                        "value" => true, // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        "compare" => "=", // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ]),
        ]);
}
