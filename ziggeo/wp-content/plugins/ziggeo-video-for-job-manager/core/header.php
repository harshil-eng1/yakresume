<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

//We are hooking into the ZiggeoWP object and adding a property of our own within the same.
add_action('ziggeo_add_to_ziggeowp_object', function() {

	$options = ziggeojobmanager_get_plugin_options();

	if($options['capture_content'] === 'embed_wp') {
		$format = '[ziggeoplayer]{token}[/ziggeoplayer]';
	}
	elseif($options['capture_content'] === 'embed_html') {
		$format = htmlentities('<ziggeoplayer ' . ziggeo_get_player_code('integrations') . ' ziggeo-video="{token}"></ziggeoplayer>');
	}
	elseif($options['capture_content'] === 'video_url') {
		$format = 'https://ziggeo.io/p/{token}';
	}
	elseif($options['capture_content'] === 'video_token') {
		$format = '{token}';
	}
	else { //default == 'https://' + embedding_obj.get('video_data.embed_video_url') + '.mp4'
		$app_token = ziggeo_get_plugin_options('token');

		if(stripos($app_token, 'r1') === 0) { //EU
			$subdomain = 'embed-eu-west-1';
		}
		else { //US
			$subdomain = 'embed';
		}

		$format = 'https://' . $subdomain . '.ziggeo.com/v1/applications/' . $app_token . '/videos/{token}/video.mp4';
	}

	//Filter to allow you to change the format yourself regardless of the setting
	//Please place {token} where video token should be placed, everything else is up to you
	$format = apply_filters('ziggeo_job_manager_capture_content', $format);

	?>
	jobmanager: {
		show_recorder: <?php echo ($options['submission_form_video_record'] != '0') ? 'true': 'false'; ?>,
		show_uploader: <?php echo ($options['submission_form_video_uploader'] != '0') ? 'true': 'false'; ?>,
		show_combined: <?php echo ($options['submission_form_video_combined'] != '0') ? 'true': 'false'; ?>,
		design: '<?php echo $options['design']; ?>',
		addons: {
			resume_manager: {
				show_recorder: <?php echo ($options['submission_form_e_rm_video_record'] != '0') ? 'true': 'false'; ?>,
				show_uploader: <?php echo ($options['submission_form_e_rm_video_uploader'] != '0') ? 'true': 'false'; ?>,
				show_combined: <?php echo ($options['submission_form_e_rm_video_combined'] != '0') ? 'true': 'false'; ?>,
				hide_link_field: <?php echo ($options['submission_form_e_rm_video_link'] != '0') ? 'true': 'false'; ?>
			}
		},
		custom_tags: "<?php echo $options['custom_tags']; ?>",
		capture_content: "<?php echo $options['capture_content']; ?>",
		capture_format: "<?php echo $format; ?>"
	},
	<?php
});

?>