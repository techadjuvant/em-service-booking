<?php

    // When the page loads fetch data from database
    global $wpdb;
    $emsb_settings_data = $wpdb->prefix . 'emsb_settings';
    $emsb_settings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_settings_data ORDER BY id DESC LIMIT 1" );
    $admin_mail_subject = $emsb_settings_data_fetch->admin_mail_subject;
    $admin_mail_body = $emsb_settings_data_fetch->admin_mail_body;
    $customer_mail_subject = $emsb_settings_data_fetch->customer_mail_subject;
    $customer_mail_body = $emsb_settings_data_fetch->customer_mail_body;

    if(isset($_POST['emsb_submit_booking'])){

        global $wpdb;
        
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

        // $emsb_bookings_availability = "";
        // var_dump($emsb_service_orders_per_slot);
        // wp_die(); 

        $emsb_bookings_table = $wpdb->prefix . 'emsb_bookings';
        $emsb_bookings_table_data_fetch = $wpdb->get_row( "SELECT * FROM wp_emsb_bookings WHERE ( booked_slot_id = '$booked_slot_id') ORDER BY id DESC LIMIT 1" );

        if( is_object($emsb_bookings_table_data_fetch) ){ 
            $emsb_bookings_availability_string = $emsb_bookings_table_data_fetch->available_orders;
            $emsb_bookings_availability_int = (int)$emsb_bookings_availability_string;
            $emsb_bookings_availability = $emsb_bookings_availability_int - 1;
 
        }
        if(is_null($emsb_bookings_table_data_fetch)){
            $emsb_bookings_availability_string = $emsb_service_orders_per_slot; 
            $emsb_bookings_availability_int = (int)$emsb_bookings_availability_string;
            $emsb_bookings_availability = $emsb_bookings_availability_int - 1;

        }

        if($emsb_bookings_availability < 0){ ?>
            <div class="emsb-booking-ticket-container text-left">
            
            <div class="emsb-form-submission-error">
                <h5> <?php _e( 'Sorry, the slot is booked, please try another available date or slot', 'service-booking' ); ?></h5>
                <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Try Again', 'service-booking' ); ?></button>
            </div>
            
        </div> <?php 
        } else {  
            // var_dump($emsb_bookings_availability);
            // wp_die();
        
            $customer_name = $_POST['emsb_user_fullName'];
            $customer_email = $_POST['emsb_user_email'];
            $customer_phone = $_POST["emsb_user_telephone"];
            $service_provider_email = $_POST['emsb_selected_service_provider_email'];
            $customer_IP = '27.147.206.102';
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
            $valid_emsb_service_orders_per_slot = !empty($emsb_service_orders_per_slot);

            if ($valid_customer_name && $valid_customer_email && $valid_customer_phone && $valid_service_id && $valid_service_name && $valid_booked_date_id && $valid_booked_slot_id && $valid_booked_date && $valid_booked_time_slot && $valid_emsb_booking_slot_starts_at && $valid_emsb_service_orders_per_slot ) {
                // If everything is okay then insert the value to database and send emails
                $emsb_bookings_table_name = $wpdb->prefix . "emsb_bookings";
        
                $dataInsert = $wpdb->insert( $emsb_bookings_table_name, array(
                        'approve_booking' => 0,
                        'service_id' => $service_id,
                        'service_name' => $service_name,
                        'service_price' => $service_price,
                        'booked_date_id' => $booked_date_id,
                        'booked_slot_id' => $booked_slot_id,
                        'booked_date' => $booked_date,
                        'booked_time_slot' => $booked_time_slot,
                        'customer_name' => $customer_name,
                        'customer_email' => $customer_email,
                        'customer_phone' => $customer_phone,
                        'customer_IP' => $customer_IP,
                        'starting_time_ms' => $emsb_booking_slot_starts_at,
                        'available_orders' => $emsb_bookings_availability
                    ) );

                
            
            
                $headers = array('Content-Type: text/html; charset=UTF-8');
            
            
                $admin_email_address =  $service_provider_email;
                $admin_email_subject = $admin_mail_subject;
            
                $customer_email_address = $customer_email;
                $customer_email_subject = $customer_mail_subject;

        
                // prepare email body text
            
                $admin_email_Body = $admin_mail_body;
                $customer_mail_body = $customer_mail_body;
            
            
                $wp_customer_mail = wp_mail( $customer_email_address, $customer_email_subject, $customer_mail_body, $headers );
            
                $wp_admin_mail = wp_mail( $admin_email_address, $admin_email_subject, $admin_email_Body, $headers );

                // Get inserted data
                $emsb_bookings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_bookings_table_name ORDER BY id DESC LIMIT 1" );
                $emsb_booking_id = $emsb_bookings_data_fetch->id;
        
                // if($wp_customer_mail){
                //   echo "wp_customer_mail works";
                // } else {
                //   echo "wp_customer_mail doesn't works";
                // }
            
            
                // if($wp_admin_mail){
                //   echo "wp_admin_mail works";
                // } else {
                //   echo "wp_admin_mail doesn't works";
                // }

            ?> 
            <div class="emsb-booking-ticket-container text-left">
                <div id="emsb_booking_ticket">
                    <div class="emsb-ticket-wrapper">
                        <div  class="text-center" >
                            <h5> <?php _e( 'Your Booking is Pending', 'service-booking' ); ?> </h5>
                        </div>
                        <div class="emsb-booked-service">
                            <div class="em-service-excerpt d-flex align-items-center">
                                <div class="emsb-booked-service-info">
                                    <h4 id="emsb-service-name"> <?php _e( 'Service: ', 'service-booking' ); ?> <?php echo $service_name;?> </h4> 
                                    <p> <?php _e( 'Title: ', 'service-booking' ); ?> <?php echo $service_title; ?>  </p>
                                    <p> <?php _e( 'Location: ', 'service-booking' ); ?> <?php echo $service_location; ?> </p>
                                    <p class="em-reservation-service-price"> <?php _e( 'Price: ', 'service-booking' ); ?> <b> <?php echo $service_price; ?> </b> </p>  
                                </div>
                            </div>
                        </div>
                        <div class="emsb-booking-info">
                            <h4> <?php _e( 'Booking Info ', 'service-booking' ); ?></h4>
                            <div class="emsb-booked-id">
                                <p><?php _e( 'Booking Id: ', 'service-booking' ); ?> <?php echo $emsb_booking_id; ?></p>
                            </div>
                            <div class="emsb-booked-date d-flex align-items-center">
                                <p class="emsb-date"> <?php _e( 'Booked Date: ', 'service-booking' ); ?> <?php echo $booked_date; ?></p>
                            </div>
                            <div class="em-booked-time-slot d-flex align-items-center">
                                <p class="emsb-time-slot"> <?php _e( 'Booked Time-slot: ', 'service-booking' ); ?> <?php echo  $booked_time_slot; ?> </p>
                            </div>
                        </div>
                        <div class="emsb-booking-user-info">
                            <div class="d-flex align-items-center">
                                <p class="emsb-user-name"> <?php _e( 'Name: ', 'service-booking' ); ?> <?php echo $customer_name; ?></p>
                            </div>
                            <div class="d-flex align-items-center">
                                <p class="emsb-user-phone"><?php _e( ' Phone no: ', 'service-booking' ); ?> <?php echo $customer_phone; ?> </p>
                            </div>
                        </div>
                    </div>
                    <div class="emsb-ticket-button-wrapper">
                        <h4> <?php _e( 'Thank You', 'service-booking' ); ?></h4>
                        <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Go Back ', 'service-booking' ); ?></button>
                    </div>
                </div>
                
                
            </div>
            
           

        <?php

        } else { ?>

        <div class="emsb-booking-ticket-container text-left">
            
            <div class="emsb-form-submission-error">
                <h5> <?php _e( 'Something went wrong. Please fill out the form accurately and then submit.', 'service-booking' ); ?></h5>
                <button id="goBackButton" class="btn btn-dark emsb-ticket-button"> <?php _e( 'Try Again', 'service-booking' ); ?></button>
            </div>
            
        </div>
        <?php
        }
    
    }
  
}