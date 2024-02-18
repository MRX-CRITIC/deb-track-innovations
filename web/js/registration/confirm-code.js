
function timeShowErrors() {
    setTimeout(function() {
        $('#error-message-code').fadeOut();
    }, 3000);
}

$(document).ready(function () {
    $('#verify-code-btn').click(function (e) {
        e.preventDefault();

        let confirmationCode = '';
        $('.code-input').each(function () {
            confirmationCode += $(this).val();
        });

        const email = $('#email').val();
        const password = $('#password').val();
        const repeatPassword = $('#repeatPassword').val();

        $.ajax({
            type: "POST",
            url: '/user/confirm-registration',
            data: {
                confirmationCode: confirmationCode,
                email: email,
                password: password,
                repeatPassword: repeatPassword,
            },
            success: function (response) {
                if (response.confirmationCode && response.validation) {
                    window.location.href = '/site/index';

                } else if (!response.confirmationCode && response.validation) {
                    $('#error-message-code').text('Неверный код подтверждения').show();
                    timeShowErrors();

                } else if (!(response.confirmationCode && response.validation) && (response.errors.confirmationCode.length < 0)) {
                    $('#error-message-code').text('Не пройдена валидация').show();
                    timeShowErrors();

                } else if (response.errors.confirmationCode.length > 0) {
                    $('#error-message-code').text(response.errors.confirmationCode[0]).show();
                    timeShowErrors();
                }
            }
        });
    });
});

function closeModalAndEnableButton() {
    $('#overlay-modal').css('display', 'none');
    $('#registration-btn').prop('disabled', false);
    $('.input-field').prop('disabled', false);
}



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

            } else if (event.key === 'Enter') {
                $('#verify-code-btn').click();
            } else {
                if (event.key < '0' || event.key > '9') {
                    event.preventDefault();
                }
            }
        });

        $(document).on('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModalAndEnableButton();
            }
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#code-modal').length) {
                closeModalAndEnableButton();
            }
        });

        $('.code-input').on('focus', function() {
            $(this).val('');
        });

        $(this).on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length >= this.maxLength) {
                if (index < codeInputs.length - 1) {
                    codeInputs.get(index + 1).focus();
                } else {
                    $('#verify-code-btn').focus();
                }
            }
        });
    });
});


