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
    $lorem = '<p>Nullam pulvinar ante nec massa mollis tincidunt. Sed consectetur, lacus a vulputate molestie, velit ipsum semper enim, eu consectetur quam leo quis nisi. In mattis nisi eget orci convallis vel tempus eros imperdiet. Duis in lacus nec sapien porttitor iaculis eu et dui. Proin feugiat turpis ut tellus hendrerit pretium. Donec laoreet ante sed dui placerat venenatis. Aliquam pulvinar eros a velit eleifend elementum.</p>';
  /**********               Add Parent pages            ************/
  $parent_pages = array();
  // menu order => title
  $titles = array(
    5   => 'About Us',
    10  => 'Building Surveying',
    20  => 'Commercial Agency',
    40  => 'Case Studies',
    50  => 'Areas We Cover',
    60  => 'Contact Us',
  );
  foreach ( $titles as $menu_order => $title ) {
    $parent_pages[$menu_order]['title']   = $title;
    $parent_pages[$menu_order]['content'] = 'Please add content for ' . $title . '<br/>' . $lorem;
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

      //if ( $content['title'] !== 'About Us' ) {
        wp_update_nav_menu_item($menu_id, 0, array(
          'menu-item-title' => $content['title'],
          'menu-item-object' => 'page',
          'menu-item-object-id' => get_page_by_path( $slug )->ID,
          'menu-item-type' => 'post_type',
          'menu-item-status' => 'publish')
        );
      //}
    }
  }

  // Set About as the home page and case studies as the posts page.
  // Use a static front page
  $about = get_page_by_title( 'About Us' );
  update_option( 'page_on_front', $about->ID );
  update_option( 'show_on_front', 'page' );

  // Add pages to primary Nav
}
