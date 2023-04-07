<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

//Function that checks and confirms that AJAX request is asking for one of the expected things from the rest of the code.
function ziggeojobmanager_is_ok_type($type) {

	$result = false;

	$types = [
		'video-recorder', 'video-player'
		//'audio-recorder', 'audio-player',
		//'image-capture', 'image-viewer'
	];

	if(in_array($type, $types)) {
		return true;
	}

	return $result;
}

//We hook into the Ziggeo AJAX call
add_filter('ziggeo_ajax_call', function($result, $operation) {

	if($operation === 'ziggeojobmanager-get-template') {

		if(isset($_POST['template_type'])) {
			if(ziggeojobmanager_is_ok_type($_POST['template_type'])) {
				//return the template that is set to be used by plugins, or the one defined in the settings
				return ziggeojobmanager_get_template($_POST['template_type']);
			}
		}
	}

	return '';
}, 10, 2);

add_filter('ziggeo_ajax_call_client', function($result, $operation) {

	if($operation === 'ziggeojobmanager-get-template') {

		if(isset($_POST['template_type'])) {
			if(ziggeojobmanager_is_ok_type($_POST['template_type'])) {
				//return the template that is set to be used by plugins, or the one defined in the settings
				return ziggeojobmanager_get_template($_POST['template_type']);
			}
		}
	}

	return '';
}, 10, 2);

?>