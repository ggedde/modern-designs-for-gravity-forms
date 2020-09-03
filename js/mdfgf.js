/**!
 * Modern Designs for Gravity Forms
 */
if(typeof window.jQuery !== 'undefined') {
    
    jQuery(function($){
        function mdfgfRenderForms(){
            
            $('.mdfgf-submitted').removeClass('mdfgf-submitted');

            $('.mdfgf-render').each(function(){
                var form = $(this);

                form.find('.mdfgf-input').off('change.mdfgf').on('change.mdfgf', function(){
                    if ($(this).is(':invalid')) {
                        $(this).closest('.mdfgf-field').addClass('invalid');
                    } else {
                        $(this).closest('.mdfgf-field').removeClass('invalid');
                    }
                });

                if (form.find('.gf_page_steps .gf_step').length) {
                    form.find('.gf_page_steps .gf_step').after('<div class="mdfgf-step-spacer"></div>');
                    var steps = form.find('.gf_step').length;
                    form.find('.mdfgf-step-spacer').css('width', 'calc('+(100 / (steps - 1))+'% - '+(42 * (steps - (steps > 2 ? 1 : 0)))+'px)');
                }

                form.find('select').each(function(){
                    $(this).wrap('<div class="mdfgf-select'+($(this).prop('multiple') ? ' multiple' : '')+'"></div>');
                });

                form.find('.gform_button, .gform_next_button, .gform_previous_button').each(function(){
                    $(this).wrap('<span class="mdfgf-button'+($(this).hasClass('gform_next_button') ? ' mdfgf-next' : ($(this).hasClass('gform_previous_button') ? ' mdfgf-prev' : ($(this).hasClass('gform_button') ? ' mdfgf-submit' : '')))+'"></span>');
                });

                form.off('submit.mdfgf').on('submit.mdfgf', function(){
                    $(this).addClass('mdfgf-submitted');
                });

                form.find('.gform_page:visible .mdfgf-prev').on('click.mdfgf').on('click.mdfgf', function(e){
                    $(this).closest('form').addClass('mdfgf-prev-submitted');
                });

                form.find('.gfield_error .mdfgf-input').on('change.mdfgf').on('change.mdfgf', function(){
                    $(this).closest('.gfield_error').removeClass('gfield_error');
                });

                $(this).removeClass('mdfgf-render').addClass('mdfgf-rendered');
            });
        } 

        $(document).on('gform_post_render', function(event, form_id, current_page){
            mdfgfRenderForms();
            mdfgfUpdateUploadPreviews();
        });

        mdfgfRenderForms();
        mdfgfUpdateUploadPreviews();

        if (typeof gform !== 'undefined') {
            gform.addFilter( 'gform_file_upload_markup', function( html, file, up, strings, imagesUrl ) {
                html = html.split('\'gformDeleteUploadedFile(').join('\'mdfgfUpdateUploadPreviews();gformDeleteUploadedFile(')
                mdfgfUpdateUploadPreviews();
                return html;
            });
        }
    });

    function mdfgfUpdateUploadPreviews() {
        setTimeout(function(){
            jQuery('.mdfgf-multifile [id^="gform_preview"]').each(function(){
                if (jQuery(this).find('.ginput_preview').length) {
                    jQuery(this).fadeIn(200);
                } else {
                    jQuery(this).fadeOut(200);
                }
            });
        }, 100);
    }
}