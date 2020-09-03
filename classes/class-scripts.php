<?php

/**
 * This file is to handle Enqueuing JS and CSS Scripts
 *
 * @package MDFGF
 */
namespace MDFGF;

/**
 * Class for Enqueuing JS and CSS Scripts
 */
class Scripts
{
    /**
     * Class Constructor
     *
     * @param object $plugin    contains plugin info.
     * @param object $settings  contains settings object.
     *
     * @return void
     */
    public function __construct( $plugin, $settings )
    {
        $this->plugin = $plugin;
        $this->settings = $settings;
    }
    
    /**
     * Class Constructor
     *
     * @param array $form contains the plugin version.
     *
     * @return void
     */
    public function register_scripts( $form )
    {
        $settings = $this->settings->get( $form['id'] );
        
        if ( empty($settings['design']) || 'mdfgf-gf' !== $settings['design'] ) {
            wp_deregister_style( 'gforms_reset_css' );
            wp_deregister_style( 'gforms_formsmain_css' );
            wp_deregister_style( 'gforms_ready_class_css' );
            wp_deregister_style( 'gforms_browsers_css' );
            wp_deregister_script( 'gform_placeholder' );
            
            if ( !empty($settings['design']) ) {
                
                if ( mdfgf_fs()->is_not_paying() ) {
                    wp_enqueue_script(
                        'mdfgf_js',
                        $this->plugin->url . '/js/mdfgf.js',
                        array( 'jquery' ),
                        $this->plugin->version,
                        true
                    );
                    wp_enqueue_style(
                        'mdfgf_css',
                        $this->plugin->url . '/css/mdfgf.min.css',
                        array(),
                        $this->plugin->version
                    );
                }
            
            } elseif ( !empty($settings['framework']) ) {
                if ( 'mdbpro' === $settings['framework'] ) {
                    wp_deregister_script( 'jquery-ui-datepicker' );
                }
                wp_enqueue_style(
                    'mdfgf_base_css',
                    $this->plugin->url . '/css/base.css',
                    array(),
                    $this->plugin->version
                );
                wp_enqueue_script(
                    'mdfgf_js',
                    $this->plugin->url . '/pro/js/mdfgf.min.js',
                    array( 'jquery' ),
                    $this->plugin->version,
                    true
                );
            }
        
        }
    
    }

}