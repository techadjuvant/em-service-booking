<?php

add_action('wp_ajax_emsb_booked_dates', 'emsb_booked_dates');
add_action('wp_ajax_nopriv_emsb_booked_dates', 'emsb_booked_dates');

function emsb_booked_dates() {
    check_ajax_referer( 'emsb_booked_slot_nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $check_availability_of_date = $_POST['check_availability_of_date'];
    $results = $wpdb->get_results("SELECT booked_date_id FROM $table_name WHERE service_id = $check_availability_of_date", ARRAY_A);
    
    echo json_encode($results);

    wp_die();

}



add_action('wp_ajax_emsb_booked_slot', 'emsb_booked_slot');
add_action('wp_ajax_nopriv_emsb_booked_slot', 'emsb_booked_slot');


function emsb_booked_slot() {
    check_ajax_referer( 'emsb_booked_slot_nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $check_slots_availability = $_POST['check_slots_availability'];
    $bookedSlotIds = $wpdb->get_results("SELECT booked_slot_id FROM $table_name WHERE booked_date_id = '$check_slots_availability'", ARRAY_A);

    echo json_encode($bookedSlotIds);

    wp_die();


}


add_action('wp_ajax_emsb_booking_approval', 'emsb_booking_approval');
add_action('wp_ajax_nopriv_emsb_booking_approval', 'emsb_booking_approval');


function emsb_booking_approval() {
    check_ajax_referer( 'emsb_booking_approval_nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $emsb_booking_approval_action_value = $_POST['emsb_booking_approval_action_value'];
    $emsb_booking_approval_id = $_POST['emsb_booking_approval_id'];
    $booking_approval_res = $wpdb->update($table_name, array( 'approve_booking' => $emsb_booking_approval_action_value), array( 'id' => $emsb_booking_approval_id ));

    echo json_encode($results);

    wp_die();


}

add_action('wp_ajax_emsb_fetch_bookings', 'emsb_fetch_bookings');
add_action('wp_ajax_nopriv_emsb_fetch_bookings', 'emsb_fetch_bookings');


function emsb_fetch_bookings() {
    check_ajax_referer( 'emsb_booking_approval_nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE approve_booking = '0' ORDER BY id DESC LIMIT 10", ARRAY_A);

    echo json_encode($results);

    wp_die();


}

