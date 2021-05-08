<?php
/**
 * Handler for contact pages.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'Tista_Contact' ) ) :
/**
 * Handle contact pages.
 */
class Tista_Contact {

	/**
	 * The recaptcha class instance
	 *
	 * @access public
	 * @var bool|object
	 */
	public $re_captcha = false;

	/**
	 * Do we have an error? (bool)
	 *
	 * @access public
	 * @var bool
	 */
	public $has_error = false;

	/**
	 * Contact name
	 *
	 * @access public
	 * @var string
	 */
	public $name = '';

	/**
	 * Subject
	 *
	 * @access public
	 * @var string
	 */
	public $subject = '';
	
	/**
	 * Subject
	 *
	 * @access public
	 * @var string
	 */
	public $mobile = '';

	/**
	 * Email address
	 *
	 * @access public
	 * @var string
	 */
	public $email = '';

	/**
	 * The message
	 *
	 * @access public
	 * @var string
	 */
	public $message = '';

	/**
	 * Has the email been sent?
	 *
	 * @access public
	 * @var bool
	 */
	public $email_sent = false;

	/**
	 * The class constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		// @codingStandardsIgnoreLine
		if ( isset( $_POST['submit'] ) ) {
			$this->process_name();
			$this->process_subject();
			$this->process_mobile();
			$this->process_email();
			$this->process_message();

			if ( ! $this->has_error ) {
				$this->tista_send_email();
			}
		}
	}
	
	/************************************************************************
	| Check to make sure that the name field is not empty. Access private
	*************************************************************************/ 
	private function process_name() {
		// @codingStandardsIgnoreLine
		$post_contact_name = ( isset( $_POST['contact_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
		if ( '' == $post_contact_name || esc_html__( 'Name (required)', 'tista' ) == $post_contact_name ) {
			$this->has_error = true;
		} else {
			$this->name = $post_contact_name;
		}
	}
	
	/***********************************************************
	| Subject field is not required, Access private
	*************************************************************/ 
	private function process_mobile() {
		// @codingStandardsIgnoreLine
		$post_mob      = ( isset( $_POST['mob'] ) ) ? sanitize_text_field( wp_unslash( $_POST['mob'] ) ) : '';
		$this->mobile = ( function_exists( 'stripslashes' ) ) ? stripslashes( $post_mob ) : $post_mob;
	}

	/***********************************************************
	| Subject field is not required, Access private
	*************************************************************/ 
	private function process_subject() {
		// @codingStandardsIgnoreLine
		$post_url      = ( isset( $_POST['url'] ) ) ? sanitize_text_field( wp_unslash( $_POST['url'] ) ) : '';
		$this->subject = ( function_exists( 'stripslashes' ) ) ? stripslashes( $post_url ) : $post_url;
	}

	/***********************************************************
	| Check to make sure sure that a valid email 
	|address is submitted., Access private
	*************************************************************/ 
	private function process_email() {
		// @codingStandardsIgnoreLine
		$email = ( isset( $_POST['email'] ) ) ? trim( sanitize_email( wp_unslash( $_POST['email'] ) ) ) : '';
		$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
		if ( '' == $email || esc_html__( 'Mail (required)', 'tista' ) == $email ) {
			$this->has_error = true;
		} elseif ( 0 === preg_match( $pattern, $email ) ) {
			$this->has_error = true;
		} else {
			$this->email = trim( $email );
		}
	}

	/***********************************************************
	| Check to make sure a message was entered, Access private
	*************************************************************/ 
	private function process_message() {
		// @codingStandardsIgnoreLine
		$message = ( isset( $_POST['msg'] ) ) ? esc_textarea( wp_unslash( $_POST['msg'] ) ) : '';
		if ( '' == $message || esc_html__( 'Message', 'tista' ) == $message ) {
			$this->has_error = true;
		} else {
			$this->message = ( function_exists( 'stripslashes' ) ) ? stripslashes( $message ) : $message;
		}
	}

	/****************************************************
	| Send Email, Access private
	****************************************************/
	private function tista_send_email() {
		$options = get_option( Tista::get_option_name() );
		$name    = wp_filter_kses( $this->name );
		$email   = wp_filter_kses( $this->email );
		$subject = wp_filter_kses( $this->subject );
		$mobile = wp_filter_kses( $this->mobile );
		$message = wp_filter_kses( $this->message );

		if ( function_exists( 'stripslashes' ) ) {
			$subject = stripslashes( $subject );
			$message = stripslashes( $message );
		}

		$email_to = $options['email_address'];
		$body  = esc_html__( 'Name:', 'tista' ) . " $name \n\n";
		$body .= esc_html__( 'Email:', 'tista' ) . " $email \n\n";
		$body .= esc_html__( 'Subject:', 'tista' ) . " $subject \n\n";
		$body .= esc_html__( 'Mobile:', 'tista' ) . " $mobile \n\n";
		$body .= esc_html__( 'Comments:', 'tista' ) . "\n $message";

		$headers = 'Reply-To: ' . $name . ' <' . $email . '>' . "\r\n";
		if( function_exists( 'wp_mail' ) ){
		wp_mail( $email_to, $subject, $body, $headers );
		}

		$this->email_sent = true;

		if ( true == $this->email_sent ) {
			$_POST['contact_name'] = '';
			$_POST['email']        = '';
			$_POST['url']          = '';
			$_POST['mob']          = '';
			$_POST['msg']          = '';
		}
	}
}
endif;