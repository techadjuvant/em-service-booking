<?php
add_action( 'admin_menu', array ( 'emsb_Admin_Page', 'emsb_admin_menu' ) );
/**
 * Register three admin pages and add a stylesheet and a javascript to two of
 * them only.
 *
 * @author toscho
 *
 */
class emsb_Admin_Page
{
	/**
	 * Register the pages and the style and script loader callbacks.
	 *
	 * @wp-hook admin_menu
	 * @return  void
	 */
	public static function emsb_admin_menu()
	{
		// $main is now a slug named "toplevel_page_emsb_admin_page"
		// built with get_plugin_page_hookname( $menu_slug, '' )
		$main = add_menu_page(
			'EMSB',                         // page title
			'Service Booking',                         // menu title
			// Change the capability to make the pages visible for other users.
			// See http://codex.wordpress.org/Roles_and_Capabilities
			'manage_options',                  // capability
			'emsb_admin_page',                         // menu slug
            array ( __CLASS__, 'emsb_admin_main_page' ),
            '',
            26// callback function
		);
		// $sub is now a slug named "emsb_admin_page_page_emsb_admin_page-sub"
		// built with get_plugin_page_hookname( $menu_slug, $parent_slug)
		$sub = add_submenu_page(
			'emsb_admin_page',                         // parent slug
			'Manage Bookings',                         // page title
			'Dashboard',                                // menu title
			'manage_options',                          // capability
			'emsb_admin_page',                         // menu slug
			array ( __CLASS__, 'emsb_admin_main_page' )         // callback function, same as above
		);
		/* See http://wordpress.stackexchange.com/a/49994/73 for the difference
		 * to "'admin_enqueue_scripts', $hook_suffix"
		 */
        // $text is now a slug named "emsb_admin_page_page_t5-text-included"
        // built with get_plugin_page_hookname( $menu_slug, $parent_slug)
        
        $emsb_post_type = 'emsb_service';
        /* Get CPT Object */
        $emsb_post_type_obj = get_post_type_object( $emsb_post_type );
        $emsb_services_menu_page = add_submenu_page(
            'emsb_admin_page',                             // parent slug
            $emsb_post_type_obj->labels->name,             // page title
            'All Services',        // menu title
            $emsb_post_type_obj->cap->edit_posts,          // capability
            'edit.php?post_type=' . $emsb_post_type        // menu slug
        );

        $emsb_add_new_service_menu_page = add_submenu_page(
            'emsb_admin_page',                             // parent slug
            $emsb_post_type_obj->labels->name,             // page title
            'Add Service',                                 // menu title
            $emsb_post_type_obj->cap->edit_posts,          // capability
            'post-new.php?post_type=' . $emsb_post_type    // menu slug
        );

        
        

        $emsb_bookings = add_submenu_page(
			'emsb_admin_page',                         // parent slug
			'EMSB bookings',                           // page title
			'bookings',                                // menu title
			'manage_options',                          // capability
			'emsb_admin_bookings_page',                // menu slug
			array ( __CLASS__, 'emsb_admin_bookings_page_func' )         // callback function, same as above
		);
        
		foreach ( array ( $main, $sub, $emsb_bookings, $emsb_services_menu_page) as $slug )
		{
			// make sure the style callback is used on our page only
			add_action(
				"admin_print_styles-$slug",
				array ( __CLASS__, 'enqueue_style' )
			);
			// make sure the script callback is used on our page only
			add_action(
				"admin_print_scripts-$slug",
				array ( __CLASS__, 'enqueue_script' )
            );
            
            
        }

        add_action( 'admin_enqueue_scripts', array ( __CLASS__, 'emsb_edit_services' ));
        add_action( 'admin_notices', array ( __CLASS__, 'emsb_services_header_html' ));

		
    }

