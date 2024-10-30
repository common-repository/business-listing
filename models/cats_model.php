<?php
/** Database Functions for Categories and Regions
 * @Package			Business Listing WP Plugin
 * @File				models/listings_model.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			3/5/2016
*/
class otgblist_Cats_Model {
	
	/** Gets Full List of Regions (paginated)
	 * @global type $wpdb
	 * @return array
	 */
	function get_regions () {
		global $wpdb;
		$table_listings = OTGBLIST_TABLES['listings'];
		$table_regions = OTGBLIST_TABLES['regions'];
		
		$sql = "SELECT $table_regions.*, COUNT($table_listings.region_id) as uses FROM $table_regions
				LEFT JOIN $table_listings ON $table_listings.region_id = $table_regions.region_id
				GROUP BY $table_regions.region_id";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}
	
	/** Gets a List of the Regions with Their Id
	 * @global type $wpdb
	 * @param int $active_only
	 * @return array
	 */
	function get_region_list ($active_only=1, $used_only=1) {
		global $wpdb;

		// ***** Build Where *****
		$where = '';
		if ($used_only)
			$where .= 'region_id IN (SELECT DISTINCT(region_id) FROM ' . OTGBLIST_TABLES['listings'] . ' WHERE active = 1) AND ';
		if ($active_only)
			$where .= 'active = 1 ';
		if (!empty($where)) 
			$where = ' WHERE ' . trim($where, 'AND');

		// ***** Get Number of Results and Calculate Number of Pages *****
		$sql = 'SELECT region_id, name FROM ' . OTGBLIST_TABLES['regions'] . $where . ' ORDER BY name ASC';
		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));

      return $result;
	}
	
	/** Set a Region as Inactive or Active
	 * @global type $wpdb
	 * @param int $region_id
	 * @param int $active
	 * @return boolean
	 */
	function set_region_active ($region_id, $active=1) {
		global $wpdb;
		$result_update = $wpdb->update(
			OTGBLIST_TABLES['regions'],
			['active' => $active],
			['region_id' => $region_id]
		);
		return $result_update;
	}
	
	/** Adds a region if it does not already exist
	 * @global type $wpdb
	 * @param string $name
	 * @param int $active
	 * @return array
	 */
	function add_region ($name, $active=1) {
		if (empty($name)) 
			return ['success' => 0, 'message' => 'Empty Name'];
		
		global $wpdb;
		$sql = $wpdb->prepare('SELECT region_id FROM ' . OTGBLIST_TABLES['regions'] . ' WHERE name = %s', $name);
		$result = stripslashes_deep($wpdb->get_row($sql, ARRAY_A));
		if (!empty($result)) {
			return ['succes' => 0, 'message' => "Region $name Already Exists"];
		} else {
			$result_insert = $wpdb->insert(
				OTGBLIST_TABLES['regions'], [
					'name' => $name,
					'active' => $active
				]
			);
			if (!$result_insert)
				return ['success' => 0, 'message' => 'Error Adding Region'];
			else 
				return ['success' => 1, 'message' => "Region $name Added"];
		}
	}
	
	/** Rename a Region
	 * @global object $wpdb
	 * @param int $region_id
	 * @param string $name
	 * @return boolean
	 */
	function region_rename ($region_id, $name) {
		global $wpdb;
		$result_update = $wpdb->update(
			OTGBLIST_TABLES['regions'],
			['name' => $name],
			['region_id' => $region_id]
		);
		if (false === $result_update)
			return false;
		
		return true;
	}

	/** Delete a Region
	 * @global type $wpdb
	 * @param int $id
	 * @return boolean
	 */
	function delete_region ($id) {
		global $wpdb;
		$result = $wpdb->delete(OTGBLIST_TABLES['regions'], ['region_id' => $id], ['%d']);
		if (false === $result || 0 == $result)
			return false;
		return true;
	}
	
	// **********************************************************
	
	/** Gets Full List of Categories (paginated)
	 * @global object $wpdb
	 * @param int $page
	 * @param int $per_page
	 * @param string $order_by
	 * @param string $order_direction
	 * @return array
	 */
	function get_categories () {
		global $wpdb;
		$table_listings = OTGBLIST_TABLES['listings'];
		$table_categories = OTGBLIST_TABLES['categories'];
		
		$sql = "SELECT $table_categories.*, COUNT($table_listings.category_id) as uses FROM $table_categories
				LEFT JOIN $table_listings ON $table_listings.category_id = $table_categories.category_id
				GROUP BY $table_categories.category_id";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}
	
	/** Gets a List of the Categories with Their Id
	 * @global object $wpdb
	 * @param int $active_only
	 * @param int $used_only
	 * @return array
	 */
	function get_category_list ($active_only=1, $used_only=1) {
		global $wpdb;
		$table_listings = OTGBLIST_TABLES['listings'];
		$table_categories = OTGBLIST_TABLES['categories'];		

		// ***** Build Where *****
		$where = '';
		if ($used_only)
			$where .= "category_id IN (SELECT DISTINCT(category_id) FROM $table_listings WHERE active = 1) AND ";
		if ($active_only)
			$where .= 'active = 1 ';
		if (!empty($where)) 
			$where = ' WHERE ' . trim($where, 'AND');

		// ***** Get Number of Results and Calculate Number of Pages *****
		$sql = "SELECT category_id, name FROM $table_categories
            $where
				ORDER BY name ASC";
		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
      return $result;
	}
	
	/** Set a Category as Inactive or Active
	 * @global object $wpdb
	 * @param int $category_id
	 * @param int $active
	 * @return boolean
	 */
	function set_category_active ($category_id, $active=1) {
		global $wpdb;
		$result_update = $wpdb->update(
			OTGBLIST_TABLES['categories'],
			['active' => $active],
			['category_id' => $category_id]
		);
		return $result_update;
	}
	
	/** Adds a Category if It Does not Already Exist
	 * @global object $wpdb
	 * @param string $name
	 * @param int $active
	 * @return array
	 */
	function add_category ($name, $active=1) {
		if (empty($name)) 
			return ['success' => 0, 'message' => 'Empty Name'];
		global $wpdb;
		$sql = $wpdb->prepare('SELECT category_id FROM ' . OTGBLIST_TABLES['categories'] . ' WHERE name = %s', $name);
		$result = stripslashes_deep($wpdb->get_row($sql, ARRAY_A));		
		if (!empty($result)) {
			return ['success'=>0, 'message'=>"Category $name already exists."];
		} else {
			$result_insert = $wpdb->insert(
				OTGBLIST_TABLES['categories'], [
					'name' => $name,
					'active' => $active
				]
			);
			if (!$result_insert)
				return ['success' => 0, 'message' => 'Error Adding Category'];
			else 
				return ['success' => 1, 'message' => "Category $name Added"];
		}
	}		
	
	/** Rename a Category
	 * @global object $wpdb
	 * @param int $category_id
	 * @param string $name
	 * @return boolean
	 */
	function category_rename ($category_id, $name) {
		global $wpdb;
		$result_update = $wpdb->update(
			OTGBLIST_TABLES['categories'],
			['name' => $name],
			['category_id' => $category_id]
		);
		if (false === $result_update)
			return false;
		
		return true;
	}

	/** Delete a Category
	 * @global type $wpdb
	 * @param int $id
	 * @return boolean
	 */
	function delete_category ($id) {
		global $wpdb;
		$result = $wpdb->delete(OTGBLIST_TABLES['categories'], ['category_id' => $id], ['%d']);
		if (false === $result || 0 == $result)
			return false;
		return true;
	}
	
}