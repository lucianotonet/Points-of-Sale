<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'RWMB_Field' ) )
{
	class RWMB_Poslocationpicker_Field extends RWMB_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static public function html( $meta, $field )
		{			        
	        //Display the map
	        $output = '<input type="text" name="%s" id="pos_search_address" class="controls" placeholder="' . __('Pesquisar endereço...','points_of_sale') . '">';
	        
	        $output .= '<div id="pos_locationpicker"></div>';
	        
	        return sprintf( $output, $field['field_name'],	$meta );

	    }

	    /**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function normalize_field( $field )
		{
			$field = wp_parse_args( $field, array(				
				'placeholder' => '',
			) );

			return $field;
		}
	}
}