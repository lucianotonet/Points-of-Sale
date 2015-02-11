<?php
/**
 * This class defines new "RWMB_Pos_Location_Picker_Field" field type for Meta Box class
 * 
 * @author Tran Ngoc Tuan Anh <rilwis@gmail.com>
 * @package Meta Box
 * @see http://metabox.io/?post_type=docs&p=390
 */
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;
if ( class_exists( 'RWMB_Field' ) )
{

	class RWMB_Pos_Location_Picker_Field extends RWMB_Field
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
	        $output = '<input type="text" name="%s" id="pos_search_address" class="controls" placeholder="' . __('Pesquisar endereço...','points-of-sale') . '">';
	        //$output = '<input type="text" name="%s" id="pos_search_address" class="controls" placeholder="' . __('Pesquisar endereço...','points-of-sale') . '">';
	        //echo '<input type="text" value="" aria-required="true" id="_pos_locationpicker" placeholder="' . __('Digite um endereço','points-of-sale') . '" autocomplete="off"> <br />';
	        $output .= '<div id="pos_locationpicker"></div>';
	        // echo '<a style="margin-right: 17px;float: right;margin-top: 10px;" class="button" id="UpdateMap" href="#">' . __('Update','maplistpro') . '</a>';
	        return sprintf( $output, $field['field_name'] );


			// return sprintf(
			// 	'<input type="tel" name="%s" id="%s" value="%s" pattern="\d{3}-\d{4}">',
			// 	'<input id="pos_search_address" class="controls" type="text" placeholder="' . __('Pesquisar endereço...','points-of-sale') . '">'
			// 	$field['field_name'],
			// 	$field['id'],
			// 	$meta
			// );
		}


	}
}