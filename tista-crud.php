<?php
/**
 * Plugin Name: Tista CRUD Operation
 * Plugin URI: 
 * Description: CRUD Operation
 * Version: 4.2.1
 * Author: TistaTeam
 * Author URI: 
 * Requires at least: 
 * Tested up to: 
 *
 * @package TistaTeam
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* Set plugin version constant. */
define( 'TISTA_CRUD_VERSION', '1.2.1' );

/* Debug output control. */
define( 'TISTA_CRUD_DEBUG_OUTPUT', 0 );

/* Set constant path to the plugin directory. */
define( 'TISTA_CRUD_SLUG', basename( plugin_dir_path( __FILE__ ) ) );

/* Set constant path to the main file for activation call */
define( 'TISTA_CRUD_CORE_FILE', __FILE__ );

/* Set constant path to the plugin directory. */
define( 'TISTA_CRUD_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'TISTA_CRUD_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		// Makes sure the plugin functions are defined before trying to use them.
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	define( 'TISTA_CRUD_NETWORK_ACTIVATED', is_plugin_active_for_network( TISTA_CRUD_SLUG . '/tista-crud.php' ) );

	/* Tista_Crud Class */
	require_once TISTA_CRUD_PATH . 'inc/class-tista-crud.php';

	if ( ! function_exists( 'tista_crud' ) ) :
		/**
		 * The main function responsible for returning the one true
		 * Tista_Crud Instance to functions everywhere.
		 *
		 * Use this function like you would a global variable, except
		 * without needing to declare the global.
		 *
		 * Example: <?php $tista_crud = tista_crud(); ?>
		 *
		 * @since 1.0.0
		 * @return Tista_Crud The one true Tista_Crud Instance
		 */
		function tista_crud() {
			return Tista_Crud::instance();
		}
	endif;

	/**
	 * Loads the main instance of Tista_Crud to prevent
	 * the need to use globals.
	 *
	 * This doesn't fire the activation hook correctly if done in 'after_setup_theme' hook.
	 *
	 * @since 1.0.0
	 * @return object Tista_Crud
	 */
	tista_crud();