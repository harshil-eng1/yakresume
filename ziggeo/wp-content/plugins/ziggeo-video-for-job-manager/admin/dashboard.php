<?php

//Mainly used for the purpose of selecting where Ziggeo would be used to show video or record it.

// Index
//	1. Hooks
//		1.1. admin_init
//		1.2. admin_menu
//	2. Fields and sections
//		2.1. ziggeojobmanager_show_form()
//		2.2. ziggeojobmanager_d_core()
//		2.3. ziggeojobmanager_o_submit_form_videor_field()
//		2.4. ziggeojobmanager_o_submit_form_videou_field()
//		2.5. ziggeojobmanager_o_design()
//		2.6. ziggeojobmanager_d_e_resume()
//		2.7. ziggeojobmanager_o_submit_form_e_rm_videor_field()
//		2.8. ziggeojobmanager_o_submit_form_e_rm_videou_field()


//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();



/////////////////////////////////////////////////
//	1. HOOKS
/////////////////////////////////////////////////

	//Add plugin options
	add_action('admin_init', function() {
		//Register settings
		register_setting('ziggeojobmanager', 'ziggeojobmanager', 'ziggeojobmanager_validate');

		//Active hooks
		add_settings_section('ziggeojobmanager_section_core', '', 'ziggeojobmanager_d_core', 'ziggeojobmanager');
		add_settings_section('ziggeojobmanager_section_e_resumem', '', 'ziggeojobmanager_d_e_resume', 'ziggeojobmanager');


			// Option to turn on or off the option in Job Manager for the videos recorder being part of
			// the submission form or just the standard input field
			add_settings_field('ziggeojobmanager_submit_form_videor_field',
			                    __('Add Ziggeo recorder on submission form', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_submit_form_videor_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_core');

			add_settings_field('ziggeojobmanager_submit_form_videou_field',
			                   __('Add Ziggeo uploader on submission form', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_submit_form_videou_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_core');
			add_settings_field('ziggeojobmanager_submit_form_videocombo_field',
			                   __('Combine Recorder and uploader together (recommended)'),
			                   'ziggeojobmanager_o_submit_form_videocombo_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_core');
			add_settings_field('ziggeojobmanager_design_buttons',
			                   __('The design of the recorder and uploader on submission forms', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_design',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_core');

			add_settings_field('ziggeojobmanager_custom_tags_from_fields',
			                   __('Select what fields to use from form as tags.', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_custom_tags',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_core');

			// The type of data that is captured once the video is recorded
			add_settings_field('ziggeojobmanager_captured_content',
			                   __('Choose the data that is saved once video is recorded', 'ziggeojobmanager'),
			                   'ziggeojobmanager_g_captured_content',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_core');

			//Resume Manager
			add_settings_field('ziggeojobmanager_submit_form_e_rm_videor_field',
			                   __('Add Ziggeo recorder on Resume submission form', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_submit_form_e_rm_videor_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_e_resumem');

			add_settings_field('ziggeojobmanager_submit_form_e_rm_videou_field',
			                   __('Add Ziggeo uploader on Resume submission form', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_submit_form_e_rm_videou_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_e_resumem');
			add_settings_field('ziggeojobmanager_submit_form_videocombo_field',
			                   __('Combine Recorder and uploader together (recommended)'),
			                   'ziggeojobmanager_o_submit_form_e_videocombo_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_e_resumem');

			add_settings_field('ziggeojobmanager_submit_form_e_rm_videol_field',
			                   __('Hide video URL field on Resume submission form', 'ziggeojobmanager'),
			                   'ziggeojobmanager_o_submit_form_e_rm_videol_field',
			                   'ziggeojobmanager',
			                   'ziggeojobmanager_section_e_resumem');
	});

	add_action('admin_menu', function() {
		if(function_exists('ziggeo_p_add_addon_submenu')) {
			ziggeo_p_add_addon_submenu(array(
				'page_title'    => 'Ziggeo Video for Job Manager',  //page title
				'menu_title'    => 'Ziggeo Video for Job Manager',  //menu title
				'capability'    => 'manage_options',                //min capability to view
				'slug'          => 'ziggeojobmanager',              //menu slug
				'callback'      => 'ziggeojobmanager_show_form')    //function
			);
		}
		else {
			add_action( 'admin_notices', function() {
				?>
				<div class="error notice">
					<p><?php _e( 'Please install <a href="https://wordpress.org/plugins/ziggeo/">Ziggeo plugin</a>. It is required for this plugin (Ziggeo Video For Job Manager) to work properly!', 'ziggeojobmanager' ); ?></p>
				</div>
				<?php
			});
		}
	}, 12);



/////////////////////////////////////////////////
//	2. FIELDS AND SECTIONS
/////////////////////////////////////////////////

	//Dashboard form
	function ziggeojobmanager_show_form() {
		?>
		<div>
			<h2>Ziggeo Video for Job Manager</h2>

			<form action="options.php" method="post">
				<?php
				wp_nonce_field('ziggeojobmanager_nonce_action', 'ziggeojobmanager_video_nonce');
				get_settings_errors();
				settings_fields('ziggeojobmanager');
				do_settings_sections('ziggeojobmanager');
				submit_button('Save Changes');
				?>
			</form>
		</div>
		<?php
	}

		function ziggeojobmanager_d_core() {
			?>
			<h3><?php _e('Job Manager settings', 'ziggeojobmanager'); ?></h3>
			<?php
			_e('Use the settings bellow to change the way Job Manager pages are handling videos', 'ziggeojobmanager');
		}

			function ziggeojobmanager_o_submit_form_videor_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_video_record');

				?>
				<input id="ziggeojobmanager_submission_form_video_record" name="ziggeojobmanager[submission_form_video_record]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_video_record"><?php _e('When checked your submission form will show record option in job submission form', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_submit_form_videou_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_video_uploader');

				?>
				<input id="ziggeojobmanager_submission_form_video_uploader" name="ziggeojobmanager[submission_form_video_uploader]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_video_uploader"><?php _e('When checked your submission form will show upload option in job submission form', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_submit_form_videocombo_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_video_combined');

				?>
				<input id="ziggeojobmanager_submission_form_video_combined" name="ziggeojobmanager[submission_form_video_combined]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_video_combined"><?php _e('Shows single embedding for both recording and uploading (recommended)', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_design() {
				$option = ziggeojobmanager_get_plugin_options('design');

				?>
				<select id="ziggeojobmanager_design" name="ziggeojobmanager[design]">
					<option value="default" <?php echo ($option === 'default') ? 'selected="selected"' : '' ?> >Default (button and icon)</option>
					<option value="icons" <?php echo ($option === 'icons') ? 'selected="selected"' : '' ?> >Show Icons</option>
					<option value="buttons" <?php echo ($option === 'buttons') ? 'selected="selected"' : '' ?> >Show buttons</option>
				</select>
				<label for="ziggeojobmanager_design"><?php _e('Select the design that best matches what you want to have shown', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_custom_tags() {
				$option = ziggeojobmanager_get_plugin_options('custom_tags');
				?>
				<ul id="ziggeojobmanager_custom_tags_placeholder">
					<li>
						<label>
							<input name="ziggeojobmanager_custom_tags"
							        type="checkbox"
							        <?php if(stripos($option, 'job_title') > -1) {
							            echo 'checked="checked"';
							        } ?>
							        value="job_title">Job Title</label>
					</li>
					<li>
						<label>
						<input name="ziggeojobmanager_custom_tags"
							        type="checkbox"
							        <?php if(stripos($option, 'job_location') > -1) {
							            echo 'checked="checked"';
							        } ?>
							        value="job_location">Job Location</label>
					</li>
					<li>
						<label>
							<input name="ziggeojobmanager_custom_tags"
							        type="checkbox"
							        <?php if(stripos($option, 'job_type') > -1) {
							            echo 'checked="checked"';
							        } ?>
							        value="job_type">Job Type</label>
					</li>
					<li>
						<label>
							<input name="ziggeojobmanager_custom_tags"
							        type="checkbox"
							        <?php if(stripos($option, 'company_name') > -1) {
							            echo 'checked="checked"';
							        } ?>
							        value="company_name">Company Name</label>
					</li>
					<li>
						<label>
							<input name="ziggeojobmanager_custom_tags"
							        type="checkbox"
							        <?php if(stripos($option, 'company_twitter') > -1) {
							            echo 'checked="checked"';
							        } ?>
							        value="company_twitter">Twitter Username</label>
					</li>
				</ul>
				<input id="ziggeojobmanager_custom_tags" name="ziggeojobmanager[custom_tags]" type="hidden" value="<?php echo $option; ?>">
				<?php
			}

			function ziggeojobmanager_g_captured_content() {
				$option = ziggeojobmanager_get_plugin_options('capture_content');
				?>
				<select id="ziggeojobmanager_capture_content" name="ziggeojobmanager[capture_content]">
					<option value="embed_wp" <?php ziggeo_echo_selected($option, 'embed_wp'); ?>>WP Embed code</option>
					<option value="embed_html" <?php ziggeo_echo_selected($option, 'embed_html'); ?>>HTML Embed code</option>
					<option value="video_url" <?php ziggeo_echo_selected($option, 'video_url'); ?>>Video URL</option>
					<option value="video_token" <?php ziggeo_echo_selected($option, 'video_token'); ?>>Video Token</option>
					<option value="default" <?php ziggeo_echo_selected($option, 'default'); ?>>Default</option>
				</select>
				<label for="ziggeojobmanager_capture_content"><?php _e('Depending on your choice here you will change what is captured once the video is recorded', 'ziggeojobmanager'); ?></label>
				<?php
			}

		//Resume manager
		function ziggeojobmanager_d_e_resume() {
			?>
			<h3><?php _e('Resume Manager settings', 'ziggeojobmanager'); ?></h3>
			<?php
			_e('Use the settings bellow to change the way Resume Manager pages are handling videos', 'ziggeojobmanager');
		}

			function ziggeojobmanager_o_submit_form_e_rm_videor_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_e_rm_video_record');

				?>
				<input id="ziggeojobmanager_submission_form_e_rm_video_record" name="ziggeojobmanager[submission_form_e_rm_video_record]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_e_rm_video_record"><?php _e('When checked your Resume Manager submission form will show record option in job submission form', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_submit_form_e_rm_videou_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_e_rm_video_uploader');

				?>
				<input id="ziggeojobmanager_submission_form_e_rm_video_uploader" name="ziggeojobmanager[submission_form_e_rm_video_uploader]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_e_rm_video_uploader"><?php _e('When checked your Resume Manager submission form will show upload option in job submission form', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_submit_form_e_videocombo_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_e_rm_video_combined');

				?>
				<input id="ziggeojobmanager_submission_form_e_rm_video_combined" name="ziggeojobmanager[submission_form_e_rm_video_combined]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_e_rm_video_combined"><?php _e('Shows single embedding for both recording and uploading (recommended)', 'ziggeojobmanager'); ?></label>
				<?php
			}

			function ziggeojobmanager_o_submit_form_e_rm_videol_field() {
				$option = ziggeojobmanager_get_plugin_options('submission_form_e_rm_video_link');

				?>
				<input id="ziggeojobmanager_submission_form_e_rm_video_link" name="ziggeojobmanager[submission_form_e_rm_video_link]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeojobmanager_submission_form_e_rm_video_link"><?php _e('When checked you will hide the default video URL field on the submission form.', 'ziggeojobmanager'); ?></label>
				<?php
			}

?>