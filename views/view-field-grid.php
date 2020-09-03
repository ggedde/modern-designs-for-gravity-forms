<?php
/**
 * Template file for GFrom Feild Appearance Grid Setting
 *
 * @package MDFGF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="size_setting size_input_setting field_setting">
	<label for="field_inputsize" class="section_label">
		<?php esc_html_e( 'Input Size', 'mdfgf' ); ?>
		<?php wp_kses_post( gform_tooltip( 'mdfgf_field_input_size' ) ); ?>
	</label>
	<select id="field_inputsize" onchange="SetFieldProperty('inputsize', jQuery(this).val());">
		<option value="tiny"><?php esc_html_e( 'Tiny (1/4 Column)', 'mdfgf' ); ?></option>
		<option value="small"><?php esc_html_e( 'Small (1/3 Column)', 'mdfgf' ); ?></option>
		<option value="medium"><?php esc_html_e( 'Medium (1/2 Column)', 'mdfgf' ); ?></option>
		<option value="large"><?php esc_html_e( 'Large (Full Width)', 'mdfgf' ); ?></option>
	</select>
</li>
