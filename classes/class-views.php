<?php
/**
 * This file is to handle View Templates
 *
 * @package MDFGF
 */

namespace MDFGF;

/**
 * Class for managing and rendering views
 */
class Views {

	/**
	 * Class Constructor
	 *
	 * @param object $plugin    contains plugin info.
	 *
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Render the Template File
	 *
	 * @param string     $template  view file located in the views folder minus the "views-" prefix and ".php" extension.
	 * @param array|null $vars      array of variables to be extracted to the templates.
	 * @param bool       $echo      whether to echo or return the output.
	 *
	 * @return string|void
	 */
	public function render( $template, $vars = null, $echo = false ) {

		$tempate_file = $this->plugin->path . '/views/view-' . $template . '.php';

		if ( ! file_exists( $tempate_file ) ) {
			wp_die( esc_html( __( 'Modern Designs for Gravity Forms Error - Missing Template file' ) ) );
		}

		if ( $vars ) {
			$vars = (object) $vars;
		}

		if ( ! $echo ) {
			ob_start();
			require $tempate_file;
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}

		require $tempate_file;
	}
}