    public static function emsb_services_header_html() {
        global $pagenow ,$post;
        global $post_type;

        if( $post_type == 'emsb_service' && ($pagenow == 'edit.php' || $pagenow == 'post.php') ) {
            ?>
                <div class="emsb-container">
                    <header class="emsb-admin-main-page-header-wrapper">
                        <div class="jumbotron text-center">
                            <h2 class="display-5">EM Service Booking</h2>
                            <!-- <hr> -->
                        </div>
                    </header>

                    <main class="emsb-admin-main-page-wrapper">
                        <div class="tabs">
                            <ul>
                                <li><a href="admin.php?page=emsb_admin_page" > Dashboard </a></li>
                                <li><a href="edit.php?post_type=emsb_service" class="active">Services</a></li>
                                <li><a href="post-new.php?post_type=emsb_service">Add Service</a></li>
                                <li><a href="admin.php?page=emsb_admin_bookings_page">Bookings</a></li>
                                
                            </ul>
                        </div>
                        
                    </main>

                </div>
            <?php
        }

        if( $post_type == 'emsb_service' && $pagenow == 'post-new.php' ) {
            ?>
                <div class="emsb-container">
                    <header class="emsb-admin-main-page-header-wrapper">
                        <div class="jumbotron text-center">
                            <h2 class="display-5">EM Service Booking</h2>
                            <!-- <hr> -->
                        </div>
                    </header>

                    <main class="emsb-admin-main-page-wrapper">
                        <div class="tabs">
                            <ul>
                                <li><a href="admin.php?page=emsb_admin_page" > Dashboard </a></li>
                                <li><a href="edit.php?post_type=emsb_service" >Services</a></li>
                                <li><a href="post-new.php?post_type=emsb_service" class="active">Add Service</a></li>
                                <li><a href="admin.php?page=emsb_admin_bookings_page">Bookings</a></li>
                                
                            </ul>
                        </div>
                        
                    </main>

                </div>
            <?php
        }


    }

