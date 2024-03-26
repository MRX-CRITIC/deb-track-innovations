$(document).ready(function () {
    $('.login-button').click(function (e) {
        e.preventDefault();
        $('.error-message').hide().text('');

        $(this).prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">' +
            '</span>'
        );

        $.ajax({
            type: "POST",
            url: '/user/login',
            data: $('#login-form').serialize(),
            success: function (data) {
                if (data.validation) {
                    window.location.href = '/site/index';
                }else {
                    $.each(data.errors, function (key, value) {
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
                $('.login-button').prop('disabled', false).html('Авторизоваться');
            }
        });
    });
});