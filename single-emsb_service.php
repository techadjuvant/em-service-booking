<?php 
get_header();
  global $wpdb;
  $emsb_settings_data = $wpdb->prefix . 'emsb_settings';
  $emsb_settings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_settings_data ORDER BY id DESC LIMIT 1" );
  $customer_cookie_duration = $emsb_settings_data_fetch->customer_cookie_duration;

  $emsb_plugin_path = plugin_dir_url( __FILE__ );
  $emsb_booking_form_process_url = $emsb_plugin_path . 'emsb-booking-form-process.php';


?>

<!-- emsb container  -->
<div class="emsb-plugin-container emsb-single-service-page">
<!-- emsb-services-and-form-container -->
  <div class="emsb-services-and-form-container">
  <!-- emsb-services container -->
    <div class="em-services-container">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 offset-lg-2"> 

          <?php
          $current_time_milliseconds = round(microtime(true) * 1000);
          /* Start the Loop */
          while ( have_posts() ) : the_post(); ?>
            <?php 
              $emsb_service_availability_ends_at = get_post_meta( get_the_ID(), 'emsb_service_availability_ends_at', true ); 
              if($emsb_service_availability_ends_at){ 
                $emsb_service_availability_ends_at = strtotime($emsb_service_availability_ends_at) * 1000;
              } else { 
                $emsb_service_availability_ends_at = $current_time_milliseconds;
              } 
              if($emsb_service_availability_ends_at <= $current_time_milliseconds){
            ?>
              <div class="alert alert-warning" role="alert">
                   <?php _e( 'Ended availability of this service!!!', 'emsb-service-booking' ); ?>
              </div>
            <?php } ?>
            <article id="post-<?php the_ID(); ?>"  class="em-service">
                
                  <div class="em-service-excerpt d-flex align-items-center">
                      <?php if ( has_post_thumbnail() ) : ?>
                          <?php the_post_thumbnail(); ?>
                      <?php endif; ?>
                      <div class="em-service-excerpt-info">
                        <h4 id="emsb-service-name"> <?php the_title(); ?> </h4> 
                        <label id="emsb-service-id" class="d-none"> <input type="number" value="<?php the_ID(); ?>"> </label>
                        <p class="emsb-service-title">
                            <?php 
                              $emsb_display_service_title = get_post_meta( get_the_ID(), 'emsb_display_service_title', true );
                              if($emsb_display_service_title){
                                echo $emsb_display_service_title;
                              }
                            ?>
                        </p>
                        <?php 
                          $emsb_display_service_location = get_post_meta( get_the_ID(), 'emsb_display_service_location', true );
                          if($emsb_display_service_location){ ?>
                              <p class="emsb-service-location"><?php _e( 'Location: ', 'emsb-service-booking' ); ?> <?php echo $emsb_display_service_location; ?> </p>
                          <?php 
                              }
                          ?>
                          <?php 
                            $emsb_display_service_price = get_post_meta( get_the_ID(), 'emsb_display_service_price', true );
                            if($emsb_display_service_price){ ?>
                              <p class="em-reservation-service-price"><?php _e( 'Price: ', 'emsb-service-booking' ); ?> <b id="emsb-service-price"> <?php echo $emsb_display_service_price; ?> </b> </p>
                          <?php 
                              }
                          ?>
                          
                        
                          <button type="button" class="btn btn-light mb-2 em-select-service-button">  <?php _e( 'Book Now ', 'emsb-service-booking' ); ?>  </button>
                      </div>
                  </div>
                  <div class="em-service-meta-info">
                      <div class="emsb-service-available-on-calendar">
                            <!-- starting date  -->
                            <?php $emsb_service_availability_starts_at = get_post_meta( get_the_ID(), 'emsb_service_availability_starts_at', true ); 
                            
                            if($emsb_service_availability_starts_at){
                                $current_time_milliseconds = round(microtime(true) * 1000);
                                $startingTimeToMs = strtotime($emsb_service_availability_starts_at) * 1000;
                            ?>
                              <?php if($startingTimeToMs > $current_time_milliseconds){ ?>
                                    <input type="text" name="emsb_service_availability_starts_at" class="emsb_service_availability_starts_at" value="<?php echo $emsb_service_availability_starts_at; ?>"/>
                              <?php } else { ?>
                                    <input type="text" name="emsb_service_availability_starts_at" class="emsb_service_availability_starts_at" value="<?php echo date("Y-m-d 00:00:01"); ?>"/>
                              <?php } ?>
                            <?php } else { ?>
                              <input type="text" name="emsb_service_availability_starts_at" class="emsb_service_availability_starts_at" value="<?php echo date("Y-m-d 00:00:01"); ?>"/>
                            <?php } ?>
                              <!-- ending date  -->
                            <?php $emsb_service_availability_ends_at = get_post_meta( get_the_ID(), 'emsb_service_availability_ends_at', true ); 
                            if($emsb_service_availability_ends_at){ ?>
                              <input type="text" name="emsb_service_availability_ends_at" class="emsb_service_availability_ends_at" value="<?php echo $emsb_service_availability_ends_at; ?>"/>
                            <?php 
                              } else { ?>
                                <input type="text" name="emsb_service_availability_ends_at" class="emsb_service_availability_ends_at" value="<?php echo date("Y-m-d 00:00:01"); ?>"/>
                              <?php } 
                            ?>
                        </div>
                      <!-- weekly off-days  -->
                    <div class="em-off-days">
                      <?php $emsb_service_off_day_sun = get_post_meta( get_the_ID(), 'emsb_service_off_day_sun', true );
                          if($emsb_service_off_day_sun){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_sun; ?>" placeholder="sunday">
                      <?php } ?>
                      <?php $emsb_service_off_day_mon = get_post_meta( get_the_ID(), 'emsb_service_off_day_mon', true );
                          if($emsb_service_off_day_mon){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_mon; ?>" placeholder="monday">
                      <?php } ?>
                      <?php $emsb_service_off_day_tues = get_post_meta( get_the_ID(), 'emsb_service_off_day_tues', true );
                          if($emsb_service_off_day_tues){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_tues; ?>" placeholder="tuesday">
                      <?php } ?>
                      <?php $emsb_service_off_day_wed = get_post_meta( get_the_ID(), 'emsb_service_off_day_wed', true );
                          if($emsb_service_off_day_wed){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_wed; ?>" placeholder="wednesday">
                      <?php } ?>
                      <?php $emsb_service_off_day_thurs = get_post_meta( get_the_ID(), 'emsb_service_off_day_thurs', true );
                          if($emsb_service_off_day_thurs){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_thurs; ?>" placeholder="thursday">
                      <?php } ?>
                      <?php $emsb_service_off_day_fri = get_post_meta( get_the_ID(), 'emsb_service_off_day_fri', true );
                          if($emsb_service_off_day_fri){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_fri; ?>" placeholder="friday">
                      <?php } ?>
                      <?php $emsb_service_off_day_sat = get_post_meta( get_the_ID(), 'emsb_service_off_day_sat', true );
                          if($emsb_service_off_day_sat){ ?>
                              <input  class="emOffDays" type="text" value="<?php echo $emsb_service_off_day_sat; ?>" placeholder="saturday">
                      <?php } ?>
                          
                    </div>
                    <!-- Full day reservation  -->
                    <div class="full-day-reservation">
                      <?php 
                          $emsb_service_full_day_reservation = get_post_meta( get_the_ID(), 'emsb_service_full_day_reservation', true ); ?>
                          <input type="checkbox" name="emsb_fullDayReserve" id="emsb_fullDayReserve" <?php echo $emsb_service_full_day_reservation; ?> />
                    </div>

                    <!-- Time slot  -->
                    <div class="em-time-slot">
                      <div class="am-time-slot">
                          <?php 
                              $emsb_service_am_starting_time = get_post_meta( get_the_ID(), 'emsb_service_am_starting_time', true );
                              if($emsb_service_am_starting_time){ ?>
                                  <input class="amSlotStarts" value="<?php echo $emsb_service_am_starting_time; ?>" />
                          <?php } else { ?>
                                  <input class="amSlotStarts" value="10:00" />
                          <?php } ?>
                          <?php 
                              $emsb_service_am_ending_time = get_post_meta( get_the_ID(), 'emsb_service_am_ending_time', true );
                              if($emsb_service_am_ending_time){ ?>
                                  <input class="amSlotEnds" value="<?php echo $emsb_service_am_ending_time; ?>" />
                          <?php } else { ?>
                                  <input class="amSlotEnds" value="11:00" />
                          <?php } ?>

                          <?php 
                              $emsb_service_am_slot_duration = get_post_meta( get_the_ID(), 'emsb_service_am_slot_duration', true );
                              if($emsb_service_am_slot_duration){ ?>
                                  <input class="amSlotDuration" type="text" name="amSlotDuration" value="<?php echo $emsb_service_am_slot_duration; ?>">
                          <?php } else { ?>
                                  <input class="amSlotDuration" type="text" name="amSlotDuration" value="160" />
                          <?php } ?>
                              
                      </div>
                      <div class="pm-time-slot">
                          <?php 
                              $emsb_service_pm_starting_time = get_post_meta( get_the_ID(), 'emsb_service_pm_starting_time', true );
                              if($emsb_service_pm_starting_time){ ?>
                                  <input class="pmSlotStarts" value="<?php echo $emsb_service_pm_starting_time; ?>" />
                          <?php } else { ?>
                                  <input class="pmSlotStarts"  value="15:00" />
                          <?php } ?>

                          <?php 
                              $emsb_service_pm_ending_time = get_post_meta( get_the_ID(), 'emsb_service_pm_ending_time', true );
                              if($emsb_service_pm_ending_time){ ?>
                                  <input class="pmSlotEnds" value="<?php echo $emsb_service_pm_ending_time; ?>" />
                          <?php } else { ?>
                                  <input class="pmSlotEnds"  value="17:00" />
                          <?php } ?>

                          <?php 
                              $emsb_service_pm_slot_duration = get_post_meta( get_the_ID(), 'emsb_service_pm_slot_duration', true );
                              if($emsb_service_pm_slot_duration){ ?>
                                  <input class="pmSlotDuration" type="text" name="pmSlotDuration" value="<?php echo $emsb_service_pm_slot_duration; ?>">
                          <?php } else { ?>
                                  <input class="pmSlotDuration" type="text" name="pmSlotDuration" value="160" />
                          <?php } ?>

                      </div>

                    </div>

                    <!--  Orders per slot  -->
                    <div class="emsb-service-booking-orders-per-slot">
                        <?php 
                            $emsb_service_orders_per_slot = get_post_meta( get_the_ID(), 'emsb_service_orders_per_slot', true );
                            if($emsb_service_orders_per_slot){ ?>
                              <input type="number" value="<?php echo $emsb_service_orders_per_slot; ?>">
                        <?php } else {  ?>
                              <input type="number" value="1">
                        <?php 
                          } 
                        ?>
                    </div>
                    <!--  ervice-provider-email  -->
                    <div class="emsb-service-provider-email">
                        <?php 
                            $emsb_service_provider_email = get_post_meta( get_the_ID(), 'emsb_service_provider_email', true );
                            if($emsb_service_provider_email){ ?>
                              <input type="email" value="<?php echo $emsb_service_provider_email; ?>">
                        <?php 
                          } 
                        ?>
                    </div>
                  </div>
                   <!-- service description -->
                   <div class="emsb-service-description">
                      <?php 
                        $emsbtexteditor_check = get_post_meta( get_the_ID(), 'emsbtexteditor_check', true );
                        if($emsbtexteditor_check){
                            $emsbtexteditor = get_post_meta( get_the_ID(), 'emsbtexteditor', true );
                            if($emsbtexteditor){ 
                              echo $emsbtexteditor; 
                              
                            }
                        }
                      ?>
                    </div>
                
            </article>

            <?php endwhile; ?> <!-- End of the loop. -->

            <div class="emsb-single-page-pagination">
              <div class="emsb-next-prev emsb-next-service">
                  <?php next_post_link( '&laquo %link', 'Next' ); ?>
              </div>
              <div class="emsb-next-prev emsb-prev-service">
                  <?php previous_post_link( '%link &raquo;', 'Prev' ); ?>
              </div>
              
            </div>
            
            

            </div>
        </div>
      </div>
    </div>
    <!-- emsb-services container ends -->

     <!-- emsb-reservation-process container -->
     <div class="em-reservation-process-container">
      <div class="em-reservation-div-position em-hide">
          <!-- All Selected Info  -->
          <div class="em-selected-all-wrapper">
            <div class="em-selected-service">
              <div class="em-get-selected-service em-service-excerpt d-flex align-items-center">
                  
              </div>
              <button  class="btn em-change-service-btn"><?php _e( 'Again Details ', 'emsb-service-booking' ); ?> </button>
            </div>
            <div class="em-selected-date-wrapper">
              <div class="em-selected-date d-flex align-items-center">
                  <label class="date"></label>
                  <button  class="btn ml-sm-5 em-change-date-btn"><?php _e( 'Change Date ', 'emsb-service-booking' ); ?> </button>
              </div>
            </div>
            <div class="em-selected-time-slot-wrapper">
              <div class="em-selected-time-slot d-flex align-items-center">
                  <label class="time-slot"></label>
                  <button  class="btn ml-sm-5 em-change-time-slot-btn"><?php _e( 'Change Slot ', 'emsb-service-booking' ); ?> </button>
              </div>
            </div>

          </div>
          
          <!-- All Selected Info Ends  -->

          <div class="em-calendar-wrapper">
              <div class="emsb-calender-loading-gif">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/loading.gif'; ?>">
              </div>
            <div class="em-reservation-calendar"></div>
          </div>

          <div class="em-timer">
              <div class="emsb-loading-gif">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/img/loading.gif'; ?>">
              </div>
            <div id="em-accordion" class="my-3 em-am-time ">
              <div class="am-or-pm">
                  <button id="amButton"  class="btn btn-light mb-2 em-am-button active mr-1"><span><?php _e( 'Before noon ( AM ) ', 'emsb-service-booking' ); ?> </span></button>
                  <button id="pmButton"  class="btn btn-light mb-2 em-pm-button ml-1" ><span> <?php _e( 'After noon ( PM ) ', 'emsb-service-booking' ); ?> </span></button>
              </div>

              <div class="slots-container">
                
                  <ul class="slots list-group" id="emShowAM"></ul>
                  <ul class="slots list-group" id="emShowPM"></ul>
              </div>
            </div>
              
          </div>

          <div class="em-booking-form-container">
            <form method="post" class="needs-validation" novalidate <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> >
              <div class="em-booking-form-fields">
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <input name="emsb_user_fullName" type="text" class="form-control" id="emsb_user_fullName" placeholder="<?php _e( 'Your Full Name ', 'emsb-service-booking' ); ?>" value="" required>
                    <div class="invalid-feedback">
                       <?php _e( 'Please Enter Your Full Name ', 'emsb-service-booking' ); ?>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                      <div >
                        <input name="emsb_user_email" type="email" class="form-control" id="emsb_user_email" placeholder="<?php _e( 'Your Email Address  ', 'emsb-service-booking' ); ?>" required>
                        <div class="invalid-feedback">
                          <?php _e( 'Please Enter Your Valid Email Address  ', 'emsb-service-booking' ); ?>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <input name="emsb_user_telephone" id="emsb_user_telephone" type="tel" pattern="\d*" maxlength="25" size="40" class="form-control"  placeholder="<?php _e( '+880 1812-345678 ', 'emsb-service-booking' ); ?>" required>
                    <div class="invalid-feedback">
                      <?php _e( 'Please Enter Your Valid Phone Number  ', 'emsb-service-booking' ); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="emsb-hidden-fields d-none">
                  <fieldset class="form-group">
                      <input type="number" name="emsb_selected_service_id" id="emsb_selected_service_id" value="112" >
                      <input type="text" name="emsb_selected_service" id="emsb_selected_service" value="service-one" >
                      <input type="text" name="emsb_selected_service_title" id="emsb_selected_service_title" value="" >
                      <input type="text" name="emsb_selected_service_location" id="emsb_selected_service_location" value="service-one" >
                      <input type="text" name="emsb_selected_service_date_id" id="emsb_selected_service_date_id" value="123123" >
                      <input type="text" name="emsb_selected_slot_id" id="emsb_selected_slot_id" value="AM112" >
                      <input type="text" name="emsb_selected_service_provider_email" id="emsb_selected_service_provider_email" value="motahar1201123@gmail.com" >
                      <input type="text" name="emsb_selected_service_date" id="emsb_selected_service_date" value="12/12/2019" >
                      <input type="text" name="emsb_selected_time_slot" id="emsb_selected_time_slot" value="12:30 PM - 1:30 PM" >
                      <input type="text" name="emsb_selected_service_price" id="emsb_selected_service_price" value="$1000" >
                      <input type="number" name="emsb_booking_slot_starts_at" id="emsb_booking_slot_starts_at" value="" >
                      <input type="number" name="emsb_service_orders_per_slot" id="emsb_service_orders_per_slot" value="" >
                      <input type="number" name="emsb_cookie_duration" id="emsb_cookie_duration" value="<?php echo $customer_cookie_duration; ?>" >
                      <input type="hidden" name="action" value="contact_form">
                      <input name="emsb-create-nonce" id="emsb-create-nonce" value="<?php echo wp_create_nonce("emsb_booked_slot_nonce"); ?>" >
                  </fieldset>
              </div>

              <button id="submitForm" class="btn btn-light em-confirm-booking-button" name="emsb_submit_booking" type="submit" value="Submit"> <?php _e( 'Confirm Booking ', 'emsb-service-booking' ); ?> </button>
            </form>
          </div>

      </div>
    </div>
    <!-- emsb-reservation-process container ends -->
  </div>
  <!-- emsb services and reservation-process container ends -->
  <?php include( plugin_dir_path( __FILE__ ) . 'emsb-booking-form.php'); ?>

  </div>
  <!-- emsb container ends -->
  

<?php

  get_footer();
?>
