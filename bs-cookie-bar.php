<?php

/*
Plugin Name: Bootstrap 5 Cookie bar
Description: Simple cookie bar for Bootstrap 5 themes
Version: 1.0.2
Author: JP Burato
Author URI: https://jpburato.now.sh
*/

define( 'BS_JP_COOKIE_V', '1.0.2' );
define( 'BS_JP_OPTION_BUTTON', 'jp_bs_cookie_button');
define( 'BS_JP_OPTION_TEXT', 'jp_bs_cookie_text');
define( 'BS_JP_OPTION_LAYOUT', 'jp_bs_cookie_layout');
define( 'BS_JP_BAR_ACTIVE', 'jp_bs_bar_active');

define( 'BS_JP_PATH', plugin_dir_path(__FILE__) );
define( 'BS_JP_URL', plugin_dir_url( __FILE__ ) );

include_once BS_JP_PATH . '/classes/class.cookiebar.php';
include_once BS_JP_PATH . '/classes/class.dashboard.php';