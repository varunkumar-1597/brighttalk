<?php

/**
 * Plugin Name: BrightTALK News Feed
 * Plugin URI: https://www.brighttalk.com/
 * Description: API Integrated with https://newsapi.org/ to post all the updated news.
 * Version: 1.0.0
 * Author: BrightTALK
 * Author URI: https://www.brighttalk.com/
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('BRIGHT_TALK_MAIN_URL')) {
    define('BRIGHT_TALK_MAIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('BRIGHT_TALK_MAIN_PATH')) {
    define('BRIGHT_TALK_MAIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('BRIGHT_TALK_MAIN_IMG')) {
    define('BRIGHT_TALK_MAIN_IMG', BRIGHT_TALK_MAIN_URL . "assets/images/");
}

add_action('admin_menu','bright_talk_create_settings_page_menu');

/* Function to add settings page link in installed plugin page.*/
function bright_talk_create_settings_page_menu(){
	
	add_menu_page(__('BrightTALK News','brighttalk'), __('BrightTALK News', 'brighttalk'), 'administrator', "brighttalk", "brighttalk_settings_call_method", "dashicons-format-aside", 25);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bright_talk_action_link');

function bright_talk_action_link($links){
	$plugin_links = array('<a href="' . admin_url('admin.php?page=brighttalk') . '">'.__('Settings','wsdesk').'</a>');
	return array_merge($plugin_links, $links);
}

/* Template for create news feed rules. */
function brighttalk_settings_call_method()
{
	include_once(BRIGHT_TALK_MAIN_PATH . "include/brighttalk-create-news-feed-rule.php");
}

?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>