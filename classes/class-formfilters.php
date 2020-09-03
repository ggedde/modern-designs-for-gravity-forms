<?php

/**
 * This file is to handle Form Content Filters
 *
 * @package MDFGF
 */
namespace MDFGF;

/**
 * Class for managing and rendering views
 */
class FormFilters
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
     * Pre Render Form functon before Gravity Form is Created
     *
     * @param array $form GFrom form array.
     *
     * @return array
     */
    public function pre_render_form( $form )
    {
        $settings = $this->settings->get( $form['id'] );
        $design = $settings['design'];
        if ( empty($form['labelPlacement']) ) {
            $form['labelPlacement'] = 'top_label';
        }
        
        if ( empty($design) ) {
            $framework = $settings['framework'];
            if ( $framework ) {
                switch ( $framework ) {
                    case 'bootstrap':
                    case 'mdbpro':
                        $form['labelPlacement'] .= ' form-row';
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        
        if ( !empty($settings['design']) && 'mdfgf-gf' !== $settings['design'] ) {
            $form['cssClass'] .= (( empty($form['cssClass']) ? '' : ' ' )) . $settings['design'] . ' mdfgf-render';
        }
        return $form;
    }
    
    /**
     * Filter the Field Classes
     *
     * @param string $classes string of classes to return.
     * @param object $field   object of field data.
     * @param array  $form    array of GForm form.
     *
     * @return string
     */
    public function field_classes( $classes, $field, $form )
    {
        $settings = $this->settings->get( $form['id'] );
        $design = $settings['design'];
        
        if ( !empty($design) && 'mdfgf-gf' !== $design ) {
            if ( 'honeypot' !== $field->type ) {
                $classes .= ' mdfgf-field-type-' . $field->type;
            }
            if ( 'fileupload' === $field->type && !empty($field['allowedExtensions']) ) {
                $classes .= ' mdfgf-show-extensions';
            }
            
            if ( !empty($field->label) && 'hidden_label' !== $field['labelPlacement'] ) {
                $classes .= ' mdfgf-has-label';
            } elseif ( empty($field->label) ) {
                $classes .= ' mdfgf-no-label';
            }
            
            if ( 'fileupload' === $field->type && !empty($field['multipleFiles']) ) {
                $classes .= ' mdfgf-multifile';
            }
            if ( 'time' === $field['type'] && '24' === $field['timeFormat'] ) {
                $classes .= ' mdfgf-24';
            }
            if ( in_array( $field->type, $this->settings->single_text_fields, true ) || 'date' === $field['type'] && 'datepicker' === $field['dateType'] ) {
                $classes .= ' mdfgf-field';
            }
            if ( !empty($settings['use_grid']) && in_array( $field->type, $this->settings->column_fields, true ) ) {
                $classes .= ' ' . (( 'tiny' === $field->size ? ' mdfgfcol-3' : (( 'small' === $field->size ? ' mdfgfcol-4' : (( 'large' === $field->size ? ' mdfgfcol-12' : ' mdfgfcol-6' )) )) ));
            }
            
            if ( in_array( $field->type, $this->settings->complex_fields, true ) ) {
                $classes .= ' mdfgf-complex';
                if ( !empty($settings['use_grid']) ) {
                    $classes .= ' ' . (( !empty($field->inputsize) && 'tiny' === $field->inputsize ? ' mdfgfcol-input-3' : (( !empty($field->inputsize) && 'small' === $field->inputsize ? ' mdfgfcol-input-4' : (( !empty($field->inputsize) && 'large' === $field->inputsize ? ' mdfgfcol-input-12' : ' mdfgfcol-input-6' )) )) ));
                }
            }
        
        }
        
        
        if ( empty($design) ) {
            $framework = $settings['framework'];
            if ( $framework ) {
                switch ( $framework ) {
                    case 'bootstrap':
                    case 'mdbpro':
                        break;
                    case 'mdbpro':
                        // code...
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        
        return $classes;
    }
    
    /**
     * Filter the Content
     *
     * @param string $content   existing contect to return.
     * @param array  $field     GFrom field data.
     * @param mixed  $value     current value of field.
     * @param int    $lead_id   id of lead.
     * @param int    $form_id   id of form.
     *
     * @return string
     */
    public function field_content(
        $content,
        $field,
        $value,
        $lead_id,
        $form_id
    )
    {
        $settings = $this->settings->get( $form_id );
        $design = $settings['design'];
        $framework = $settings['framework'];
        if ( empty($design) && empty($framework) ) {
            return $content;
        }
        // @codingStandardsIgnoreLine This variable is from Gravity Forms
        $date_type = ( !empty($field->dateType) ? $field->dateType : '' );
        // @codingStandardsIgnoreLine This variable is from Gravity Forms
        $multiple_files = ( !empty($field->multipleFiles) ? $field->multipleFiles : '' );
        $complex_fields_classes = array(
            'name_prefix',
            'name_first',
            'name_last',
            'name_middle',
            'name_suffix',
            'address_line_1',
            'address_line_2',
            'address_city',
            'address_state',
            'address_zip',
            'address_country',
            'gfield_time_hour',
            'gfield_time_minute',
            'gfield_time_ampm',
            'ginput_full',
            'gfield_date_dropdown_month',
            'gfield_date_dropdown_day',
            'gfield_date_dropdown_year',
            'gfield_date_month',
            'gfield_date_day',
            'gfield_date_year'
        );
        if ( strpos( $content, 'ginput_complex' ) === false && ('time' === $field['type'] || 'date' === $field['type'] && 'datepicker' !== $field['dateType']) ) {
            $content = preg_replace( "/class=\\'([^\\']*ginput_container[^\\']*)\\'/m", "class='ginput_complex \$1'", $content );
        }
        if ( 'date' === $field['type'] ) {
            $content = preg_replace( '/\\<input /m', '<input autocomplete="mdfgfnone" ', $content );
        }
        if ( empty($design) && !empty($framework) ) {
            switch ( $framework ) {
                case 'bootstrap':
                case 'mdbpro':
                    break;
                default:
                    // code...
                    break;
            }
        }
        if ( empty($design) || 'mdfgf-gf' === $design ) {
            return $content;
        }
        
        if ( in_array( $field['type'], array(
            'address',
            'name',
            'time',
            'post_image',
            'date'
        ), true ) ) {
            if ( !empty($settings['use_grid']) ) {
                $content = preg_replace( '/(ginput_container_address|ginput_container_name|ginput_container_post_image|clear-multi)/m', 'mdfgf-row $1', $content );
            }
            $content = preg_replace( '/(' . implode( '|', $complex_fields_classes ) . ')/m', 'mdfgf-field $1', $content );
        }
        
        preg_match_all( '/\\<input [^\\>]+\\>/ms', $content, $inputs );
        preg_match_all( '/\\<select [^\\>]+\\>.*?\\<\\/select\\>/ms', $content, $selects );
        preg_match_all( '/\\<textarea [^\\>]+\\>.*?\\<\\/textarea\\>/ms', $content, $textareas );
        $tags = array_merge( $inputs[0], $selects[0], $textareas[0] );
        if ( !empty($tags) ) {
            foreach ( $tags as $tag ) {
                $new_tag = '';
                if ( stripos( $tag, "type='hidden" ) || stripos( $tag, "type='button" ) ) {
                    continue;
                }
                $input_classes = array( 'mdfgf-input' );
                
                if ( 'mdfgf-bootstrap' !== $design ) {
                    if ( stripos( $tag, "type='checkbox" ) ) {
                        if ( mdfgf_fs()->is_not_paying() ) {
                            $input_classes[] = 'mdfgf-checkbox';
                        }
                    }
                    if ( stripos( $tag, "type='radio" ) ) {
                        $input_classes[] = 'mdfgf-radio';
                    }
                }
                
                $new_tag = $tag;
                $add_textarea_wrapper = strpos( $tag, '<textarea' ) !== false && in_array( $settings['label_animation'], array( 'in', 'line' ), true );
                
                if ( preg_match( "/class=\\'([^\\']*)\\'/m", $tag, $class_matches ) ) {
                    $new_tag = str_replace( $class_matches[0], "class='" . implode( ' ', $input_classes ) . (( $class_matches[1] ? ' ' . $class_matches[1] : '' )) . "'", $new_tag );
                } else {
                    $new_tag = preg_replace( '/\\<(select|input|textarea) /m', '<$1 class="' . implode( ' ', $input_classes ) . '" ', $new_tag );
                }
                
                if ( $new_tag && $add_textarea_wrapper ) {
                    $new_tag = '<span class="mdfgf-input mdfgf-textarea">' . $new_tag . '</span>';
                }
                if ( $new_tag ) {
                    $content = str_replace( $tag, '<span class="mdfgf-field-input">' . $new_tag . '</span>', $content );
                }
            }
        }
        $has_tooltip = false;
        if ( preg_match_all( '/\\<(label)[^\\>]+\\>.*\\<\\/label\\>/mU', $content, $matches ) ) {
            if ( !empty($matches[0]) ) {
                foreach ( $matches[0] as $tag ) {
                    $new_tag = '';
                    
                    if ( 'post_image' === $field['type'] && stripos( $tag, 'ginput_post_image_file' ) !== false ) {
                        continue;
                        // Skip file for post image.
                    }
                    
                    
                    if ( preg_match( "/class=\\'([^\\']*)\\'/m", $tag, $class_matches ) ) {
                        $new_tag = str_replace( $class_matches[0], "class='mdfgf-label" . (( $has_tooltip && strpos( $class_matches[1], 'gfield_label' ) !== false ? ' mdfgf-has-tooltip' : '' )) . (( $class_matches[1] ? ' ' . $class_matches[1] : '' )) . "'", $tag );
                    } elseif ( !in_array( $field['type'], array( 'radio', 'checkbox', 'consent' ), true ) ) {
                        $new_tag = preg_replace( '/\\<(label)/m', '<$1 class="mdfgf-label"', $tag );
                    }
                    
                    
                    if ( 'hidden_label' === $field['labelPlacement'] && !empty($class_matches[1]) && strpos( $class_matches[1], 'gfield_label' ) !== false ) {
                        
                        if ( $has_tooltip ) {
                            $content = str_replace( $tag, '<label class="gfield_label mdfgf-label mdfgf-has-tooltip"></label>', $content );
                        } else {
                            $content = str_replace( $tag, '', $content );
                        }
                    
                    } elseif ( $new_tag ) {
                        $content = str_replace( $tag, $new_tag, $content );
                    }
                
                }
            }
        }
        $content = str_replace( "'gformDeleteUploadedFile(", "'mdfgfUpdateUploadPreviews();gformDeleteUploadedFile(", $content );
        return $content;
    }
    
    /**
     * Filter the Form Buttons
     *
     * @param string $progress_steps  html content.
     * @param array  $form            GForm array.
     * @param int    $page            current page.
     *
     * @return string
     */
    public function render_steps( $progress_steps, $form, $page )
    {
        $settings = $this->settings->get( $form['id'] );
        $design = $settings['design'];
        $framework = $settings['framework'];
        if ( empty($design) ) {
            if ( $framework ) {
                switch ( $framework ) {
                    case 'bootstrap':
                    case 'mdbpro':
                        $progress_steps = preg_replace( "/class\\=\\'(gf_step_number)\\'/m", "class='\$1 form-control'", $progress_steps );
                        $progress_steps = preg_replace( '/\\<span class\\=[^\\>]*gf_step_number[^\\>]*\\>' . $page . '\\<\\/span\\>/m', "<span class='gf_step_number form-control bg-primary text-light'>" . $page . '</span>', $progress_steps );
                        break;
                }
            }
        }
        return $progress_steps;
    }
    
    /**
     * Filter the Form Buttons
     *
     * @param string $button  html content for the button.
     * @param array  $form    GForm Array.
     *
     * @return string
     */
    public function render_button( $button, $form )
    {
        $settings = $this->settings->get( $form['id'] );
        $design = $settings['design'];
        $framework = $settings['framework'];
        if ( empty($design) ) {
            if ( $framework ) {
                switch ( $framework ) {
                    case 'bootstrap':
                    case 'mdbpro':
                        break;
                }
            }
        }
        return $button;
    }
    
    /**
     * Filter the form html and return content
     *
     * @param string $string     contents of form.
     * @param array  $form       Form being used.
     *
     * @return string
     */
    public function render_form( $string, $form )
    {
        return $this->filter_form( $string, $form['id'] );
    }
    
    /**
     * Filter the Shortcode settings and return content
     *
     * @param string $string     contents of form.
     * @param array  $attributes added attributes when using form.
     * @param string $content    main content from shortcode.
     *
     * @return string
     */
    public function shortcode_form( $string, $attributes, $content )
    {
        return $this->filter_form( $string, $attributes['id'], $attributes );
    }
    
    /**
     * Filter the form html and return content
     *
     * @param string $string     contents of form.
     * @param int    $form_id    Form being used.
     * @param array  $attributes added attributes when using form.
     *
     * @return string
     */
    public function filter_form( $string, $form_id, $attributes = array() )
    {
        if ( stripos( $string, 'mdfgf-container' ) ) {
            return $string;
        }
        $settings = $this->settings->get( $form_id );
        if ( empty($settings['design']) || 'mdfgf-gf' === $settings['design'] ) {
            return $string;
        }
        $rand_id = wp_rand( 1000, 9999 );
        $classes = array( 'mdfgf-container', 'mdfgf-form-id-' . $form_id, 'mdfgf-' . $rand_id );
        $main_color = '';
        $theme_class = '';
        $text_color_class = '';
        $auto_grow_textareas = false;
        $use_custom_selects = false;
        $use_custom_datepicker = false;
        $color_string = '';
        $label_animation = '';
        $field_appearance = '';
        
        if ( !empty($settings['design']) && 'mdfgf-gf' !== $settings['design'] ) {
            if ( $settings['color'] ) {
                $main_color = esc_attr( $settings['color'] );
            }
            if ( $settings['theme'] ) {
                $theme_class = esc_attr( $settings['theme'] );
            }
            if ( !empty($settings['text_class']) ) {
                $text_color_class = esc_attr( $settings['text_class'] );
            }
            if ( !empty($settings['label_animation']) ) {
                $label_animation = esc_attr( $settings['label_animation'] );
            }
            if ( !empty($settings['field_appearance']) ) {
                $field_appearance = esc_attr( $settings['field_appearance'] );
            }
            if ( !empty($settings['auto_grow_textareas']) ) {
                $auto_grow_textareas = true;
            }
            if ( !empty($settings['use_custom_selects']) ) {
                $use_custom_selects = true;
            }
            if ( !empty($settings['use_custom_datepicker']) ) {
                $use_custom_datepicker = true;
            }
        }
        
        // Get Attribute Values.
        if ( isset( $attributes['mdfgf_color'] ) ) {
            $main_color = esc_attr( $attributes['mdfgf_color'] );
        }
        if ( isset( $attributes['mdfgf_theme'] ) ) {
            $theme_class = esc_attr( $attributes['mdfgf_theme'] );
        }
        if ( isset( $attributes['mdfgf_text_class'] ) ) {
            $text_color_class = esc_attr( $attributes['mdfgf_text_class'] );
        }
        if ( isset( $attributes['mdfgf_label_animation'] ) ) {
            $label_animation = esc_attr( $attributes['mdfgf_label_animation'] );
        }
        if ( isset( $attributes['mdfgf_field_appearance'] ) ) {
            $field_appearance = esc_attr( $attributes['mdfgf_field_appearance'] );
        }
        if ( isset( $attributes['mdfgf_auto_grow_textareas'] ) ) {
            $auto_grow_textareas = !empty($attributes['mdfgf_auto_grow_textareas']);
        }
        if ( isset( $attributes['mdfgf_use_custom_selects'] ) ) {
            $use_custom_selects = !empty($attributes['mdfgf_use_custom_selects']);
        }
        if ( isset( $attributes['mdfgf_use_custom_datepicker'] ) ) {
            $use_custom_datepicker = !empty($attributes['mdfgf_use_custom_datepicker']);
        }
        // Update Classes.
        if ( $text_color_class ) {
            $classes[] = $text_color_class;
        }
        if ( $theme_class ) {
            $classes[] = $theme_class;
        }
        if ( !empty($label_animation) ) {
            $classes[] = 'mdfgf-animate-' . $label_animation;
        }
        if ( !empty($field_appearance) ) {
            $classes[] = 'mdfgf-' . $field_appearance;
        }
        if ( $auto_grow_textareas ) {
            $classes[] = 'mdfgf-auto-grow-textareas';
        }
        if ( $use_custom_selects ) {
            $classes[] = 'mdfgf-use-custom-selects';
        }
        if ( $use_custom_datepicker ) {
            $classes[] = 'mdfgf-use-custom-datepicker';
        }
        
        if ( !$main_color && 'mdfgf-bootstrap' === $settings['design'] ) {
            $main_color = '#007bff';
            $hover_color = $this->settings->adjust_brightness( $main_color, -0.2 );
        }
        
        
        if ( $main_color ) {
            $main_color = strtolower( $main_color );
            if ( empty($hover_color) ) {
                $hover_color = $this->settings->adjust_brightness( $main_color, 0.2 );
            }
            $rgb = $this->settings->hex_to_rgb( $main_color );
            $color_string = '
		<style>
/* Modern Designs for Gravity Forms Custom css for Single Form */
body .mdfgf-container.mdfgf-' . $rand_id . ' .gf_progressbar .gf_progressbar_percentage,
body .mdfgf-container.mdfgf-' . $rand_id . ' .ginput_container input[type="checkbox"]:checked:after,
body .mdfgf-container.mdfgf-' . $rand_id . ' .ginput_container input[type="radio"]:checked:after,
body .mdfgf-container.mdfgf-' . $rand_id . ' input[type="file"]:active:before,
body .mdfgf-container.mdfgf-' . $rand_id . ' input[type="file"]:before,
body .mdfgf-container.mdfgf-' . $rand_id . ' select[multiple="multiple"] option:checked,
body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-custom-select.multiple button.active:before,
body .mdfgf-container.mdfgf-' . $rand_id . ' .gf_page_steps .gf_step_active .gf_step_number,
body .mdfgf-container.mdfgf-' . $rand_id . ' .gf_page_steps .gf_step_completed .gf_step_number,
body .mdfgf-container.mdfgf-' . $rand_id . ' .gf_page_steps .gf_step_completed + .mdfgf-step-spacer,
body .mdfgf-container.mdfgf-' . $rand_id . ' form.mdfgf-md .mdfgf-input[type="checkbox"]:checked {
	background-color: ' . $main_color . ' !important;
	border-color: ' . $main_color . ';
	color: #eee !important;
}
.mdfgf-' . $rand_id . ' .mdfgf-prev-submitted .mdfgf-prev input,
.mdfgf-' . $rand_id . ' .mdfgf-submitted:not(.mdfgf-prev-submitted) .mdfgf-next input,
.mdfgf-' . $rand_id . ' .mdfgf-submitted:not(.mdfgf-prev-submitted) .mdfgf-submit input {
	color: transparent !important;
}
body.mdfgf-use-custom-datepicker .ui-datepicker .ui-datepicker-calendar td a:hover {
	background-color: rgba(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ',.2);
}
body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-checkbox-switch .ginput_container input.mdfgf-input[type="checkbox"]:checked:after,
body.mdfgf-use-custom-datepicker .ui-datepicker .ui-datepicker-calendar td a.ui-state-active,
body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-field.has-focus .mdfgf-tooltip {
	background-color: ' . $main_color . ' !important;
}
.mdfgf-' . $rand_id . ' .mdfgf-md .mdfgf-field.has-focus .mdfgf-label {
	color: ' . $main_color . ';
}
body .mdfgf-container.mdfgf-' . $rand_id . ' .gform_wrapper form .mdfgf-checkbox-switch .mdfgf-input[type="checkbox"]:checked {
	background-color: rgba(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ',.4) !important;
}

body .mdfgf-container.mdfgf-' . $rand_id . ' .gform_wrapper .mdfgf-bootstrap .mdfgf-checkbox-switch input[type="checkbox"]:checked {
	background-color: ' . $main_color . ' !important;
}

body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-radio:checked,
body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-radio:checked:hover,
body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-radio:checked:focus,
body .mdfgf-container.mdfgf-' . $rand_id . ' .mdfgf-checkbox:checked {
	box-shadow: inset 0 0 0 1px ' . $main_color . ' !important;
}

body .mdfgf-container.mdfgf-' . $rand_id . ' form.mdfgf-md .mdfgf-radio:checked,
body .mdfgf-container.mdfgf-' . $rand_id . ' form.mdfgf-md .mdfgf-radio:checked:hover,
body .mdfgf-container.mdfgf-' . $rand_id . ' form.mdfgf-md .mdfgf-radio:checked:focus,
body .mdfgf-container.mdfgf-' . $rand_id . ' form.mdfgf-md .mdfgf-checkbox:checked {
	box-shadow: inset 0 0 0 2px ' . $main_color . ' !important;
}

body .mdfgf-' . $rand_id . ' .mdfgf-field.has-focus .mdfgf-textarea,
body .mdfgf-' . $rand_id . ' .mdfgf-input:focus,
body .mdfgf-' . $rand_id . ' .mdfgf-field.has-focus .mdfgf-input,
body .mdfgf-' . $rand_id . ' .gform_confirmation_wrapper,
body .mdfgf-' . $rand_id . ' .mdfgf-md .mdfgf-field.has-focus .mdfgf-field-input:after,
body .mdfgf-container.mdfgf-md-outlined.mdfgf-' . $rand_id . ' .mdfgf-field.has-focus .mdfgf-fieldset .mdfgf-fieldblock:before,
body .mdfgf-' . $rand_id . ' .mdfgf-field.has-focus .mdfgf-fieldset .mdfgf-fieldblock {
	border-color: ' . (( 'mdfgf-bootstrap' === $settings['design'] ? $this->settings->adjust_brightness( $main_color, 0.5 ) : $main_color )) . ' !important;
}
body .mdfgf-' . $rand_id . ' form .field_sublabel_above .ginput_complex .mdfgf-fieldset .mdfgf-fieldblock:nth-child(2).mdfgf-remove-border, 
body .mdfgf-' . $rand_id . ' form .ginput_container:not(.ginput_complex) .mdfgf-fieldset .mdfgf-fieldblock:nth-child(2).mdfgf-remove-border {
	border-top-color: transparent !important;
}
body .mdfgf-' . $rand_id . ' form .field_sublabel_below .ginput_complex .mdfgf-fieldset .mdfgf-fieldblock:nth-child(2).mdfgf-remove-border {
	border-bottom-color: transparent !important;
}';
            $color_string .= '
body .mdfgf-container.mdfgf-' . $rand_id . ' .button,
body .mdfgf-container.mdfgf-' . $rand_id . ' input.button,
body .mdfgf-container.mdfgf-' . $rand_id . ' .button:active {
	background-color: ' . $main_color . ';
	border-color: ' . $main_color . ';
	color: #eee !important;
}
body .mdfgf-container.mdfgf-' . $rand_id . ' .button:hover,
body .mdfgf-container.mdfgf-' . $rand_id . ' input[type="file"]:hover:before,
body .mdfgf-container.mdfgf-' . $rand_id . ' .button:focus,
body .mdfgf-container.mdfgf-' . $rand_id . ' input[type="file"]:focus:before {
	background-color: ' . $hover_color . ';
}';
            $color_string .= '
</style>';
        }
        
        return str_replace( array(
            "\n",
            "\r",
            "\t",
            ' {',
            '{ ',
            '  ',
            ''
        ), array(
            '',
            '',
            ' ',
            '{',
            '{',
            ' '
        ), $color_string ) . '<div class="' . implode( ' ', $classes ) . '">' . $string . '</div>';
    }

}