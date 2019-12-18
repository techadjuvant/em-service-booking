<?php
/*
Plugin Name:	EM-Service-Booking
Description: 	Plugin for booking feature of your service.
Author: 		Motahar Hossain
Version:		1.1.0
Author URI: 	www.e-motohar.com
License:      	GNU GENERAL PUBLIC LICENSE Version 3,
License URI: 	https://www.gnu.org/licenses/gpl-3.0.txt
*/

namespace emsb_service_booking_plugin;
/**
 * Use namespace to avoid conflict
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}




//************** Starts Custom post type service booking with meta boxes ***************//
/**
 * Class emsb_service_booking_plugin_base_class
 * @package emsb_service_booking_plugin
 *
 * Use actual name of post type for
 * easy readability.
 *
 * Potential conflicts removed by namespace
 */
if ( !class_exists( 'emsb_service_booking_plugin_base_class' ) ) {
    class emsb_service_booking_plugin_base_class {
        /**
         * @var string
         *
         * Set post type params
         */

        /**
         * Register post type
         */

        public function emsb_service_post_type() {

                $type               = 'emsb_service';
                $slug               = 'emsb_service';
                $name               = 'Services';
                $singular_name      = 'Booking Service';
                
                $labels = array(
                    'name'                  => $name,
                    'singular_name'         => $singular_name,
                    'add_new'               => 'Add New',
                    'add_new_item'          => 'Add New '   . $singular_name,
                    'edit_item'             => 'Edit '      . $singular_name,
                    'new_item'              => 'New '       . $singular_name,
                    'all_items'             => 'All '       . $name,
                    'view_item'             => 'View '      . $name,
                    'search_items'          => 'Search '    . $name,
                    'not_found'             => 'No '        . strtolower($name) . ' found',
                    'not_found_in_trash'    => 'No '        . strtolower($name) . ' found in Trash',
                    'parent_item_colon'     => '',
                    'menu_name'             => $name
                );
                $args = array(
                    'labels'                => $labels,
                    'public'                => true,
                    'publicly_queryable'    => true,
                    'show_ui'               => true,
                    'show_in_menu'          => false,
                    'query_var'             => true,
                    'rewrite'               => array( 'slug' => $slug ),
                    'capability_type'       => 'post',
                    'has_archive'           => true,
                    'hierarchical'          => true,
                    'menu_icon'             => 'dashicons-buddicons-buddypress-logo',
                    'menu_position'         => 26,
                    'supports'              => array( 'title', 'excerpt', 'thumbnail', 'author'),
                    'show_in_rest'          => true
                );
                register_post_type( $type, $args );
        }

        //adding meta box to save additional meta data for the content type
        public function emsb_add_meta_boxes_to_booking_service(){
            //add a meta box
            add_meta_box(
                'emsb_service_meta_box', //id
                'Add meta fields of the service', // $title
                array($this,'emsb_callback_to_show_the_service_meta_boxes'),  //display function
                'emsb_service', //content type 
                'normal', //context
                'high' //priority
            );
            
        }

        //displays the back-end admin output for the event information
        public function emsb_callback_to_show_the_service_meta_boxes( $post ) {
            wp_nonce_field( basename( __FILE__ ), 'emsb_nonce' );
            $emsb_service_stored_meta = get_post_meta( $post->ID );
        ?>
          
          <div class="emsb-service-header-info">
                <label for="emsb-service-header-info"> <h3> Service Main Info </h3> </label>
                <p>
                    <label for="emsb_display_service_title" class="emsb-row-title"><?php _e( "Service Sub Title:", 'emsb' )?></label>
                    <input type="text" name="emsb_display_service_title" id="emsb_display_service_title" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_display_service_title'] ) ) echo $emsb_service_stored_meta['emsb_display_service_title'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_display_service_location" class="emsb-row-location"><?php _e( "Location:", 'emsb' )?></label>
                    <input type="text" name="emsb_display_service_location" id="emsb_display_service_location" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_display_service_location'] ) ) echo $emsb_service_stored_meta['emsb_display_service_location'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_display_service_price" class="emsb-row-price"><?php _e( "Service Price:", 'emsb' )?></label>
                    <input type="text" name="emsb_display_service_price" id="emsb_display_service_price" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_display_service_price'] ) ) echo $emsb_service_stored_meta['emsb_display_service_price'][0]; ?>" />
                </p>
            </div>

            <!-- service reservation duration -->
            <div class="emsb-servation-availability-container emsb-service-meta-field"> 
                <label for="emsb-time-slot"> <h3> Service Availability </h3> </label> 
                <p>
                    <label for="emsb_service_availability_starts_at" class="emsb-row-date-availability"><?php _e( "Will be Available From: ", 'emsb' )?></label>
                    <input type="date" name="emsb_service_availability_starts_at" id="emsb_service_availability_starts_at" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_availability_starts_at'] ) ) echo $emsb_service_stored_meta['emsb_service_availability_starts_at'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_service_availability_ends_at" class="emsb-row-date-availability"><?php _e( "Will be Unavailable From: ", 'emsb' )?></label>
                    <input type="date" name="emsb_service_availability_ends_at" id="emsb_service_availability_ends_at" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_availability_ends_at'] ) ) echo $emsb_service_stored_meta['emsb_service_availability_ends_at'][0]; ?>" />
                </p>
                <footer class="blockquote-footer"> If you don't set these value, this service will always be available for future 1 year </footer>
            </div>
          
          <!-- weekly Off-days  -->
          <div class="emsb-off-days">
              <label for="emsb-off-days"> <h3> Weekly Off Days </h3> </label>
            <p>
                
                <input type="checkbox" name="emsb_service_off_day_sun" id="emsb_service_off_day_sun" value="1" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_sun'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_sun'][0], '1' ); ?> />
                <label for="emsb_service_off_day_sun" class="emsb-row-off_days"><?php _e( "Sunday:", 'emsb' )?></label>
                
                <input type="checkbox" name="emsb_service_off_day_mon" id="emsb_service_off_day_mon" value="2" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_mon'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_mon'][0], '2' ); ?> />
                <label for="emsb_service_off_day_mon" class="emsb-row-off_days"><?php _e( "Monday:", 'emsb' )?></label>
                
                <input type="checkbox" name="emsb_service_off_day_tues" id="emsb_service_off_day_tues" value="3" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_tues'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_tues'][0], '3' ); ?> />
                <label for="emsb_service_off_day_tues" class="emsb-row-off_days"><?php _e( "Tuesday:", 'emsb' )?></label>
                
                <input type="checkbox" name="emsb_service_off_day_wed" id="emsb_service_off_day_wed" value="4" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_wed'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_wed'][0], '4' ); ?> />
                <label for="emsb_service_off_day_wed" class="emsb-row-off_days"><?php _e( "Wednesday:", 'emsb' )?></label>
                
                <input type="checkbox" name="emsb_service_off_day_thurs" id="emsb_service_off_day_thurs" value="5" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_thurs'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_thurs'][0], '5' ); ?> />
                <label for="emsb_service_off_day_thurs" class="emsb-row-off_days"><?php _e( "Thursday:", 'emsb' )?></label>
                
                <input type="checkbox" name="emsb_service_off_day_fri" id="emsb_service_off_day_fri" value="6" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_fri'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_fri'][0], '6' ); ?> />
                <label for="emsb_service_off_day_fri" class="emsb-row-off_days"><?php _e( "Friday:", 'emsb' )?></label>
                
                <input type="checkbox" name="emsb_service_off_day_sat" id="emsb_service_off_day_sat" value="7" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_off_day_sat'] ) ) checked( $emsb_service_stored_meta['emsb_service_off_day_sat'][0], '7' ); ?> />
                <label for="emsb_service_off_day_sat" class="emsb-row-off_days"><?php _e( "Saturday:", 'emsb' )?></label>

            </p>
          </div>
            <!-- weekly Off-days ends  -->

            <!-- service reservation duration -->
            <div class="emsb-servation-duration-container emsb-service-meta-field"> 
                <label for="emsb-time-slot"> <h3> Full Day Reservation </h3> </label> 
                <p>
                    <label for="emsb_service_for_full_day" class="emsb-row-time_slot"><?php _e( "Is this service for full day reservation? ( 24 hours ) ", 'emsb' )?></label>
                    <input type="checkbox" name="emsb_service_for_full_day" id="emsb_service_for_full_day" value="checked" <?php if ( isset ( $emsb_service_stored_meta['emsb_service_for_full_day'] ) ) checked( $emsb_service_stored_meta['emsb_service_for_full_day'][0], 'checked' ); ?> />
                </p>
            </div>
            

             <!-- AM Time Slot  -->
            <div class="emsb-service-meta-field emsb-time-slot-container">
                <label for="emsb-time-slot"> <h3> AM Time Slot </h3> </label>
                <p>
                    <label for="emsb_service_am_starting_time" class="emsb-row-time_slot"><?php _e( "Starting Time:", 'emsb' )?></label>
                    <input min='00:00' max= '11:00' type="time" name="emsb_service_am_starting_time" id="emsb_service_am_starting_time" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_am_starting_time'] ) ) echo $emsb_service_stored_meta['emsb_service_am_starting_time'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_service_am_ending_time" class="emsb-row-time_slot"><?php _e( "Ending Time:", 'emsb' )?></label>
                    <input min='01:00' max= '11:59' type="time" name="emsb_service_am_ending_time" id="emsb_service_am_ending_time" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_am_ending_time'] ) ) echo $emsb_service_stored_meta['emsb_service_am_ending_time'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_service_am_slot_duration" class="emsb-row-time_slot"><?php _e( "Slot Duration in Munites:", 'emsb' )?></label>
                    <input type="number" name="emsb_service_am_slot_duration" id="emsb_service_am_slot_duration" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_am_slot_duration'] ) ) echo $emsb_service_stored_meta['emsb_service_am_slot_duration'][0]; ?>" />
                </p>
            </div>
            <!-- AM Time Slot Ends  -->

            <!-- PM Time Slot Starts  -->
            <div class="emsb-service-meta-field emsb-time-slot-container">
                <label for="emsb-time-slot"> <h3> PM Time Slot </h3> </label>
                <p>
                    <label for="emsb_service_pm_starting_time" class="emsb-row-time_slot"><?php _e( "Starting Time:", 'emsb' )?></label>
                    <input min='12:00' max= '22:59' type="time" name="emsb_service_pm_starting_time" id="emsb_service_pm_starting_time" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_pm_starting_time'] ) ) echo $emsb_service_stored_meta['emsb_service_pm_starting_time'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_service_pm_ending_time" class="emsb-row-time_slot"><?php _e( "Ending Time:", 'emsb' )?></label>
                    <input min='13:00' max= '23:59' type="time" name="emsb_service_pm_ending_time" id="emsb_service_pm_ending_time" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_pm_ending_time'] ) ) echo $emsb_service_stored_meta['emsb_service_pm_ending_time'][0]; ?>" />
                </p>
                <p>
                    <label for="emsb_service_pm_slot_duration" class="emsb-row-time_slot"><?php _e( "Slot Duration in Munites:", 'emsb' )?></label>
                    <input type="number" name="emsb_service_pm_slot_duration" id="emsb_service_pm_slot_duration" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_pm_slot_duration'] ) ) echo $emsb_service_stored_meta['emsb_service_pm_slot_duration'][0]; ?>" />
                </p>
            </div>
          <!-- PM Time Slot Ends  -->

          <!-- Service Provider Email Address Starts  -->
          <div class="emsb-service-meta-field">
                <label for="emsb-time-slot"> <h3> Service Provider Email Address </h3> </label>
                <p>
                    <label for="emsb_service_provider_email" class="emsb-row-email"><?php _e( "Email Address: ", 'emsb' )?><?php echo get_the_author_meta('user_email'); ?></label>
                    <input type="email" name="emsb_service_provider_email" id="emsb_service_provider_email" value="<?php if ( isset ( $emsb_service_stored_meta['emsb_service_provider_email'] ) ) echo $emsb_service_stored_meta['emsb_service_provider_email'][0]; ?>" />
                </p>

            </div>
          <!-- Service Provider Email Address Ends  -->
          
          
        <?php }

        // *************** Save all post types meta fields ************* //
        public function emsb_save_all_posts_types_meta_fields_meta( $post_id ) {
            // Checks save status 
            $is_autosave = wp_is_post_autosave( $post_id );
            $is_revision = wp_is_post_revision( $post_id );
            $is_valid_nonce = ( isset( $_POST[ 'emsb_nonce' ] ) && wp_verify_nonce( $_POST[ 'emsb_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

            // Exits script depending on save status 
            if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
                return;
            }
        // *************** post-type: Reviews **************//

            // Checks for input and sanitizes/saves if needed 
            if( isset( $_POST[ 'emsb_display_service_title' ] ) ) {
                update_post_meta( $post_id, 'emsb_display_service_title', $_POST[ 'emsb_display_service_title' ] );
            }
            if( isset( $_POST[ 'emsb_display_service_location' ] ) ) {
                update_post_meta( $post_id, 'emsb_display_service_location', $_POST[ 'emsb_display_service_location' ] );
            }
            if( isset( $_POST[ 'emsb_display_service_price' ] ) ) {
                update_post_meta( $post_id, 'emsb_display_service_price', $_POST[ 'emsb_display_service_price' ] );
            }

            // *************** Service Availability starts at **************//
            if( isset( $_POST[ 'emsb_service_availability_starts_at' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_availability_starts_at', $_POST[ 'emsb_service_availability_starts_at' ] );
            }

            // *************** Service Availability starts at **************//
            if( isset( $_POST[ 'emsb_service_availability_ends_at' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_availability_ends_at', $_POST[ 'emsb_service_availability_ends_at' ] );
            }

            // *************** Off-days **************//
            if( isset( $_POST[ 'emsb_service_off_day_sun' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_sun', '1' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_sun', '' );
            }

            if( isset( $_POST[ 'emsb_service_off_day_mon' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_mon', '2' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_mon', '' );
            }

            if( isset( $_POST[ 'emsb_service_off_day_tues' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_tues', '3' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_tues', '' );
            }

            if( isset( $_POST[ 'emsb_service_off_day_wed' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_wed', '4' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_wed', '' );
            }

            if( isset( $_POST[ 'emsb_service_off_day_thurs' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_thurs', '5' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_thurs', '' );
            }

            if( isset( $_POST[ 'emsb_service_off_day_fri' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_fri', '6' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_fri', '' );
            }

            if( isset( $_POST[ 'emsb_service_off_day_sat' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_off_day_sat', '7' );
            } else {
                update_post_meta( $post_id, 'emsb_service_off_day_sat', '' );
            }

            // *************** Full Day Reservation **************//
            if( isset( $_POST[ 'emsb_service_for_full_day' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_for_full_day', $_POST[ 'emsb_service_for_full_day' ] );
            }

            // *************** AM Time slot **************//
            if( isset( $_POST[ 'emsb_service_am_starting_time' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_am_starting_time', $_POST[ 'emsb_service_am_starting_time' ] );
            }
            if( isset( $_POST[ 'emsb_service_am_ending_time' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_am_ending_time', $_POST[ 'emsb_service_am_ending_time' ] );
            }
            if( isset( $_POST[ 'emsb_service_am_slot_duration' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_am_slot_duration', $_POST[ 'emsb_service_am_slot_duration' ] );
            }

            // *************** PM Time slot **************//
            if( isset( $_POST[ 'emsb_service_pm_starting_time' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_pm_starting_time', $_POST[ 'emsb_service_pm_starting_time' ] );
            }
            if( isset( $_POST[ 'emsb_service_pm_ending_time' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_pm_ending_time', $_POST[ 'emsb_service_pm_ending_time' ] );
            }
            if( isset( $_POST[ 'emsb_service_pm_slot_duration' ] ) ) {
                update_post_meta( $post_id, 'emsb_service_pm_slot_duration', $_POST[ 'emsb_service_pm_slot_duration' ] );
            }


            // *************** Service Provider Email Address **************//
            $email_address = get_bloginfo('admin_email');
            if(isset( $_POST[ 'emsb_service_provider_email' ] )) {
                update_post_meta( $post_id, 'emsb_service_provider_email', $_POST[ 'emsb_service_provider_email' ] );
            } else {
                update_post_meta( $post_id, 'emsb_service_provider_email', $email_address );
            }
            


        }

        
        
        public function emsb_get_archive_template( $archive_template ) {
            global $post;
        
            if ( is_post_type_archive ( 'emsb_service' ) ) {
                 $archive_template = dirname( __FILE__ ) . '/archive-emsb_service.php';
            }
            return $archive_template;
        }

        public function emsb_get_single_template( $emsb_single_service ) {
            global $post;
            // global $post_type;
        
            if ( is_singular( 'emsb_service' ) ) {
                 $emsb_single_service = dirname( __FILE__ ) . '/single-emsb_service.php';
            }
            return $emsb_single_service;
        }

        
         

        public function em_reservation_enqueue_public_scripts(){
  
            wp_enqueue_style('bootstrap-css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', array(), '1.1', false );
            wp_enqueue_style('aicon-style', plugin_dir_url(__FILE__) . 'calendar/aicon/style.css', array(), '1.1', false );
            wp_enqueue_style('pseudo-ripple', plugin_dir_url(__FILE__) . 'calendar/css/jquery-pseudo-ripple.css', array(), '1.1', false );
            wp_enqueue_style('nao-calendar', plugin_dir_url(__FILE__) . 'calendar/css/jquery-nao-calendar.css', array(), '1.1', false );
            wp_enqueue_style('emr-style', plugin_dir_url(__FILE__) . 'assets/public/css/style.css', array(), '1.1', false );

            wp_enqueue_script('jquery-js', plugin_dir_url(__FILE__) . 'assets/js/jquery.min.js', array(), '1.1', true );
            wp_localize_script( 'jquery-js', 'frontend_ajax_object',
                array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'data_var_1' => 'value 1',
                    'data_var_2' => 'value 2',
                )
            );
            wp_enqueue_script('popper-js', plugin_dir_url(__FILE__) . 'assets/js/popper.min.js', array(), '1.1', true );
            wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array(), '1.1', true );
            wp_enqueue_script('pseudo-ripple-js', plugin_dir_url(__FILE__) . 'calendar/jquery-pseudo-ripple.js', array(), '1.1', true );
            wp_enqueue_script('nao-calendar-js', plugin_dir_url(__FILE__) . 'calendar/jquery-nao-calendar.js', array(), '1.1', true );
            wp_enqueue_script('html2canvas', plugin_dir_url(__FILE__) . 'assets/public/js/html2canvas.js', array(), '1.1', true );
            wp_enqueue_script('jsPDF', plugin_dir_url(__FILE__) . 'assets/public/js/jsPDF.js', array(), '1.1', true );
            
            wp_enqueue_script('emr-script-js', plugin_dir_url(__FILE__) . 'assets/public/js/script.js', array(), '1.1', true );
            

        }



        /**
         * When class is instantiated
         */
        public function __construct() {
            
            add_action('init', array($this, 'emsb_service_post_type'));// Register the post type
            add_action('add_meta_boxes', array($this,'emsb_add_meta_boxes_to_booking_service')); //add meta boxes
            add_action('save_post', array($this,'emsb_save_all_posts_types_meta_fields_meta')); //add meta boxes
            add_filter( 'archive_template',  array($this,'emsb_get_archive_template') ) ; 
            add_filter( 'single_template',  array($this,'emsb_get_single_template') ) ; 
            add_action( 'wp_enqueue_scripts', array($this,'em_reservation_enqueue_public_scripts')  );
            
        }
                
                
                
    }
}
/**
 * Instantiate class
 */
$emsb_service_booking_plugin = new emsb_service_booking_plugin_base_class();



class emsb_database {
    public function __construct() {
        register_activation_hook( __FILE__, array($this,'create_emsb_bookings_table'));
        register_activation_hook( __FILE__, array($this,'emsb_default_booking'));
        register_activation_hook( __FILE__, array($this,'create_emsb_settings_table'));
        register_activation_hook( __FILE__, array($this,'emsb_settings_default_data'));
        
    }

    public function create_emsb_bookings_table(){
            global $wpdb;
            $sql_enquiry = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}emsb_bookings (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `service_id` int(11) NOT NULL,
            `service_name` varchar(200),
            `service_price` text,
            `booked_date_id` text,
            `booked_slot_id` text,
            `booked_date` text,
            `booked_time_slot` text,
            `customer_name` varchar(200),
            `customer_email` text,
            `customer_phone` text
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_enquiry );
        

    }

    public function emsb_default_booking(){
        global $wpdb;
        $emsb_bookings_table_name = $wpdb->prefix . "emsb_bookings";
    
        $dataInsert = $wpdb->insert( $emsb_bookings_table_name, array(
            'service_id' => 0,
            'service_name' => "Default Data",
            'service_price' => "Default Data",
            'booked_date_id' => "Default Data",
            'booked_slot_id' => "Default Data",
            'booked_date' => "Default Data",
            'booked_time_slot' => "Default Data",
            'customer_name' => "Default Data",
            'customer_email' => "Default Data",
            'customer_phone' => "Default Data"
        ) );
    

    }

    public function create_emsb_settings_table(){
            global $wpdb;
            $sql_enquiry_notifiction_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}emsb_settings (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `admin_mail_subject` text,
            `admin_mail_body` text,
            `customer_mail_subject` text,
            `customer_mail_body` text,
            `customer_cookie_duration` int(11)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_enquiry_notifiction_table );

    }
    public function emsb_settings_default_data() {
		global $wpdb;
        $emsb_settings_data = $wpdb->prefix . 'emsb_settings';
		$wpdb->insert($emsb_settings_data, array(
            'admin_mail_subject' => "Service provider's email subject",
            'admin_mail_body' => "Service provider's email message body",
            'customer_mail_subject' => "Customer's email subject",
            'customer_mail_body' => "Customer's email message body",
            'customer_cookie_duration' => 30
        ));

    }
    

}
$emsb_database = new emsb_database();


class emsb_post_pages {
    public function __construct() {
        $emsb_page_slug = get_page_by_path( 'emsb_service' );
        if(!$emsb_page_slug){
            register_activation_hook( __FILE__, array($this,'emsb_create_archive_page'));
        } 
        
        
    }

    public function emsb_create_archive_page(){

        $my_post  = array( 'post_title'     => 'Servie-Archive',
                        'post_type'      => 'page',
                        'post_name'      => 'emsb_service',
                        'post_status'    => 'publish',
                        'comment_status' => 'closed'
                    );

        $PageID = wp_insert_post( $my_post, FALSE ); // Get Post ID - FALSE to return 0 instead of wp_error.

    }

    
}
$emsb_post_pages = new emsb_post_pages();


include( plugin_dir_path( __FILE__ ) . 'emsb-ajax-calls.php');
include( plugin_dir_path( __FILE__ ) . 'emsb-admin-pages.php');
