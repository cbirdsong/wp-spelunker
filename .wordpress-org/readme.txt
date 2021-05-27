=== WP Spelunker ===
Author URI: https://birdsong.dev
Plugin URI: https://github.com/cbirdsong/wp-spelunker
Tags: admin
Requires at least: 5.0
Tested up to: 5.7
Requires PHP: 7.3
Stable tag: 0.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a list of stuff your site is using.


== Description ==

Ever wonder which editor blocks and page templates a site actually uses? Now you can easily find out!

Caveats: 

- Nested reusable blocks are not listed by name, only that the page contains a nested reusable block.
- Untitled reusable blocks are all grouped under a single item
- Page templates are only detected on pages, not custom post types.
- I have not tested this extensively using lots of different third-party blocks.

== Frequently Asked Questions ==

= How about a list of <other thing>? =

I'd eventually like to list:

- Images used in content
- Advanced Custom Fields on posts
- Advanced Custom Fields in ACF editor blocks
- Shortcodes _(in the meantime, check out [Shortcodes in Use](https://wordpress.org/plugins/shortcodes-in-use/))_


== Installation ==

1. Download this repo as a zip file.
2. Go to `Plugins` in the Admin menu
3. Click on the button `Add new`
4. Search for "WP Spelunker" *or* click on the `upload` link to upload the zip file.
5. Click on `Activate plugin`


== Usage ==

1. Go to `Tools` in the Admin menu
2. Click `Spelunker` and select a tab.
3. Look at stuff!


== Changelog ==

= 0.5.0: March 12, 2021 =
* Add SEO Info.
* Remove page template support due to bug.

= 0.5.0: March 12, 2021 =
* Reorganize UI to use tabs.

= 0.3.0: March 12, 2021 =
* Add basic support for nested blocks and reusable blocks.

= 0.2.0: March 12, 2021 =
* Split into separate pages for blocks/templates.
* Group blocks by source/type.

= 0.1.0: November 17, 2020 =
* Initial release.