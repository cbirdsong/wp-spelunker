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
			'WP Spelunker', // page_title
			'Spelunker', // menu_title
			'manage_options', // capability
			'spelunker', // menu_slug
			array( $this, 'create_admin_page' ) // function
		);
	}

	public function create_admin_page() {
		$this->spelunker_options = get_option( 'spelunker_option_name' ); 
		
		$sections = [
			[
				'name' => '<span title="About">⛏&nbsp;<span class="screen-reader-text">About</span></span>',
				'slug' => 'about',
				'template' => 'about.php'
			],
			[
				'name' => 'Editor Blocks',
				'slug' => 'editor-blocks',
				'template' => 'editor-blocks.php'
			],
			[
				'name' => 'Featured Images',
				'slug' => 'images',
				'template' => 'images.php'
			],
			[
				'name' => 'Page Templates',
				'slug' => 'page-templates',
				'template' => 'page-templates.php'
			],
		];
		
		$default_tab = 'about';
  	$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

		?>

		<div class="wrap">
			<h1><?php _e( 'WP Spelunker', 'wp-spelunker' ); ?></h1>

			<nav class="nav-tab-wrapper">
			<?php foreach ($sections as $section): ?>
				<a href="?page=spelunker&tab=<?= $section['slug'] ?>" class="nav-tab <?php if($tab===$section['slug']):?>nav-tab-active<?php endif; ?>">
					<?= $section['name'] ?>
				</a>
			<?php endforeach; ?>
			</nav>
			
			<div class="tab-content">
			<?php 
			if (!empty($tab)) {
				require_once plugin_dir_path( __FILE__ ) . 'sections/' . $tab . '.php';
			} ?>
    	</div>
		</div>
	<?php }
}
if ( is_admin() ) {
	$spelunker = new Spelunker();
}