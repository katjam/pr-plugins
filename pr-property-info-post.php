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
      'register_meta_box_cb' => 'pr_property_listing_meta_boxes',
      'rewrite' => ['slug' => 'property'],
      'supports' => array('title', 'editor', 'excerpt', 'revisions', 'thumbnail'),
    ]
  );
}

function pr_property_listing_meta_boxes() {
  add_meta_box(
    'pr_property_pdf',
    'Property Listing PDF',
    'pr_property_listing_pdf'
  );
}

function pr_property_listing_pdf() {
    wp_nonce_field(plugin_basename(__FILE__), 'pr_property_listing_pdf_nonce');
    $html = '<p class="description">';
    $html .= 'Upload your PDF here.';
    $html .= '</p>';
    $html .= '<input type="file" id="pr_property_listing_pdf" name="pr_property_listing_pdf" value="" size="25">';
    $filearray = get_post_meta( get_the_ID(), 'pr_property_listing_pdf', true );
    $this_file = $filearray ? $filearray['url'] : '';
    if($this_file != ""){
      $html .= '<div><b>Warning</b> If saved with new chosen file, the current pdf will be replaced by the new file.<br>CURRENT PDF: <a href="'.$this_file.'" target="_blank">' . $this_file . '</a></div>';
    }
    echo $html;
}

add_action('save_post', 'save_custom_meta_data');
function save_custom_meta_data($id) {
    if(!empty($_FILES['pr_property_listing_pdf']['name'])) {
        $supported_types = array('application/pdf');
        $arr_file_type = wp_check_filetype(basename($_FILES['pr_property_listing_pdf']['name']));
        $uploaded_type = $arr_file_type['type'];

        if(in_array($uploaded_type, $supported_types)) {
            $upload = wp_upload_bits($_FILES['pr_property_listing_pdf']['name'], null, file_get_contents($_FILES['pr_property_listing_pdf']['tmp_name']));
            if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                update_post_meta($id, 'pr_property_listing_pdf', $upload);
            }
        }
        else {
            wp_die("The file type that you've uploaded is not a PDF.");
        }
    }
}

function update_pr_property_listing_edit_form() {
    echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'update_pr_property_listing_edit_form');

/* Deregister on de-activate */
function unregister_property_listings() {
  unregister_post_type('property_listing', '');
}

register_deactivation_hook(__FILE__, 'unregister_property_listings');
