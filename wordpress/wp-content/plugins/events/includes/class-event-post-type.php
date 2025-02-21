<?php

class Event_Post_Type {

    /**
     * Register the custom post type "Eventos".
     *
     * @return void
     */
    public static function register(): void {
        $labels = array(
            'name' => 'Eventos',
            'singular_name' => 'Evento',
            'menu_name' => 'Eventos',
            'name_admin_bar' => 'Evento',
            'add_new' => 'Añadir Nuevo',
            'add_new_item' => 'Añadir Nuevo Evento',
            'new_item' => 'Nuevo Evento',
            'edit_item' => 'Editar Evento',
            'view_item' => 'Ver Evento',
            'all_items' => 'Todos los Eventos',
            'search_items' => 'Buscar Eventos',
            'not_found' => 'No se encontraron eventos.',
            'not_found_in_trash' => 'No se encontraron eventos en la papelera.'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
            'rewrite' => array('slug' => 'eventos'),
        );

        register_post_type('eventos', $args);
    }
}