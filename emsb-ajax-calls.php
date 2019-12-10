<?php

add_action('wp_ajax_emsb_booked_slot', 'emsb_booked_slot');
add_action('wp_ajax_nopriv_emsb_booked_slot', 'emsb_booked_slot');


function emsb_booked_slot() {
    check_ajax_referer( 'emsb_booked_slot_nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";

    $check_availability = $_POST['check_availability'];

    
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE booked_slot_id = '$check_availability'");

    $availability;
    
    if($results != null){
        $availability = "booked";
    } else {
        $availability = "available";
    }

    echo $availability;

    wp_die();


}

