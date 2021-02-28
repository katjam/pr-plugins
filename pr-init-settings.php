<?php

define('PR_PLUGIN_PATH', plugin_dir_path(__FILE__) . 'pr-settings.php');
register_activation_hook(PR_PLUGIN_PATH, 'pr_init_settings');
register_activation_hook(PR_PLUGIN_PATH, 'pr_add_pages');

/**
 * Initialise Philips Rogers site options.
 */
function pr_init_settings()
{
  // Set up core defaults options vars in wp_options table.
  // http://codex.wordpress.org/Option_Reference
  $core_settings = array(
    'default_comment_status' => 'closed',
    'default_role' => 'author',
    'comments_per_page' => 0,
    'blogdescription' => __('Chartered Building Surveyors & Commercial Property Agents'),
    'date_format' => __('j F Y'),
    'permalink_structure' => '/%postname%/',
  );
  foreach ( $core_settings as $k => $v ) {
    update_option( $k, $v );
  }
  // Delete dummy content.
  wp_delete_post(1, true);
  wp_delete_post(2, true);
  wp_delete_comment(1);
}

/**
 * Add default pages to new Philips Rogers install.
 */
function pr_add_pages()
{
  /**********               Add Parent pages            ************/
  $parent_pages = array();
  // menu order => title
  $titles = array(
    10  => 'Propery Search',
    20  => 'Building Surveying',
    30  => 'About Us',
    40  => 'Careers',
    50  => 'Completed Projects',
  );

  $content = array(
    10 => "<p>Please contact us if you wish to register an acquisition requirement or to market a property with us:</p>
<a href=\"mailto:commercial@philipsrogers.co.uk\">commercial@philipsrogers.co.uk</a>
<p>Tel: 01208 812 812</p>",
    20 => "<p>Philips Rogers offer a full range of building surveying and architectural design services.  Services we provide include:</p>
<ul>
<li>Planning & Development</li>
<li>Design & Specification</li>
<li>Contract Administration</li>
<li>Project Management</li>
<li>Dilapidations</li>
<li>Party Wall Advice</li>
<li>Survey & Defect Diagnosis</li>
<li>Expert Witness</li>
</li>"
,
    30 => "<p>Philips Rogers Ltd. was established in 2017 by Phil Wiltshire following 24 years of employment in the construction and property industry working throughout the UK, but predominantly in London, Cornwall and Devon.</p>
<p>Philips Rogers offers a unique combination of professional surveying and property development expertise coupled with a deep understanding of Cornwall and Devon commercial market conditions.  This combination enables us to identify and add significant value to development opportunities, whether acting in a professional capacity as chartered building surveyors or as commercial estate agents.</p>
<p>Philips Rogers also provide property management services for clients who require assistance in meeting legislative compliance with their portfolios or who are perhaps too busy or geographically remote from properties to effectively manage them on a day to day basis.</p>
<h2>Areas we Cover</h2>
<p>Philips Rogers are based in Wadebridge on the Camel Estuary in North Cornwall.  Being based in Wadebridge, we are able to cost effectively provide professional and agency services to many of the surrounding towns and villages as well as being close to the A30 and A39, enabling us to cover most of Cornwall and Devon with ease.</p>",
    40 => "Careers at Philips Rogers...",
    50 => "Completed projects...",
  );

  foreach ( $titles as $menu_order => $title ) {
    $parent_pages[$menu_order]['title']   = $title;
    $parent_pages[$menu_order]['content'] = $content[$menu_order];
  }

  $menu_name = 'PR Main Nav';
  $menu_loc = 'primary_navigation';
  $menu_exists = wp_get_nav_menu_object( $menu_name );
  $menu_id = null;
  if ( !$menu_exists ) { $menu_id = wp_create_nav_menu( $menu_name ); }
  $locs = get_theme_mod( 'nav_menu_locations' );
  $locs[$menu_loc] = $menu_id;
  set_theme_mod( 'nav_menu_locations', $locs);
  foreach ( $parent_pages as $p => $content ) {
    if (!get_page_by_title( $content['title'] )) {
      $slug = sanitize_title( $content['title'] );
      $page = array(
        'post_author' => 1,
        'post_content' => $content['content'],
        'post_title' => $content['title'],
        'post_name' => $slug,
        'post_type' => 'page',
        'post_status' => 'publish',
        'menu_order' => $p,
      );
      wp_insert_post($page);

      wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => $content['title'],
        'menu-item-object' => 'page',
        'menu-item-object-id' => get_page_by_path( $slug )->ID,
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish')
      );
    }
  }

  // Set About as the home page.
  // Use a static front page
  $about = get_page_by_title( 'About Us' );
  update_option( 'page_on_front', $about->ID );
  update_option( 'show_on_front', 'page' );
}
