<?php
add_action( 'init', 'create_property_listing_post_type' );

function create_property_listing_post_type() {
  register_post_type(
    'property_listing', [
      'labels' => ['name' => __('Properties'), 'singular_name' => __('Property')],
      'public' => true,
      'has_archive' => false,
      'register_meta_box_cb' => 'pr_property_listing_meta_boxes',
      'rewrite' => ['slug' => 'commercial-property'],
      'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
    ]
  );
}

add_filter( 'default_content', 'default_listing_text', 10, 2 );

function default_listing_text($content, $post) {
  $blank_property =
    "<h4>Description</h4>


<h4>Accommodation</h4>


<h4>Legal Costs</h4>


<h4>Price</h4>


<h4>Terms</h4>


<h4>Business Rates</h4>


<h4>VAT</h4>
The property is elected for VAT and as such VAT will be chargeable on the rent.

<h4>Subject to Contract</h4>
This brochure is for guidance purposes only and does not constitute an offer or contract. All descriptions, particulars and dimensions stated are understood to be correct, but prospective purchasers or tenants must satisfy themselves that the information is correct and not rely on the information if entering into a contract or incurring expenses.

<h4>Viewing Arrangements</h4>
Viewings are by appointment only. Contact Phil Wiltshire BSc(Hons) MRICS on:
01208 812 812

For full details please download the PDF Brochure, which also contains some useful links.
";
  switch( $post->post_type) {
    case 'property_listing':
        $content = $blank_property;
    break;
    default:
        $content = '';
    break;
  }
  return $content;
}

function pr_property_listing_meta_boxes() {
  // https://developer.wordpress.org/reference/functions/add_meta_box/
  add_meta_box(
    'pr_property_details',
    'Property Details',
    'pr_property_details_form',
    null,
    'normal',
    'high'
  );
  add_meta_box(
    'pr_property_pdf',
    'Property PDF',
    'pr_property_pdf_form',
    null,
    'normal',
    'high'
  );
  add_meta_box(
    'pr_property_display_as',
    'Property Display As',
    'pr_property_listing_display_as',
    null,
    'side',
  );
  add_meta_box(
    'pr_property_status',
    'Property Status',
    'pr_property_listing_status',
    null,
    'side',
  );
  add_meta_box(
    'pr_property_disposal',
    'Property Disposal Type',
    'pr_property_listing_disposal',
    null,
    'side',
  );
}

function pr_property_details_form() {
    wp_nonce_field(plugin_basename(__FILE__), 'pr_property_details_nonce');
    $details = get_post_meta( get_the_ID(), 'pr_property_details', true );
?>
    <div style="padding: 10px; width: 150px;">
      <label for="details_name">Property Type </label>
      <input type="text" id="detail_type" name="pr_property_details[type]" size="80" value="<?= isset($details["type"]) ? $details["type"] : '' ?>" />
    </div>
    <div style="padding: 10px; width: 150px">
      <label for="details_address">Property Address </label>
      <textarea id="detail_address" name="pr_property_details[address]" rows="5" cols="60" ><?= isset($details["address"]) ? $details["address"] : '' ?></textarea>
    </div>
    <div style="padding: 10px; width: 150px">
      <label for="details_size">Property Size </label>
      <input type="text" id="detail_size" name="pr_property_details[size]" size="80" value="<?= isset($details["size"]) ? $details["size"] : '' ?>" />
    </div>
    <div style="padding: 10px; width: 150px;">
      <label for="details_price">Property Price </label>
      <input type="text" id="detail_price" name="pr_property_details[price]" size="80" value="<?= isset($details["price"]) ? $details["price"] : '' ?>" />
    </div>
    <div style="padding: 10px; width: 150px;">
      <label for="details_highlights">Property Highlight </label>
      <input type="text" id="details_highlights" name="pr_property_details[highlights]" size="80" value="<?= isset($details["highlights"]) ? $details["highlights"] : '' ?>" />
    </div>
    <div style="padding: 10px; width: 150px;">
      <label for="details_highlights2">Property Highlight </label>
      <input type="text" id="details_highlights2" name="pr_property_details[highlights2]" size="80" value="<?= isset($details["highlights2"]) ? $details["highlights2"] : '' ?>" />
    </div>
    <div style="padding: 10px; width: 150px;">
      <label for="details_teaser">Property Teaser</label>
      <input type="text" id="details_teaser" name="pr_property_details[teaser]" size="80" value="<?= isset($details["teaser"]) ? $details["teaser"] : '' ?>" />
    <span>NOTE: Displayed in list of properties. Not displayed on single property.</span>
    </div>

<?php }

function pr_property_pdf_form() {
    wp_nonce_field(plugin_basename(__FILE__), 'pr_property_pdf_nonce');
    $html = '<p class="description">';
    $html .= 'Upload your PDF here.';
    $html .= '</p>';
    $html .= '<input type="file" id="pr_property_pdf" name="pr_property_pdf" value="" size="25">';
    $filearray = get_post_meta( get_the_ID(), 'pr_property_pdf', true );
    $this_file = $filearray ? $filearray['url'] : '';
    if($this_file != ""){
      $html .= '<div><b>Warning</b> If saved with new chosen file, the current pdf will be replaced by the new file.<br>CURRENT PDF: <a href="'.$this_file.'" target="_blank">' . $this_file . '</a></div>';
    }
    echo $html;
}

