<?php
/** Admin Page Controller
 * @Package			Business Listing WP Plugin
 * @File				admin.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 * @created			4/23/15
*/

/** Shows the Page and Handles Bulk Actions
 */
function otgblist_list_page() {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGBLIST_ROOT_PATH . 'models/listings_model.php');
	$otgblist_Listings_Model = new otgblist_Listings_Model();
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();

	// ***** Run Bulk Actions if Submitted *****
	if (isset($_POST['action'])) {
		check_admin_referer('list');
		$action = otgblist_get_request_string('action');
		$bulk_action_list = otgblist_get_request_int_array();

		if (empty($bulk_action_list)) {
			$message_list[] = ['Nothing to do that to.', 3, 2];
		} else {
			$listings_affected = 0;
			switch ($action) {
				case 'delete':
					foreach ($bulk_action_list as $listing_id) {
						$result = $otgblist_Listings_Model->delete_listing($listing_id);
						if (!$result)
							$message_list[] = ["There was an error deleting listing #$listing_id", 3, 1];
						else
							$listings_affected++;
					}
					$message_list[] = ["Listings Deleted: $listings_affected", 1, 3];
					break;
				case 'make_active':
					foreach ($bulk_action_list as $listing_id) {
						$result = $otgblist_Listings_Model->set_active($listing_id, 1);
						if (false === $result)
							$message_list[] = ["There was an error updating listing #$listing_id", 3, 1];
						else
							$listings_affected += $result;
					}
					$message_list[] = ["Listings set as active: $listings_affected", 1, 3];
					break;
				case 'make_inactive':
					foreach ($bulk_action_list as $listing_id) {
						$result = $otgblist_Listings_Model->set_active($listing_id, 0);
						if (false === $result)
							$message_list[] = ["There was an error updating listing #$listing_id", 3, 1];
						else
							$listings_affected += $result;
					}
					$message_list[] = ["Listings set as inactive: $listings_affected", 1, 3];
					break;
				default:
					$message_list[] = ['What do you want me to do?', 3, 1];
			}
		}		
	}
	
	// ***** Get Data *****
	$table_data = $otgblist_Listings_Model->get_all_listings();
	
	// ***** Call View *****
	include('views/list.php');
}

/** Shows the Add Listing Page and Handles Its Form
 */
function otgblist_listing_page() {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	
	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGBLIST_ROOT_PATH . 'models/listings_model.php');
	$otgblist_Listings_Model = new otgblist_Listings_Model();
	require_once(OTGBLIST_ROOT_PATH . 'models/cats_model.php');
	$otgblist_Cats_Model = new otgblist_Cats_Model();
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();
	
	$listing['listing_id'] = otgblist_get_request_int('listing_id');
	if (isset($_POST['_wpnonce'])) {
		// ***** Handle Form Submission *****
		if (empty($listing['listing_id']))
			check_admin_referer('listing_add');
		else
			check_admin_referer('listing_edit');
		
		$listing['name'] = otgblist_get_request_string('otgblist_name');
		$listing['city'] = otgblist_get_request_string('otgblist_city');
		$listing['state'] = otgblist_get_request_string('otgblist_state');
		$listing['region_id'] = otgblist_get_request_int('otgblist_region_id');
		$listing['category_id'] = otgblist_get_request_int('otgblist_category_id');
		$listing['link'] = otgblist_get_request_link('otgblist_link');
		$listing['image_id'] = otgblist_get_request_int('otgblist_image_id');
		$listing['active'] = otgblist_get_request_int('otgblist_active', 0);

		if (!empty($listing['name'])) {
			if (empty($listing['listing_id'])) {
				$listing['listing_id'] = $otgblist_Listings_Model->create_listing($listing['name'], $listing['city'], $listing['state'], $listing['region_id'],
						$listing['category_id'], $listing['link'], $listing['image_id'], $listing['active']);
				
				$message_list[] = [$listing['name'] . ' Added', 1, 3];
			} else {
				$otgblist_Listings_Model->update_listing($listing['listing_id'], $listing['name'], $listing['city'], $listing['state'], $listing['region_id'],
						$listing['category_id'], $listing['link'], $listing['image_id'], $listing['active']);
				$message_list[] = [$listing['name'] . ' Updated', 1, 3];
			}

		} else {
			$message_list[] = ['Name is Required', 3, 2];
		}
	}
	
	// ***** Call View *****
	if (empty($listing['listing_id']))
		$listing = ['listing_id'=>'', 'name'=>'', 'city'=>'', 'state'=>'', 'region_id'=>'', 'category_id'=>'', 'link'=>'', 'image_id'=>'', 'active'=>1];
		else
		$listing = $otgblist_Listings_Model->get_listing($listing['listing_id']);
	
	$regions = $otgblist_Cats_Model->get_region_list(1, 0);
	$categories = $otgblist_Cats_Model->get_category_list(1, 0);
	include('views/listing_edit.php');		
}

