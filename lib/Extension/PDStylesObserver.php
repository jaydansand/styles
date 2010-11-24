<?php

/**
 * PDStyles_Extension_Observer
 *
 * Observer class to implement the observer method
 * 
 * @package 		pdstyles
 * @author 			Paul Clark <pdclark@pdclark.com>
 * @copyright 		2009-2010 Paul Clark. All rights reserved.
 * @license 		http://opensource.org/licenses/bsd-license.php  New BSD License
 */
abstract class PDStyles_Extension_Observer extends Scaffold_Extension_Observer
{
	/**
	 * Form element ID
	 * 
	 * @since 0.1
	 * @var string
	 **/
	var $form_id;
	
	/**
	 * Form element name for DB insert
	 * 
	 * @since 0.1
	 * @var string
	 **/
	var $form_name;
	
	/**
	 * Variable key in array
	 * 
	 * @since 0.1
	 * @var string
	 **/
	var $key;
	
	/**
	 * Nice text name for display in element label
	 * 
	 * @since 0.1
	 * @var string
	 **/
	var $label;
	
	/**
	 * Array of values loaded from database
	 * 
	 * @since 0.1
	 * @var array
	 **/
	var $values;
	
	/**
	 * Variable values to match this object to
	 * @since 0.1
	 * @var array
	 */
	var $keywords = array();
	
	/**
	 * Arguments passed from CSS
	 * 
	 * @since 0.1
	 * @var array
	 **/
	var $args = array();

	/**
	 * Attaches the observer to the observable class
	 *
	 * @param $observer
	 * @access public
	 * @return void
	 */
	public function __construct( $args = array(), Scaffold_Extension_Observable $observable = null)
	{
		if ( !is_null($observable)) {
			parent::construct( $observable );
		}
		
		$defaults = array(
			// 'default'		=> '',
		);
		$args = wp_parse_args( $args, $defaults );
		
		$this->key = $args['key'];
		$this->label = $args['label'];
		$this->method = $args['method'];
		
		$this->form_name = "{$args['form_name']}[$this->key]";
		$this->form_id = 'pds_'.md5( $this->form_name );
		
		unset( $args['method'], $args['key'], $args['label'], $args['form_name'] );
		
		$this->args = $args;
	}
	
	/**
	 * Get value with correct formatting
	 * 
	 * @since 0.1
	 * @return string
	 **/
	function value( $context = null, $key = null ) {
		
		$css_method = $this->method;
		if ($context == 'css' && method_exists( $this, $css_method ) ) {
			return $this->$css_method();
		}
		
		$method = $context.'_value';
		if ( method_exists( $this, $method ) ) {
			return $this->$method( $key );
		}else {
			return $this->values;
		}
	}
	
	/**
	 * Return value for output in form element
	 * 
	 * @since 0.1
	 * @return string
	 **/
	function form_value($key = null) {
		return $this->values[$key];
	}
	
	abstract function set( $variable, $value, $context = 'default' );
	abstract function output();
	
	
}