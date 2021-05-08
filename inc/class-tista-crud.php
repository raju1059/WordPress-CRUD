<?php
/**
 * Tista CRUD class.
 *
 * @package Tista_Crud
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Tista_Crud' ) ) :

	/**
	 * It's the main class that does all the things.
	 *
	 * @class Tista_Crud
	 * @version 4.2.1
	 * @since 1.0.0
	 */
	final class Tista_Crud {
		
		/**
		 * name
		 *
		 * @access public
		 * @var string
		 */
		public $name = '';
		/**
		 * Do we have an error? (bool)
		 *
		 * @access public
		 * @var bool
		 */
		public $has_error = false;

		/**
		 * The single class instance.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var object
		 */
		private static $_instance = null;

		/**
		 * Plugin data.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var object
		 */
		private $data;

		/**
		 * The slug.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $slug;

		/**
		 * The version number.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $version;

		/**
		 * The web URL to the plugin directory.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $plugin_url;

		/**
		 * The server path to the plugin directory.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $plugin_path;

		/**
		 * The web URL to the plugin admin page.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $page_url;

		/**
		 * The setting option name.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $option_name;

		/**
		 * Main Tista_Crud Instance
		 *
		 * Ensures only one instance of this class exists in memory at any one time.
		 *
		 * @see Tista_Crud()
		 * @uses Tista_Crud::init_globals() Setup class globals.
		 * @uses Tista_Crud::init_includes() Include required files.
		 * @uses Tista_Crud::init_actions() Setup hooks and actions.
		 *
		 * @since 1.0.0
		 * @static
		 * @return Tista_Crud.
		 * @codeCoverageIgnore
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
				self::$_instance->init_globals();
				self::$_instance->init_includes();
				self::$_instance->init_actions();
			}
			return self::$_instance;
		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @see Tista_Assistant::instance()
		 *
		 * @since 1.0.0
		 * @access private
		 * @codeCoverageIgnore
		 */
		private function __construct() {
			/* We do nothing here! */
		}

		/**
		 * You cannot clone this class.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'tista-crud' ), '1.0.0' );
		}

		/**
		 * You cannot unserialize instances of this class.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'tista-crud' ), '1.0.0' );
		}

		/**
		 * Setup the class globals.
		 *
		 * @since 1.0.0
		 * @access private
		 * @codeCoverageIgnore
		 */
		private function init_globals() {
			$this->data        = new stdClass();
			$this->version     = TISTA_CRUD_VERSION;
			$this->slug        = 'tista-crud';
			$this->option_name = self::sanitize_key( $this->slug );
			$this->plugin_url  = TISTA_CRUD_URI;
			$this->plugin_path = TISTA_CRUD_PATH;
			$this->page_url    = TISTA_CRUD_NETWORK_ACTIVATED ? network_admin_url( 'admin.php?page=' . $this->slug ) : admin_url( 'admin.php?page=' . $this->slug );
			$this->data->admin = true;

		}

		/**
		 * Include required files.
		 *
		 * @since 1.0.0
		 * @access private
		 * @codeCoverageIgnore
		 */
		private function init_includes() {
			require $this->plugin_path . '/inc/class-tista-crud-init.php';
			new Tista_Crud_Init;
		}

		/**
		 * Setup the hooks, actions and filters.
		 *
		 * @uses add_action() To add actions.
		 * @uses add_filter() To add filters.
		 *
		 * @since 1.0.0
		 * @access private
		 * @codeCoverageIgnore
		 */
		private function init_actions() {
			// Activate plugin.
			register_activation_hook( TISTA_CRUD_CORE_FILE, array( $this, 'activate' ) );

			// Deactivate plugin.
			register_deactivation_hook( TISTA_CRUD_CORE_FILE, array( $this, 'deactivate' ) );

			// Load the textdomain.
			add_action( 'init', array( $this, 'load_textdomain' ) );				
			add_action( 'admin_menu', array($this, 'tista_crud_adminmenu') );					
			add_action( 'admin_enqueue_scripts', array($this, 'tista_crud_enqueue_scripts') );			
			add_action( 'wp_ajax_tista_crud_do_ajax', array( $this, 'tista_crud_do_ajax' ) );			
		}

		/**
		 * Activate plugin.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function activate() {
			self::set_plugin_state( true );
		}
		/**
		 * Deactivate plugin.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function tista_plugin_cach() {
				// Deactivate plugin.
			register_deactivation_hook( TISTA_CRUD_CORE_FILE, array( $this, 'deactivate' ) );
		}
		/**
		 * Deactivate plugin.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function deactivate() {
			self::set_plugin_state( false );
		}

		/**
		 * Loads the plugin's translated strings.
		 *
		 * @since 1.0.0
		 * @codeCoverageIgnore
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'tista-crud', false, TISTA_CRUD_PATH . 'languages/' );
		}

		/**
		 * Sanitize data key.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param string $key An alpha numeric string to sanitize.
		 * @return string
		 */
		private function sanitize_key( $key ) {
			return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
		}

		/**
		 * Recursively converts data arrays to objects.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param array $array An array of data.
		 * @return object
		 */
		private function convert_data( $array ) {
			foreach ( (array) $array as $key => $value ) {
				if ( is_array( $value ) ) {
					$array[ $key ] = self::convert_data( $value );
				}
			}
			return (object) $array;
		}

		/**
		 * Set the `is_plugin_active` option.
		 *
		 * This setting helps determine context. Since the plugin can be included in your theme root you
		 * might want to hide the admin UI when the plugin is not activated and implement your own.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param bool $value Whether or not the plugin is active.
		 */
		private function set_plugin_state( $value ) {
			self::set_option( 'is_plugin_active', $value );
		}

		/**
		 * Set option value.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name Option name.
		 * @param mixed  $option Option data.
		 */
		public function set_option( $name, $option ) {
			$options          = self::get_options();
			$name             = self::sanitize_key( $name );
			$options[ $name ] = esc_html( $option );
			$this->set_options( $options );
		}


		/**
		 * Set option.
		 *
		 * @since 2.0.0
		 *
		 * @param mixed $options Option data.
		 */
		public function set_options( $options ) {
			TISTA_CRUD_NETWORK_ACTIVATED ? update_site_option( $this->option_name, $options ) : update_option( $this->option_name, $options );
		}

		/**
		 * Return the option settings array.
		 *
		 * @since 1.0.0
		 */
		public function get_options() {
			return TISTA_CRUD_NETWORK_ACTIVATED ? get_site_option( $this->option_name, array() ) : get_option( $this->option_name, array() );
		}

		/**
		 * Return a value from the option settings array.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name Option name.
		 * @param mixed  $default The default value if nothing is set.
		 * @return mixed
		 */
		public function get_option( $name, $default = '' ) {
			$options = self::get_options();
			$name    = self::sanitize_key( $name );
			return isset( $options[ $name ] ) ? $options[ $name ] : $default;
		}

		/**
		 * Set data.
		 *
		 * @since 1.0.0
		 *
		 * @param string $key Unique object key.
		 * @param mixed  $data Any kind of data.
		 */
		public function set_data( $key, $data ) {
			if ( ! empty( $key ) ) {
				if ( is_array( $data ) ) {
					$data = self::convert_data( $data );
				}
				$key = self::sanitize_key( $key );
				// @codingStandardsIgnoreStart
				$this->data->$key = $data;
				// @codingStandardsIgnoreEnd
			}
		}

		/**
		 * Get data.
		 *
		 * @since 1.0.0
		 *
		 * @param string $key Unique object key.
		 * @return string|object
		 */
		public function get_data( $key ) {
			return isset( $this->data->$key ) ? $this->data->$key : '';
		}

		/**
		 * Return the plugin slug.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_slug() {
			return $this->slug;
		}

		/**
		 * Return the plugin version number.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Return the plugin URL.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_plugin_url() {
			return $this->plugin_url;
		}

		/**
		 * Return the plugin path.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_plugin_path() {
			return $this->plugin_path;
		}

		/**
		 * Return the plugin page URL.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_page_url() {
			return $this->page_url;
		}

		/**
		 * Return the option settings name.
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_option_name() {
			return $this->option_name;
		}
		
		/**
		 * Register admin menu
		 *
		 * @return array
		 */
		public function tista_crud_adminmenu(  ) { 
			add_menu_page( __( 'Tista CRUD','tista-crud' ), __( 'Tista CRUD','tista-crud' ), 'read', 'tista-crud', array($this, 'tista_crud_form'));	
		}	
		/**
		 * tista CRUD form
		 *
		 * @access  public
		 */
		public function tista_crud_form(){				
			include( TISTA_CRUD_PATH . 'inc/views/html-tista-crud-form.php' );
		}		
		/**
		 * Processing form
		 */
		public function tista_crud_do_ajax() {
			check_admin_referer('tista_crud', 'tista_crud_nonce');
				if (! empty($_POST['name'])) {
					$this->tista_crud_data_process();
				}
		}
			
		/**
		 *  Data process
		 *
		 * @access  public
		 */
		public function tista_crud_data_process() {
			// @codingStandardsIgnoreLine
			 global $wpdb;
			 $wpdb->hide_errors();
						$table = $wpdb->prefix.'student';
						$data = array(
						'name' => $this->process_name(),
						);
						$format = array(
						'%s',					
						);
						$success=$wpdb->insert( $table, $data, $format ); 
		}
		
		/**
		 * Process name
		 *
		 * @access  public
		 */
		public function process_name() {
			// @codingStandardsIgnoreLine
			$post_name = ( isset( $_POST['name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			if ( '' == $post_name || esc_html__( 'Name (required)', 'tista-crud' ) == $post_name ) {
				return $this->has_error = true;
			} else {
				return $this->name = $post_name;
			}
		}
		/**
		 * Tista CRUD operation script
		 *
		 * @access  public
		 */
		public function tista_crud_enqueue_scripts() {
			wp_enqueue_style( 'tista-bootstrap-min-admin', TISTA_CRUD_URI.'/assets/css/bootstrap-min.css', '', '','screen' );		
			wp_enqueue_style( 'tista-crud-admin', TISTA_CRUD_URI.'/assets/css/smart-forms.css', '', '','screen' );	
			wp_enqueue_style( 'tista-shortcodes-admin', TISTA_CRUD_URI.'/assets/css/shortcodes.css', '', '','screen' );	
			wp_enqueue_style( 'tista-font-awesome-min-admin', TISTA_CRUD_URI.'/assets/css/font-awesome/css/font-awesome-min.css', '', '','screen' );		

			wp_enqueue_script( 'tista-crud-admin', TISTA_CRUD_URI.'/assets/js/smart-form.js', '', '',true);
			wp_enqueue_script( 'tista-bootstrap-min-admin', TISTA_CRUD_URI.'/assets/js/bootstrap-min.js', '', '',true);
			wp_enqueue_script( 'jquery' );					
		}
	}

endif;