<?php

//
//	This file holds the functionality needed to show the toolbar and add buttons to it.
//	This toolbar is shown above the Post and Pages editor.
//	All plugins utilizing the same (like WooCommerce) should have the toolbar shown as well.
//

// INDEX:
// 1. Hooks
//		1.1. hook:'edit_form_after_title'
// 2. Functionality
//		2.1. ziggeo_p_pre_editor()
//		2.2. ziggeo_create_toolbar_button()
//


//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();



/////////////////////////////////////////////////
// 1. HOOOKS
/////////////////////////////////////////////////

	//Hook after the title and right before the editor
	global $wp_version;

	//only fire this if appropriate version, which is currently < 5.0.0
	if( version_compare( $wp_version, '5.0') < 0 ) {
		add_filter( 'edit_form_after_title', 'ziggeo_p_pre_editor' );
	}
	else {
		//Lets see if it is gutenber or not
		//Code from here moved to admin.js

		//Handling the AJAX request
		add_filter('ziggeo_ajax_call', function($result, $operation) {

			if($operation === 'admin_post_toolbar') {
				ziggeo_p_pre_editor(true);
				wp_die();
			}

			return $result;
		}, 10, 2);

	}

?>