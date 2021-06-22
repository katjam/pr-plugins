<?php
/* Add page property for displaying teasers */

/**
* Add metabox in sidebar pane
*/
function pr_metabox_display_property_teasers() {
  add_meta_box(
    'pr_property_teasers',
    'Property listings',
    'pr_property_teasers',
    'page',
    'side',
    'default'
  );
}
add_action( 'add_meta_boxes', 'pr_metabox_display_property_teasers' );

function pr_property_teasers() {
  global $post; // The current post
  wp_nonce_field( basename( __FILE__ ), 'pr_nonce' );
  $pr_current_listings = get_post_meta( $post->ID, 'pr_current_listing', true);
  $pr_completed_listings = get_post_meta( $post->ID, 'pr_completed_listing', true);
  $currentChecked = $pr_current_listings === 'on' ? true : false;
  $completedChecked = $pr_completed_listings === 'on' ? true : false;
?>
  <div>
    <input type="checkbox" name="pr_current_listing" <?php if ($currentChecked) echo "checked"; ?> />Display <strong>current</strong> property listings
  </div>
  <div>
    <input type="checkbox" name="pr_completed_listing" <?php if ($completedChecked) echo "checked"; ?> />Display <strong>completed</strong> property listings
  </div>
<?php }

/**
 * Save the display property teaser value.
 */
function pr_display_property_teasers_meta_save( $post_id ) {
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'pr_nonce' ] )
        && wp_verify_nonce( $_POST[ 'pr_nonce' ],
            basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    if ($_POST && array_key_exists('pr_current_listing', $_POST)) {
      update_post_meta( $post_id, 'pr_current_listing', $_POST['pr_current_listing'] );
    } else {
      delete_post_meta($post_id, 'pr_current_listing');
    }

    if ($_POST && array_key_exists('pr_completed_listing', $_POST)) {
      update_post_meta( $post_id, 'pr_completed_listing', $_POST['pr_completed_listing'] );
    } else {
      delete_post_meta($post_id, 'pr_completed_listing');
    }
}

add_action( 'save_post', 'pr_display_property_teasers_meta_save' );
