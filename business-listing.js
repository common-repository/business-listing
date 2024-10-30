/** Javascripts
 * @Package			Business Listing WP Plugin
 * @File				listing.js
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			5/3/15
*/

var otgblistImageSelector;
jQuery(document).ready(function ($) {
	/***** Listeners *****/
	$("#otgblist_image_button").click(function (e) {
		e.preventDefault();
		otgblistImageSelector = wp.media({title: "Upload Image", multiple: false}).open()
			.on("select", function (e2) {
				var uploadedImage = otgblistImageSelector.state().get("selection").first();
				$("#otgblist_image_id").val(uploadedImage.toJSON().id);
				$("#otgblist_listing_image").attr('src', uploadedImage.attributes.sizes.thumbnail.url).show(150);
				$(".media-modal-close").click();
			});
	});

	jQuery("#bulk-action-selector-top").change(function (e) {
		jQuery("#bulk-action-selector-bottom").val($("#bulk-action-selector-top").val());
		otgblistRenameElements();
	});
	jQuery("#bulk-action-selector-bottom").change(function (e) {
		jQuery("#bulk-action-selector-top").val($("#bulk-action-selector-bottom").val());
		otgblistRenameElements();
	});

	jQuery("#cb-select-all-1").change(function (e) {
		if (jQuery("#cb-select-all-1").prop("checked")) {
			jQuery(".otgblist_list_checkbox").prop("checked", true);
		} else {
			jQuery(".otgblist_list_checkbox").prop("checked", false);
		}
	});
});

/** Show All Listsings
 * @param {string} buttonid
 */
function otgblistShowAll (buttonid) {
    jQuery(".otgblist_region_buttons button").removeClass("otgblist_active");
    jQuery(".otgblist_cat_buttons button").removeClass("otgblist_active");
    jQuery("#" + buttonid).addClass("otgblist_active");
    jQuery(".otgblist_box").show(500);
}

/** Hide All But Listings in Selected Region
 * @param {int} region
 * @param {string} buttonid
 */
function otgblistRegionSelect (region, buttonid) {
    jQuery(".otgblist_region_buttons button").removeClass("otgblist_active");
    jQuery(".otgblist_cat_buttons button").removeClass("otgblist_active");
    jQuery("#" + buttonid).addClass("otgblist_active");
	 
    jQuery(".otgblist_r" + region).show(500);
    jQuery('.otgblist_box:not(".otgblist_r' + region + '")').hide(500);
}

/** Hide All But Listings in Selected Category
 * @param {int} cat
 * @param {string} buttonid
 */
function otgblistCatSelect (cat, buttonid) {
    jQuery(".otgblist_region_buttons button").removeClass("otgblist_active");
    jQuery(".otgblist_cat_buttons button").removeClass("otgblist_active");
    jQuery("#" + buttonid).addClass("otgblist_active");
   
    jQuery(".otgblist_c" + cat).show(500);
    jQuery('.otgblist_box:not(".otgblist_c' + cat + '")').hide(500);
}

/** Shows and Hides the Renaming Fields Based on Bulk Action Selected
 */
function otgblistRenameElements () {
	if ("rename" == jQuery("#bulk-action-selector-top").val()) {
		jQuery(".otgblist_rename_off").hide(50);
		jQuery(".otgblist_rename_on").show(50);
	} else {
		jQuery(".otgblist_rename_off").show(50);
		jQuery(".otgblist_rename_on").hide(50);
	}
}

/** Automatically Checks the Bulk Action Checkbox on a Row When Something Is Typeed in the Rename Input
 * @param {in} id
 */
function otgblistRenameEntered (id) {
	jQuery("#bulk_action_list_" + id).prop('checked', true);
}