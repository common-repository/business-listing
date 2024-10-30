=== Business Listing ===
Contributors: falcon13
Donate link: https://onthegridwebdesign.com/product/support-business-listing-wp-plugin/
Tags: business, listings, shortcode, thumbnail, business directory
Requires at least: 4.0
Tested up to: 6.6
Requires PHP: 5.6
Stable tag: 2.2.0
License: GPLv3

Displays a list of businesses in box with a a description below an image. They can be filter by category and region.

== License ==
Released under the terms of the GNU General Public License.

== Description ==
This plugin allows you to display listings of businesses in boxes which show an image, the business name, city and state. Visitors can click on optional region or category boxes to filter out businesses not in the region and/or category the are looking for. The regions and categories can be set by the user.

= Features =
*   Shortcode for showing the listings
*   Buttons allowing site visitors to select the region and category (if used)
*   Each listing gets an image along with a city, state, website, category and region.
*   Listings can be deactivated
*   Regions and categories are set by the user
*   There is an setting for what to call the regions

DISCLAIMER: Under no circumstances do we release this plugin with any warranty, implied or otherwise. We cannot be held responsible for any damage that might arise from the use of this plugin. Back up your WordPress database and files before installation.

== Installation ==
= Shortcode Usage =
Shortcode: [otg_business_listing]

= Options =
There are no options in the shortcode, all settings are on the plugin's options page. You can show/hide region and category buttons and the name for the regions button.

= Examples =
[otg_business_listing]

== Screenshots ==
1. Shortcode with regions only used.
2. Setting up the regions admin
3. Short code with both regions and categories used.
4. Listing add/edit form in the admin.
5. Main admin page showing the listings.
6. Live site with a bunch of listings.

== Changelog ==
2.2.0 (9/10/2024)
- Updated validation and filter helper functions, including splitting validation and filter functions into separate helper files.

2.1.2 (12/9/2022)
- Validation, Filter and View helpers improvements and updates for PHP 8.2.

2.1.1 (12/6/2022)
- Updated Datatables Javascript library
- Tweaks and code improvements.

2.1 (2/7/2022)
- Switched lists to use Datatables Javascript library
- Added ability to rename categories and region from list

2.0 (5/11/2021)
- First openly released version
- Brought the plugin up to current standards
- Moved shortcode functions into their own file
- Updated way table names are handled
- Run all user submitted data through filter functions
- Added nonces to forms
- Fixed adding new business
- Moved images into outer container to handle different aspect ratios
- Improved responsiveness

1.0 (5/5/2015)
- Plugin created in April 2015 as custom plugin for specific site.
- Originally called Store Listings

== Frequently Asked Questions ==
= Can I change the button color? =
* Right now you have to override the colors by CSS. In a future version it'll be in the options.
