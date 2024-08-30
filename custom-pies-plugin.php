<?php
/*
Plugin Name: Custom Pies Plugin
Description: A custom plugin to manage pies using a custom post type.
Version: 1.0
Author: Naveen Nandharam
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-custom-pies-plugin.php';

// Initialize the plugin
function custom_pies_plugin_init() {
    $plugin = new Custom_Pies_Plugin();
    $plugin->run();
}
add_action('plugins_loaded', 'custom_pies_plugin_init');

