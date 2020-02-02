<?php
    // When the page loads fetch data from database

    if(isset($_POST['emsb_submit_booking'])){
        global $wpdb;
        $emsb_settings_data_table = $wpdb->prefix . 'emsb_settings';
        $emsb_settings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_settings_data_table ORDER BY id DESC LIMIT 1" );
        $admin_mail_subject = $emsb_settings_data_fetch->admin_mail_subject;
        $admin_mail_body = $emsb_settings_data_fetch->admin_mail_body;
        $customer_mail_subject = $emsb_settings_data_fetch->customer_mail_pending_subject;
        $customer_mail_body = $emsb_settings_data_fetch->customer_mail_pending_body;


        $service_id = $_POST['emsb_selected_service_id'];
        $service_name = $_POST['emsb_selected_service'];
        $service_title = $_POST['emsb_selected_service_title'];
        $service_location = $_POST['emsb_selected_service_location'];
        $service_price = $_POST['emsb_selected_service_price'];
        $booked_date_id = $_POST["emsb_selected_service_date_id"];
        $booked_slot_id = $_POST["emsb_selected_slot_id"];
        $booked_date = $_POST["emsb_selected_service_date"];
        $booked_time_slot = $_POST["emsb_selected_time_slot"];
        $emsb_booking_slot_starts_at = $_POST["emsb_booking_slot_starts_at"];
        $emsb_service_orders_per_slot = $_POST["emsb_service_orders_per_slot"];

        
        // Count the confirmed bookings
        $emsb_bookings_table = $wpdb->prefix . 'emsb_bookings';
        $emsb_bookings_table_data_fetch = '';
        if(!empty($booked_slot_id)){
            $booked_slot_id_xss_proof = htmlspecialchars($booked_slot_id);
            $booked_slot_id_xss_proof_and_sanitized = sanitize_text_field($booked_slot_id_xss_proof);
        
            $emsb_bookings_table_data_fetch_prep_sql = $wpdb->prepare( "SELECT * FROM $emsb_bookings_table WHERE booked_slot_id = %s ORDER BY id DESC LIMIT 1", $booked_slot_id_xss_proof_and_sanitized );
            $emsb_bookings_table_data_fetch = $wpdb->get_row( $emsb_bookings_table_data_fetch_prep_sql );
            
        }
        
        if( is_object($emsb_bookings_table_data_fetch) ){ 
            $emsb_bookings_availability_string = $emsb_bookings_table_data_fetch->available_orders;
            $emsb_bookings_availability_int = (int)$emsb_bookings_availability_string;
        }

        if(is_null($emsb_bookings_table_data_fetch)){
            $emsb_bookings_availability_string = $emsb_service_orders_per_slot; 
            $emsb_bookings_availability_int = (int)$emsb_bookings_availability_string;
        }

        if($emsb_bookings_availability_int <= 0){ ?>
            <div class="emsb-booking-ticket-container text-left">
                <div class="emsb-form-submission-error">
                    <h5> <?php _e( 'Sorry, the slot is booked, please try another available date or slot', 'emsb-service-booking' ); ?></h5>
                    <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Try Another', 'emsb-service-booking' ); ?></button>
                </div>
            </div> 
        <?php 
        } else {  
            $customer_name = $_POST['emsb_user_fullName'];
            $customer_email = $_POST['emsb_user_email'];
            $customer_phone = $_POST["emsb_user_telephone"];
            $service_provider_email = $_POST['emsb_selected_service_provider_email'];
            $customer_IP = '2700.147.206.102';

            // Validation Check 
            $valid_customer_name = !empty($customer_name);
            $valid_customer_email = filter_var($customer_email, FILTER_VALIDATE_EMAIL) && !empty($customer_email);
            $valid_customer_phone = preg_match("/[0-9]/", $customer_phone) && !empty($customer_phone);
            $valid_service_id = !empty($service_id);
            $valid_service_name = !empty($service_name);
            $valid_booked_date_id = !empty($booked_date_id);
            $valid_booked_slot_id = !empty($booked_slot_id);
            $valid_booked_date = !empty($booked_date);
            $valid_booked_time_slot = !empty($booked_time_slot);
            $valid_emsb_booking_slot_starts_at = !empty($emsb_booking_slot_starts_at);

            if ($valid_customer_name && $valid_customer_email && $valid_customer_phone && $valid_service_id && $valid_service_name && $valid_booked_date_id && $valid_booked_slot_id && $valid_booked_date && $valid_booked_time_slot && $valid_emsb_booking_slot_starts_at ) {
                $customer_name_xss_proof = htmlspecialchars($customer_name);
                $customer_name_xss_proof_and_sanitized = sanitize_text_field($customer_name_xss_proof);

                $customer_email_xss_proof = htmlspecialchars($customer_email);
                $customer_email_xss_proof_and_sanitized = sanitize_email($customer_email_xss_proof);
                
                $customer_phone_xss_proof = htmlspecialchars($customer_phone);
                $customer_phone_xss_proof_and_sanitized = absint($customer_phone_xss_proof);
                
                $service_provider_email_xss_proof = htmlspecialchars($service_provider_email);
                $service_provider_email_xss_proof_and_sanitized = sanitize_email($service_provider_email_xss_proof);
                
                $service_id_xss_proof = htmlspecialchars($service_id);
                $service_id_xss_proof_and_sanitized = absint($service_id_xss_proof);
                
                $service_name_xss_proof = htmlspecialchars($service_name);
                $service_name_xss_proof_and_sanitized = sanitize_text_field($service_name_xss_proof);
                
                $service_title_xss_proof = htmlspecialchars($service_title);
                $service_title_xss_proof_and_sanitized = sanitize_text_field($service_title_xss_proof);
                
                $service_location_xss_proof = htmlspecialchars($service_location);
                $service_location_xss_proof_and_sanitized = sanitize_text_field($service_location_xss_proof);
                
                $service_price_xss_proof = htmlspecialchars($service_price);
                $service_price_xss_proof_and_sanitized = sanitize_text_field($service_price_xss_proof);
                
                $booked_date_id_xss_proof = htmlspecialchars($booked_date_id);
                $booked_date_id_xss_proof_and_sanitized = sanitize_text_field($booked_date_id_xss_proof);
                
                $booked_slot_id_xss_proof = htmlspecialchars($booked_slot_id);
                $booked_slot_id_xss_proof_and_sanitized = sanitize_text_field($booked_slot_id_xss_proof);
                
                $booked_date_xss_proof = htmlspecialchars($booked_date);
                $booked_date_xss_proof_and_sanitized = sanitize_text_field($booked_date_xss_proof);
                
                $booked_time_slot_xss_proof = htmlspecialchars($booked_time_slot);
                $booked_time_slot_xss_proof_and_sanitized = sanitize_text_field($booked_time_slot_xss_proof);
                
                $emsb_booking_slot_starts_at_xss_proof = htmlspecialchars($emsb_booking_slot_starts_at);
                $emsb_booking_slot_starts_at_xss_proof_and_sanitized = sanitize_text_field($emsb_booking_slot_starts_at_xss_proof);

                // If everything is okay then insert the value to database and send emails
                $emsb_bookings_table_name = $wpdb->prefix . "emsb_bookings";

                $emsb_insert_query = $wpdb->prepare("INSERT INTO $emsb_bookings_table_name 
                    (`approve_booking`, `service_id`, `service_name`, `service_price`, `booked_date_id`, `booked_slot_id`, `booked_date`, `booked_time_slot`, `customer_name`, `customer_email`, `customer_phone`, `customer_IP`, `starting_time_ms`, `available_orders`) 
                    values (%s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %d)", 
                    0, 
                    $service_id_xss_proof_and_sanitized, 
                    $service_name_xss_proof_and_sanitized, 
                    $service_price_xss_proof_and_sanitized, 
                    $booked_date_id_xss_proof_and_sanitized, 
                    $booked_slot_id_xss_proof_and_sanitized, 
                    $booked_date_xss_proof_and_sanitized, 
                    $booked_time_slot_xss_proof_and_sanitized, 
                    $customer_name_xss_proof_and_sanitized, 
                    $customer_email_xss_proof_and_sanitized, 
                    $customer_phone_xss_proof_and_sanitized, 
                    $customer_IP, 
                    $emsb_booking_slot_starts_at_xss_proof_and_sanitized, 
                    $emsb_bookings_availability_int
                );

                $emsb_insert_booking = $wpdb->query($emsb_insert_query);

                if($emsb_insert_booking){

                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    // prepare customer email
                    $customer_email_address = $customer_email_xss_proof_and_sanitized;
                    $customer_email_subject = $customer_mail_subject;

                    $customer_mail_body = $customer_mail_body .'<br>' ."\r\n";
                    $customer_mail_body .= ' <br>' ."\r\n";
                    $customer_mail_body .= ' <br>' ."\r\n";
                    $customer_mail_body .= 'Booking Info:  <br>' ."\r\n";
                    $customer_mail_body .= 'Your Name: ' . $customer_name_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $customer_mail_body .= 'Your Phone: ' . $customer_phone_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $customer_mail_body .= 'Your Email Address: ' . $customer_email_address . '<br>' ."\r\n";
                    $customer_mail_body .= 'Service: ' . $service_name_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $customer_mail_body .= 'Booked Date: ' . $booked_date_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $customer_mail_body .= 'Booked Time slot: ' . $booked_time_slot_xss_proof_and_sanitized . '<br>' ."\r\n";

                    $wp_customer_mail = wp_mail( $customer_email_address, $customer_email_subject, $customer_mail_body, $headers );

                    $admin_email_address =  $service_provider_email_xss_proof_and_sanitized;
                    $admin_email_subject = $admin_mail_subject ." Customer: " .$customer_name_xss_proof_and_sanitized;

                    // prepare admin email body 
                    $admin_email_Body = $admin_mail_body .'<br>' ."\r\n";  
                    $admin_email_Body .= ' <br>' ."\r\n";
                    $admin_email_Body .= ' <br>' ."\r\n";
                    $admin_email_Body .= 'Booking Info:  <br>' ."\r\n";
                    $admin_email_Body .= 'Customer Name: ' . $customer_name_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $admin_email_Body .= 'Customer Phone: ' . $customer_phone_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $admin_email_Body .= 'Customer Email Address: ' . $customer_email_address . '<br>' ."\r\n";
                    $admin_email_Body .= 'Service: ' . $service_name_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $admin_email_Body .= 'Booked Date: ' . $booked_date_xss_proof_and_sanitized . '<br>' ."\r\n";
                    $admin_email_Body .= 'Booked Time slot: ' . $booked_time_slot_xss_proof_and_sanitized . '<br>' ."\r\n"; 
                
                    $wp_admin_mail = wp_mail( $admin_email_address, $admin_email_subject, $admin_email_Body, $headers );

                    // Get inserted data

                    $emsb_bookings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_bookings_table_name ORDER BY id DESC LIMIT 1" );
                    $emsb_booking_id = $emsb_bookings_data_fetch->id;

                ?> 

                <div class="emsb-booking-ticket-container text-left">
                    <div id="emsb_booking_ticket">
                        <div class="emsb-ticket-wrapper">
                            <div  class="text-center" >
                                <div class="emsb-pending-notification"> <?php _e( 'Booking Approval Status:', 'emsb-service-booking' ); ?> 
                                    <span class="text-warning"> <?php _e( 'Pending', 'emsb-service-booking' ); ?> </span>
                                </div>
                            </div>
                            <div class="emsb-booked-service">
                                <div class="em-service-excerpt d-flex align-items-center">
                                    <div class="emsb-booked-service-info">
                                        <h4 id="emsb-service-name"> <?php _e( 'Service: ', 'emsb-service-booking' ); ?> <?php echo $service_name_xss_proof_and_sanitized;?> </h4> 
                                        <p> <?php _e( 'Title: ', 'emsb-service-booking' ); ?> <?php echo $service_title_xss_proof_and_sanitized; ?>  </p>
                                        <p> <?php echo $service_location_xss_proof_and_sanitized; ?> </p>
                                        <p class="em-reservation-service-price"> <?php _e( 'Price: ', 'emsb-service-booking' ); ?> <b> <?php echo $service_price_xss_proof_and_sanitized; ?> </b> </p>  
                                    </div>
                                </div>
                            </div>
                            <div class="emsb-booking-info">
                                <h4> <?php _e( 'Booking Info ', 'emsb-service-booking' ); ?> </h4>
                                <div class="emsb-booked-id">
                                    <p><?php _e( 'Booking Id: ', 'emsb-service-booking' ); ?> <?php echo $emsb_booking_id; ?></p>
                                </div>
                                <div class="emsb-booked-date d-flex align-items-center">
                                    <p class="emsb-date"> <?php _e( 'Booked Date: ', 'emsb-service-booking' ); ?> <?php echo $booked_date_xss_proof_and_sanitized; ?></p>
                                </div>
                                <div class="em-booked-time-slot d-flex align-items-center">
                                    <p class="emsb-time-slot"> <?php _e( 'Booked Time-slot: ', 'emsb-service-booking' ); ?> <?php echo  $booked_time_slot_xss_proof_and_sanitized; ?> </p>
                                </div>
                            </div>
                            <div class="emsb-booking-user-info">
                                <div class="d-flex align-items-center">
                                    <p class="emsb-user-name"> <?php _e( 'Name: ', 'emsb-service-booking' ); ?> <?php echo $customer_name_xss_proof_and_sanitized; ?></p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="emsb-user-phone"><?php _e( ' Phone no: ', 'emsb-service-booking' ); ?> <?php echo $customer_phone_xss_proof_and_sanitized; ?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="emsb-ticket-button-wrapper">
                            <h4> <?php _e( 'Thank You', 'emsb-service-booking' ); ?></h4>
                            <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Go Back ', 'emsb-service-booking' ); ?></button>
                        </div>
                    </div>
                </div>
                <script>
                    if ( window.history.replaceState ) {
                        window.history.replaceState( null, null, window.location.href );
                    }
                </script>
            <?php
            } else { ?>
                <div class="emsb-booking-ticket-container text-left">
                    <div class="emsb-form-submission-error p-3">
                        <span> <?php _e( 'Please fill out the form accurately and then submit', 'emsb-service-booking' ); ?></span>
                        <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Try Again', 'emsb-service-booking' ); ?></button>
                    </div>
                </div>
        <?php    }

        } else { ?>
            <div class="emsb-booking-ticket-container text-left">
                <div class="emsb-form-submission-error p-3">
                    <span> <?php _e( 'Please fill out the form accurately and then submit', 'emsb-service-booking' ); ?></span>
                    <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Try Again', 'emsb-service-booking' ); ?></button>
                </div>
            </div>
        <?php

        }

    }

}