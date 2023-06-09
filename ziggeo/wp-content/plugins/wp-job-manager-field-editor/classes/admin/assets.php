<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class WP_Job_Manager_Field_Editor_Admin_Assets
 *
 * @since 1.1.9
 *
 */
class WP_Job_Manager_Field_Editor_Admin_Assets {

	private $hooks;

	function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'in_admin_header', array( $this, 'add_popover_div' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'death_to_heartbeat' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'death_to_sloppy_devs' ), 99999 );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_chosen' ) );

		$this->hooks = array(
			'job_listing_page_edit_job_fields',
			'job_listing_page_edit_company_fields',
			'job_listing_page_field-editor-settings',
			'resume_page_edit_resume_fields'
		);
	}

	/**
	 * Maybe Enqueue Chosen in Admin Area
	 *
	 *
	 * @since 1.8.0
	 *
	 */
	public function maybe_enqueue_chosen() {

		$screen = get_current_screen();

		if ( $screen && defined( 'JOB_MANAGER_PLUGIN_URL' ) && in_array( $screen->id, apply_filters( 'job_manager_admin_screen_ids', array(
				'edit-job_listing',
				'job_listing',
				'edit-resume',
				'resume'
			) ) ) ) {

			if( ! get_option( 'jmfe_admin_enable_enqueue_chosen', false ) ){
				return;
			}

			if ( ! wp_script_is( 'chosen', 'registered' ) ) {
				wp_register_script( 'chosen', JOB_MANAGER_PLUGIN_URL . '/assets/js/jquery-chosen/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );
			}

			wp_localize_script( 'chosen', 'job_manager_chosen_multiselect_args', apply_filters( 'job_manager_chosen_multiselect_args', array( 'search_contains' => true ) ) );

			if ( ! wp_script_is( 'wp-job-manager-multiselect', 'registered' ) ) {
				wp_register_script( 'wp-job-manager-multiselect', JOB_MANAGER_PLUGIN_URL . '/assets/js/multiselect.min.js', array(
					'jquery',
					'chosen'
				), JOB_MANAGER_VERSION, true );
			}

			if ( ! wp_style_is( 'chosen', 'enqueued' ) ) {
				wp_enqueue_style( 'chosen', JOB_MANAGER_PLUGIN_URL . '/assets/css/chosen.css', array(), '1.1.0' );
				$chosenAdminCSS = '.jmfe-multiselect-field { width: 100%; }';
				wp_add_inline_style( 'chosen', $chosenAdminCSS );
			}

		}

	}

	/**
	 * Dequeue scripts/styles that conflict with plugin
	 *
	 * Sloppy developers eneuque their scripts and styles on all pages instead of
	 * only the pages they are needed on.  This almost always causes problems and
	 * to try and prevent this, I dequeue any known scripts/styles that have known
	 * compatibility issues.
	 *
	 * @since 1.2.1
	 *
	 * @param $hook
	 */
	function death_to_sloppy_devs( $hook ){
		// Return if not on plugin page, which some devs fail to check!
		if ( empty( $hook ) || ( ! empty( $hook ) && ! in_array( $hook, $this->hooks ) ) ) {
			return;
		}

		$assets = array( 'wpum-admin-js', 'jquery-ui-sortable', 'jquery-ui-draggable', 'scporderjs', 'kwayyhs-custom-js', 'mobiloud-menu-config', 'wp-seo-premium-quickedit-notification' );

		foreach( $assets as $asset ){
			if( wp_script_is( $asset, 'enqueued' ) ) {
				wp_dequeue_script( $asset );
			}
		}

		$this->death_to_sloppy_dev_styles( $hook );
	}

	/**
	 * Dequeue styles that conflict with plugin
	 *
	 * Sloppy developers eneuque their scripts and styles on all pages instead of
	 * only the pages they are needed on.  This almost always causes problems and
	 * to try and prevent this, I dequeue any known scripts/styles that have known
	 * compatibility issues.
	 *
	 * @since @@since
	 *
	 * @param $hook
	 */
	public function death_to_sloppy_dev_styles( $hook ) {

		// Return if not on plugin page, which some devs fail to check!
		// Return if not on plugin page, which some devs fail to check!
		if ( empty( $hook ) || ( ! empty( $hook ) && ! in_array( $hook, $this->hooks ) ) ) {
			return;
		}

		$styles = array(
			'woocommerce_admin_styles', // YITH WooCommerce Social Login Premium (all KINDS of sloppy enqueues)
			'cuar.admin', // WP Customer Area (Enqueued on ALL admin pages)
			'ots-common', // Our Team Showcase Plugin (loads on every page)
			'pixelgrade_care_style',
			'pixelgrade_care',
			'pods-styles'
		);

		foreach ( $styles as $style ) {
			if ( wp_style_is( $style, 'enqueued' ) ) {
				wp_dequeue_style( $style );
			} elseif ( wp_style_is( $style, 'registered' ) ) {
				wp_deregister_style( $style );
			}
		}

	}

	/**
	 * Check if current page is one of plugin pages
	 *
	 *
	 * @since 1.1.9
	 *
	 * @param null $page
	 *
	 * @return bool
	 */
	function is_plugin_page( $page = null ){

		global $pagenow;

		$plugin_pages = array(
			'edit_job_fields',
			'edit_company_fields',
			'edit_resume_fields',
			'edit_education_fields',
			'edit_links_fields',
			'edit_experience_fields',
			'field-editor-settings'
		);

		$current_page = ( isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : '' );

		if ( $pagenow == 'edit.php' && in_array( $current_page, $plugin_pages ) ){
			// Return TRUE if $page not defined, or if defined and matches $current_page
			if( ! $page || $current_page == $page ) return TRUE;
			// Return false because $page is set, but does not match $current_page
			return false;
		}

		return false;
	}

	/**
	 * Add <div> between #wpcontent and #body
	 *
	 *
	 * @since 1.1.9
	 *
	 */
	function add_popover_div(){

		if( $this->is_plugin_page() ) echo "<div id=\"jmfe-popover-viewport\"></div>";

	}

	/**
	 * Register Vendor/Core CSS and Scripts
	 *
	 * @since 1.1.9
	 *
	 */
	function register_assets() {

		$this->register_semantic();

		$styles          = '/assets/css/jmfe.min.css';
		$vendor_styles   = '/assets/css/vendor.min.css';
		$vendor_scripts  = '/assets/js/vendor.min.js';
		$radio           = '/assets/js/radio.min.js';
		$date            = '/assets/js/date.min.js';
		$vendor_phone    = '/assets/js/intlTelInput.min.js';
		$phone           = '/assets/js/phone.min.js';
		$scripts         = '/assets/js/jmfe.min.js';
		$metaboxes       = '/assets/js/metaboxes.min.js';
		$sortable   = '/assets/js/sortable.min.js';
		$scripts_version = WPJM_FIELD_EDITOR_VERSION;

		if ( defined( 'WPJMFE_DEBUG' ) && WPJMFE_DEBUG == TRUE ) {

			$styles          = '/assets/css/build/jmfe.css';
			$vendor_styles   = '/assets/css/build/vendor.css';
			$vendor_scripts  = '/assets/js/build/vendor.js';
			$radio           = '/assets/js/build/radio.js';
			$date            = '/assets/js/build/date.js';
			$vendor_phone    = '/assets/js/build/intlTelInput.js';
			$phone           = '/assets/js/build/phone.js';
			$scripts         = '/assets/js/build/jmfe.js';
			$metaboxes       = '/assets/js/build/metaboxes.js';
			$sortable   = '/assets/js/build/sortable.js';
			$scripts_version = filemtime( WPJM_FIELD_EDITOR_PLUGIN_DIR . $scripts );

		}

		wp_register_style( 'jmfe-styles', WPJM_FIELD_EDITOR_PLUGIN_URL . $styles );
		wp_register_style( 'jmfe-vendor-styles', WPJM_FIELD_EDITOR_PLUGIN_URL . $vendor_styles );
		// wp_register_style( 'jmfe-phone-field-style', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/css/intlTelInput.min.css', array(), WPJM_FIELD_EDITOR_VERSION );

		wp_register_script( 'jmfe-sortable', WPJM_FIELD_EDITOR_PLUGIN_URL . $sortable, array( 'jquery' ), $scripts_version, TRUE );
		wp_register_script( 'jmfe-vendor-scripts', WPJM_FIELD_EDITOR_PLUGIN_URL . $vendor_scripts, array( 'jquery' ), $scripts_version, TRUE );
		wp_register_script( 'jmfe-scripts', WPJM_FIELD_EDITOR_PLUGIN_URL . $scripts, array( 'jquery' ), $scripts_version, TRUE );
		wp_register_script( 'jmfe-admin-metaboxes', WPJM_FIELD_EDITOR_PLUGIN_URL . $metaboxes, array( 'jquery' ), $scripts_version, TRUE );

		$assets = WP_Job_Manager_Field_Editor_Assets::get_instance();
		$assets->register_assets();
	}

	/**
	 * Enqueue already registered styles
	 *
	 *
	 * @since    1.1.9
	 *
	 * @param bool $include_vendor
	 */
	public function enqueue_assets( $include_vendor = true ){

		wp_enqueue_style( 'jmfe-styles' );

		if( $include_vendor ){
			wp_enqueue_style( 'jmfe-vendor-styles' );
			wp_enqueue_script( 'jmfe-vendor-scripts' );
		}

		wp_enqueue_script( 'jmfe-scripts' );
	}

	public function register_semantic() {

		if ( defined( 'WPJMFE_DEBUG' ) && WPJMFE_DEBUG == true ) {

			$cjs = 'build/admin-conditionals.js';
			$sjs = 'semantic.js';
			$scss = 'semantic.css';
			$swpcss = 'wordpress.css';

		} else {

			$swpcss = 'wordpress.css';
			$scss = 'semantic.min.css';
			$sjs = 'semantic.min.js';
			$cjs = 'admin-conditionals.min.js';

		}

		wp_register_style( 'jmfe-semantic-ui-wp', WPJM_FIELD_EDITOR_PLUGIN_URL . "/assets/semantic/{$swpcss}", array(), WPJM_FIELD_EDITOR_VERSION );
		wp_register_style( 'jmfe-semantic-ui', WPJM_FIELD_EDITOR_PLUGIN_URL . "/assets/semantic/{$scss}", array( 'jmfe-semantic-ui-wp' ), WPJM_FIELD_EDITOR_VERSION );

		wp_register_script( 'jmfe-semantic-ui', WPJM_FIELD_EDITOR_PLUGIN_URL . "/assets/semantic/{$sjs}", array( 'jquery' ), WPJM_FIELD_EDITOR_VERSION, true );

		wp_register_script( 'jmfe-handlebars', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/handlebars.min.js', array( 'jquery' ), WPJM_FIELD_EDITOR_VERSION, true );
		wp_register_script( 'jmfe-jq-serialize', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/jquery.serialize-object.min.js', array( 'jquery' ), WPJM_FIELD_EDITOR_VERSION, true );
		wp_register_script( 'jmfe-admin-conditionals', WPJM_FIELD_EDITOR_PLUGIN_URL . "/assets/js/{$cjs}", array( 'jquery', 'jmfe-handlebars', 'jmfe-jq-serialize' ), WPJM_FIELD_EDITOR_VERSION, true );

	}

	/**
	 * Deregister WP Heartbeat Script
	 *
	 * @since 1.1.9
	 *
	 */
	function death_to_heartbeat() {

		if( $this->is_plugin_page() ) wp_deregister_script( 'heartbeat' );

	}
}