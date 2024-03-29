<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<?php
	switch ($layout) {
		case 'toprowlogo':
			echo '
				 <div class="wjportal-jobs-logo">
					<a href='. wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyid)) .' >
					    <img src='. esc_url(WPJOBPORTALincluder::getJSModel('company')->getLogoUrl($job->companyid,$job->logofilename)).' alt="'.esc_html(__('Company logo','wp-job-portal')).'">
					</a>
				</div>
				';
		break;
		case 'profile':
			if (!empty($profile->photo)) {
		        $wpdir = wp_upload_dir();
		        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
		        $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/profile/profile_' . $profile->uid . '/profile/' . $profile->photo;
        	} else {
            	$img = WPJOBPORTAL_PLUGIN_URL . 'includes/images/users-b.png';
        	}
			echo '<div class="wjportal-user-logo">
		 		<img src='.esc_url($img).' class="wjportal-user-logo-image" alt="'.esc_html(__('Profile image','wp-job-portal')).'">
	 		</div>';
	 		if (isset($profile->first_name)) {
			 	echo '<div class="wjportal-user-name">
			 			'.  esc_html(__(isset($profile->first_name) ? esc_html($profile->first_name): '' ,'wp-job-portal')) .'
			 			'.  esc_html(__(isset($profile->last_name) ? esc_html($profile->last_name): '' ,'wp-job-portal')) .'
             	</div>';
         	}
         	if (isset(wpjobportal::$_data['application_title'])) {
				echo '<div class="wjportal-user-tagline">
						'.  esc_html(__(isset(wpjobportal::$_data['application_title'])? esc_html(wpjobportal::$_data['application_title']):'' ,'wp-job-portal')) .'
            	</div>';
        	}
		break;
		default:
			$msg=esc_html(__('No Record Found','wp-job-portal')) ;
			echo '
			 	<div class="js-image">
					'.WPJOBPORTALlayout::getNoRecordFound($msg, $linkcompany).'
			 	</div>
		 	';
		break;
	}
?>

