<?php

add_action('wp_ajax_emsb_booked_dates', 'emsb_booked_dates');
add_action('wp_ajax_nopriv_emsb_booked_dates', 'emsb_booked_dates');

function emsb_booked_dates() {
    check_ajax_referer( 'emsb_booked_slot_nonce', 'security' );

    $current_time_milliseconds = round(microtime(true) * 1000);
    $current_time_minas_24_hours = $current_time_milliseconds - 86400000;

    global $wpdb;

    $emsb_bookings_table = $wpdb->prefix . "emsb_bookings";

    $check_availability_of_date = $_POST['check_availability_of_date'];
    if(!empty($check_availability_of_date)){
        $check_availability_of_date_xss_proof = htmlspecialchars($check_availability_of_date);
        $check_availability_of_date_xss_proof_and_sanitized = absint($check_availability_of_date_xss_proof);
        
        $prep_sql = $wpdb->prepare( "SELECT * FROM $emsb_bookings_table WHERE service_id = %d AND starting_time_ms > %d ORDER BY id ASC", $check_availability_of_date_xss_proof_and_sanitized, $current_time_minas_24_hours );
        $results = $wpdb->get_results( $prep_sql , ARRAY_A );
        echo wp_json_encode($results);
        wp_die();
    } else {
        $results = 'error';
        echo wp_json_encode($results);
        wp_die();
    }
    

    


}



add_action('wp_ajax_emsb_booked_slot', 'emsb_booked_slot');
add_action('wp_ajax_nopriv_emsb_booked_slot', 'emsb_booked_slot');


function emsb_booked_slot() {
    check_ajax_referer( 'emsb_booked_slot_nonce', 'security' );

    global $wpdb;
    $emsb_bookings_table = $wpdb->prefix . "emsb_bookings";

    $check_slots_availability = $_POST['check_slots_availability'];
    if(!empty($check_slots_availability)){
        $check_slots_availability_xss_proof = htmlspecialchars($check_slots_availability);
        $check_slots_availability_xss_proof_and_sanitized = sanitize_text_field($check_slots_availability_xss_proof);

        $bookedSlotIds_prepare = $wpdb->prepare("SELECT * FROM $emsb_bookings_table WHERE booked_date_id = %s ORDER BY id ASC", $check_slots_availability_xss_proof_and_sanitized );
        $bookedSlotIds = $wpdb->get_results( $bookedSlotIds_prepare , ARRAY_A );
        echo wp_json_encode($bookedSlotIds);
        wp_die();
    } else {
        $bookedSlotIds = 'error';
        echo wp_json_encode($bookedSlotIds);
        wp_die();
    }

}


add_action('wp_ajax_emsb_booking_approval', 'emsb_booking_approval');

