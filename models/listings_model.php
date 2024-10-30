<?php
/** Database Functions for Main Listings
 * @Package			Business Listing WP Plugin
 * @File				models/listings_model.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			4/23/15
*/
class otgblist_Listings_Model {

	/** Gets all the records for active stores
	 * @global object $wpdb
	 * @return array
	 */
	function get_active_listings () {
		global $wpdb;
		$sql = 'SELECT * FROM ' . OTGBLIST_TABLES['listings'] . ' WHERE active = 1';
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}
	
	/** Gets All the Records for Active Businesses
	 * @global object $wpdb
	 * @param int $active_status
	 * @return array
	 */
	function get_all_listings ($active_status=null) {
		global $wpdb;
		$table_listings = OTGBLIST_TABLES['listings'];
		$table_regions = OTGBLIST_TABLES['regions'];
		$table_categories = OTGBLIST_TABLES['categories'];
	
		// ***** Build Where Statement *****
		$where = '';
		if (!empty($active_status)) {
			if (1 == $active_status) $where = ' WHERE active = 1';
			if (2 == $active_status) $where = ' WHERE active = 0';
		}
		
		$sql = "SELECT $table_listings.*, $table_regions.name AS region, $table_categories.name AS category
				FROM $table_listings
				LEFT JOIN $table_regions ON $table_regions.region_id = $table_listings.region_id
				LEFT JOIN $table_categories ON $table_categories.category_id = $table_listings.category_id"
				. $where;
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}

	/** Returns All Fields in a Listing Record
	 * @global object $wpdb
	 * @param int $listing_id
	 * @return array
	 */
	function get_listing ($listing_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM ' . OTGBLIST_TABLES['listings'] . ' WHERE listing_id = %d', $listing_id);
		return stripslashes_deep($wpdb->get_row($sql, ARRAY_A));
	}

	/** Creates a New Listing
	 * @global object $wpdb
	 * @param string $name
	 * @param string $city
	 * @param string $state
	 * @param string $link
	 * @param int $image_id
	 * @param int $active
	 */
	function create_listing ($name, $city, $state, $region_id, $category_id, $link, $image_id, $active=1) {
		global $wpdb;
		$wpdb->insert(
			OTGBLIST_TABLES['listings'],
			[
				'created' => current_time('mysql'),
				'name' => $name,
				'city' => $city,
				'state' => $state,
				'region_id' => $region_id,
				'category_id' => $category_id,
				'link' => $link,
				'image_id' => $image_id,
				'active' => $active
			]
		);
		return $wpdb->insert_id;
	}
	
	/** Updates a Listing
	 * @global object $wpdb
	 * @param int $id
	 * @param string $name
	 * @param string $city
	 * @param string $state
	 * @param string $link
	 * @param int $image_id
	 * @param int $active
	 */
	function update_listing ($id, $name, $city, $state, $region_id, $category_id, $link, $image_id, $active) {
		global $wpdb;
		$wpdb->update(
			OTGBLIST_TABLES['listings'],
			[
				'name' => $name,
				'city' => $city,
				'state' => $state,
				'region_id' => $region_id,
				'category_id' => $category_id,
				'link' => $link,
				'image_id' => $image_id,
				'active' => $active
			],
			['listing_id' =>  $id]
		);
	}
	
	/** Delete a Listing
	 * @global type $wpdb
	 * @param int $id
	 */
	function delete_listing ($id) {
		global $wpdb;
		$result = $wpdb->delete(OTGBLIST_TABLES['listings'], ['listing_id' => $id]);
		if (false === $result || 0 == $result)
			return false;
		return true;		
	}

	/** Set a Listing as Inactive or Active
	 * @global object $wpdb
	 * @param int $id
	 * @param int $active
	 */
	function set_active ($id, $active=1) {
		global $wpdb;
		return $wpdb->update(
			OTGBLIST_TABLES['listings'],
			['active' => $active],
			['listing_id' => $id]
		);
	}
}