/** Displays the Region List Page and Handles Bulk Actions
 */
function otgblist_region_page() {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGBLIST_ROOT_PATH . 'models/cats_model.php');
	$otgblist_Cats_Model = new otgblist_Cats_Model();
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();

	// ***** Run Bulk Actions if Submitted *****
	if (isset($_POST['action'])) {
		check_admin_referer('regions');
		$action = otgblist_get_request_string('action');
		$bulk_action_list = otgblist_get_request_int_array();
		if (empty($bulk_action_list)) {
			$message_list[] = ['Nothing to do that to.', 3, 2];
		} else {
			$regions_affected = 0;
			switch ($action) {
				case 'rename':
					$rename_list = otgblist_get_request_str_array('rename');
					foreach ($bulk_action_list as $region_id) {
						$result = $otgblist_Cats_Model->region_rename($region_id, $rename_list[$region_id]);
						if (!$result)
							$message_list[] = ["There was an error renaming region #$region_id to " . $rename_list[$region_id], 3, 1];
					}
					$message_list[] = ['Regions Renamed' , 1, 3];
					break;
				case 'delete':
					foreach ($bulk_action_list as $region_id) {
						$result = $otgblist_Cats_Model->delete_region($region_id);
						if (!$result)
							$message_list[] = ["There was an error deleting region # $region_id", 3, 1];
						else
							$regions_affected++;
					}
					$message_list[] = ["Regions Deleted: $regions_affected" , 1, 3];
					break;
				case 'make_active':
					foreach ($bulk_action_list as $region_id) {
						$result = $otgblist_Cats_Model->set_region_active($region_id, 1);
						if (false === $result)
							$message_list[] = ["There was an error updating region # $region_id", 3, 1];
						else
							$regions_affected += $result;
					}
					$message_list[] = ["Regions set as active: $regions_affected" , 1, 3];
					break;
				case 'make_inactive':
					foreach ($bulk_action_list as $region_id) {
						$result = $otgblist_Cats_Model->set_region_active($region_id, 0);
						if (false === $result)
							$message_list[] = ["There was an error updating region # $region_id", 3, 1];
						else
							$regions_affected += $result;
					}
					$message_list[] = ["Regions set as inactive: $regions_affected" , 1, 3];
					break;
				default:
					$message_list[] = ['What do you want me to do?', 3, 1];
			}
		}
	}
	
	// ***** Get Data *****
	$table_data = $otgblist_Cats_Model->get_regions();
	
	// ***** Call View *****
	include('views/regions.php'); 
}

/** Displays the Region List Page and Handles Add Region Actions
 */
function otgblist_region_add() {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	check_admin_referer('regions_add');

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGBLIST_ROOT_PATH . 'models/cats_model.php');
	$otgblist_Cats_Model = new otgblist_Cats_Model();
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();

	// ***** Add Region *****
	$new_region = otgblist_get_request_string('otgblist_name');
	if (!empty($new_region)) {
		$result_add = $otgblist_Cats_Model->add_region($new_region);
		if ($result_add['success'])
			$message_list[] = [$result_add['message'], 1, 3];
		else
			$message_list[] = [$result_add['message'], 3, 2];
	}
	
	// ***** Get Data *****
	$table_data = $otgblist_Cats_Model->get_regions();
	
	// ***** Call View *****
	include('views/regions.php');
}

/** Displays the Category List Page and Handles Bulk Actions
 */
function otgblist_category_page () {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGBLIST_ROOT_PATH . 'models/cats_model.php');
	$otgblist_Cats_Model = new otgblist_Cats_Model();
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();

	// ***** Run Bulk Actions if Submitted *****
	if (isset($_POST['action'])) {
		check_admin_referer('categories');
		$action = otgblist_get_request_string('action');
		$bulk_action_list = otgblist_get_request_int_array();

		if (empty($bulk_action_list)) {
			$message_list[] = ['Nothing to do that to.', 3, 2];
		} else {
			$categories_affected = 0;
			switch ($action) {
				case 'rename':
					$rename_list = otgblist_get_request_str_array('rename');
					foreach ($bulk_action_list as $category_id) {
						$result = $otgblist_Cats_Model->category_rename($category_id, $rename_list[$category_id]);
						if (!$result)
							$message_list[] = ["There was an error deleting category #$category_id", 3, 1];
					}
					$message_list[] = ['Categories Renamed' , 1, 3];
					break;				
				case 'delete':
					foreach ($bulk_action_list as $category_id) {
						$result = $otgblist_Cats_Model->delete_category($category_id);
						if (!$result)
							$message_list[] = ["There was an error deleting category #$category_id", 3, 1];
						else
							$categories_affected++;
					}
					$message_list[] = ["Categories Deleted: $categories_affected" , 1, 3];
					break;
				case 'make_active':
					foreach ($bulk_action_list as $category_id) {
						$result = $otgblist_Cats_Model->set_category_active($category_id, 1);
						if (false === $result)
							$message_list[] = ["There was an error updating category #$category_id", 3, 1];
						else
							$categories_affected += $result;
					}
					$message_list[] = ["Categories set as active: $categories_affected" , 1, 3];
					break;
				case 'make_inactive':
					foreach ($bulk_action_list as $category_id) {
						$result = $otgblist_Cats_Model->set_category_active($category_id, 0);
						if (false === $result)
							$message_list[] = ["There was an error updating category #$category_id", 3, 1];
						else
							$categories_affected += $result;
					}
					$message_list[] = ["Categories set as inactive: $categories_affected" , 1, 3];
					break;
				default:
					$message_list[] = ['What do you want me to do?', 3, 1];
			}
		}
	}
	
	// ***** Get Data *****
	$table_data = $otgblist_Cats_Model->get_categories();
	
	// ***** Call View *****
	include('views/categories.php');
}

