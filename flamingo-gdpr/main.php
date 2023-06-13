<?php

/**
 * Plugin Name: Flamingo GDPR
 * Description: Enforce GDPR on Flamingo
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 7.2
 * Author: Webbame Ltd
 */

function flamingogdpr_delete_contact() {  
  $contacts = get_posts(array(
    'fields' => 'ids',
    'post_type'  => 'flamingo_contact'
  ));

  foreach ($contacts as $contact) {
    wp_delete_post($contact, true);
  }
}

function flamingogdpr_delete_inbound() {
  $posts = get_posts(array(    
    'post_type'  => 'flamingo_inbound'
  ));
  foreach ($posts as $post) {    
    if (date_diff(new DateTimeImmutable($post->post_date), new DateTimeImmutable('now'))->d >= 7) {
      wp_delete_post($post->ID);
    }    
  }
}

function flamingogdpr_execute_cron() {
  # delete flamingo_contact
  flamingogdpr_delete_contact();
  
  # amend flamingo_inbound
  flamingogdpr_delete_inbound();
}

# define cron jobs
add_action( 'famingogdrp_cron', 'flamingogdpr_execute_cron' );
if ( ! wp_next_scheduled( 'famingogdrp_cron' ) ) {
  wp_schedule_event( time(), 'hourly', 'famingogdrp_cron' );
}

register_deactivation_hook( __FILE__, 'famingogdrp_deactivate' ); 
function famingogdrp_deactivate() {
    $timestamp = wp_next_scheduled( 'famingogdrp_cron' );
    wp_unschedule_event( $timestamp, 'famingogdrp_cron' );
}