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
 * Allow file uploads.
 */
function update_edit_form() {
  echo ' enctype="multipart/form-data"';
}
add_action( 'post_edit_form_tag', 'update_edit_form' );

/**
 * Metabox default values.
 */
function pr_img_text_defaults() {
  return array(
    'image' => '',
    'heading' => '',
    'text' => '',
    'pdf_src' => '',
    'pdf_text' => '',
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
            row.insertBefore( '#pr-img-text-sets .multi.row:last' );
            return false;
        });

        $( '.remove-row' ).on('click', function() {
            $(this).parents('div .multi.row').remove();
            return false;
        });
    });
    </script>
<div class="container" id="pr-img-text-sets">
<?php
    $count = 1;
    if ( $img_text_sets ) :
        foreach ( $img_text_sets as $field ) {
?><div class="multi row postbox">
    <div class="inside">
      <div>
        <input id="image_url_<?php echo $count; ?>" name="image[]" type="hidden" value="<?php echo $field['image'] ? $field['image'] : $defaults['image'] ?>" />
        <img id="picsrc_<?php echo $count; ?>" src="<?php echo $field['image'] ?: $defauts['image']; ?>" style="width:150px;" />
        <input id="upload_img_btn_<?php echo $count ?>" type="button" value="Upload New Image" />
      </div>
      <div>
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Heading</label>
        </p>
        <input name="heading[]" type="text" size="80" value="<?php echo $field['heading'] ?: $defaults['heading'] ?>" />
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Paragraph Text</label>
        </p>
        <textarea name="text[]" rows="5" cols="80"><?php echo $field['text'] ?: $defaults['text'] ?></textarea></div>
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Pdf</label>
        </p>
        <input id="pdf_src_<?php echo $count; ?>" title="select file" multiple="multiple" name="pdf_src[]" size="25" type="file" value="" />
        <?php if ($field['pdf_src'] && is_array($field['pdf_src'])) { echo 'Attached pdf: ' . $field['pdf_src']['url']; } ?>
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Pdf Title</label>
        </p>
        <input name="pdf_text[]" type="text" size="80" value="<?php echo $field['pdf_text'] ?: $defaults['pdf_text'] ?>"/>
      </div>

      <div><a class="button remove-row" href="#">Remove</a></div>
    </div>
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
?><div class="multi row postbox">
    <div class="inside">
      <div>
        <input id="image_url_<?php echo $count; ?>" name="image[]" type="hidden" value="<?php echo $defaults['image'] ?>" />
        <img id="picsrc_<?php echo $count; ?>" src="<?php echo $defauts['image']; ?>" style="width:150px;" />
        <input id="upload_img_btn_<?php echo $count; ?>" type="button" value="Upload New Image" />
      </div>
      <div>
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Heading</label>
        </p>
        <input name="heading[]" type="text" size="80" value="<?php echo $defaults['heading'] ?>" />
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Paragraph Text</label>
        </p>
        <textarea name="text[]" rows="5" cols="80"><?php echo $defaults['text'] ?></textarea></div>
      </div>
      <div><a class="button remove-row" href="#">Remove</a></div>
    </div>
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
  <div class="multi row empty-row screen-reader-text postbox">
    <div class="inside">
      <div>
        <input id="image_url_<?php echo $count; ?>" name="image[]" type="hidden" value="<?php echo $defaults['image'] ?>" />
        <img id="picsrc_<?php echo $count; ?>" src="<?php echo $defauts['image']; ?>" style="width:150px;" />
        <input id="upload_img_btn_<?php echo $count; ?>" type="button" value="Upload New Image" />
      </div>
      <div>
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Heading</label>
        </p>
        <input name="heading[]" type="text" size="80" value="<?php echo $defaults['heading'] ?>" />
        <p class="post-attributes-label-wrapper">
          <label class="post-attributes-label">Paragraph Text</label>
        </p>
        <textarea name="text[]" rows="5" cols="80"><?php echo $defaults['text'] ?></textarea></div>
      </div>
      <div><a class="button remove-row" href="#">Remove</a></div>
    </div>
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

  <div><a id="add-row" class="button" href="#">Add another</a></div>
</div>

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
    $pdfs = $_FILES['pdf_src'];

    $count = max ( count ( $headings ), count ( $texts ), count ( $images ) );
    // For all fields with any value - make sure not blank then save.
    for ( $i = 0; $i < $count; $i++ ) {
      if ( ! ( $headings[$i] . $texts[$i] . $images[$i] === '' ) ) :
        if ( $headings[$i] != '' )
          $new[$i]['heading'] = stripslashes( strip_tags( $headings[$i] ) );
        else
          $new[$i]['heading'] = '';

        if ( $texts[$i] != '' )
          $new[$i]['text'] = $texts[$i];
        else
          $new[$i]['text'] = '';

        if ( $images[$i] != '' )
          $new[$i]['image'] = stripslashes( $images[$i] );
        else
          $new[$i]['image'] = '';
      endif;
      if ( $pdfs['tmp_name'][$i] )
        $new[$i]['pdf_src'] = wp_upload_bits($pdfs['name'][$i], null, file_get_contents($pdfs['tmp_name'][$i]));
      else
        $new[$i]['pdf_src'] = '';
      error_log(print_r($new[$i]['pdf_src'], true));
    }
    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'pr_img_text_sets', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'pr_img_text_sets', $old );
}
add_action( 'save_post', 'pr_img_text_meta_save' );
?>


