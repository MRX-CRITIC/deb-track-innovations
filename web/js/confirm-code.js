$(document).ready(function () {
    $('#verify-code-btn').click(function (e) {
        e.preventDefault();

        const confirmationCode = $('#verification-code').val();
        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            type: "POST",
            url: '/user/confirm-registration',
            data: {
                confirmationCode: confirmationCode,
                email: email,
                password: password,
            },
            success: function (response) {
                if (response.confirmationCode) {
                    window.location.href = '/site/index';
                } else {
                    $('#error-message-code').text('Неверный код подтверждения').show();
                }
            }
        });
    });
});
