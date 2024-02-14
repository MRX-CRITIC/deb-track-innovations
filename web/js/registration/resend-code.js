$(document).ready(function() {
    let counter = 60;
    const countdownElement = $('<span id="countdown-timer"></span>');
    const resendButton = $('#resend-code-btn');
    resendButton.before(countdownElement);
    let timerInterval = setInterval(countdown, 1000);

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
                    counter = 60;
                    countdownElement.css('display', 'inline');
                    resendButton.css('display', 'none');
                    clearInterval(timerInterval);
                    timerInterval = setInterval(countdown, 1000);
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
