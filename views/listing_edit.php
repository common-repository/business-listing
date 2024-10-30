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
	<?php otgblist_display_messages($message_list); ?>

	<h2>Business Listings: Add/Edit Listing</h2>

	<form method="post" class="otgblist_form1" style="display: inline-block; max-width: 550px;">
		<?php if (empty($listing['listing_id'])) wp_nonce_field('listing_add'); else wp_nonce_field('listing_edit'); ?>
		<input type="hidden" id="otgblist_image_id" name="otgblist_image_id" value="<?= $listing['image_id'] ?>">
		
		<p><label>*Name:</label> <input type="text" name="otgblist_name" maxlength="50" value="<?php esc_attr_e($listing['name']); ?>"></p>
		<p><label>Display City:</label> <input type="text" name="otgblist_city" maxlength="50" value="<?php esc_attr_e($listing['city']); ?>"></p>
		<p><label>Display State:</label> <input type="text" name="otgblist_state" maxlength="50" value="<?php esc_attr_e($listing['state']); ?>"></p>
<?php if (!empty($regions)) { ?>	
		<p>
			<label>Region:</label>
			<select name="otgblist_region_id">
				<option></option>
	<?php	foreach ($regions as $option) {
		if ($listing['region_id'] == $option['region_id']) $selected = ' selected="selected"';
		else $selected = '';
		echo "<option value='{$option['region_id']}'$selected>" . esc_html($option['name']) . '</option>';
	} ?>
			</select>
		</p>
<?php } ?>		
		
<?php if (!empty($categories)) { ?>	
		<p>
			<label>Category:</label>
			<select name="otgblist_category_id">
				<option></option>
	<?php	foreach ($categories as $option) {
		if ($listing['category_id'] == $option['category_id']) $selected = ' selected="selected"';
		else $selected = '';
		echo "<option value='{$option['category_id']}'$selected>" . esc_html($option['name']) . '</option>';
	} ?>
			</select>
		</p>
<?php } ?>		
		<p><label>Link:</label> <input type="text" name="otgblist_link" maxlength="200" value="<?php echo esc_url($listing['link']); ?>"></p>
		<p><label>Image:</label> <input type="button" id="otgblist_image_button" name="otgblist_image_button" class="button-secondary" value="Set Image"></p>
		<p><label>Active:</label> <input type="checkbox" name="otgblist_active" value="1" <?php if ($listing['active']) echo $checked_text ?>></p>

		<p style="text-align: center;">
			<input type="submit" class="button-primary" value="Save">
			<a href="admin.php?page=business-listings" class='button-primary' style="margin-left: 17px;">Back to List</a>
		</p>
	</form>	

<?php
if (!empty($listing['image_id'])) {
	$image_thumb_url = wp_get_attachment_thumb_url($listing['image_id']);
	$image_alt = get_post_meta($listing['image_id'], '_wp_attachment_image_alt', true);
} else {
	$image_thumb_url = '';
	$image_alt = '';
}
?>
	<img id="otgblist_listing_image" src="<?= $image_thumb_url ?> " alt="<?php esc_attr_e($image_alt); ?>" <?php if (empty($image_thumb_url)) echo 'style="display: none;"' ?>>

</div>