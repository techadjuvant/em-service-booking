<?php

add_action('wp_ajax_emsb_booked_dates', 'emsb_booked_dates');
add_action('wp_ajax_nopriv_emsb_booked_dates', 'emsb_booked_dates');

function emsb_booked_dates() {
    check_ajax_referer( 'emsb_booked_slot_nonce', 'security' );
    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $check_availability_of_date = $_POST['check_availability_of_date'];
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE service_id = '$check_availability_of_date' ORDER BY id ASC", ARRAY_A);
    
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
    $bookedSlotIds = $wpdb->get_results("SELECT * FROM $table_name WHERE booked_date_id = '$check_slots_availability' ORDER BY id ASC", ARRAY_A);

    echo json_encode($bookedSlotIds);

    wp_die();


}


add_action('wp_ajax_emsb_booking_approval', 'emsb_booking_approval');

function emsb_booking_approval() {
    check_ajax_referer( 'emsb_booking_approval_nonce', 'security' );
    global $wpdb;
    $emsb_bookings_data_table = $wpdb->prefix . "emsb_bookings";
    $emsb_booking_approval_action_value = $_POST['emsb_booking_approval_action_value'];
    $emsb_booking_update_availability = $_POST['emsb_booking_update_availability'];
    $booked_slot_id = $_POST['booked_slot_id'];
    $emsb_booking_approval_id = $_POST['emsb_booking_approval_id'];
    // Update Booking table
    $booking_approval_res = $wpdb->update($emsb_bookings_data_table, array( 'approve_booking' => $emsb_booking_approval_action_value), array( 'id' => $emsb_booking_approval_id ));
    $booking_approval_res = $wpdb->update($emsb_bookings_data_table, array( 'available_orders' => $emsb_booking_update_availability), array( 'booked_slot_id' => $booked_slot_id ));
    
    // prepare email
    $emsb_customer_email_address = $_POST['emsb_customer_email_address'];
    // Fetch data for sending email
    $emsb_settings_table = $wpdb->prefix . "emsb_settings";
    $emsb_settings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_settings_table ORDER BY id DESC LIMIT 1" );

    $headers = array('Content-Type: text/html; charset=UTF-8');  
            
    $fetch_emsb_customer_confirmed_email_subject = $emsb_settings_data_fetch->customer_mail_confirmed_subject;
    $fetch_emsb_customer_confirmed_email_body = $emsb_settings_data_fetch->customer_mail_confirmed_body;

    $fetch_emsb_customer_cancelled_email_subject = $emsb_settings_data_fetch->customer_mail_cancel_subject;
    $fetch_emsb_customer_cancelled_email_body = $emsb_settings_data_fetch->customer_mail_cancel_body;

    if($emsb_booking_approval_action_value == "trush"){
        $emsb_booking_cancellation_email = wp_mail( $emsb_customer_email_address, $fetch_emsb_customer_cancelled_email_subject, $fetch_emsb_customer_cancelled_email_body, $headers );
    } else {
        $emsb_booking_confirmation_email = wp_mail( $emsb_customer_email_address, $fetch_emsb_customer_confirmed_email_subject, $fetch_emsb_customer_confirmed_email_body, $headers );
    }

    echo json_encode($emsb_settings_data_fetch);

    wp_die();

}


add_action('wp_ajax_emsb_fetch_pending_bookings', 'emsb_fetch_pending_bookings');

function emsb_fetch_pending_bookings() {

    $current_time_milliseconds = round(microtime(true) * 1000);

    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE ( approve_booking = '0' AND starting_time_ms > $current_time_milliseconds) ORDER BY id ASC LIMIT 10", ARRAY_A);

    echo json_encode($results);

    wp_die();


}


add_action('wp_ajax_emsb_fetch_pending_bookings_counts', 'emsb_fetch_pending_bookings_counts');

function emsb_fetch_pending_bookings_counts() {

    $current_time_milliseconds = round(microtime(true) * 1000);

    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $result = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE ( approve_booking = '0' AND starting_time_ms > $current_time_milliseconds) ");

    echo $result;

    wp_die();


}

