(function ($) {
    $(document).ready(function () {

        $( ".competition-date-picker" ).datepicker({ dateFormat: 'dd-mm-yy' });

        $(document).on('click','input#se_submit',function () {

            var se_first_name = $('#se_first_name').val();
            var se_last_name = $('#se_last_name').val();
            var se_email = $('#se_email').val();
            var se_phone = $('#se_phone').val();
            var se_description = $('#se_description').val();
            var se_post_id = $('#se_post_id').val();
            var se_nonce = $('#se_nonce').val();

            var data = {
                'action': 'competition_submit_enquiry',
                'first_name':se_first_name,
                'last_name':se_last_name,
                'email':se_email,
                'phone':se_phone,
                'description':se_description,
                'post_id':se_post_id,
                '_nonce':se_nonce,
            }

            $.post(competition_scripts.ajax_url, data, function (response) {
                $('label.message').html('');
                $('label.message').html(response);
            });

        });
    });
})(jQuery);