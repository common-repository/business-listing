<?php
/** Edit Listing Page
 * @Package			Business Listing WP Plugin
 * @File				view/listing_edit.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2021, On the Grid Web Design LLC
 * @created			5/3/15
*/
?>
<div class="wrap">
	<h2>Business Listings: Options</h2>
<?php	include(OTGBLIST_ROOT_PATH . 'views/about.php'); ?>

	<form name="form1" method="post" class="otgblist_form1" style="display: inline-block; max-width: 550px;">
		<?php wp_nonce_field('options'); ?>
		<h4>Shortcode</h4>
		<p><label>Region Selection:</label>
		<?php otgblist_on_off_select('otgblist_region_select', $options['otgblist_region_select']); ?>
		</p>
		<p><label>Regions Label:</label>
			<input name="otgblist_region_label" value="<?php esc_attr_e($options['otgblist_region_label']); ?>" type="text" maxlength="20">
		</p>
		<p><label>Category Selection:</label>
		<?php otgblist_on_off_select('otgblist_category_select', $options['otgblist_category_select']); ?>
		</p>
		<p style="text-align: center;">
			<input type="submit" class="button-primary" value="Save">
		</p>
	</form>	

</div>