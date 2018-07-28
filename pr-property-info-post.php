<?php

/*
Plugin Name: PR Property Listing Post Type
Version: 0.0.0
Description: PR property listing post type.
*/

add_action( 'init', 'create_property_listing_post_type' );

function create_property_listing_post_type() {
  register_post_type(
    'property_listing', [
      'labels' => ['name' => __('Properties'), 'singular_name' => __('Property')],
      'public' => true,
      'has_archive' => true,
      'rewrite' => ['slug' => 'property'],
    ]
  );
}

function pr_property_listing_pdf() {
  add_meta_box(
    'pr_property_pdf',
    'Property Listing PDF',
    'pr_property_pdf_content'
    // add only to htis post type.
  );
}

function unregister_property_listings() {
  unregister_post_type('property_listing', '');
}

register_deactivation_hook(__FILE__, 'unregister_property_listings');
