<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://lucianotonet.com
 * @since      1.0.0
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/includes
 * @author     Luciano Tonet <contato@lucianotonet.com>
 */
class Points_Of_Sale_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	//TODO: Add a setting for this
    //Post types that can be used for locations - Add your post type here if you want to use them as locations
    public $post_types_to_use = array('point_of_sale');

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @var      string               $hook             The name of the WordPress action that is being registered.
	 * @var      object               $component        A reference to the instance of the object on which the action is defined.
	 * @var      string               $callback         The name of the function definition on the $component.
	 * @var      int      Optional    $priority         The priority at which the function should be fired.
	 * @var      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @var      string               $hook             The name of the WordPress filter that is being registered.
	 * @var      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @var      string               $callback         The name of the function definition on the $component.
	 * @var      int      Optional    $priority         The priority at which the function should be fired.
	 * @var      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @var      string               $hook             The name of the WordPress filter that is being registered.
	 * @var      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @var      string               $callback         The name of the function definition on the $component.
	 * @var      int      Optional    $priority         The priority at which the function should be fired.
	 * @var      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   type                                   The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}		

		// Hook into the 'init' action
		add_action( 'init', array( $this, 'register_pos_cpt' ), 0 );        

		//Add meta boxes to point of sale       
        //add_action( 'init', array( $this, 'configure_pos_meta_boxes' ), 0 );      

        add_filter( 'cmb_meta_boxes', array( $this, 'pos_meta_boxes' ) );

        
        add_action( 'init', array( $this, 'initialize_pos_meta_boxes' ), 9 );     

        //Add map option to metaboxes
        add_action( 'cmb_render_map', array($this,'pos_render_map'), 10, 2 );   

	}


	
	/***********************************
     * 	POINTS OF SALE
     *
     * Register "Point Of Sale" Custom Post Type */

	public function register_pos_cpt() {

		$labels = array(
			'name'                => _x( 'Pontos de Venda', 'Post Type General Name', 'points_of_sale' ),
			'singular_name'       => _x( 'Ponto de Venda', 'Post Type Singular Name', 'points_of_sale' ),
			'menu_name'           => __( 'PDV\'s', 'points_of_sale' ),
			'parent_item_colon'   => __( 'Pai:', 'points_of_sale' ),
			'all_items'           => __( 'Todos os Pontos de Venda', 'points_of_sale' ),
			'view_item'           => __( 'Ver Ponto de Venda', 'points_of_sale' ),
			'add_new_item'        => __( 'Adicionar novo Ponto de Venda', 'points_of_sale' ),
			'add_new'             => __( 'Adicionar novo', 'points_of_sale' ),
			'edit_item'           => __( 'Editar Ponto de Venda', 'points_of_sale' ),
			'update_item'         => __( 'Atualizar Ponto de Venda', 'points_of_sale' ),
			'search_items'        => __( 'Buscar Ponto de Venda', 'points_of_sale' ),
			'not_found'           => __( 'Nada encontrado', 'points_of_sale' ),
			'not_found_in_trash'  => __( 'Nada encontrado no lixo', 'points_of_sale' ),
		);
		$args = array(
			'label'               => __( 'points_of_sale', 'points_of_sale' ),
			'description'         => __( 'Pontos de venda onde os seus clientes encontram os seus produtos', 'points_of_sale' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions' ),			
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-store',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'point_of_sale', $args );

	}



	/**
	 * Define the metabox and field configurations.
	 *
	 * @param  array $meta_boxes
	 * @return array
	 */
	public function pos_meta_boxes( array $meta_boxes ) {

		// echo "<pre>";
		// print_r( $this->post_types_to_use );
		// echo "</pre>";
		// exit;



		// Start with an underscore to hide fields from custom fields list
		$prefix = '_pos_';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$meta_boxes['test_metabox'] = array(
			'id'         => 'details_metabox',
			'title'      => __( 'Detalhes do Ponto de Venda', 'points-of-sale' ),
			'pages'      => $this->post_types_to_use, // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
			'fields'     => array(
			    array(
                        // 'name' 	=> __('Local','points-of-sale'),
                        'desc' 	=> __('Location picker','points-of-sale'),
                        'id' 	=> $prefix . 'map',
                        'type' 	=> 'map'),				
			    array(
                		'name' => __('Estado','points_of_sale'),                		
                		'id'   => $prefix . 'state',
                		'type' => 'text'),
			    array(
                		'name' => __('Cidade','points_of_sale'),                		
                		'id'   => $prefix . 'city',
                		'type' => 'text'),
			    array(
                		'name' => __('Bairro','points_of_sale'),                		
                		'id'   => $prefix . 'neighborhood',
                		'type' => 'text'),
			    array(
                		'name' => __('Rua','points_of_sale'),                		
                		'id'   => $prefix . 'street',
                		'type' => 'text'),
			    array(
                		'name' => __('Número','points_of_sale'),                		
                		'id'   => $prefix . 'number',
                		'type' => 'text'),
			    array(
                		'name' => __('CEP','points_of_sale'),                		
                		'id'   => $prefix . 'postal_code',
                		'type' => 'text'),

                array(
                		'name' => __('E-mail','points_of_sale'),                		
                		'id' => $prefix . 'email',
                		'type' => 'text'),
                array(
                		'name' => __('Telefone','points_of_sale'),                		
                		'id' => $prefix . 'phone',
                		'type' => 'text'),
                array(
						'name' => __( 'Marcador', 'points_of_sale' ),						
						'id'   => $prefix . 'marker',
						'type' => 'file'),
                
                array(
                		'name' => __('Latitude','points_of_sale'),                		
                		'id'   => $prefix . 'latitude',                		
                		'type' => 'text'),
                array(
                		'name' => __('Longitude','points_of_sale'),                		
                		'id' => $prefix . 'longitude',
                		'type' => 'text'),    
                // array(
                // 		'name' => __('Descrição','points_of_sale'),                		
                // 		'id' => $prefix . 'content',
                // 		'type' => 'textarea_small'),
     //            array(
					// 	'name' => __( 'Marcador', 'points_of_sale' ),						
					// 	'id'   => $prefix . 'marker',
					// 	'type' => 'file',
					// ),
   				
			),
		);
		

		// Add other metaboxes as needed

		return $meta_boxes;
	}


	/***********************************
	* 	INITIALIZE META BOXES of POINTS OF SALE
	*
	*/
	public function initialize_pos_meta_boxes(){
		/**
		 * Initialize the metabox class.
		 */	
		if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'metaboxes/init.php';	
	}

	/***********************************
	* 	RENDERIZE MAP LOCATION PICKER
	*
	*/
	public function pos_render_map( $field, $meta ) {

        //Add Google maps
        



        //Get start position	        
        $defaultEditMapLocationLat = '-28.3043001';	        
        $defaultEditMapLocationLong = '-52.3880269';	        
        $defaultEditMapZoom = '16';

        if($defaultEditMapLocationLat == '' || $defaultEditMapLocationLong == ''){
            $defaultEditMapLocationLat = '40.3';
            $defaultEditMapLocationLong ='-98.2' ;
        }

        if($defaultEditMapZoom == '' || $defaultEditMapZoom == 'None' ){
            $defaultEditMapZoom = 4;
        }

        wp_localize_script( 'pos_google_maps', 'maplocationdata', array(
                           													'defaultEditMapLocationLat'  => $defaultEditMapLocationLat,
                           													'defaultEditMapLocationLong' => $defaultEditMapLocationLong,
                           													'defaultEditMapZoom'		 => $defaultEditMapZoom ));

        //Display the map
        echo '<input id="pos_search_address" class="controls" type="text" placeholder="Pesquisar endereço...">';
        //echo '<input type="text" value="" aria-required="true" id="_pos_locationpicker" placeholder="' . __('Digite um endereço','points-of-sale') . '" autocomplete="off"> <br />';
        echo '<div id="pos_locationpicker"></div>';
        // echo '<a style="margin-right: 17px;float: right;margin-top: 10px;" class="button" id="UpdateMap" href="#">' . __('Update','maplistpro') . '</a>';

        ?>

        

    <?php
      
    }


}