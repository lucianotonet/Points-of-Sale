<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://lucianotonet.com
 * @since      1.0.0
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/admin
 * @author     Luciano Tonet <contato@lucianotonet.com>
 */
class Points_Of_Sale_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/points-of-sale-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if( $hook != 'post.php' && $hook != 'post-new.php' ) 
			return;

		//DEFINE LANGUAGE OF GOOGLE MAPS
        $google_map_language = 'pt';

        $languageString = '';
        if($google_map_language != '' && $google_map_language != 'pt'){
            $languageString = '&language=' . $google_map_language;
        }

        // INCLUDE THE .JS
        if (is_ssl()) {
            wp_enqueue_script( 'pos_google_maps', 'https://maps.googleapis.com/maps/api/js?sensor=true&libraries=places' . $languageString);
        }
        else{
            wp_enqueue_script( 'pos_google_maps', 'http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places' . $languageString);
        }
		
		wp_enqueue_script( 'pos_locationpicker', plugin_dir_url( __FILE__ ) . 'js/locationpicker.jquery.min.js', array( 'jquery' ), $this->version, false  );			

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/points-of-sale-admin.js', array( 'pos_google_maps', 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_locationpicker() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		

	}
	
}
