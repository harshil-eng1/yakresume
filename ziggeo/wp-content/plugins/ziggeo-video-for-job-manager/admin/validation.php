<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

//Validate options
function ziggeojobmanager_validate($input) {

	$allowed_options = array(
		'submission_form_video_record'          => true,
		'submission_form_video_uploader'        => true,
		'submission_form_video_combined'        => true,
		'design'                                => true,
		'custom_tags'                           => true,
		'capture_content'                       => true,
		//the Resume Manager addon
		'submission_form_e_rm_video_record'     => true,
		'submission_form_e_rm_video_uploader'   => true,
		'submission_form_e_rm_video_combined'   => true,
		'submission_form_e_rm_video_link'       => true
	);

	$options = ziggeojobmanager_get_plugin_options();

	foreach($allowed_options as $option => $value) {

		if(isset($input[$option])) {
			$options[$option] = $input[$option];
		}
		else {
			$options[$option] = '0';
		}
	}

	return $options;
}



?>