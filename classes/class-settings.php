<?php
/**
 * This file is to handle retrieving of the form settings
 *
 * @package MDFGF
 */

namespace MDFGF;

use GFAPI;

/**
 * Class for managing and rendering views
 */
class Settings {

	/**
	 * Array of Single Text Types for Gravity Forms
	 *
	 * @var array $single_text_fields
	 */
	public $single_text_fields = array(
		'text',
		'email',
		'phone',
		'textarea',
		'multiselect',
		'select',
		'number',
		'website',
		'quantity',
		'option',
		'post_title',
		'post_content',
		'post_excerpt',
		'post_tags',
		'post_category',
		'post_custom_field',
	);


	/**
	 * Array of Single Text Types for Gravity Forms
	 *
	 * @var array $column_fields
	 */
	public $column_fields = array(
		'text',
		'email',
		'phone',
		'textarea',
		'multiselect',
		'select',
		'number',
		'date',
		'time',
		'radio',
		'checkbox',
		'website',
		'name',
		'address',
		'post_image',
		'quantity',
		'option',
	);


	/**
	 * Array of Single Text Types for Gravity Forms
	 *
	 * @var array $complex_fields
	 */
	public $complex_fields = array(
		'name',
		'address',
		'post_image',
		'date',
		'time',
		'creditcard',
	);


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
	 * Get the default settings incase not settings available.
	 *
	 * @return array
	 */
	public function get_default() {
		return array(
			'design'                      => 'mdfgf-gf',
			'theme'                       => 'mdfgf-theme-default',
			'text_class'                  => '',
			'color'                       => '',
			'label_animation'             => '',
			'field_appearance'            => '',
			'framework'                   => '',
			'override_globals'            => 0,
			'auto_grow_textareas'         => 0,
			'use_grid'                    => 0,
			'use_custom_selects'          => 0,
			'use_custom_datepicker'       => 0,
			'use_transparent_backgrounds' => 0,
		);
	}


	/**
	 * Uninstall Function to remove unused Data
	 *
	 * @return void
	 */
	public function uninstall() {
		delete_option( 'mdfgf_settings' );
	}


	/**
	 * Get the form settings by id or get global settings
	 *
	 * @param int $form_id form id.
	 *
	 * @return array
	 */
	public function get( $form_id = null ) {

		$default_settings = $this->get_default();

		if ( $form_id ) {
			$form = $this->get_form( $form_id );
			if ( $form && ! empty( $form['mdfgf_override_globals'] ) ) {
				foreach ( $default_settings as $setting_key => $setting ) {
					if ( isset( $form[ 'mdfgf_' . $setting_key ] ) ) {
						$settings[ $setting_key ] = sanitize_text_field( $form[ 'mdfgf_' . $setting_key ] );
					}
				}
			}
		}

		if ( empty( $settings ) ) {
			$settings = get_option( 'mdfgf_settings' );

			// Set Defaults.
			if ( empty( $settings ) ) {
				$settings['design']   = 'mdfgf-mdfgf';
				$settings['use_grid'] = 1;
			}
		}

		foreach ( $default_settings as $setting_key => $setting ) {
			if ( ! isset( $settings[ $setting_key ] ) ) {
				$settings[ $setting_key ] = $setting;
			}
		}

		return $settings;
	}


	/**
	 * Get the form from GForms
	 *
	 * @param int $form_id form id.
	 *
	 * @return object|null
	 */
	public function get_form( $form_id ) {
		if ( class_exists( 'GFAPI' ) ) {
			$form = GFAPI::get_form( $form_id );
			if ( $form ) {
				return $form;
			}
		}
		return null;
	}


	/**
	 * Change hex color
	 *
	 * @param string $hex_code         string containging hex code.
	 * @param int    $adjust_percent   percent to adjuct the hex code.
	 *
	 * @return string
	 */
	public function adjust_brightness( $hex_code, $adjust_percent ) {
		$hex_code = ltrim( $hex_code, '#' );
		if ( strlen( $hex_code ) === 3 ) {
			$hex_code = $hex_code[0] . $hex_code[0] . $hex_code[1] . $hex_code[1] . $hex_code[2] . $hex_code[2];
		}
		$hex_code = array_map( 'hexdec', str_split( $hex_code, 2 ) );
		foreach ( $hex_code as & $color ) {
			$adjustable_limit = $adjust_percent < 0 ? $color : 255 - $color;
			$adjust_amount    = ceil( $adjustable_limit * $adjust_percent );
			$color            = str_pad( dechex( $color + $adjust_amount ), 2, '0', STR_PAD_LEFT );
		}
		return '#' . implode( $hex_code );
	}


	/**
	 * Get RGB color
	 *
	 * @param string $hex_code string for hex code.
	 *
	 * @return string
	 */
	public function hex_to_rgb( $hex_code ) {
		$hex_code = ltrim( $hex_code, '#' );
		if ( strlen( $hex_code ) === 3 ) {
			$hex_code = $hex_code[0] . $hex_code[0] . $hex_code[1] . $hex_code[1] . $hex_code[2] . $hex_code[2];
		}
		list($r, $g, $b) = sscanf( '#' . $hex_code, '#%02x%02x%02x' );

		return array(
			'r' => $r,
			'g' => $g,
			'b' => $b,
		);
	}
}
