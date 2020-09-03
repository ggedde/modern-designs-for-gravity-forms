<?php

/**
 * This file handles all Admin pages and setup
 *
 * @package MDFGF
 */
namespace MDFGF;

use  GFFormSettings ;
use  GFAPI ;
/**
 * Class for Admin Pages and Custom Post Types
 */
class Admin
{
    /**
     * Class Constructor
     *
     * @param object $plugin    contains plugin info.
     * @param object $settings  contains settings object.
     * @param object $views     contains views object.
     *
     * @return void
     */
    public function __construct( $plugin, $settings, $views )
    {
        $this->plugin = $plugin;
        $this->settings = $settings;
        $this->views = $views;
    }
    
    /**
     * Run check on Admin Init
     *
     * @return void
     */
    public function admin_init()
    {
        if ( !class_exists( 'GFAPI' ) ) {
            add_action( 'admin_notices', function () {
                ?>
					<div class="error notice">
						<h2><?php 
                esc_html_e( 'Warning', 'mdfgf' );
                ?></h2>
						<p><?php 
                esc_html_e( 'The plugin', 'mdfgf' );
                ?> <b><?php 
                esc_html_e( 'Modern Designs for Gravity Forms', 'mdfgf' );
                ?></b> <?php 
                esc_html_e( 'requires the plugin Gravity Forms. Please install and activate Gravity Forms or deactivate Modern Designs for Gravity Forms', 'mdfgf' );
                ?></p>
						<p><a href="https://www.gravityforms.com/" target="_blank"><?php 
                esc_html_e( 'Download the latest version of Gravity Forms', 'mdfgf' );
                ?></a></p>
					</div>
					<?php 
            } );
        }
    }
    
    /**
     * Add Plugin Setting Links
     *
     * @param array  $links        Array of existing links.
     * @param string $plugin_file  path to current plugin.
     *
     * @return array
     */
    public function plugin_action_links( $links, $plugin_file )
    {
        if ( strpos( $plugin_file, 'modern-designs-for-gravity-forms.php' ) !== false ) {
            $links = array_merge( array( '<a class="' . $plugin_file . '" href="' . admin_url( 'admin.php?page=gf_settings&subview=mdfgf' ) . '">' . __( 'Settings' ) . '</a>' ), $links );
        }
        return $links;
    }
    
    /**
     * Filter the GForms settings array to include Moder Designs
     *
     * @param array $items  List of existing items.
     *
     * @return array
     */
    public function settings_menu( $items )
    {
        $items[] = array(
            'name'  => 'mdfgf',
            'label' => 'Modern Designs',
        );
        return $items;
    }
    
    /**
     * Adjust Field Appearance Settings
     * Add Switches and Grid Columns
     *
     * @param int $position  Position of setting.
     * @param int $form_id   ID of Form.
     *
     * @return void
     */
    public function field_appearance_settings( $position, $form_id )
    {
        $settings = $this->settings->get( $form_id );
        if ( 100 === $position ) {
            $this->views->render( 'field-switch', null, true );
        }
        if ( 500 === $position && !empty($settings['use_grid']) ) {
            $this->views->render( 'field-grid', null, true );
        }
    }
    
