$(document).ready(function () {
    $('#verify-code-btn').click(function (e) {
        e.preventDefault();

        let confirmationCode = '';
        $('.code-input').each(function () {
            confirmationCode += $(this).val();
        });
        const email = $('#email').val();
        const password = $('#password').val();
        console.log(confirmationCode)
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

$(document).ready(function () {

    const codeInputs = $('#code-inputs .code-input');
    codeInputs.each(function (index) {
        $(this).on('keydown', function (event) {
            if (event.key === 'Backspace') {

                if (this.value.length === 0 && index > 0) {
                    event.preventDefault();
                    const prevInput = codeInputs.get(index - 1);
                    $(prevInput).focus();
                    prevInput.value = prevInput.value.slice(0, -1);
                }

            } else if (event.key === 'ArrowLeft' && index > 0) {

                if (this.selectionStart === 0) {
                    codeInputs.get(index - 1).focus();
                }

            } else if (event.key === 'ArrowRight' && index < codeInputs.length - 1) {

                if (this.selectionEnd === this.value.length) {
                    codeInputs.get(index + 1).focus();
                }

            } else {
                if (event.key < '0' || event.key > '9') {
                    event.preventDefault();
                }
            }
        });

        $(this).on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length >= this.maxLength) {
                if (index < codeInputs.length - 1) {
                    codeInputs.get(index + 1).focus();
                }
            }
        });
    });
});

