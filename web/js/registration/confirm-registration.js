$(document).ready(function () {
    $('#registration-btn').click(function (e) {
        e.preventDefault();
        $('.error-message').hide().text('');

        $.ajax({
            type: "POST",
            url: '/user/registration',
            data: $('#registration-form').serialize(),

            success: function (data) {
                if (data.validation) {
                    $('#overlay-modal').css('display', 'flex');
                    $('#verification-code-1').focus();
                    // $('.input-field').prop('disabled', true);
                    // $('.input-field').addClass('disabled-style');
                    $('#registration-btn').prop('disabled', true);
                    $('#registration-form').serialize();
                    $('#code-inputs .code-input').val('');
                } else {
                    $.each(data.errors, function (key, value) {
                        $('#error-' + key).text(value[1]).show();
                    });
                }
            }
        });
    });
});