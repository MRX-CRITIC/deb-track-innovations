function showModel() {
    $('#overlay-modal').css('display', 'flex');
    $('#verification-code-1').focus();
    $('.input-field').prop('disabled', true);
    $('#registration-btn').prop('disabled', true);
    $('#code-inputs .code-input').val('');
}


$(document).ready(function () {
    $('#registration-btn').click(function (e) {
        e.preventDefault();
        $('.error-message').hide().text('');
        // $('.input-field').css('background', 'transparent');
        // $('.input-field').css('color', 'white');

        $.ajax({
            type: "POST",
            url: '/user/registration',
            data: $('#registration-form').serialize(),
            success: function (data) {
                if (data.validation) {
                    showModel();
                } else if (data.time) {
                    $('#error-message-code').text(data.errors).show();
                    timeShowErrors();
                    showModel();
                } else {
                    $.each(data.errorsYii, function (key, value) {
                        $('#error-' + key).text(value[0]).show();
                        // $('#' + key).css('background', 'rgba(250,219,218,0.92)');
                        // $('#' + key).css('color', 'black');
                    });

                    timeShowErrors();
                }
            }
        });
    });
});