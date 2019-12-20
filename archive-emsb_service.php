<?php 
get_header();
  global $wpdb;
  $emsb_settings_data = $wpdb->prefix . 'emsb_settings';
  $emsb_settings_data_fetch = $wpdb->get_row( "SELECT * FROM $emsb_settings_data ORDER BY id DESC LIMIT 1" );
  $customer_cookie_duration = $emsb_settings_data_fetch->customer_cookie_duration;

?>

<!-- emsb container  -->
<div class="emsb-plugin-container">
<!-- emsb-services-and-form-container -->
  <div class="emsb-services-and-form-container">
  <!-- emsb-services container -->
    <div class="em-services-container">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 offset-lg-2"> 
            <header class="d-flex justify-content-center py-4"> 
              <h2> 
                <?php 
                      $emsb_page_slug = get_page_by_path( 'emsb_service' );
                      if($emsb_page_slug){
                        echo get_the_title( $emsb_page_slug );
                      }  
                ?> 
            </h2> 
            </header>

            <?php
          /* Start the Loop */
          while ( have_posts() ) : the_post(); ?>
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
                              <p class="emsb-service-location">Location: <?php echo $emsb_display_service_location; ?> </p>
                          <?php 
                              }
                          ?>
                          <?php 
                            $emsb_display_service_price = get_post_meta( get_the_ID(), 'emsb_display_service_price', true );
                            if($emsb_display_service_price){ ?>
                              <p class="em-reservation-service-price">Price: <b id="emsb-service-price"> <?php echo $emsb_display_service_price; ?> </b> </p>
                          <?php 
                              }
                          ?>
                          
                        
                        
                      </div>
                  </div>
                  <div class="em-service-meta-info">
                      <div class="emsb-service-available-on-calendar">
                              <!-- starting date  -->
                            <?php $emsb_service_availability_starts_at = get_post_meta( get_the_ID(), 'emsb_service_availability_starts_at', true ); 
                            if($emsb_service_availability_starts_at){
                            ?>
                              <input type="text" name="emsb_service_availability_starts_at" class="emsb_service_availability_starts_at" value="<?php echo $emsb_service_availability_starts_at; ?>"/>
                            <?php } else { ?>
                              <input type="text" name="emsb_service_availability_starts_at" class="emsb_service_availability_starts_at" value="<?php echo date("Y-m-d"); ?>"/>
                            <?php } ?>
                              <!-- ending date  -->
                            <?php $emsb_service_availability_ends_at = get_post_meta( get_the_ID(), 'emsb_service_availability_ends_at', true ); 
                            if($emsb_service_availability_ends_at){ ?>
                              <input type="text" name="emsb_service_availability_ends_at" class="emsb_service_availability_ends_at" value="<?php echo $emsb_service_availability_ends_at; ?>"/>
                            <?php 
                              } else { ?>
                                <input type="text" name="emsb_service_availability_ends_at" class="emsb_service_availability_ends_at" value="<?php echo date('Y-m-d', strtotime('+1 years')); ?>"/>
                              <?php }
                            ?>
                            
                            
                      </div>
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
                    
                    <div class="full-day-reservation">
                      <?php 
                          $emsb_service_for_full_day = get_post_meta( get_the_ID(), 'emsb_service_for_full_day', true ); ?>
                          <input type="checkbox" name="emsb_fullDayReserve" id="emsb_fullDayReserve" <?php echo $emsb_service_for_full_day; ?> />
                    </div>
                    <div class="em-time-slot">
                      <div class="am-time-slot">
                          <?php 
                              $emsb_service_am_starting_time = get_post_meta( get_the_ID(), 'emsb_service_am_starting_time', true );
                              if($emsb_service_am_starting_time){ ?>
                                  <input id="amSlotStarts" class="amSlotStarts" value="<?php echo $emsb_service_am_starting_time; ?>" />
                          <?php } ?>

                          <?php 
                              $emsb_service_am_ending_time = get_post_meta( get_the_ID(), 'emsb_service_am_ending_time', true );
                              if($emsb_service_am_ending_time){ ?>
                                  <input id="amSlotEnds" class="amSlotEnds" value="<?php echo $emsb_service_am_ending_time; ?>" />
                          <?php } ?>

                          <?php 
                              $emsb_service_am_slot_duration = get_post_meta( get_the_ID(), 'emsb_service_am_slot_duration', true );
                              if($emsb_service_am_slot_duration){ ?>
                                  <input id="amSlotDuration" class="amSlotDuration" type="text" name="amSlotDuration" value="<?php echo $emsb_service_am_slot_duration; ?>">
                          <?php } ?>
                              
                      </div>
                      <div class="pm-time-slot">
                          <?php 
                              $emsb_service_pm_starting_time = get_post_meta( get_the_ID(), 'emsb_service_pm_starting_time', true );
                              if($emsb_service_pm_starting_time){ ?>
                                  <input id="pmSlotStarts" class="pmSlotStarts" value="<?php echo $emsb_service_pm_starting_time; ?>" />
                          <?php } ?>

                          <?php 
                              $emsb_service_pm_ending_time = get_post_meta( get_the_ID(), 'emsb_service_pm_ending_time', true );
                              if($emsb_service_pm_ending_time){ ?>
                                  <input id="pmSlotEnds" class="pmSlotEnds" value="<?php echo $emsb_service_pm_ending_time; ?>" />
                          <?php } ?>

                          <?php 
                              $emsb_service_pm_slot_duration = get_post_meta( get_the_ID(), 'emsb_service_pm_slot_duration', true );
                              if($emsb_service_pm_slot_duration){ ?>
                                  <input id="pmSlotDuration" class="pmSlotDuration" type="text" name="pmSlotDuration" value="<?php echo $emsb_service_pm_slot_duration; ?>">
                          <?php } ?>

                      </div>

                    </div>
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
                  <button type="button" class="btn btn-light mb-2 em-select-service-button">Select</button>
                
            </article>

            <?php endwhile; ?> <!-- End of the loop. -->
            

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
              <button  class="btn btn-light em-change-service-btn">Change Service</button>
            </div>
            <div class="em-selected-date-wrapper">
              <div class="em-selected-date d-flex align-items-center">
                  <label class="date"></label>
                  <button  class="btn btn-light ml-sm-5 em-change-date-btn">Change Date</button>
              </div>
            </div>
            <div class="em-selected-time-slot-wrapper">
              <div class="em-selected-time-slot d-flex align-items-center">
                  <label class="time-slot"></label>
                  <button  class="btn btn-light ml-sm-5 em-change-time-slot-btn">Change Slot</button>
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
                  <button id="amButton"  class="btn btn-light mb-2 em-am-button active mr-1"><span>Before noon ( AM )</span></button>
                  <button id="pmButton"  class="btn btn-light mb-2 em-pm-button ml-1" ><span>After noon ( PM )</span></button>
              </div>

              <div class="slots-container">
                
                  <ul class="slots list-group" id="emShowAM"></ul>
                  <ul class="slots list-group" id="emShowPM"></ul>
              </div>
            </div>
              
          </div>

          <div class="em-booking-form-container">
            <form method="post" class="needs-validation" novalidate>
              <div class="em-booking-form-fields">
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <input name="emsb_user_fullName" type="text" class="form-control" id="emsb_user_fullName" placeholder="Full name" value="" required>
                    <div class="invalid-feedback">
                      Please Enter Your Name
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                      <div class="input-group">
                        <input name="emsb_user_email" type="email" class="form-control" id="emsb_user_email" placeholder="Email" aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                          Please enter an Email.
                        </div>
                      </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <input name="emsb_user_telephone" id="emsb_user_telephone" type="tel" pattern="\d*" maxlength="25" size="40" class="form-control"  placeholder="+880 1812-345678" required>
                    <div class="invalid-feedback">
                      Please Enter Your Valid Phone Number
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
                      <input type="number" name="emsb_cookie_duration" id="emsb_cookie_duration" value="<?php echo $customer_cookie_duration; ?>" >
                      <input name="emsb-create-nonce" id="emsb-create-nonce" value="<?php echo wp_create_nonce("emsb_booked_slot_nonce"); ?>" >
                  </fieldset>
              </div>

              <button id="submitForm" class="btn btn-light em-confirm-booking-button" name="emsb_submit_booking" type="submit" value="Submit"> Confirm Booking </button>
            </form>
          </div>

      </div>
    </div>
    <!-- emsb-reservation-process container -->
  </div>
  <!-- emsb services and reservation-process container ends -->
  <?php include( plugin_dir_path( __FILE__ ) . 'emsb-booking-form.php'); ?>

  </div>
  <!-- emsb container ends -->
  

<?php
  get_footer();
?>