function pr_property_listing_display_as() {
    wp_nonce_field(plugin_basename(__FILE__), 'pr_property_display_as_nonce');
    $display_as_options = ['Not listed', 'Current', 'Completed'];
    $saved_display_as = get_post_meta( get_the_ID(), 'pr_property_display_as', true );
    $display_as = $saved_display_as ? $saved_display_as : 'Not listed';
    $html = '<p class="description">';
    $html .= 'Select listing to display property in';
    $html .= '</p><ul>';
    foreach ($display_as_options as $i => $t) {
      $ckd = $t === $display_as ? 'checked' : '';
      $html .= '<li>';
      $html .= '<input type="radio" id="display_as'.$i.'" name="pr_property_display_as" value="'.$t.'"'.$ckd.'>';
      $html .= '<label for="display_as'.$i.'">'.$t.'</label>';
      $html .= '</li>';
    }
    $html .= '</ul>';
    echo $html;
}

function pr_property_listing_status() {
    wp_nonce_field(plugin_basename(__FILE__), 'pr_property_listing_status_nonce');
    $statuses = ['None', 'Sold STC', 'Under Offer', 'Let STC', 'Let Agreed', 'Sold'];
    $saved_status = get_post_meta( get_the_ID(), 'pr_property_listing_status', true );
    $status = $saved_status ? $saved_status : 'None';
    $html = '<p class="description">';
    $html .= 'Select a status or "None" for no banner';
    $html .= '</p><ul>';
    foreach ($statuses as $i => $s) {
      $ckd = $s === $status ? 'checked' : '';
      $html .= '<li>';
      $html .= '<input type="radio" id="status_'.$i.'" name="pr_property_listing_status" value="'.$s.'"'.$ckd.'>';
      $html .= '<label for="status_'.$i.'">'.$s.'</label>';
      $html .= '</li>';
    }
    $html .= '</ul>';
    echo $html;
}

function pr_property_listing_disposal() {
    wp_nonce_field(plugin_basename(__FILE__), 'pr_property_disposal_type_nonce');
    $disposal_types = ['For Sale', 'To Let'];
    $saved_types = unserialize(get_post_meta( get_the_ID(), 'pr_property_disposal_type', true ));
    $disposal_type = $saved_types ? $saved_types : [''];
    $html = '<p class="description">';
    $html .= 'Select a type of disposal';
    $html .= '</p><ul>';
    foreach ($disposal_types as $i => $t) {
      $ckd = in_array($t, $disposal_type) ? 'checked' : '';
      $html .= '<li>';
      $html .= '<input type="checkbox" id="disposal_'.$i.'" name="pr_property_disposal_type[]" value="'.$t.'"'.$ckd.'>';
      $html .= '<label for="disposal_'.$i.'">'.$t.'</label>';
      $html .= '</li>';
    }
    $html .= '</ul>';
    echo $html;
}

add_action('save_post', 'save_custom_meta_data');
function save_custom_meta_data($id) {
    if (!current_user_can('edit_post', $id)) {return;}
    if(!empty($_FILES['pr_property_pdf']['name'])) {
        if (!isset($_POST['pr_property_pdf_nonce']) || !wp_verify_nonce($_POST['pr_property_pdf_nonce'], plugin_basename(__FILE__))) {return;}
        $supported_types = array('application/pdf');
        $arr_file_type = wp_check_filetype(basename($_FILES['pr_property_pdf']['name']));
        $uploaded_type = $arr_file_type['type'];

        if(in_array($uploaded_type, $supported_types)) {
            // Todo Delete the old one
            $upload = wp_upload_bits($_FILES['pr_property_pdf']['name'], null, file_get_contents($_FILES['pr_property_pdf']['tmp_name']));
            if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                update_post_meta($id, 'pr_property_pdf', $upload);
            }
        }
        else {
            wp_die("The file type that you've uploaded is not a PDF.");
        }
    }
    if ( isset ($_REQUEST['pr_property_display_as'] )) {
        if (!isset($_POST['pr_property_display_as_nonce']) || !wp_verify_nonce($_POST['pr_property_display_as_nonce'], plugin_basename(__FILE__))) {return;}
        update_post_meta($id, 'pr_property_display_as',  sanitize_text_field($_POST['pr_property_display_as']));
    }
    if ( isset ($_REQUEST['pr_property_listing_status'] )) {
        if (!isset($_POST['pr_property_listing_status_nonce']) || !wp_verify_nonce($_POST['pr_property_listing_status_nonce'], plugin_basename(__FILE__))) {return;}
        update_post_meta($id, 'pr_property_listing_status', sanitize_text_field( $_POST['pr_property_listing_status']));
    }
    if ( isset ($_REQUEST['pr_property_disposal_type'] )) {
        if (!isset($_POST['pr_property_disposal_type_nonce']) || !wp_verify_nonce($_POST['pr_property_disposal_type_nonce'], plugin_basename(__FILE__))) {return;}
        update_post_meta($id, 'pr_property_disposal_type',  serialize($_POST['pr_property_disposal_type']));
    }

    if ( isset ($_REQUEST['pr_property_details'] )) {
        if (!isset($_POST['pr_property_details_nonce']) || !wp_verify_nonce($_POST['pr_property_details_nonce'], plugin_basename(__FILE__))) {return;}
        update_post_meta($id, 'pr_property_details',  $_POST['pr_property_details']);
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