    /**
     * Array of ToolTips for Gravity Forms
     *
     * @param array $tooltips array of tooltips to use.
     *
     * @return array
     */
    public function form_tooltips( $tooltips = array() )
    {
        $tooltips['mdfgf_design_tooltip'] = __( "Select which Design Style you would like to use. When using somthing other than Gravity Forms Default, Gravity forms Styles will be de-registered for faster page loads. If you are already using a css framework like Bootstrap or MDB, then it is best to set this to None and 'Add Framework Classes' for your Framework.", 'mdfgf' );
        $tooltips['mdfgf_framework_tooltip'] = __( 'Alternatively, if you are already including a css framework like Bootstrap or MDB Pro then you can add the classes to the form markup. Currently Supports Bootstrap 4 and MDB Pro (mdbootstrap.com)', 'mdfgf' );
        $tooltips['mdfgf_shortcode_overrides_tooltip'] = __( 'You can Override these values within the shortcode attributes. This is useful when needing to change colors or themes when embedding the form in different locations.<br>Examples:<br>mdfgf_theme="mdfgf-theme-default"<br>mdfgf_color="#21759b"', 'mdfgf' );
        $tooltips['mdfgf_color_tooltip'] = __( 'This will override the Highlight color used for Buttons, Radios and Checkboxes when filled and Focus events. Use Hexadecimal value.<br>Ex #8a8a8a', 'mdfgf' );
        $tooltips['mdfgf_auto_grow_textareas_tooltip'] = __( 'This will collapse all textarea fields in the form to 80px and will auto grow the height when the user types content into the box. The Max height is 300px and will show a scrollbar once they enter that much data.', 'mdfgf' );
        $tooltips['mdfgf_use_grid_tooltip'] = __( 'This will add the ability to set fields in responsive 1/4 grid. See "Field Size" setting under Appearance tab when editing a field. Only applies to certain field types.', 'mdfgf' );
        $tooltips['mdfgf_use_custom_selects_tooltip'] = __( 'This will add custom styles and functionality to the Dropdown fields (select fields). Their may be issues when using this with other frameworks, plugins, or older devices.', 'mdfgf' );
        $tooltips['mdfgf_use_custom_datepicker_tooltip'] = __( 'This will add custom styles to the Datepicker.', 'mdfgf' );
        $tooltips['mdfgf_override_globals_tooltip'] = __( 'This will allow you to override the settings from the Global Settings.', 'mdfgf' );
        $tooltips['mdfgf_label_animation_tooltip'] = __( 'This will place the label inside field and use it as a Placeholder. This will also remove the placeholder text if it has been set. The label will be animated once the user sets focus to the field. The placement of the animated label (Above or Below) will still depend on the setting you give it within your field settings', 'mdfgf' );
        $tooltips['mdfgf_field_appearance_tooltip'] = __( 'This will determine how the field inputs will show. When removing the backgrounds or borders make sure your page background color contrasts with the fields enough to be seen. You cannot remove both the border and background as that would make it too difficult for the user to see the field.', 'mdfgf' );
        $tooltips['mdfgf_field_input_size'] = __( 'This will determine the width of the input fields inside the multi-input field container.', 'mdfgf' );
        return $tooltips;
    }
    
    /**
     * Customize the sizes for the Field in the Editor
     * binding to the load field settings event to initialize the checkbox
     *
     * @return void
     */
    public function editor_js()
    {
        
        if ( $this->is_global() ) {
            $settings = $this->settings->get( $form_id );
        } else {
            // phpcs:ignore
            $form_id = ( isset( $_GET['id'] ) ? sanitize_key( $_GET['id'] ) : null );
            $settings = $this->settings->get( $form_id );
        }
        
        ?>
		<script>
		var column_fields = <?php 
        echo  wp_json_encode( $this->settings->column_fields ) ;
        ?>;
		var complex_fields = <?php 
        echo  wp_json_encode( $this->settings->complex_fields ) ;
        ?>;
		<?php 
        include $this->plugin->path . '/js/admin-field-editor-tooltip.js';
        if ( !empty($settings['use_grid']) ) {
            include $this->plugin->path . '/js/admin-field-editor-grid.js';
        }
        ?>
		</script>
		<?php 
    }
    
    /**
     * Customize the sizes for the Field in the Editor
     *
     * @param string $hook  string of page id form wp.
     *
     * @return void
     */
    public function admin_enqueue_scripts( $hook )
    {
        if ( in_array( $hook, array( 'toplevel_page_gf_edit_forms', 'forms_page_gf_settings' ), true ) ) {
            wp_enqueue_script(
                'mdfgf-admin-fields',
                $this->plugin->url . '/js/admin-fields.js',
                array( 'jquery' ),
                $this->plugin->version,
                true
            );
        }
    }
    
    /**
     * Check if Form Settings page is global or not
     *
     * @return bool
     */
    private function is_global()
    {
        $current_screen = get_current_screen();
        if ( is_admin() && empty($current_screen->base) ) {
            wp_die( esc_html( __( 'Modern Designs for Gravity Forms - Missing Admin Page Information', 'mdfgf' ) ) );
        }
        $is_global = ( 'forms_page_gf_settings' === $current_screen->base ? true : false );
        return $is_global;
    }
    
