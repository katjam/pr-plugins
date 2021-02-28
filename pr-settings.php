<?php
/*
Plugin Name: Philips Rogers setup
Description: Provides default settings and pages for PR.
Author: katjam
Version: 2.0.0
 */

define('PR_PATH', plugin_dir_path(__FILE__));
define('PR_LOCATION', plugin_basename(__FILE__));
define('PR_URL', plugins_url('', __FILE__));
require_once(PR_PATH . 'pr-init-settings.php');
