<?php
/*
  Plugin Name: Eventos
  Plugin URI: http://d2producciones.net
  Description: Plugin para la gestión de eventos D2 Producciones
  Version: 1
  Author: Jaume Fite Planes
  Author URI: http://bububass.com
 */

/**
 * Creación de eventos
 */

define( 'ROOT', plugins_url( '', __FILE__ ) );
define( 'IMAGES', ROOT . '/img/' );
define( 'STYLES', ROOT . '/css/' );
define( 'SCRIPTS', ROOT . '/js/' );

function uep_custom_post_type() {

function uep_add_event_info_metabox() {
    add_meta_box(
        'uep-event-info-metabox',
        __( 'Event Info', 'uep' ),
        'uep_render_event_info_metabox',
        'event',
        'side',
        'core'
    );
}
add_action( 'add_meta_boxes', 'uep_add_event_info_metabox' );

    $labels = array(
        'name'                  =>   __( 'Events', 'uep' ),
        'singular_name'         =>   __( 'Event', 'uep' ),
        'add_new_item'          =>   __( 'Add New Event', 'uep' ),
        'all_items'             =>   __( 'All Events', 'uep' ),
        'edit_item'             =>   __( 'Edit Event', 'uep' ),
        'new_item'              =>   __( 'New Event', 'uep' ),
        'view_item'             =>   __( 'View Event', 'uep' ),
        'not_found'             =>   __( 'No Events Found', 'uep' ),
        'not_found_in_trash'    =>   __( 'No Events Found in Trash', 'uep' )
    );
 
    $supports = array(
        'title',
        'editor',
        'excerpt'
    );
 
    $args = array(
        'label'         =>   __( 'Events', 'uep' ),
        'labels'        =>   $labels,
        'description'   =>   __( 'A list of upcoming events', 'uep' ),
        'public'        =>   true,
        'show_in_menu'  =>   true,
        'menu_icon'     =>   IMAGES . 'event.svg',
        'has_archive'   =>   true,
        'rewrite'       =>   true,
        'supports'      =>   $supports
    );
 
    register_post_type( 'event', $args );
}
add_action( 'init', 'uep_custom_post_type' );

function uep_render_event_info_metabox( $post ) {
 
    // generate a nonce field
    wp_nonce_field( basename( __FILE__ ), 'uep-event-info-nonce' );
 
    // get previously saved meta values (if any)
    $event_start_date = get_post_meta( $post->ID, 'event-start-date', true );
    $event_end_date = get_post_meta( $post->ID, 'event-end-date', true );
    $event_venue = get_post_meta( $post->ID, 'event-venue', true );
 
    // if there is previously saved value then retrieve it, else set it to the current time
    $event_start_date = ! empty( $event_start_date ) ? $event_start_date : time();
 
    //we assume that if the end date is not present, event ends on the same day
    $event_end_date = ! empty( $event_end_date ) ? $event_end_date : $event_start_date;
 
    ?>
 
<label for="uep-event-start-date"><?php _e( 'Event Start Date:', 'uep' ); ?></label>
        <input class="widefat uep-event-date-input" id="uep-event-start-date" type="text" name="uep-event-start-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $event_start_date ); ?>" />
 
<label for="uep-event-end-date"><?php _e( 'Event End Date:', 'uep' ); ?></label>
        <input class="widefat uep-event-date-input" id="uep-event-end-date" type="text" name="uep-event-end-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $event_end_date ); ?>" />
 
<label for="uep-event-venue"><?php _e( 'Event Venue:', 'uep' ); ?></label>
        <input class="widefat" id="uep-event-venue" type="text" name="uep-event-venue" placeholder="eg. Times Square" value="<?php echo $event_venue; ?>" />
 
    <?php <br ?>}
