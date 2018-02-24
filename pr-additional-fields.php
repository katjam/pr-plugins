<?php
/*
Plugin Name: PR  additional fields
Description: Provides additional fields on specific pages to allow multiple pics with text.
Author: katjam
*/

/**
* Add metabox below editing pane
*/
function pr_metabox_img_text() {
  add_meta_box(
    'img-text',
    'Image with Text',
    'pr_img_text_metabox_content',
    'page',
    'normal',
    'default'
  );
}
add_action( 'add_meta_boxes', 'pr_metabox_img_text' );

/**
 * Metabox default values.
 */
function pr_img_text_defaults() {
  return array(
    'image' => null,
    'heading' => '',
    'text' => '',
  );
}

/**
 * Callback function to populate metabox
 */
function pr_img_text_metabox_content() {
  global $post; // The current post
  wp_nonce_field( basename( __FILE__ ), 'pr_nonce' );
  $img_text_sets = ['image', 'heading', 'text'];
  foreach ($img_text_sets as $field) {
      $saved[$field] = get_post_meta( $post->ID, 'pr_img_text_'.$field, true );
  }
  $defaults = pr_img_text_defaults();
  $details = wp_parse_args ( $saved, $defaults );
?>
  <label for="heading">Heading</label>
  <input id="heading" name="heading" type="text" value="<?php echo $details['heading'] ?>" />
  <label for="text">Text</label>
  <input id="text" name="text" type="textbox" value="<?php echo $details['text'] ?>" />
  <label for="image">Image</label>
  <input id="image" name="image" type="text" value="<?php echo $details['image'] ?>" />
<?php
}

/**
 * Save the image and text values
 */
function pr_img_text_meta_save( $post_id ) {
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

  // Checks for input and saves if needed
  $img_text_sets = ['image', 'heading', 'text'];
  foreach ( $img_text_sets as $field ) {
    if ( isset( $_POST[ $field ] ) ) {
      update_post_meta(
        $post_id,
        'pr_img_text_' . $field,
        $_POST[ $field ]
      );
    }
  }
}
add_action( 'save_post', 'pr_img_text_meta_save' );
?>
