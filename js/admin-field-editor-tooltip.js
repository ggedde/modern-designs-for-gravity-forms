// binding to the load field settings event to initialize the checkbox
jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
    if (!jQuery('#field_description_placement option[value="tooltip"]').length) {
        jQuery('#field_description_placement').append('<option value="use-tooltip" disabled>Tooltip (Pro Version)</option>');
    }
});