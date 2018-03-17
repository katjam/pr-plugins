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
    'pr_img_text_sets',
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
    'image' => '',
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
  $img_text_sets = get_post_meta( $post->ID, 'pr_img_text_sets', true );
  $defaults = pr_img_text_defaults();
?>

  <script type="text/javascript">
  jQuery(document).ready(function( $ ){
      $( '#add-row' ).on('click', function() {
          var row = $( '.empty-row.screen-reader-text' ).clone(true);
          row.removeClass( 'empty-row screen-reader-text' );
          row.insertBefore( '#pr-img-text-sets tbody>tr:last' );
          return false;
      });

      $( '.remove-row' ).on('click', function() {
          $(this).parents('tr').remove();
          return false;
      });
  });
  </script>
  <table id="pr-img-text-sets" width="100%">
    <thead>
        <tr>
            <th width="20%">Image</th>
            <th width="20%">Heading</th>
            <th width="50%">Text</th>
            <th width="8%"></th>
        </tr>
    </thead>
    <tbody>
<?php
  $count = 1;
  if ( $img_text_sets ) :
    foreach ( $img_text_sets as $field ) {
?><tr>
    <td>
      <input id="image_url_<?php echo $count; ?>" name="image[]" type="hidden" value="<?php echo $field['image'] ? $field['image'] : $defaults['image'] ?>" />
      <img id="picsrc_<?php echo $count; ?>" src="<?php echo $field['image'] ? $field['image'] : $defauts['image']; ?>" style="width:150px;" />
      <input id="upload_img_btn_<?php echo $count ?>" type="button" value="Upload Image" />
    </td>
    <td><input name="heading[]" type="text" size="48" value="<?php echo $field['heading'] ? $field['heading'] : $defaults['heading'] ?>" /></td>
    <td><textarea name="text[]" rows="5" cols="70"><?php echo $field['text'] ? $field['text'] : $defaults['text'] ?></textarea></td>
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
  <script>
        jQuery(document).ready( function( $ ) {
            jQuery('#upload_img_btn_<?php echo $count;?>').click(function() {
                //use here, because you may have multiple buttons, so `send_to_editor` needs fresh
                window.send_to_editor = function(html) {
                    imgurl = jQuery(html).attr('src')
                        jQuery('#image_url_<?php echo $count;?>').val(imgurl);
                    jQuery('#picsrc_<?php echo $count;?>').attr("src",imgurl);
                    tb_remove();
                }

                formfield = jQuery('#img_url_<?php echo $count;?>').attr('name');
                tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
                return false;
            });
        });
        </script>
<?php $count ++;
    }
  else :
  // A blank row.
?><tr>
    <td>
      <input id="image_url_<?php echo $count; ?>name="image[]" type="hidden" value="<?php echo $defaults['image'] ?>" />
      <img id="picsrc_<?php echo $count; ?>" src="<?php echo $defauts['image']; ?>" style="width:150px;" />
      <input id="upload_img_btn_<?php echo $count; ?>" type="button" value="Upload Image" />
    </td>
    <td><input name="heading[]" type="text" size="48" value="<?php echo $defaults['heading'] ?>" /></td>
    <td><textarea name="text[]" rows="5" cols="70"><?php echo $defaults['text'] ?></textarea></td>
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
  <script>
        jQuery(document).ready( function( $ ) {
            jQuery('#upload_img_btn_<?php echo $count;?>').click(function() {
                //use here, because you may have multiple buttons, so `send_to_editor` needs fresh
                window.send_to_editor = function(html) {
                    imgurl = jQuery(html).attr('src')
                        jQuery('#image_url_<?php echo $count;?>').val(imgurl);
                    jQuery('#picsrc_<?php echo $count;?>').attr("src",imgurl);
                    tb_remove();
                }

                formfield = jQuery('#img_url_<?php echo $count;?>').attr('name');
                tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
                return false;
            });
        });
        </script>


<?php endif; ?>

<!-- A blank row for jquery add another -->
  <tr class="empty-row screen-reader-text">
    <td>
      <input id="image_url_<?php echo $count; ?>" name="image[]" type="hidden" value="<?php echo $defaults['image'] ?>" />
      <img id="picsrc_<?php echo $count; ?>" src="<?php echo $defauts['image']; ?>" style="width:150px;" />
      <input id="upload_img_btn_<?php echo $count; ?>" type="button" value="Upload Image" />
    </td>
    <td><input name="heading[]" type="text" size="48" value="<?php echo $defaults['heading'] ?>" /></td>
    <td><textarea name="text[]" rows="5" cols="70"><?php echo $defaults['text'] ?></textarea></td>
    <td><a class="button remove-row" href="#">Remove</a></td>
  </tr>
  <script>
        jQuery(document).ready( function( $ ) {
            jQuery('#upload_img_btn_<?php echo $count;?>').click(function() {
                //use here, because you may have multiple buttons, so `send_to_editor` needs fresh
                window.send_to_editor = function(html) {
                    imgurl = jQuery(html).attr('src')
                        jQuery('#image_url_<?php echo $count;?>').val(imgurl);
                    jQuery('#picsrc_<?php echo $count;?>').attr("src",imgurl);
                    tb_remove();
                }

                formfield = jQuery('#img_url_<?php echo $count;?>').attr('name');
                tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
                return false;
            });
        });
        </script>


</tbody>
</table>

<p><a id="add-row" class="button" href="#">Add another</a></p>


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

  $old = get_post_meta( $post_id, 'pr_img_text_sets', true );
  $new = [];

  $headings = $_POST['heading'];
  $texts = $_POST['text'];
  $images = $_POST['image'];

  $count = count ( $headings );

  for ( $i = 0; $i < $count; $i++ ) {
    if ( $headings[$i] != '' ) :
      $new[$i]['heading'] = stripslashes( strip_tags( $headings[$i] ) );

      if ( $texts[$i] != '' )
          $new[$i]['text'] = $texts[$i];
      else
          $new[$i]['text'] = '';

      if ( $images[$i] != '' )
          $new[$i]['image'] = stripslashes( $images[$i] );
      else
          $new[$i]['image'] = '';
    endif;
  }
  if ( !empty( $new ) && $new != $old )
      update_post_meta( $post_id, 'pr_img_text_sets', $new );
  elseif ( empty($new) && $old )
      delete_post_meta( $post_id, 'pr_img_text_sets', $old );
}
add_action( 'save_post', 'pr_img_text_meta_save' );
?>