    public static function emsb_edit_services($hook) {

        global $post;
        global $post_type;

        if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'edit.php' ) {
            if ( 'emsb_service' === $post_type ) {     
                wp_enqueue_style('bootstrap-css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css', array(), '1.1', false );
                wp_enqueue_style('style-css', plugin_dir_url(__FILE__) . 'assets/private/css/emsb_post_type.css', array(), '1.1', false );
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
                
            }
        }
        
        
    }

    
    public static function my_admin_enqueue_scripts() {
        wp_register_script( 'emsb-bookings', plugins_url( 'assets/private/js/emsb-bookings-table-scripts.js', __FILE__ ), array(), FALSE, TRUE);
		wp_enqueue_script( 'emsb-bookings' );
    }
    
    /**
	 * Load stylesheet on our admin page only.
	 *
	 * @return void
	 */
	public static function enqueue_style()
	{
		wp_register_style('emsb_bootstrap_css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ));
        wp_enqueue_style( 'emsb_bootstrap_css' );
        
        wp_register_style('emsb_custom_css', plugins_url( 'assets/private/css/style.css', __FILE__ ));
		wp_enqueue_style( 'emsb_custom_css' );
	}
	/**
	 * Load JavaScript on our admin page only.
	 *
	 * @return void
	 */
	public static function enqueue_script()
	{
		wp_register_script( 'emsb-jquery', plugins_url( 'assets/js/jquery.min.js', __FILE__ ), array(), FALSE, TRUE);
        wp_enqueue_script( 'emsb-jquery' );

        wp_register_script('popper', plugins_url( 'assets/js/popper.min.js', __FILE__ ), array(), FALSE, TRUE );
        wp_enqueue_script( 'popper' );

        wp_register_script('bootstrap', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array(), FALSE, TRUE );
        wp_enqueue_script( 'bootstrap' );
        
        wp_register_script( 'emsb-bookings', plugins_url( 'assets/private/js/emsb-bookings-table-scripts.js', __FILE__ ), array(), FALSE, TRUE);
		wp_enqueue_script( 'emsb-bookings' );
	}

	/**
	 * Print page output.
	 *
	 * @wp-hook toplevel_page_emsb_admin_page In wp-admin/admin.php do_action($page_hook).
	 * @wp-hook emsb_admin_page_page_emsb_admin_page-sub
	 * @return  void
	 */
	public static function emsb_admin_main_page()
	{
        ?>
        <div class="emsb-container">
            <header class="emsb-admin-main-page-header-wrapper">
                <div class="jumbotron text-center">
                    <h2 class="display-5">EM Service Booking</h2>
                </div>
            </header>
            <main class="emsb-admin-main-page-wrapper">
                <div class="tabs">
                    <ul>
                        <li><a href="admin.php?page=emsb_admin_page" class="active"> Dashboard </a></li>
                        <li><a href="edit.php?post_type=emsb_service">Services</a></li>
                        <li><a href="post-new.php?post_type=emsb_service">Add Service</a></li>
                        <li><a href="admin.php?page=emsb_admin_bookings_page">Bookings</a></li>
                    </ul>
                </div>
            </main>
        </div>
        
            
        <?php
    }
    
    public static function emsb_admin_bookings_page_func() {
            global $title;
            global $wpdb;
            $emsb_bookings = $wpdb->prefix . 'emsb_bookings';	
            $emsb_all_bookings_from_database = "SELECT * FROM $emsb_bookings ORDER BY id DESC";
            $emcc_order_list = $wpdb->get_results($emsb_all_bookings_from_database, ARRAY_A);
        ?> 
            <div class="emsb-container">
                <header class="emsb-admin-main-page-header-wrapper">
                    <div class="jumbotron text-center">
                        <h2 class="display-5">EM Service Booking</h2>
                        <!-- <hr> -->
                    </div>
                </header>

                <main class="emsb-admin-main-page-wrapper">
                    <div class="tabs">
                        <ul>
                            <li><a href="admin.php?page=emsb_admin_page" > Dashboard </a></li>
                            <li><a href="edit.php?post_type=emsb_service">Services</a></li>
                            <li><a href="post-new.php?post_type=emsb_service">Add Service</a></li>
                            <li><a href="admin.php?page=emsb_admin_bookings_page" class="active">Bookings</a></li>
                        </ul>
                    </div>
                    <div class="emsb-table-wrapper container text-center">
                        <div class="emsb-container">
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


                </main>
            </div>
        <?php 
    }
	
	/**
	 * List available global variables.
	 *
	 * @return void
	 */
	protected static function list_globals()
	{
		print '<h2>Global variables</h2><table class="code">';
		ksort( $GLOBALS );
		foreach ( $GLOBALS as $key => $value )
		{
			print '<tr><td>$' . esc_html( $key ) . '</td><td>';
			if ( ! is_scalar( $value ) )
			{
				print '<var>' . gettype( $value ) . '</var>';
			}
			else
			{
				if ( FALSE === $value )
					$show = '<var>FALSE</var>';
				elseif ( '' === $value )
				$show = '<var>""</var>';
				else
					$show = esc_html( $value );
				print $show;
			}
			print '</td></tr>';
		}
		print '</table>';
	}
	/**
	 * Inspect program logic.
	 *
	 * @param array $backtrace
	 * @return void
	 */
	protected static function list_backtrace( $backtrace )
	{
		print '<h2>debug_backtrace()</h2><ol class="code">';
		foreach ( $backtrace as $item )
		{
			print '<li>';
			if ( isset ( $item['class'] ) )
				print $item['class'] . $item['type'];
			print $item['function'];
			if ( isset ( $item['args'] ) )
				print '<pre>args = ' . print_r( $item['args'], TRUE ) . '</pre>';
			if ( isset ( $item['file'] ) )
				print '<br>' . $item['file'] . ' line: ' . $item['line'];
			print "\n";
		}
		print '</ol>';
	}
}