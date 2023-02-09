
(function ($) {
    'use strict';

    $(document).on("click", ".nav-item .nav-link", function(e){
        e.preventDefault();

        $('.nav-item .nav-link').removeClass('active');
        $('.tab-pane').removeClass('active');
        jQuery(this).addClass('active');

        var data = $(this).data('id');
        $('.'+data).addClass('active');

        /*Add hash to browser address*/
        var url=window.location.href.split('#')[0];
        var to_url=url+"#"+data;
        window.location.href=to_url;

    });


    $(document).on("click", "#aiwa-save-settings", function (e) {
        e.preventDefault(); // prevent the form from being submitted
        var formData = $('#ai-settings-form').serialize(); // serialize the form data

        var datas = {
            'action': 'ai_writing_assistant_save_settings',
            'rc_nonce': aiwa.nonce,
            'formData': formData,
        };

        var select = $('[name="ai-image-size"]').val();
        var custom = $('[name="custom-ai-image-size"]').val();
        var regex = /^\d+x\d+$/; // Regular expression to check for "XxY" format

        if (select == 'custom' && !regex.test(custom)) { // If input does not match the regular expression
            alert("Input must be in the format like '100x100'"); //todo
            return false; // Prevent form submission
        }

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {
            },
            success: function (r) {
                if (r.success) {
                    $('#aiwa-save-settings').siblings('.badge').removeClass('aiwa-hidden');
                    setTimeout(function(){
                        $('#aiwa-save-settings').siblings('.badge').addClass('aiwa-hidden');
                    }, 5000);

                    if ($('#aiwa-placeholders-is-set').val()==="0"){
                        generate_placeholders();
                    }
                } else {
                    console.log('Something went wrong, please try again!'); //Todo

                }

            }, error: function () {
            }
        });

    });

    function generate_placeholders() {
        var datas = {
            'action': 'generate_placeholders',
            'rc_nonce': aiwa.nonce,
        };

        $.ajax({
            url: aiwa.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function () {

            },
            success: function (r) {
                if (r.success) {
                    console.log(r);

                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function () {

            }
        });
    }

    /*$(document).on("change", '[name="ai-image-size"]', function () {
        if ($(this).val() == 'custom'){
            $('[name="custom-ai-image-size"]').removeClass('aiwa-hidden');
        }else{
            $('[name="custom-ai-image-size"]').addClass('aiwa-hidden');
        }

        console.log($(this).val())
    });*/

})(jQuery);

