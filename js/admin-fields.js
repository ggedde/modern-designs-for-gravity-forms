function mdfgfUpdateSettingFields() {
    var globals = jQuery('#mdfgf_override_globals');
    if (!globals.length || globals.is(':checked')) {
        jQuery('.mdfgf-override-options').show();
        var design = jQuery('#mdfgf_design').val(); 

        if (!design || design === 'mdfgf-gf') {
            jQuery('.mdfgf-theme-options').hide();
        } else {
            jQuery('.mdfgf-theme-options').show();

            if (design && design === 'mdfgf-md') {
                jQuery('.mdfgf-md-options').show();
                jQuery('.mdfgf-not-md-options').hide();
                if (!['md-regular', 'md-filled', 'md-outlined'].includes(jQuery('#mdfgf_field_appearance').val())) {
                    jQuery('#mdfgf_field_appearance').val('md-regular');
                }
                if (!['mdfgf-theme-default', 'mdfgf-theme-dark'].includes(jQuery('#mdfgf_theme').val())) {
                    jQuery('#mdfgf_theme').val('mdfgf-theme-default');
                }
            } else {
                jQuery('.mdfgf-md-options').hide();
                jQuery('.mdfgf-not-md-options').show();

                if (!['', 'no-backgrounds', 'no-borders'].includes(jQuery('#mdfgf_field_appearance').val())) {
                    jQuery('#mdfgf_field_appearance').val('').change();
                }
            }
        }
        if (!design) {
            jQuery('.mdfgf-none-options').show();
        } else {
            jQuery('.mdfgf-none-options').hide();
        }
    } else {
        jQuery('.mdfgf-override-options').hide();
    }
    jQuery('#mdfgf-admin-settings-form').show();
}
jQuery('#mdfgf_design').on('change', function(){
    mdfgfUpdateSettingFields();
});
jQuery('#mdfgf_override_globals').on('click', function(){
    mdfgfUpdateSettingFields();
});
mdfgfUpdateSettingFields();