<?php

class Event_Shortcode {

    /**
     * Register the shortcode.
     *
     * @return void
     */
    public static function register(): void {
        add_shortcode('proximos_eventos', array(__CLASS__, 'shortcode_callback'));
    }

    /**
     * Shortcode to display upcoming events.
     *
     * @return string The HTML output of the shortcode.
     */
    public static function shortcode_callback(): string {
        ob_start();
        $args = array(
            'post_type' => 'events',
            'posts_per_page' => -1,
            'meta_key' => 'eventos_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'eventos_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            )
        );
        $events = new WP_Query($args);
        if ($events->have_posts()) :
            echo '<ul>';
            while ($events->have_posts()) : $events->the_post();
                $event_date = get_post_meta(get_the_ID(), 'eventos_date', true);
                $event_location = get_post_meta(get_the_ID(), 'eventos_location', true);
                ?>
                <li>
                    <strong><?php the_title(); ?></strong><br>
                    Date: <?php echo esc_html($event_date); ?><br>
                    Location: <?php echo esc_html($event_location); ?>
                </li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        else :
            echo 'No upcoming events.';
        endif;
        return ob_get_clean();
    }
}