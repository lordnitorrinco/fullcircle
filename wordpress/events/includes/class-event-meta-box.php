<?php

class Event_Meta_Box {

    /**
     * Register the meta box.
     *
     * @return void
     */
    public static function register(): void {
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post', array(__CLASS__, 'save_meta'));
    }

    /**
     * Add custom fields for event date and location.
     *
     * @return void
     */
    public static function add_meta_boxes(): void {
        add_meta_box('eventos_meta', 'Event Details', array(__CLASS__, 'meta_box_callback'), 'events', 'normal', 'high');
    }

    /**
     * Callback function to display the custom fields.
     *
     * @param WP_Post $post The post object.
     * @return void
     */
    public static function meta_box_callback(WP_Post $post): void {
        wp_nonce_field(basename(__FILE__), 'eventos_nonce');
        $eventos_stored_meta = get_post_meta($post->ID);
        ?>
        <p>
            <label for="eventos_date" class="eventos-row-title">Event Date</label>
            <input type="date" name="eventos_date" id="eventos_date" value="<?php if (isset($eventos_stored_meta['eventos_date'])) echo esc_attr($eventos_stored_meta['eventos_date'][0]); ?>" />
        </p>
        <p>
            <label for="eventos_location" class="eventos-row-title">Event Location</label>
            <input type="text" name="eventos_location" id="eventos_location" value="<?php if (isset($eventos_stored_meta['eventos_location'])) echo esc_attr($eventos_stored_meta['eventos_location'][0]); ?>" />
        </p>
        <?php
    }

    /**
     * Save the custom fields data.
     *
     * @param int $post_id The ID of the post being saved.
     * @return void
     */
    public static function save_meta(int $post_id): void {
        // Check nonce for security
        if (!isset($_POST['eventos_nonce']) || !wp_verify_nonce($_POST['eventos_nonce'], basename(__FILE__))) {
            return;
        }
        // Check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        // Save event date
        if (isset($_POST['eventos_date'])) {
            update_post_meta($post_id, 'eventos_date', sanitize_text_field($_POST['eventos_date']));
        }
        // Save event location
        if (isset($_POST['eventos_location'])) {
            update_post_meta($post_id, 'eventos_location', sanitize_text_field($_POST['eventos_location']));
        }
    }
}