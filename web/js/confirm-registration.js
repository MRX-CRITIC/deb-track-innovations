$(document).ready(function () {
    $('#buttonReg').click(function (e) {
        e.preventDefault();

        $('.error-message').hide().text('');

        $.ajax({
            type: "POST",
            url: '/user/registration',
            data: $('#registration-form').serialize(),
            success: function (data) {
                if (data.validation) {
                    $('#overlay-modal').css('display', 'flex');
                } else {
                    const errorMessages = {
                        email: "Некорректный формат email.",
                        password: "Пароль должен быть не менее 8 символов.",
                        repeatPassword: "Пароли не совпадают."
                    };

                    $.each(data.errors, function(key, value) {
                        if (errorMessages[key]) {
                            $('#error-' + key).text(errorMessages[key]).show();
                        } else {
                            $('#error-' + key).text(value[0]).show();
                        }
                    });
                }
            }
        });
    });
});

