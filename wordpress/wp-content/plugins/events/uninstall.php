<?php

// Prevent direct access
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all events
$events = get_posts(array('post_type' => 'events', 'numberposts' => -1));
foreach ($events as $event) {
    wp_delete_post($event->ID, true);
}