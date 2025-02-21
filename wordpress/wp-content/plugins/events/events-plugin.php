<?php
/*
Plugin Name: Eventos Plugin
Description: A plugin to manage events.
Version: 1.0
Author: Your Name
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include the necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-event-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-event-meta-box.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-event-admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-event-shortcode.php';

// Initialize the plugin
function eventos_plugin_init() {
    Event_Post_Type::register();
    Event_Meta_Box::register();
    Event_Admin_Page::register();
    Event_Shortcode::register();
}
add_action('init', 'eventos_plugin_init');