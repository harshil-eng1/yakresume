<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

function ziggeojobmanager_global() {

	//local assets
	wp_register_style('ziggeojobmanager-css', ZIGGEOJOBMANAGER_ROOT_URL . 'assets/css/styles.css', array());    
	wp_enqueue_style('ziggeojobmanager-css');

	wp_register_script('ziggeojobmanager-js', ZIGGEOJOBMANAGER_ROOT_URL . 'assets/js/codes.js', array());
	wp_enqueue_script('ziggeojobmanager-js');

}
function ziggeojobmanager_admin() {
	ziggeojobmanager_global();

	wp_register_script('ziggeojobmanager-admin-js', ZIGGEOJOBMANAGER_ROOT_URL . 'assets/js/admin.js', array());
	wp_enqueue_script('ziggeojobmanager-admin-js');
}

add_action('wp_enqueue_scripts', "ziggeojobmanager_global");
add_action('admin_enqueue_scripts', "ziggeojobmanager_admin");

?>