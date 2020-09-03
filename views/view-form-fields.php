<?php

/**
 * Template file for GFrom Feild Appearance Grid Setting
 *
 * @package MDFGF
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<style>
	.muted-link {
		transition: color .2s ease-in-out;
	}
	.muted-link:not(:hover) {
		color: #bbb;
		text-decoration: none;
	}
	.muted-link:hover {
		text-decoration: none;
	}
</style>

<?php 

if ( $vars->saved ) {
    ?>
<div class="hidden">
	<div class="updated fade">
		<p><?php 
    esc_html_e( 'Settings Updated', 'mdfgf' );
    ?></p>
	</div>
</div>
<?php 
}

?>

<?php 

if ( !empty($vars->is_global) ) {
    ?>
	<h3>
		<span>
			<i class="fa fa-check-square-o"></i> 
			<?php 
    esc_html_e( 'Modern Designs for Gravity Forms', 'mdfgf' );
    ?>
		</span>
		<span style="margin-left: 10px;font-size: 11px; font-weight: normal;margin-top: 4px;">
			<?php 
    esc_html_e( 'Version', 'mdfgf' );
    ?> <?php 
    echo  esc_html( $vars->version ) ;
    ?>
		</span>
	</h3>

	<a class="button" href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_account_url() ) ;
    ?>">
		<i class="fa fa-user"></i> 
		&nbsp;<?php 
    esc_html_e( 'My Account', 'mdfgf' );
    ?>
	</a> &nbsp; &nbsp;

	<?php 
    
    if ( mdfgf_fs()->is_not_paying() ) {
        ?>
		<a class="button" href="<?php 
        echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
        ?>">
			<i class="fa fa-cart-plus"></i> 
			&nbsp;<?php 
        esc_html_e( 'Upgrade', 'mdfgf' );
        ?>
		</a> &nbsp; &nbsp;
	<?php 
    }
    
    ?>

	<a class="button" href="<?php 
    echo  esc_url_raw( str_replace( '-pricing', '-contact', mdfgf_fs()->get_upgrade_url() ) ) ;
    ?>">
		<i class="fa fa-life-ring"></i> 
		&nbsp;<?php 
    esc_html_e( 'Support', 'mdfgf' );
    ?>
	</a>

	<br/><br/><br/>
<?php 
}

?>

<h3><span><i class="fa fa-cogs"></i> <?php 
echo  esc_html( $vars->title ) ;
?></span>
	<span style="margin-left: 10px;font-size: 11px; font-weight: normal;margin-top: 4px;">
		<?php 
esc_html_e( 'Version', 'mdfgf' );
?> <?php 
echo  esc_html( $vars->version ) ;
?>
	</span>
</h3>

<form id="mdfgf-admin-settings-form" method="post" action="" style="display: none;">
	<table class="form-table">
		<?php 

if ( empty($vars->is_global) ) {
    ?>
		<tr>
			<th>
				<label for="mdfgf_use_custom_selects">
					<?php 
    esc_html_e( 'Override Global Styles', 'mdfgf' );
    ?>
					<?php 
    echo  wp_kses_post( $vars->tooltips['mdfgf_override_globals_tooltip'] ) ;
    ?>
				</label>
			</th>
			<td>
				<input 
					type="checkbox" 
					id="mdfgf_override_globals" 
					name="mdfgf_override_globals" 
					value="1" 
					<?php 
    checked( $vars->settings['override_globals'], 1 );
    ?>> 
				&nbsp; &nbsp; 
				<small>
					<a href="/wp-admin/admin.php?page=gf_settings&subview=mdfgf">
						<?php 
    esc_html_e( 'Edit Global Settings', 'mdfgf' );
    ?>
					</a>
				</small>
			</td>
		</tr>
		<?php 
}

?>
		<tr class="mdfgf-override-options">
			<th>
				<label for="mdfgf_design">
					<?php 
esc_html_e( 'Design Style', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_design_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<select id="mdfgf_design" name="mdfgf_design" style="width: 300px;">
					<option value="mdfgf-gf" <?php 
selected( $vars->settings['design'], 'mdfgf-gf' );
?>><?php 
esc_html_e( 'Gravity Forms Default', 'mdfgf' );
?></option>
					<option value="mdfgf-mdfgf" <?php 
selected( $vars->settings['design'], 'mdfgf-mdfgf' );
?>><?php 
esc_html_e( 'Modern Designs for Gravity Forms', 'mdfgf' );
?></option>

					<option value="mdfgf-md" <?php 
selected( $vars->settings['design'], 'mdfgf-md' );
?> 
					<?php 
if ( mdfgf_fs()->is_not_paying() ) {
    ?>
						disabled
					<?php 
}
?>><?php 
esc_html_e( 'Material Design', 'mdfgf' );
?> 
					<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
						<?php 
    esc_html_e( '(Pro Version)', 'mdfgf' );
    ?>
					<?php 
}

?>
					</option>

					<option value="mdfgf-bootstrap" <?php 
selected( $vars->settings['design'], 'mdfgf-bootstrap' );
?>
					<?php 
if ( mdfgf_fs()->is_not_paying() ) {
    ?>
						disabled
					<?php 
}
?>><?php 
esc_html_e( 'Bootstrap', 'mdfgf' );
?> 
					<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
						<?php 
    esc_html_e( '(Pro Version)', 'mdfgf' );
    ?>
					<?php 
}

?>
					</option>

					<option value="" <?php 
selected( $vars->settings['design'], '' );
?>><?php 
esc_html_e( 'None', 'mdfgf' );
?></option>
				</select>
			</td>
		</tr>
		<tr class="mdfgf-theme-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th>
				<label for="mdfgf_color">
					<?php 
esc_html_e( 'Highlight Color', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_color_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<input type="text" id="mdfgf_color" placeholder="#8a8a8a" name="mdfgf_color" value="<?php 
echo  esc_attr( ( $vars->settings['color'] ? $vars->settings['color'] : '' ) ) ;
?>" style="width: 300px;">
			</td>
		</tr>
		<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
			<tr class="mdfgf-override-options"<?php 
    echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
    ?>>
				<th>
					<label for="mdfgf_use_custom_selects">
						<?php 
    esc_html_e( 'Use Responsive Grid', 'mdfgf' );
    ?>
						<?php 
    echo  wp_kses_post( $vars->tooltips['mdfgf_use_grid_tooltip'] ) ;
    ?>
					</label>
				</th>
				<td>
					<input type="checkbox" id="mdfgf_use_grid" name="mdfgf_use_grid" value="1" <?php 
    checked( $vars->settings['use_grid'], 1 );
    ?>>
				</td>
			</tr>
		<?php 
}

?>
		<tr class="mdfgf-theme-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th><label for="mdfgf_theme"><?php 
esc_html_e( 'Theme', 'mdfgf' );
?></label></th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<?php 
?>
		<tr class="mdfgf-theme-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] || 'mdfgf-md' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th>
				<label for="mdfgf_field_appearance">
					<?php 
esc_html_e( 'Field Appearance', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_field_appearance_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<tr class="mdfgf-theme-options mdfgf-not-md-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] || 'mdfgf-md' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th>
				<label for="mdfgf_label_animation">
					<?php 
esc_html_e( 'Label Animation', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_label_animation_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<tr class="mdfgf-override-options mdfgf-none-options">
			<th>
				<label for="mdfgf_framework">
					<?php 
esc_html_e( 'Add Framework Classes', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_framework_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<?php 
?>
		<tr class="mdfgf-theme-options mdfgf-not-md-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th>
				<label for="mdfgf_use_custom_selects">
					<?php 
esc_html_e( 'Use Custom Dropdowns', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_use_custom_selects_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<tr class="mdfgf-theme-options mdfgf-not-md-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th>
				<label for="mdfgf_use_custom_datepicker">
					<?php 
esc_html_e( 'Use Custom Datepicker', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_use_custom_datepicker_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<tr class="mdfgf-theme-options mdfgf-override-options"<?php 
echo  ( !$vars->settings['design'] || 'mdfgf-gf' === $vars->settings['design'] ? ' style="display:none;"' : '' ) ;
?>>
			<th>
				<label for="mdfgf_auto_grow_textareas">
					<?php 
esc_html_e( 'Auto Grow Textareas', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_auto_grow_textareas_tooltip'] ) ;
?>
				</label>
			</th>
			<td>
				<?php 
?>
				<?php 

if ( mdfgf_fs()->is_not_paying() ) {
    ?>
					<a href="<?php 
    echo  esc_url_raw( mdfgf_fs()->get_upgrade_url() ) ;
    ?>" class="muted-link"><?php 
    esc_html_e( 'Pro Version Only', 'mdfgf' );
    ?></a>
				<?php 
}

?>
			</td>
		</tr>
		<tr>
			<th>
				<label>
					<?php 
esc_html_e( 'Shortcode Overrides', 'mdfgf' );
?>
					<?php 
echo  wp_kses_post( $vars->tooltips['mdfgf_shortcode_overrides_tooltip'] ) ;
?>
				</label>
			</th>
			<td></td>
		</tr>
	</table>
	<p class="submit" style="text-align: left;">
		<input type="hidden" name="mdfgf_nonce" value="<?php 
echo  esc_attr( $vars->nonce ) ;
?>">
		<input type="submit" name="submit" value="<?php 
esc_attr_e( 'Save Settings', 'mdfgf' );
?>" class="button-primary gfbutton" id="save">
	</p>
</form>
