<?php
/* List businesses in tiles with a photo and link in a random order
  Plugin Name: Business Listing
  Plugin URI: https://onthegridwebdesign.com
  Description: List businesses in tiles with a photo and link in a random order
  Author: Chris Hood, On The Grid Web Design LLC
  Copyright: (c) 2015-2024, On the Grid Web Design LLC
  Version: 2.2
  Author URI: https://chrishood.me
  Updated: 9/10/2024; Created: 4/23/2015
 */

// ****** Global Settings *****
global $wpdb;
define('OTGBLIST_TABLES', [
		'listings' => $wpdb->prefix . 'otgblist_listings',
		'regions' => $wpdb->prefix . 'otgblist_regions',
		'categories' => $wpdb->prefix . 'otgblist_categories']);
define('OTGBLIST_ROOT_PATH',  plugin_dir_path(__FILE__));

register_activation_hook( __FILE__, 'otgblist_install');
include_once(OTGBLIST_ROOT_PATH . 'shortcodes.php');
add_shortcode('otg_business_listing', 'otgblist_shortcode');
add_action('wp_loaded', 'otgblist_scripts');
if (is_admin()) {
	include(OTGBLIST_ROOT_PATH . 'admin.php');
	add_action('admin_menu', 'otgblist_admin');
	add_action('admin_enqueue_scripts', 'otgblist_admin_styles_and_scripts');
}

/** Load CSS and JS Files
 */	
function otgblist_scripts () {
	wp_register_style('otgblist_css', plugins_url('business-listing.min.css', __FILE__));
	wp_enqueue_style('otgblist_css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('otgblist_business-listing-script', plugins_url('business-listing.min.js', __FILE__));
}

/** Install & Update Function
 * @global wpdb $wpdb
 */
function otgblist_install() {
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	
	// *** Listing Table ***
	$sql_listings = 'CREATE TABLE ' . OTGBLIST_TABLES['listings'] . " (
		listing_id mediumint(9) NOT NULL AUTO_INCREMENT,
		created timestamp,
		name varchar(50) NOT NULL,
		city varchar(50),
		state varchar(50),
		region_id int,
		category_id int,
		link varchar(200),
		image_id int,
		active tinyint(1) DEFAULT 1,
		PRIMARY KEY  (listing_id)
		) $charset_collate;";

	// *** Regions Table ***
	$sql_regions = 'CREATE TABLE ' . OTGBLIST_TABLES['regions'] . " (
		region_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(100) NOT NULL,
		active tinyint(1) DEFAULT 1,
		PRIMARY KEY  (region_id)
		) $charset_collate;";

	// *** Categories Table ***
	$sql_categories = 'CREATE TABLE ' . OTGBLIST_TABLES['categories'] . " (
		category_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(100) NOT NULL,
		active tinyint(1) DEFAULT 1,
		PRIMARY KEY  (category_id)
		) $charset_collate;";
	
	$result = dbDelta([$sql_listings, $sql_regions, $sql_categories]);
	error_log('otgcalgs_install dbDelta Results: ' . print_r($result, true));
}