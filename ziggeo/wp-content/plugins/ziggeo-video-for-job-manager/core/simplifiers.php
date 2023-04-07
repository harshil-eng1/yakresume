<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

function ziggeojobmanager_get_template($type = 'video-recorder') {
	$template_used = false;
	$code = '';

	if($type === 'video-recorder') {
		//These are only included if needed and for bellow we do need them
		//include_once(ZIGGEO_ROOT_PATH . '/templates/defaults_recorder.php');

		if($code = ziggeo_get_recorder_code('integrations')) {

			// if($template = ziggeo_p_template_params_as_object(null, $code)) {
			// 	$template_used = true;
			// }
		}
	}
	elseif($type === 'video-player') {
		//These are only included if needed and for bellow we do need them
		//include_once(ZIGGEO_ROOT_PATH . '/templates/defaults_player.php');

		if($code = ziggeo_get_player_code('integrations')) {

			// if($template = ziggeo_p_template_params_as_object(null, $code)) {
			// 	$template_used = true;
			// }
		}
	}

	//return $template;
	return $code;
}

function ziggeojobmanager_get_plugin_options_defaults() {
	$defaults = array(
		'version'                               => ZIGGEOJOBMANAGER_VERSION,
		'submission_form_video_record'          => '1',
		'submission_form_video_uploader'        => '1',
		'submission_form_video_combined'        => '0',
		'design'                                => 'default',
		'submission_form_e_rm_video_record'     => '1',
		'submission_form_e_rm_video_uploader'   => '1',
		'submission_form_e_rm_video_combined'   => '0',
		'submission_form_e_rm_video_link'       => '1',
		'custom_tags'                           => '',
		'capture_content'                       => 'default'
	);

	return $defaults;
}

// Returns all plugin settings or defaults if not existing
function ziggeojobmanager_get_plugin_options($specific = null) {
	$options = get_option('ziggeojobmanager');

	$defaults = ziggeojobmanager_get_plugin_options_defaults();

	//in case we need to get the defaults
	if($options === false || $options === '') {
		// the defaults need to be applied
		$options = $defaults;
	}
	else {
		$options = array_merge($defaults, $options);
	}

	// In case we are after a specific one.
	if($specific !== null) {
		if(isset($options[$specific])) {
			return $options[$specific];
		}
		elseif(isset($defaults[$specific])) {
			return $defaults[$specific];
		}
	}
	else {
		return $options;
	}

	return false;
}

?>