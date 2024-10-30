<?php
/** List Page
 * @Package			Business Listing WP Plugin
 * @File				view/list.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			4/23/15
*/
?>
<script>
jQuery(document).ready(function () {
	var tableData = [
<?php if (!empty($table_data)) foreach ($table_data as $listing) {
	if ($listing['active'])
		$active = '<span style="color: green">Yes</span>';
	else 
		$active = '<span style="color: red">No</span>';
	?> 
		[
			'<input type="checkbox" name="bulk_action_list[]" value="<?= $listing['listing_id'] ?>" class="otgblist_list_checkbox">',
			'<a href="admin.php?page=business-listings-edit&listing_id=<?= $listing['listing_id'] ?>" class="row-title"><?= esc_html($listing['name']) ?></a>',
			'<?= esc_html($listing['city']) ?>',
			'<?= esc_html($listing['state']) ?>',
			'<?= esc_html($listing['region']) ?>',
			'<?= esc_html($listing['category']) ?>',
			'<?= $active ?>',
			'<?php if (!empty($listing['link'])) echo '<a href="' . esc_url($listing['link']) . '" target="_blank">Visit Website</a>' ?>'
		],
<?php } ?>
	];
    jQuery('#table').DataTable( {
		data: tableData,
		autoWidth: false,
		pageLength: 25,
		stateSave: true,
		columnDefs: [ 
			{orderable: false, targets: [0, 7]}
		],
		order: [[ 1, "asc" ]]
	});
});
</script>
<div class="wrap">
	<?php otgblist_display_messages($message_list) ?>
	
	<h2>Business Listings <a href="admin.php?page=business-listings-edit" class="add-new-h2">Add New</a></h2>
	<form method="post">
		<?php wp_nonce_field('list') ?>
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-top">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="make_active">Make Active</option>
				<option value="make_inactive">Make Inactive</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
		
		<table id="table" class="otgblist_table1">
			<thead><tr>
				<th><input id="cb-select-all-1" type="checkbox"></th>
				<th>Name</th>
				<th>City</th>
				<th>State</th>
				<th>Region</th>
				<th>Category</th>
				<th>Active</th>
				<th></th>
			</tr></thead>
		</table>
		
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-bottom">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="make_active">Make Active</option>
				<option value="make_inactive">Make Inactive</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
	</form>
</div>