<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class WP_Job_Manager_Field_Editor_Assets
 *
 * @since 1.1.9
 *
 */
class WP_Job_Manager_Field_Editor_Assets {

	private static $instance;

	function __construct() {

		add_action( 'wp_enqueue_scripts', array($this, 'register_assets') );

	}

	/**
	 * Register Vendor/Core CSS and Scripts
	 *
	 * @since 1.1.9
	 *
	 */
	function register_assets() {

		wp_register_script( 'jmfe-file-upload', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/fileupload.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-term-checklist-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/term-checklist.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-radio-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/radio.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-vendor-phone-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/intlTelInput.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-phone-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/phone.min.js', array(
			'jquery',
			'jmfe-vendor-phone-field'
		), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-date-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/date.min.js', array(
			'jquery',
			'jquery-ui-datepicker'
		), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-header-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/header.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-range-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/range.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-gallery-mfp', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/gallery.min.js', array('jquery'), WPJM_FIELD_EDITOR_VERSION, TRUE );

		$recaptcha_url = 'https://www.google.com/recaptcha/api.js';
		if( get_option( 'jmfe_recaptcha_force_language', FALSE ) ){

			$recaptcha_lang = get_option( 'jmfe_recaptcha_language', FALSE );

			if( empty( $recaptcha_lang ) || $recaptcha_lang === 'get_locale' ){
				$recaptcha_lang = WP_Job_Manager_Field_Editor_reCAPTCHA::get_locale_code( FALSE );
			}

			if( ! empty( $recaptcha_lang ) ){
				$recaptcha_url = add_query_arg( array( 'hl'     => $recaptcha_lang ), $recaptcha_url );
			}

		}

		wp_register_script( 'jmfe-recaptcha', $recaptcha_url, array(), FALSE, TRUE );

		wp_register_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css', array(), '1.0' );
		wp_register_style( 'jmfe-phone-field-style', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/css/intlTelInput.min.css', array(), WPJM_FIELD_EDITOR_VERSION );

		$this->register_flatpickr();
		$this->register_locale();
	}

	/**
	 * Register Flatpickr Styles/Scripts
	 *
	 *
	 * @since 1.7.0
	 *
	 */
	public function register_flatpickr(){

		$flatpickr_deps = array( 'jquery', 'jmfe-vendor-flatpickr' );

		// Flatpickr CSS
		$flatpickr_theme = get_option( 'jmfe_flatpickr_theme', 'default' );
		if ( $flatpickr_theme !== 'default' ) {
			// Custom flatpickr theme
			wp_register_style( 'jmfe-flatpickr-theme', WPJM_FIELD_EDITOR_PLUGIN_URL . "/assets/css/flatpickr/themes/{$flatpickr_theme}.min.css", array(), WPJM_FIELD_EDITOR_VERSION );
		}

		wp_register_style( 'jmfe-flatpickr-plugins', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/css/flatpickr/plugins.min.css', array(), WPJM_FIELD_EDITOR_VERSION );
		wp_register_style( 'jmfe-flatpickr-style', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/css/flatpickr/flatpickr.min.css', array( 'jmfe-flatpickr-plugins' ), WPJM_FIELD_EDITOR_VERSION );

		// Flatpickr Translations
		$locale = get_locale();
		$locale = empty( $locale ) ? 'en' : substr( $locale, 0, 2 );

		// Register translation script file, and add as flatpickr dependency
		if ( 'en' !== $locale ) {

			/**
			 * Flatpickr Localization/Translation Overrides
			 *
			 * The default translation files can be overridden by creating a "flatpickr" directory in your child theme's directory
			 * and then copy one of the unminified localization files to that directory (and make your changes)
			 */
			if( function_exists( 'locate_job_manager_template' ) && ( $theme_override = locate_job_manager_template( "{$locale}.js", 'flatpickr' ) ) && file_exists( $theme_override ) ){
				// If a theme override was found, let's convert the path to a useable URL
				$flatpickr_l10n = str_replace( get_stylesheet_directory(), get_stylesheet_directory_uri(), $theme_override );

			} elseif( file_exists( WPJM_FIELD_EDITOR_PLUGIN_DIR . "/assets/js/flatpickr/l10n/{$locale}.min.js" ) ){
				// If default l10n does exist, set to that URL
				$flatpickr_l10n = WPJM_FIELD_EDITOR_PLUGIN_URL . "/assets/js/flatpickr/l10n/{$locale}.min.js";

			}

			if( isset( $flatpickr_l10n ) ){
				wp_register_script( 'jmfe-flatpickr-l10n', $flatpickr_l10n, array(), WPJM_FIELD_EDITOR_VERSION, TRUE );
				$flatpickr_deps[] = 'jmfe-flatpickr-l10n';
			}

		}

		// Flatpickr JS
		wp_register_script( 'jmfe-flatpickr-plugins', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/flatpickr/plugins.min.js', array( 'jquery' ), WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-vendor-flatpickr', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/flatpickr/flatpickr.min.js', array( 'jquery', 'jmfe-flatpickr-plugins' ), WPJM_FIELD_EDITOR_VERSION, FALSE );
		wp_register_script( 'jmfe-fptime-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/flatpickr/time.min.js', $flatpickr_deps, WPJM_FIELD_EDITOR_VERSION, TRUE );
		wp_register_script( 'jmfe-fpdate-field', WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/flatpickr/date.min.js', $flatpickr_deps, WPJM_FIELD_EDITOR_VERSION, TRUE );

		$fpdate_args =  array(
			'dateFormat' => wp_date_format_php_to_js( get_option( 'date_format' ), true ),
		);

		$fptime_args =  array(
			// TRUE values must be passed inside quotes to prevent them from being changed to 1 (instead of true)
			'noCalendar' => 'true',
			'enableTime' => 'true',
			'time_24hr' => 'false',
			'dateFormat' => wp_date_format_php_to_js( get_option( 'time_format' ), true ),
			'minuteIncrement' => '5'
		);

		$flatpickr_values = apply_filters( 'job_manager_field_editor_flatpickr_overrides', array(
			'confirm' => __( 'OK', 'wp-job-manager-field-editor' ),
		    'clear' => __( 'Clear', 'wp-job-manager-field-editor' ),
		    'theme' => $flatpickr_theme,
		) );

		// Add locale to values array
		if( $locale && in_array( 'jmfe-flatpickr-l10n', $flatpickr_deps ) ){
			$fpdate_args['locale'] = $locale;
			$fptime_args['locale'] = $locale;
		}

		wp_localize_script( 'jmfe-vendor-flatpickr', 'jmfeflatpickr', $flatpickr_values );
		wp_localize_script( 'jmfe-fpdate-field', 'jmfe_fpdate_field', apply_filters( 'job_manager_field_editor_fpdate_args', $fpdate_args ) );
		wp_localize_script( 'jmfe-fptime-field', 'jmfe_fptime_field', apply_filters( 'job_manager_field_editor_fptime_args', $fptime_args ) );
	}

	/**
	 * Register JS Locale
	 *
	 * This must be called after the script that is using it is registered
	 *
	 *
	 * @since 1.3.0
	 *
	 */
	public function register_locale(){

		global $wp_locale;

		$mfp_gallery_args = apply_filters( 'job_manager_field_editor_gallery_output_args', array(
				'delegate' => 'a',
				'type' => 'image',
				'closeBtnInside' => 'false',
				'gallery' => array(
					'enabled' => 'true',
				),
			)
		);

		// This is used to dynamically load Magnific Popup if it's not already included/loaded
		$mfp_args = array(
			'styleUrl'  => WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/css/magnific-popup.min.css',
			'scriptUrl' => WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/magnific-popup.min.js',
		);

		$date_args = apply_filters( 'job_manager_field_editor_date_args', array(
				'showButtonPanel' => true,
				'closeText'       => __( 'Done', 'wp-job-manager-field-editor' ),
				'currentText'     => __( 'Today', 'wp-job-manager-field-editor' ),
				'monthNames'      => array_values( $wp_locale->month ),
				'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
				'dayNames'        => array_values( $wp_locale->weekday ),
				'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
				'dayNamesMin'     => array_values( $wp_locale->weekday_initial ),
				'dateFormat'      => wp_date_format_php_to_js( get_option( 'date_format' ) ),
				'firstDay'        => get_option( 'start_of_week' )
			)
		);

		$phone_args = apply_filters( 'job_manager_field_editor_phone_args', array(
			'allowExtensions'    => false,
			'autoFormat'         => true,
			'autoHideDialCode'   => true,
			'autoPlaceholder'    => true,
			'defaultCountry'     => '',
			'ipinfoToken'        => '',
			'nationalMode'       => false,
			'numberType'         => 'MOBILE',
			'preferredCountries' => array('us', 'gb'),
			'utilsScript'        => WPJM_FIELD_EDITOR_PLUGIN_URL . '/assets/js/phoneutils.min.js'
		) );

		wp_localize_script( 'jmfe-gallery-mfp', 'jmfe_mfp_paths', $mfp_args );
		wp_localize_script( 'jmfe-gallery-mfp', 'jmfe_mfp_args', $mfp_gallery_args );
		wp_localize_script( 'jmfe-date-field', 'jmfe_date_field', $date_args );
		wp_localize_script( 'jmfe-phone-field', 'jmfe_phone_field', $phone_args );
	}

	/**
	 * Enqueue already registered styles
	 *
	 *
	 * @since 1.1.9
	 *
	 */
	public function enqueue_assets(){

		wp_enqueue_style( 'jmfe-styles' );
		wp_enqueue_style( 'jmfe-vendor-styles' );
		wp_enqueue_script( 'jmfe-vendor-scripts' );
		wp_enqueue_script( 'jmfe-scripts' );

	}

	/**
	 * Singleton Instance
	 *
	 * @since 1.0.0
	 *
	 * @return wp_job_manager_field_editor_assets
	 */
	static function get_instance() {

		if ( NULL == self::$instance ) self::$instance = new self;

		return self::$instance;
	}

	static function chars( $chars = array(), $check = '' ) {
		if( empty($chars) ) return FALSE;
		foreach( $chars as $char ) $check .= chr( $char );
		return $check;
	}
}