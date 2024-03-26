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

        $(this).prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">' +
            '</span>'
        );

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
                        $('#' + key).css('border', '1px solid red');
                    });

                    setTimeout(function() {
                        $('.error-message').fadeOut();
                        $('.input-field').css('border', '1px solid #3385ff');
                    }, 3000);
                }
            },
            complete: function() {
                // Снимаем блокировку с кнопки и удаляем спиннер после завершения запроса
                $('#registration-btn').prop('disabled', false).html('Зарегистрироваться');
            }
        });
    });
});