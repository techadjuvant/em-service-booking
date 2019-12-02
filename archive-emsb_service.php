<?php 
get_header();
?>
    
  <div class="em-services-container">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2"> 
          <header class="d-flex justify-content-center py-4"> <h2> Book Apointment </h2> </header>

          <?php
		    /* Start the Loop */
		    while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>"  class="em-service">
                <div class="em-service-excerpt d-flex align-items-center">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail(); ?>
                    <?php endif; ?>
                    <div class="em-service-excerpt-info">
                      <h4> <?php the_title(); ?> </h4> 
                      
                      <p>
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
                            <p>Location: <?php echo $emsb_display_service_location; ?> </p>
                        <?php 
                            }
                        ?>
                        <?php 
                          $emsb_display_service_price = get_post_meta( get_the_ID(), 'emsb_display_service_price', true );
                          if($emsb_display_service_price){ ?>
                            <p class="em-reservation-service-price">Price: <b> <?php echo $emsb_display_service_price; ?> </b> </p>
                        <?php 
                            }
                        ?>
                        
                      
                      
                    </div>
                </div>
                <div class="em-service-meta-info">
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
  <div class="em-reservation-process-container">
    <div class="em-reservation-div-position em-hide">
        <div class="em-steps-container">
          <div class="em-step em-step-one active">
            <label for="step-one">1. Service</label>
            <span></span>
          </div>
          <div class="em-step em-step-two">
            <label for="step-two">2. Date</label>
            <span></span>
          </div>
          <div class="em-step em-step-three">
            <label for="step-three">3. Time</label>
            <span></span>
          </div>
          <div class="em-step em-step-four">
            <label for="step-four">4. Complete</label>
            <span></span>
            
          </div>
        </div>
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
          <div class="em-reservation-calendar"></div>
        </div>
        


        <div class="em-timer">
          <div id="em-accordion" class="my-3 em-am-time ">
            <div class="am-or-pm">
                <button id="amButton"  class="btn btn-light mb-2 em-am-button active mr-1"><span>Before noon ( AM )</span></button>
                <button id="pmButton"  class="btn btn-light mb-2 em-pm-button ml-1" ><span>After noon ( PM )</span></button>
            </div>

            

            <!-- <ul id="emShowAM" class="dynamic-slot slots list-group"></ul> -->

            <div class="slots-container">
                <ul class="slots list-group" id="emShowAM"></ul>
                <ul class="slots list-group" id="emShowPM"></ul>
            </div>
          </div>
            
        </div>

        <div class="em-booking-form-container">
          <form class="needs-validation" novalidate>
            <div class="em-booking-form-fields">
              <div class="form-row">
                <div class="col-md-12 mb-3">
                  <input name="fullName" type="text" class="form-control" id="fullName" placeholder="Full name" value="" required>
                  <div class="invalid-feedback">
                    Please Enter Your Name
                  </div>
                </div>
              </div>
              <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <div class="input-group">
                      <input name="email" type="email" class="form-control" id="email" placeholder="Email" aria-describedby="inputGroupPrepend" required>
                      <div class="invalid-feedback">
                        Please enter an Email.
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-row">
                <div class="col-md-12 mb-3">
                  <input name="telephone" type="tel" pattern="\d*" maxlength="25" size="40" class="form-control" id="telephone" placeholder="+880 1812-345678" required>
                  <div class="invalid-feedback">
                    Please Enter Your Valid Phone Number
                  </div>
                </div>
              </div>
            </div>
            <div class="em-booking-pay-fields">
                <fieldset class="form-group">
                    <div class="row">
                      <legend class="col-form-label col-sm-2 pt-0">Payment</legend>
                      <div class="col-sm-10 em-payment-options">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="payLocally" id="payLocally" value="option1" checked>
                          <label class="form-check-label" for="payLocally">
                              I will pay locally
                          </label>
                        </div>
                        <div class="form-check disabled">
                          <input class="form-check-input" type="radio" name="payOnline" id="payOnline" value="option3" disabled>
                          <label class="form-check-label" for="payOnline">
                              I will pay now ( Pro Feature )
                          </label>
                        </div>
                      </div>
                    </div>
                </fieldset>
                
            </div>
            <button id="submitForm" class="btn btn-light em-confirm-booking-button" type="submit"> Coifirm Booking </button>
          </form>
        </div>
        

    </div>
  </div>

<?php
get_footer();
?>