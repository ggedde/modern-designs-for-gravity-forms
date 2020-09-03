<?php
/**
 * Modern Designs for Gravity Forms
 *
 * Plugin Name: Modern Designs for Gravity Forms
 * Plugin URI:  https://wordpress.org/plugins/modern-designs-for-gravity-forms/
 * Description: Enables Modern Designs for Gravity Forms.
 * Version:     1.0.0
 * Author URI:  https://gedde.dev
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: mdfgf
 *
  *
 * @package MDFGF
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'get_file_data' ) ) {
	$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
}

$mdfgf_plugin = (object) array(
	'version' => ! empty( $plugin_data['Version'] ) ? $plugin_data['Version'] : 'Unknown',
	'path'    => plugin_dir_path( __FILE__ ),
	'url'     => plugins_url( basename( dirname( __FILE__ ) ) ),
);

// Include MDFGF Class.
foreach ( array( 'admin', 'views', 'formfilters', 'settings', 'scripts' ) as $class ) {
	if ( ! file_exists( $mdfgf_plugin->path . 'classes/class-' . $class . '.php' ) ) {
		add_action(
			'admin_notices',
			function() {
				?>
				<div class="error notice">
					<p><?php echo esc_html( __( 'Modern Designs for Gravity Forms Error - Could not find class file', 'mdfgf' ) ); ?></p>
				</div>
				<?php
			}
		);
		return; // Stop executing this page as there is an error.
	}
	require_once $mdfgf_plugin->path . 'classes/class-' . $class . '.php';
}

if ( function_exists( 'mdfgf_fs' ) ) {
	mdfgf_fs()->set_basename( false, __FILE__ );
} else {
	// DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
	if ( ! function_exists( 'mdfgf_fs' ) ) {
		// Include Freemius SDK.
		require_once dirname( __FILE__ ) . '/fs.php';
	}
	// Initiate class for managing and rendering views.
	$mdfgf_views = new \MDFGF\Views( $mdfgf_plugin );

	// Initiate class for managing settings.
	$mdfgf_settings = new \MDFGF\Settings( $mdfgf_plugin );

	/**
	 * Disable Spacing Rule for the rest of this file to allow for better alignment.
	 * phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
	 */

	// Initiate Admin Page Settings.
	$mdfgf_admin = new \MDFGF\Admin( $mdfgf_plugin, $mdfgf_settings, $mdfgf_views );
	add_action( 'admin_init',                      array( $mdfgf_admin, 'admin_init' ) );
	add_action( 'gform_editor_js',                 array( $mdfgf_admin, 'editor_js' ) );
	add_action( 'gform_field_appearance_settings', array( $mdfgf_admin, 'field_appearance_settings' ), 10, 2 );
	add_action( 'gform_form_settings_page_mdfgf',  array( $mdfgf_admin, 'get_form_settings' ) );
	add_action( 'gform_settings_mdfgf',            array( $mdfgf_admin, 'get_form_settings' ) );
	add_filter( 'admin_enqueue_scripts',           array( $mdfgf_admin, 'admin_enqueue_scripts' ) );
	add_filter( 'gform_form_settings_menu',        array( $mdfgf_admin, 'settings_menu' ),             10, 2 );
	add_filter( 'gform_settings_menu',             array( $mdfgf_admin, 'settings_menu' ),             10, 2 );
	add_filter( 'gform_tooltips',                  array( $mdfgf_admin, 'form_tooltips' ) );
	add_filter( 'plugin_action_links',             array( $mdfgf_admin, 'plugin_action_links' ),       10, 2 );

	// Form Filters.
	$mdfgf_formfilters = new \MDFGF\FormFilters( $mdfgf_plugin, $mdfgf_settings, $mdfgf_views );
	add_action( 'gform_pre_render',                array( $mdfgf_formfilters, 'pre_render_form' ),     10, 6 );
	add_action( 'gform_shortcode_form',            array( $mdfgf_formfilters, 'shortcode_form' ),      10, 3 );
	add_action( 'gform_get_form_filter',           array( $mdfgf_formfilters, 'render_form' ),         10, 2 );
	add_filter( 'gform_next_button',               array( $mdfgf_formfilters, 'render_button' ),       10, 2 );
	add_filter( 'gform_previous_button',           array( $mdfgf_formfilters, 'render_button' ),       10, 2 );
	add_filter( 'gform_progress_steps',            array( $mdfgf_formfilters, 'render_steps' ),        10, 3 );
	add_filter( 'gform_submit_button',             array( $mdfgf_formfilters, 'render_button' ),       10, 2 );

	if ( ! is_admin() ) {
		add_action( 'gform_field_content',         array( $mdfgf_formfilters, 'field_content' ),       10, 5 );
		add_action( 'gform_field_css_class',       array( $mdfgf_formfilters, 'field_classes' ),       10, 3 );
	}

	// Scripts.
	$mdfgf_scripts = new \MDFGF\Scripts( $mdfgf_plugin, $mdfgf_settings );
	add_action( 'gform_enqueue_scripts',           array( $mdfgf_scripts, 'register_scripts' ) );

	// Uninstall.
	mdfgf_fs()->add_action( 'after_uninstall',     array( $mdfgf_settings, 'uninstall' ) );
}
