<?php
/**
 * Tista_Crud_Init class
 *
 * @package Tista
 * @subpackage Tista CRUD operation
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( ! class_exists( 'Tista_Crud_Init' ) ) :
/**
 * Handle CRUD operation 
 */
class Tista_Crud_Init {
	
	/**
	 * The class constructor.
	 *
	 * @access public
	 */
	public function __construct() {	
		
		register_activation_hook( TISTA_CRUD_CORE_FILE, array( $this, 'create_tables' ) );	
	}	
	/**
	 * tista CRUD table
	 *
	 * @access  public
	 */
	public function create_tables() {
		global $wpdb;		
			
			$wpdb->hide_errors();
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $this->tista_crud_get_schema() );		
	}
	/**
	 * tista CRUD table
	 *
	 * @access  public
	 */
	public function tista_crud_get_schema(){
			// enter code to create tables
			global $wpdb;
			$wpdb->hide_errors();
			$sql = "CREATE TABLE {$wpdb->prefix}student (
			student_id int(11) NOT NULL AUTO_INCREMENT,
			name longtext NOT NULL,
			birthday longtext NOT NULL,
			sex longtext NOT NULL,
			religion longtext NOT NULL,
			blood_group longtext NOT NULL,
			address longtext NOT NULL,
			phone longtext NOT NULL,
			email longtext NOT NULL,
			password longtext NOT NULL,
			father_name longtext NOT NULL,
			mother_name longtext NOT NULL,
			roll longtext NOT NULL,
			transport_id  int(11) NOT NULL,
			dormitory_id  int(11) NOT NULL,
			dormitory_room_number longtext NOT NULL,

			UNIQUE KEY student_id (student_id)
			);";
			
		return $sql;
	}	
}
endif;