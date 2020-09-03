<?php

/**
 * Template file for GFrom Feild Appearance Switch Setting
 *
 * @package MDFGF
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<li class="field_checkbox_style_setting field_setting" style="display: none;">
	<label class="section_label"> <?php 
esc_html_e( 'Checkbox Style', 'mdfgf' );
?></label>
	<label><input type="radio" id="field_checkbox_style_setting_normal" value="normal" name="field_checkbox_style" onclick="SetFieldProperty('checkboxstyle', jQuery(this).val());"> <?php 
esc_html_e( 'Normal', 'mdfgf' );
?></label>
	<?php 
?>
	<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
		<label><input type="radio" id="field_checkbox_style_setting_switch" value="use-switch" name="field_checkbox_style" disabled> <?php 
    esc_html_e( 'Use Switch', 'mdfgf' );
    ?> <a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" style="color: #999; text-decoration: none;"> &nbsp; <?php 
    esc_html_e( '( Pro Version Only )', 'mdfgf' );
    ?></a></label>	
	<?php 
}

?>
</li>
