<?Php


function irsl_bot_get_user_total_completed_orders($customer_id){
     // Get the valid customer orders.
 $args = array(
    'limit'  => - 1,
    'return' => 'objects',
    'status' => ["completed"],
    'type'   => 'shop_order',
);
   

$args['customer_id'] = 1;

$orders = wc_get_orders( $args );

return count($orders);
}



?>