<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

//This file will run all of the updates that might be needed, per a version check.
function ziggeojobmanager_p_on_update($options = null) {

	//Is this backed or frontend?
	//We do not run this on frontend
	if(!is_admin()) {
		return false;
	}

	//Get options - we always want to do this using the standard WP way
	$options = ziggeojobmanager_get_plugin_options();
	$defaults = ziggeojobmanager_get_plugin_options_defaults();

	//Are we already up to date?
	if(isset($options['version']) && ($options['version'] == ZIGGEOJOBMANAGER_VERSION)) {
		//All good and up to date, lets just go out of this.
		return true;
	}

	//In case this is very old version, lets make it safe for check down the road
	if(!isset($options['version'])) {
		$options['version'] = 1;
	}

	////////////////////////
	// PER VERSION UPDATES
	////////////////////////

	//Using this method, we actually allow some new options to be added and saved even if they are not made through our plugin.
	foreach($options as $option => $value) {
		$defaults[$option] = $value;
	}

	//This way all defaults are applied as well as the old settings are kept.
	$options = $defaults;

	//In the end we also update the version
	//NOTE: This should always be last
	$options['version'] = ZIGGEOJOBMANAGER_VERSION;

	update_option('ziggeojobmanager', $options);
}

add_action('plugins_loaded', 'ziggeojobmanager_p_on_update');


?>