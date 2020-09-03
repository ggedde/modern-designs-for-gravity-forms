// binding to the load field settings event to initialize the checkbox
jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
    if (!jQuery('#field_size option[value="tiny"]').length) {
        jQuery('#field_size').prepend('<option value="tiny">Tiny (1/4 Column)</option>');
    }
    jQuery('#field_size option[value="small"]').html('Small (1/3 Column)');
    jQuery('#field_size option[value="medium"]').html('Medium (1/2 Column)');
    jQuery('#field_size option[value="large"]').html('Large (Full Width)');

    if (column_fields.includes(field.type)) {
        jQuery('.size_setting.field_setting').show();
    }
    if (complex_fields.includes(field.type) && field.type !== 'date' && field.type !== 'time') {
        jQuery('#field_inputsize').val((typeof field.inputsize !== 'undefined' && field.inputsize ? field.inputsize : 'medium'));
        jQuery('.size_input_setting.field_setting').show();
    } else {
        jQuery('.size_input_setting.field_setting').hide();
    }
});