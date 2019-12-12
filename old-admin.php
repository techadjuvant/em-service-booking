<?php
class emsb_admin_page {
    public function __construct() {
        add_action( 'admin_menu', array($this,'emsb_admin_menu')  );
        add_action( 'admin_menu', array($this,'emsb_admin_sub_menu')  );
        add_action( 'admin_print_scripts-$hook', array($this,'emsb_enqueue_admin_scripts')  );
        
    }

    public function emsb_admin_menu() {
        global $em_calc_admin_page;
        $hook = add_menu_page( 'Service Booking', 'Service Booking', 'manage_options', 'emsb_service_booking', array($this,'order_settings_page'));
       
    }

    public function emsb_admin_sub_menu() { 
        $emsb_post_type = 'emsb_service';
        /* Get CPT Object */
        $emsb_post_type_obj = get_post_type_object( $emsb_post_type );
        add_submenu_page(
            'emsb_service_booking',                      // parent slug
            $emsb_post_type_obj->labels->name,             // page title
            $emsb_post_type_obj->labels->menu_name,        // menu title
            $emsb_post_type_obj->cap->edit_posts,          // capability
            'edit.php?post_type=' . $emsb_post_type        // menu slug
        );  
     }

     public function emsb_enqueue_admin_scripts($hook) {
        if ('edit.php' == $hook) {
            return;
        }
           
            wp_enqueue_style('bootstrap-css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', array(), '1.1', false );
            wp_enqueue_style('style-css', plugin_dir_url(__FILE__) . 'assets/private/css/style.css', array(), '1.1', false );
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
            wp_enqueue_script('bookings-table-js', plugin_dir_url(__FILE__) . 'assets/private/js/emsb-bookings-table-scripts.js', array(), '1.1', true );
            
        
        
    }

     public function order_settings_page(){

        global $wpdb;
        $emsb_bookings = $wpdb->prefix . 'emsb_bookings';	
        $emsb_all_bookings_from_database = "SELECT * FROM $emsb_bookings ORDER BY id DESC";
        $emcc_order_list = $wpdb->get_results($emsb_all_bookings_from_database, ARRAY_A);

        ?>
            <div class="emsb-table-wrapper text-center">
                <div class="emsb-heading mt-5">
                    <h2>All Bookings list</h2>
                </div>
                <div class="container">
                    <div class="header_wrap">
                        <div class="num_rows">
                            <div class="form-group"> 	<!--		Show Numbers Of Rows 		-->
                                    <select class  ="form-control" name="state" id="maxRows">
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="70">70</option>
                                        <option value="100">100</option>
                                        <option value="5000">Show ALL Rows</option>
                                    </select>
                                </div>
                            </div>
                        <div class="tb_search">
                            <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
                        </div>
                    </div>
                    <table class="table table-striped table-class" id= "table-id">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Booked Date</th>
                                <th>Booked Time Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                foreach($emcc_order_list as $emcc_order_list) : 
                            ?>
                                <tr>
                                    <td><?php echo $emcc_order_list['service_name']; ?></td>
                                    <td><?php echo $emcc_order_list['customer_name']; ?></td>
                                    <td><?php echo $emcc_order_list['customer_phone']; ?></td>
                                    <td><?php echo $emcc_order_list['customer_email']; ?></td>
                                    <td><?php echo $emcc_order_list['booked_date']; ?></td>
                                    <td><?php echo $emcc_order_list['booked_time_slot']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        
                        <tbody>
                    </table>
                
                        <!--    Start Pagination -->
                        <div class='pagination-container'>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <!--	Here the JS Function Will Add the Rows -->
                                </ul>
                            </nav>
                        </div>
                    <div class="rows_count">Showing 11 to 20 of 91 entries</div>
                
                </div> <!-- 		End of Container -->

            </div>

        <?php
    }
}
$emsb_admin = new emsb_admin_page();
