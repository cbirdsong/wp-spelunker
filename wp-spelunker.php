<?php
/**
 * Spelunker
 *
 * @package       Spelunker
 * @author        Cory Birdsong
 * @version       0.2.0
 *
 * @wordpress-plugin
 * Plugin Name:   Spelunker
 * Plugin URI:    https://github.com/cbirdsong/wp-spelunker
 * Description:   Displays a list of the page templates and blocks your site is using.
 * Version:       0.2.1
 * Author:        Cory Birdsong
 * Author URI:    https://birdsong.dev
 * License:       GPL-2.0+
 * License URI:   http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:   wp-spelunker
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Spelunker {
	private $spelunker_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ));
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'wp-spelunker', plugin_dir_url( __FILE__ ) . 'wp-spelunker.css', array(), plugin_dir_path( __FILE__ ) . 'wp-spelunker.css', 'all' );
	}

	public function add_plugin_page() {
		add_management_page(
			'WP Spelunker: Editor Blocks', // page_title
			'Spelunker: Blocks', // menu_title
			'manage_options', // capability
			'spelunker-blocks', // menu_slug
			array( $this, 'create_admin_page_blocks' ) // function
		);
		add_management_page(
			'WP Spelunker: Page Templates', // page_title
			'Spelunker: Templates', // menu_title
			'manage_options', // capability
			'spelunker-templates', // menu_slug
			array( $this, 'create_admin_page_blocks' ) // function
		);
	}

	public function create_admin_page_blocks() {
		$this->spelunker_options = get_option( 'spelunker_option_name' ); ?>

		<div class="wrap">
			<h2><?php _e( 'WP Spelunker: Editor Blocks', 'wp-spelunker' ); ?></h2>
			<p><?php _e( 'An overview blocks in use on your site.', 'wp-spelunker' ); ?>

			<?php
				require_once plugin_dir_path( __FILE__ ) . 'includes/editor-blocks.php';
			?>
		</div>
	<?php }

	public function create_admin_page_templates() {
		$this->spelunker_options = get_option( 'spelunker_option_name' ); ?>

		<div class="wrap">
			<h2><?php _e( 'WP Spelunker: Page Templates', 'wp-spelunker' ); ?></h2>
			<p><?php _e( 'An overview of page templates in use on your site.', 'wp-spelunker' ); ?>

			<?php
				require_once plugin_dir_path( __FILE__ ) . 'includes/page-templates.php';
			?>
		</div>
	<?php }

	public function section_editor_blocks() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/editor-blocks.php';
	}
	public function section_page_templates() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/page-templates.php';
	}

}
if ( is_admin() ) {
	$spelunker = new Spelunker();
}