    /**
     * Save Admin Form Settings
     * return true if saved successfully. false if not.
     *
     * @return bool
     */
    public function save_settings()
    {
        $is_global = $this->is_global();
        $saved = false;
        $nonce = ( isset( $_POST['mdfgf_nonce'] ) ? sanitize_key( $_POST['mdfgf_nonce'] ) : '' );
        
        if ( !empty($nonce) && wp_verify_nonce( $nonce, 'mdfgf_form_submit' ) && isset( $_POST['mdfgf_design'] ) ) {
            $settings = array();
            foreach ( $this->settings->get_default() as $key => $value ) {
                
                if ( isset( $_POST['mdfgf_' . $key] ) ) {
                    $settings[$key] = sanitize_text_field( wp_unslash( $_POST['mdfgf_' . $key] ) );
                } else {
                    $settings[$key] = $value;
                }
            
            }
            
            if ( 'mdfgf-md' === $settings['design'] ) {
                $settings['use_custom_selects'] = 1;
                $settings['use_custom_datepicker'] = 1;
                if ( !in_array( $settings['field_appearance'], array( 'md-regular', 'md-filled', 'md-outlined' ), true ) ) {
                    $settings['field_appearance'] = 'md-regular';
                }
                if ( !in_array( $settings['theme'], array( 'mdfgf-theme-default', 'mdfgf-theme-vivid', 'mdfgf-theme-dark' ), true ) ) {
                    $settings['theme'] = 'mdfgf-theme-default';
                }
                switch ( $settings['field_appearance'] ) {
                    case 'md-regular':
                        $settings['label_animation'] = 'out';
                        break;
                    case 'md-filled':
                        $settings['label_animation'] = 'in';
                        break;
                    case 'md-outlined':
                        $settings['label_animation'] = 'line';
                        break;
                }
            }
            
            
            if ( $is_global ) {
                update_option( 'mdfgf_settings', $settings );
            } else {
                // phpcs:ignore
                $form_id = ( isset( $_GET['id'] ) ? sanitize_key( $_GET['id'] ) : null );
                if ( !$form_id ) {
                    wp_die( esc_html( __( 'Modern Designs for Gravity Forms - Missing Form Id', 'mdfgf' ) ) );
                }
                $form = $this->settings->get_form( $form_id );
                if ( empty($form) ) {
                    wp_die( esc_html( __( 'Modern Designs for Gravity Forms - Missing Form', 'mdfgf' ) ) );
                }
                foreach ( $settings as $setting_key => $setting_value ) {
                    $form['mdfgf_' . $setting_key] = $setting_value;
                }
                GFAPI::update_form( $form, $form_id );
            }
            
            $saved = true;
        }
        
        return $saved;
    }
    
    /**
     * Customize the Settings for Gravity Forms
     *
     * @return void
     */
    public function get_form_settings()
    {
        $saved = $this->save_settings();
        $is_global = $this->is_global();
        
        if ( $is_global ) {
            $settings = $this->settings->get();
            $title = __( 'Global Settings', 'mdfgf' );
        } else {
            $title = __( 'Modern Designs for Gravity Forms', 'mdfgf' );
            // phpcs:ignore
            $form_id = ( isset( $_GET['id'] ) ? sanitize_key( $_GET['id'] ) : null );
            if ( !$form_id ) {
                wp_die( esc_html( __( 'Modern Designs for Gravity Forms - Missing Form Id', 'mdfgf' ) ) );
            }
            $form = $this->settings->get_form( $form_id );
            if ( empty($form) ) {
                wp_die( esc_html( __( 'Modern Designs for Gravity Forms - Missing Form', 'mdfgf' ) ) );
            }
            $settings = $this->settings->get( $form_id );
        }
        
        $vars = array(
            'title'     => $title,
            'saved'     => $saved,
            'version'   => $this->plugin->version,
            'settings'  => $settings,
            'tooltips'  => array(),
            'is_global' => $is_global,
            'nonce'     => wp_create_nonce( 'mdfgf_form_submit' ),
        );
        foreach ( $this->form_tooltips() as $key => $value ) {
            $vars['tooltips'][$key] = gform_tooltip( $key, '', true );
        }
        if ( !$is_global ) {
            GFFormSettings::page_header();
        }
        $this->views->render( 'form-fields', $vars, true );
        if ( !$is_global ) {
            GFFormSettings::page_footer();
        }
    }

}