<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      		1.3.5
 * @package    		JWD_Teams
 * @subpackage 		JWD_Teams/includes
 * @author     		JordacheWD <office@jordachewd.ro>
 */
class JWD_Teams_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 *
	 * @since    1.3.5
	 * @access   private
	 * @var      string    $textdomain    The textdomain of this plugin.
	 */
	private $textdomain;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.3.5
	 * @param      	string    $textdomain     The textdomain of the plugin.
	 */
	public function __construct($textdomain) {
		$this->textdomain = $textdomain;
	}
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->textdomain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/');
	}
}