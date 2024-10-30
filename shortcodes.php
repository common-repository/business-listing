<?php
/** Shortcode
 * @Package			Business Listing WP Plugin
 * @File				shortcodes.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2021, On the Grid Web Design LLC
 * @created			1/26/2021
*/

/** Produces Content for the Shortcode
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgblist_shortcode ($attributes, $content = null) {
//	extract(shortcode_atts(array(), $attributes));
	
	// ***** Load *****
	require_once(OTGBLIST_ROOT_PATH . 'models/listings_model.php');
	$otgblist_Listings_Model = new otgblist_Listings_Model();
	require_once(OTGBLIST_ROOT_PATH . 'models/cats_model.php');
	$otgblist_Cats_Model = new otgblist_Cats_Model();
	
	// ***** Get Data *****
	$listings = $otgblist_Listings_Model->get_active_listings();
	shuffle($listings);

	// ***** View *****
	$output = '<div class="otgblist_buttons">';
	
	// *** Region Buttons ***
   $i = 1;
	if (get_option('otgblist_region_select')) {
		$region_list = $otgblist_Cats_Model->get_region_list();
		$output .= '<div class="otgblist_region_buttons"><p>' . esc_html(get_option('otgblist_region_label')) . '</p>';
		$output .= '<button id="otgblist_ball" onclick="otgblistShowAll(\'otgblist_ball\')">All</button>';
		foreach ($region_list as $region) {
         $id_str = 'otgblist_button' . $i;
			$output .= "<button id=\"$id_str\" onclick=\"otgblistRegionSelect({$region['region_id']}, '$id_str')\">" . esc_html($region['name']) . '</button>';
         $i++;
		}
		$output .= '</div>';
	}
	
	// *** Category Buttons ***
	if (get_option('otgblist_category_select')) {
		$category_list = $otgblist_Cats_Model->get_category_list();
		$output .= '<div class="otgblist_cat_buttons"><p>Categories</p>';
		$output .= '<button id="otgblist_ball" onclick="otgblistShowAll(\'otgblist_ball\')">All</button>';
		foreach ($category_list as $category) {
         $id_str = 'otgblist_button' . $i;
			$output .= "<button id=\"$id_str\" onclick=\"otgblistCatSelect({$category['category_id']}, '$id_str')\">" . esc_html($category['name']) . '</button>';
         $i++;
		}
		$output .= '</div>';
	}
	
	$output .= '</div>';
	
	// *** Main Display ***
	$output .= '<div style="text-align: center;">';
	if (!empty($listings)) foreach ($listings as $listing) {
      $class_str=''; 
      if (!empty($listing['region_id'])) {
         $class_str .= ' otgblist_r' . $listing['region_id'];
      }
      if (!empty($listing['category_id'])) {
         $class_str .= ' otgblist_c' . $listing['category_id'];
      }
		$output .= "<div class=\"otgblist_box$class_str\">";
			// * Image *
			if (!empty($listing['image_id'])) {
				$image_url = wp_get_attachment_image_src($listing['image_id'], [300, 200]);
				$image_alt = get_post_meta($listing['image_id'], '_wp_attachment_image_alt', true);
				if (empty($listing['link'])) $listing['link'] = '#';
				$output .= '<a href="' . $listing['link'] . '" target="_blank" rel="noopener" class="otgblist_box_img_outer">';
				if (!empty($image_url[0])) $output .= '<img src="' . esc_url($image_url[0]). '" alt="' . esc_attr($image_alt) . '">';
				$output .= '</a>';
			}
			// * Text *
			$output .= '<p>';
			if (!empty($listing['link'])) {
				$output .= '<a href="' . esc_url($listing['link']) . '" target="_blank" rel="noopener" class="otgblist_box_text">';
			}
			$output .= esc_html($listing['name']);
			if (!empty($listing['city'])) $output .= '<br>' . esc_html($listing['city']);
			if (!empty($listing['state'])) $output .= '<br>' . esc_html($listing['state']);
			if (!empty($listing['link'])) $output .= '</a>';
		$output .= '</p></div>';
		
	}
	$output .= '</div>';
	
	return $output;
}
