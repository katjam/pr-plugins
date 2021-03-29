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
  $pr_display_property_listings = get_post_meta( $post->ID, 'pr_property_teasers', true);
  $isChecked = $pr_display_property_listings === 'on' ? true : false;
?>
  <input type="checkbox" name="pr_property_teasers" <?php if ($isChecked) echo "checked"; ?> /><strong>Display property listings</strong> on this page.
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

    update_post_meta( $post_id, 'pr_property_teasers', $_POST['pr_property_teasers'] );
}

add_action( 'save_post', 'pr_display_property_teasers_meta_save' );

