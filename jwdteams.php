<?php
/**
 * Plugin Name:       JWD Teams
 * Plugin URI:        http://jordachewd.com/jwd-teams/
 * Description:       Create unlimited teams and display them through a generated shortcode. Easily.
 * Version:           1.5.3
 * Author:            JordacheWD
 * Author URI:        http://jordachewd.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jwdteams
 * Domain Path:       /languages
 *
 * 
 * JWD Teams is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with JWD Teams. If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 * @link              http://jordachewd.com
 * @since             1.3.5
 * @package           JWD_Teams
 *
 */
 /** 
  * If this file is called directly, abort.
  */
if ( ! defined( 'WPINC' ) ) { die; }
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jwdteams.php';
/**
 * Begins execution of the plugin.
 *
 * @since    1.3.5
 */
 if ( ! function_exists( 'run_jwdteams' ) ) {
	function run_jwdteams() {
		$plugin_file = basename( __FILE__ );
		$plugin_dir = plugin_basename( dirname( __FILE__ ) );
		$JWD_Teams = new JWD_Teams($plugin_file, $plugin_dir);
		$JWD_Teams->run();
	}
	run_jwdteams();
}