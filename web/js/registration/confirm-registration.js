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
                    $('.input-field').prop('disabled', true);
                    $('#registration-btn').prop('disabled', true);
                } else {
                    $.each(data.errors, function (key, value) {
                        $('#error-' + key).text(value[0]).show();
                    });
                }
            }
        });
    });
});


