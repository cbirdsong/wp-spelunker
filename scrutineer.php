<?php
/**
 * Scrutineer
 *
 * @package       Scrutineer
 * @author        Cory Birdsong
 * @version       0.1.0
 *
 * @wordpress-plugin
 * Plugin Name:   Scrutineer
 * Plugin URI:    https://github.com/cbirdsong/site-content-audit-wordpress-plugin
 * Description:   Lists the page templates and blocks your site is actively using.
 * Version:       0.1.0
 * Author:        Cory Birdsong
 * Author URI:    https://birdsong.dev
 * License:       GPL-2.0+
 * License URI:   http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:   wp-scrutineer
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Scrutineer {
	private $scrutineer_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ));
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'scrutineer', plugin_dir_url( __FILE__ ) . 'scrutineer.css', array(), plugin_dir_path( __FILE__ ) . 'scrutineer.css', 'all' );
	}

	public function add_plugin_page() {
		add_management_page(
			'Scrutineer', // page_title
			'Scrutineer', // menu_title
			'manage_options', // capability
			'scrutineer', // menu_slug
			array( $this, 'create_admin_page' ) // function
		);
	}

	public function create_admin_page() {
		$this->scrutineer_options = get_option( 'scrutineer_option_name' ); ?>

		<div class="wrap">
			<h2><?php _e( 'Scrutineer', 'scrutineer' ); ?></h2>
			<p><?php _e( 'An overview of templates, shortcodes and blocks in use on your site.', 'scrutineer' ); ?>

			<?php
				do_settings_sections( 'scrutineer-admin' );
			?>
		</div>
	<?php }

	public function page_init() {
		add_settings_section(
			'scrutineer_setting_section_editor_blocks', // id
			'Editor Blocks', // title
			array( $this, 'section_editor_blocks' ), // callback
			'scrutineer-admin' // page
		);

		add_settings_section(
			'scrutineer_setting_section_page_templates', // id
			'Page Templates', // title
			array( $this, 'section_page_templates' ), // callback
			'scrutineer-admin' // page
		);
	}

	public function section_editor_blocks() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/editor-blocks.php';
	}
	public function section_page_templates() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/page-templates.php';
	}

}
if ( is_admin() ) {
	$scrutineer = new Scrutineer();
}