<?php

/**
 * File for including Freemius
 *
 * @package MDFGF
 */

if ( !function_exists( 'mdfgf_fs' ) ) {
    /**
     * Create a helper function for easy SDK access.
     */
    function mdfgf_fs()
    {
        global  $mdfgf_fs ;
        
        if ( !isset( $mdfgf_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/incfs/start.php';
            $mdfgf_fs = fs_dynamic_init( array(
                'id'             => '6728',
                'slug'           => 'modern-designs-for-gravity-forms',
                'premium_slug'   => 'modern-designs-for-gravity-forms-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_d738236e750bf624890bf4b717b40',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'first-path' => 'plugins.php',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $mdfgf_fs;
    }
    
    // Init Freemius.
    mdfgf_fs();
    // Signal that SDK was initiated.
    do_action( 'mdfgf_fs_loaded' );
}