/** Handles Add Category Action
 */
function otgblist_category_add () {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	check_admin_referer('categories_add');

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGBLIST_ROOT_PATH . 'models/cats_model.php');
	$otgblist_Cats_Model = new otgblist_Cats_Model();
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();

	// ***** Add Category ****
	$new_category = otgblist_get_request_string('otgblist_name');
	if (!empty($new_category)) {
		$result_add = $otgblist_Cats_Model->add_category($new_category);
		if ($result_add['success'])
			$message_list[] = [$result_add['message'], 1, 3];
		else
			$message_list[] = [$result_add['message'], 3, 2];
	}
	
	// ***** Get Data *****
	$table_data = $otgblist_Cats_Model->get_categories();
	
	// ***** Call View *****
	include('views/categories.php');
}

/** Shows and Handles the Options Page
 */
function otgblist_options_page () {
	// ***** Security Check *****
	if (!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	include_once(OTGBLIST_ROOT_PATH . 'helpers/view_helper.php');
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();	
	$option_list = ['otgblist_region_select', 'otgblist_region_label', 'otgblist_category_select'];
	
	if (isset($_POST['_wpnonce'])) {
		// *** Save Options ***
		check_admin_referer('options');
		foreach ($option_list as $option)
			if (isset($_POST[$option])) update_option($option, otgblist_get_request_string($option));
	}

	// *** Get Options for View ***
	foreach ($option_list as $option) {
		$options[$option] = get_option($option);
	}

	// ***** Call View *****
	include('views/options.php');
}

/** Registers Admin Pages and Put Into Menu
 */
function otgblist_admin () {
	add_menu_page('Business Listings', 'Business Listings', 'edit_pages',  'business-listings', 'otgblist_list_page', '', 4.234);
	add_submenu_page('business-listings', 'Business Listings', 'List', 'edit_pages', 'business-listings', 'otgblist_list_page');
	add_submenu_page('business-listings', 'Business Listings Add', 'Add/Edit', 'edit_pages', 'business-listings-edit', 'otgblist_listing_page');
	add_submenu_page('business-listings', 'Business Listings Region', 'Regions', 'edit_pages', 'business-listings-regions', 'otgblist_region_page');
	add_submenu_page(null, 'Business Listings Add Region', 'Add Region', 'edit_pages', 'business-listings-add-region', 'otgblist_region_add');
	add_submenu_page('business-listings', 'Business Listings Category', 'Categories', 'edit_pages', 'business-listings-categories', 'otgblist_category_page');
	add_submenu_page(null, 'Business Listings Add Category', 'Add Category', 'edit_pages', 'business-listings-add-category', 'otgblist_category_add');
	add_submenu_page('business-listings', 'Business Listings Options', 'Options', 'edit_pages', 'business-listings-options', 'otgblist_options_page');
}

/** Loads and Setups Scripts and Styles for Admin Pages
 */
function otgblist_admin_styles_and_scripts () {
	wp_register_style('otgcalgs_datatables_css', plugins_url('datatables.min.css', __FILE__));
	wp_enqueue_style('otgcalgs_datatables_css');
	wp_enqueue_script('otgcalgs_datatables', plugins_url('datatables.min.js', __FILE__));
	include_once(OTGBLIST_ROOT_PATH . 'helpers/filter_helper.php');
	
	// ***** wp_enqueue_media Long Form to Go Around Bugs *****
	$mode = get_user_option('media_library_mode', get_current_user_id()) ? get_user_option('media_library_mode', get_current_user_id()) : 'grid';
	$mode_list = ['grid', 'list'];

	$new_mode = otgblist_get_request_string('mode');
	if (in_array($new_mode, $mode_list)) {
		$mode = $new_mode;
		update_user_option(get_current_user_id(), 'media_library_mode', $mode);
	}
	if (!empty($_SERVER['PHP_SELF']) && 'upload.php' === basename($_SERVER['PHP_SELF']) && 'grid' !== $mode) {
		wp_dequeue_script('media');
	}
	wp_enqueue_media();
}
