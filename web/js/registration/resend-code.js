$(document).ready(function() {
    $('#registration-btn').click(function () {
        let timerInterval = setInterval(countdown, 1000)

    let counter = 6;
    const countdownElement = $('<span id="countdown-timer"></span>');
    const resendButton = $('#resend-code-btn');
    resendButton.before(countdownElement);


    function countdown() {
        if (counter === 0) {
            resendButton.css('display', 'inline');
            countdownElement.css('display', 'none');
            clearInterval(timerInterval);
        } else {
            countdownElement.text(`Запросить код повторно ${counter} сек.`);
            counter--;
        }
    }

    function resendCode() {
        $('.error-message').hide().text('');

        $.ajax({
            type: "POST",
            url: '/user/registration',
            data: $('#registration-form').serialize(),
            success: function (data) {
                if (data.validation) {
                    $('#overlay-modal').css('display', 'flex');
                    $('#verification-code-1').focus();
                    counter = 6;
                    countdownElement.css('display', 'inline');
                    resendButton.css('display', 'none');
                    clearInterval(timerInterval);
                    timerInterval = setInterval(countdown, 1000);
                    $('#code-inputs .code-input').val('');
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#error-' + key).text(value[0]).show();
                    });
                }
            }
        });
    }

    resendButton.click(function(e) {
        e.preventDefault();
        resendCode();
        });
    });
});
