$(document).ready(function () {
    $('#verify-code-btn').click(function (e) {
        e.preventDefault();

        const verificationCode = $('#verification-code').val();
        const mail = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            type: "POST",
            url: '/user/confirm-registration',
            data: {
                code: verificationCode,
                mail: mail,
                password: password,
            },
            success: function (response) {
                if (response.codeValid) {
                    alert('Регистрация успешно завершена!');
                    $('#overlay-modal').css('display', 'none');
                } else {
                    console.log(response)
                    alert('Неверный код! Попробуйте снова.');
                }
            }
        });
    });
});
