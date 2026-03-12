<?php
/**
 * Plugin Name: GO Day Mega Menu
 * Plugin URI:  https://goday.world
 * Description: Elementor widget that adds the GO Day mega menu dropdown to your site header.
 * Version:     1.0.0
 * Author:      GO Movement
 * Author URI:  https://gomovement.world
 * License:     GPL-2.0-or-later
 * Text Domain: goday-mega-menu
 * Requires Plugins: elementor
 * Elementor tested up to: 3.25.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GODAY_MEGA_MENU_VERSION', '1.0.0' );
define( 'GODAY_MEGA_MENU_URL', plugin_dir_url( __FILE__ ) );
define( 'GODAY_MEGA_MENU_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Initialize the plugin after all plugins are loaded.
 */
function goday_mega_menu_init() {

	// Bail if Elementor is not loaded.
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'goday_mega_menu_missing_elementor_notice' );
		return;
	}

	// Register the widget.
	add_action( 'elementor/widgets/register', 'goday_mega_menu_register_widgets' );

	// Register frontend assets (lazy-loaded per widget).
	add_action( 'elementor/frontend/after_register_scripts', 'goday_mega_menu_register_scripts' );
	add_action( 'elementor/frontend/after_register_styles', 'goday_mega_menu_register_styles' );

	// Register custom widget category.
	add_action( 'elementor/elements/categories_registered', 'goday_mega_menu_register_categories' );
}
add_action( 'plugins_loaded', 'goday_mega_menu_init' );

/**
 * Admin notice when Elementor is missing.
 */
function goday_mega_menu_missing_elementor_notice() {
	echo '<div class="notice notice-warning is-dismissible"><p>';
	echo esc_html__( 'GO Day Mega Menu requires Elementor to be installed and activated.', 'goday-mega-menu' );
	echo '</p></div>';
}

/**
 * Register the Elementor widget.
 */
function goday_mega_menu_register_widgets( $widgets_manager ) {
	require_once GODAY_MEGA_MENU_PATH . 'widgets/class-goday-mega-menu-widget.php';
	$widgets_manager->register( new \GoDayMegaMenu\Widgets\GoDayMegaMenuWidget() );
}

/**
 * Register frontend JavaScript.
 */
function goday_mega_menu_register_scripts() {
	wp_register_script(
		'goday-mega-menu',
		GODAY_MEGA_MENU_URL . 'assets/js/goday-mega-menu.js',
		[],
		GODAY_MEGA_MENU_VERSION,
		true
	);
}

/**
 * Register frontend CSS.
 */
function goday_mega_menu_register_styles() {
	wp_register_style(
		'goday-mega-menu',
		GODAY_MEGA_MENU_URL . 'assets/css/goday-mega-menu.css',
		[],
		GODAY_MEGA_MENU_VERSION
	);
}

/**
 * Register custom Elementor widget category.
 */
function goday_mega_menu_register_categories( $elements_manager ) {
	$elements_manager->add_category( 'goday', [
		'title' => __( 'GO Day', 'goday-mega-menu' ),
		'icon'  => 'fa fa-globe',
	] );
}