function emsb_booking_approval() {
    check_ajax_referer( 'emsb_booking_approval_nonce', 'security' );
    global $wpdb;
    $emsb_bookings_table = $wpdb->prefix . "emsb_bookings";

    $emsb_booking_approval_action_value = $_POST['emsb_booking_approval_action_value'];
    $emsb_booking_update_availability = $_POST['emsb_booking_update_availability'];
    $booked_slot_id = $_POST['booked_slot_id'];
    $emsb_booking_approval_id = $_POST['emsb_booking_approval_id'];

    if(!empty($emsb_booking_approval_id)){
        $emsb_booking_approval_action_value_xss_proof = htmlspecialchars($emsb_booking_approval_action_value);
        $emsb_booking_approval_action_value_xss_proof_and_sanitized = sanitize_text_field($emsb_booking_approval_action_value_xss_proof);
    
        $emsb_booking_update_availability_xss_proof = htmlspecialchars($emsb_booking_update_availability);
        $emsb_booking_update_availability_xss_proof_and_sanitized = absint($emsb_booking_update_availability_xss_proof);
    
        $booked_slot_id_xss_proof = htmlspecialchars($booked_slot_id);
        $booked_slot_id_xss_proof_and_sanitized = sanitize_text_field($booked_slot_id_xss_proof);
    
        $emsb_booking_approval_id_xss_proof = htmlspecialchars($emsb_booking_approval_id);
        $emsb_booking_approval_id_xss_proof_and_sanitized = absint($emsb_booking_approval_id_xss_proof);
        // Update Booking table 
    
        $wpdb->query($wpdb->prepare("UPDATE $emsb_bookings_table SET approve_booking=%s WHERE id=%d", $emsb_booking_approval_action_value_xss_proof_and_sanitized, $emsb_booking_approval_id_xss_proof_and_sanitized));
    
        $wpdb->query($wpdb->prepare("UPDATE $emsb_bookings_table SET available_orders=%d WHERE booked_slot_id=%s", $emsb_booking_update_availability_xss_proof_and_sanitized, $booked_slot_id_xss_proof_and_sanitized));
    
    }
    
    // Fetch data for sending email
    $emsb_settings_table = $wpdb->prefix . "emsb_settings";
    $emsb_settings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_settings_table ORDER BY id DESC LIMIT 1" );

    $emsb_customer_email_address_xss_proof_and_sanitized = $_POST['emsb_customer_email_address'];

    if(!empty($emsb_customer_email_address_xss_proof_and_sanitized)){
        $emsb_customer_email_address_xss_proof = htmlspecialchars($emsb_customer_email_address);
        $emsb_customer_email_address_xss_proof_and_sanitized = sanitize_email($emsb_customer_email_address_xss_proof);
    
        $headers = array('Content-Type: text/html; charset=UTF-8');  
                
        $fetch_emsb_customer_confirmed_email_subject = $emsb_settings_data_fetch->customer_mail_confirmed_subject;
        $fetch_emsb_customer_confirmed_email_body = $emsb_settings_data_fetch->customer_mail_confirmed_body;
    
        $fetch_emsb_customer_cancelled_email_subject = $emsb_settings_data_fetch->customer_mail_cancel_subject;
        $fetch_emsb_customer_cancelled_email_body = $emsb_settings_data_fetch->customer_mail_cancel_body;
    
        if($emsb_booking_approval_action_value == "trush"){
            $emsb_booking_cancellation_email = wp_mail( $emsb_customer_email_address_xss_proof_and_sanitized, $fetch_emsb_customer_cancelled_email_subject, $fetch_emsb_customer_cancelled_email_body, $headers );
        } else {
            $emsb_booking_confirmation_email = wp_mail( $emsb_customer_email_address_xss_proof_and_sanitized, $fetch_emsb_customer_confirmed_email_subject, $fetch_emsb_customer_confirmed_email_body, $headers );
        }
    
        echo wp_json_encode($emsb_customer_email_address_xss_proof_and_sanitized);
    
        wp_die();
    }
    

}


add_action('wp_ajax_emsb_fetch_pending_bookings', 'emsb_fetch_pending_bookings');

function emsb_fetch_pending_bookings() {

    $current_time_milliseconds = round(microtime(true) * 1000);
    $current_time_minas_24_hours = $current_time_milliseconds - 86400000;

    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE ( approve_booking = '0' AND starting_time_ms > $current_time_minas_24_hours) ORDER BY id ASC LIMIT 10", ARRAY_A);

    echo wp_json_encode($results);

    wp_die();


}


add_action('wp_ajax_emsb_fetch_pending_bookings_counts', 'emsb_fetch_pending_bookings_counts');

function emsb_fetch_pending_bookings_counts() {

    $current_time_milliseconds = round(microtime(true) * 1000);
    $current_time_minas_24_hours = $current_time_milliseconds - 86400000;

    global $wpdb;
    $table_name = $wpdb->prefix . "emsb_bookings";
    $result = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE ( approve_booking = '0' AND starting_time_ms > $current_time_minas_24_hours) ");

    echo $result;

    wp_die();


}

