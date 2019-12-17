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
    
        $customer_name = $_POST['emsb_user_fullName'];
        $customer_email = $_POST['emsb_user_email'];
        $customer_phone = $_POST["emsb_user_telephone"];
        $service_provider_email = $_POST['emsb_selected_service_provider_email'];
    
        $emsb_bookings_table_name = $wpdb->prefix . "emsb_bookings";
    
       $dataInsert = $wpdb->insert( $emsb_bookings_table_name, array(
            'service_id' => $service_id,
            'service_name' => $service_name,
            'service_price' => $service_price,
            'booked_date_id' => $booked_date_id,
            'booked_slot_id' => $booked_slot_id,
            'booked_date' => $booked_date,
            'booked_time_slot' => $booked_time_slot,
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone
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
        
        <div id="emsb_booking_ticket" class="py-5" >
            <div class="emsb-ticket-wrapper">
                <div  class="text-center emsb-site-name" >
                    <h4> <?php echo bloginfo("name"); ?> </h4>
                    <p><?php echo bloginfo("description"); ?></p>
                </div>
                <div class="emsb-booked-service">
                    <div class="em-service-excerpt d-flex align-items-center">
                        <div class="em-service-excerpt-info">
                            <h4 id="emsb-service-name"> Service: <?php echo $service_name;?> </h4> 
                            <p> Title: <?php echo $service_title; ?>  </p>
                            <p><?php echo $service_location; ?> </p>
                            <p class="em-reservation-service-price">Price: <b> <?php echo $service_price; ?> </b> </p>  
                        </div>
                    </div>
                </div>
                <div class="emsb-booking-info">
                    <h4>Booking Info</h4>
                    <div class="emsb-booked-id">
                        <p>Booked Id: <?php echo $emsb_booking_id; ?></p>
                    </div>
                    <div class="emsb-booked-date d-flex align-items-center">
                        <p class="emsb-date">Booked Date: <?php echo $booked_date; ?></p>
                    </div>
                    <div class="em-booked-time-slot d-flex align-items-center">
                        <p class="emsb-time-slot"> <span> Booked Time-slot: <?php echo  $booked_time_slot; ?></span> </p>
                    </div>
                </div>
                <div class="emsb-booking-user-info">
                    <!-- <label for="emsb-booked-service">User Info</label> -->
                    <div class="d-flex align-items-center">
                        <p class="emsb-user-name"> Name: <?php echo $customer_name; ?></p>
                    </div>
                    <div class="d-flex align-items-center">
                        <p class="emsb-user-phone"> <span> Phone no: <?php echo $customer_phone; ?></span> </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="emsb-ticket-button-wrapper">
            <h4>Your Booking is Confirmed</h4>
            <button id="createPDF" class="btn btn-dark emsb-ticket-button" >Download Ticket</button>
            <button id="goBackButton" class="btn btn-dark emsb-ticket-button">Go Back</button>
        </div>
        
    </div>
           
    
    <?php
  
  }