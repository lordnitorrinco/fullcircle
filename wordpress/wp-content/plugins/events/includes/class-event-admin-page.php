<?php

class Event_Admin_Page {

    /**
     * Register the admin page.
     *
     * @return void
     */
    public static function register(): void {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
    }

    /**
     * Add an admin page to list all events.
     *
     * @return void
     */
    public static function add_admin_menu(): void {
        add_menu_page('Eventos', 'Eventos', 'manage_options', 'eventos', array(__CLASS__, 'admin_page_callback'), 'dashicons-calendar', 20);
    }

    /**
     * Callback function to display the events list in the admin page.
     *
     * @return void
     */
    public static function admin_page_callback(): void {
        ?>
        <div class="wrap">
            <h1>Eventos</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col">Event Title</th>
                        <th scope="col">Event Date</th>
                        <th scope="col">Location</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $args = array(
                        'post_type' => 'events',
                        'posts_per_page' => -1,
                        'orderby' => 'date',
                        'order' => 'ASC'
                    );
                    $events = new WP_Query($args);
                    if ($events->have_posts()) :
                        while ($events->have_posts()) : $events->the_post();
                            $event_date = get_post_meta(get_the_ID(), 'eventos_date', true);
                            $event_location = get_post_meta(get_the_ID(), 'eventos_location', true);
                            ?>
                            <tr>
                                <td><?php the_title(); ?></td>
                                <td><?php echo esc_html($event_date); ?></td>
                                <td><?php echo esc_html($event_location); ?></td>
                                <td><a href="<?php echo get_edit_post_link(); ?>">Edit</a></td>
                            </tr>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <tr>
                            <td colspan="4">No events found.</td>
                        </tr>
                        <?php
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}