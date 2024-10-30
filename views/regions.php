<?php
/** Regions Control Page
 * @Package			Business Listing WP Plugin
 * @File				view/regions.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			3/5/16
*/
?>
<script>
jQuery(document).ready(function () {
	var tableData = [
<?php if (!empty($table_data)) foreach ($table_data as $region) {
	if ($region['uses'])
		$uses = '<span style="color: green">' . esc_html($region['uses']) . '</span>';
	else 
		$uses = '<span style="color: red">None</span>';	
	if ($region['active'])
		$active = '<span style="color: green">Yes</span>';
	else 
		$active = '<span style="color: red">No</span>';
	?> 
		[
			'<input type="checkbox" id="bulk_action_list_<?= $region['region_id'] ?>" name="bulk_action_list[]" value="<?= $region['region_id'] ?>" class="otgblist_list_checkbox">',
			'<span class="otgblist_rename_off"><?= esc_html($region['name']) ?></span><input name="rename[<?= $region['region_id'] ?>]" value="<?= esc_html($region['name']) ?>" maxlength="99" onkeyup="otgblistRenameEntered(<?= $region['region_id'] ?>)" class="otgblist_rename_on">',			
			'<?= $uses ?>',
			'<?= $active ?>'
		],
<?php } ?>
	];
    jQuery('#table').DataTable( {
		data: tableData,
		autoWidth: false,
		pageLength: 25,
		stateSave: true,
		columnDefs: [ 
			{orderable: false, targets: [0]}
		],
		order: [[ 1, "asc" ]]
	});
});
</script>
<div class="wrap">
	<?php otgblist_display_messages($message_list); ?>
	
	<h2>Business Listings: Regions</h2>
	<form method="post" action="?page=business-listings-regions" style="max-width: 600px;">
		<?php wp_nonce_field('regions') ?>
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-top">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="rename">Rename</option>
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
				<th>Uses</th>
				<th>Active</th>
			</tr></thead>
		</table>
		
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-bottom">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="rename">Rename</option>
				<option value="make_active">Make Active</option>
				<option value="make_inactive">Make Inactive</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>		
	</form>
	
	<form method="post" action="?page=business-listings-add-region">
		<?php wp_nonce_field('regions_add') ?>
		<h3>Add Region</h3>
		<p><label>Name:</label> <input type="text" name="otgblist_name" maxlength="100" required></p>
		<input type="submit" class="button-primary" value="Add">
	</form>
</div>