$(document).ready(function () {
    let timerInterval;

    $('#registration-btn').click(function () {
        clearInterval(timerInterval);

        let counter = 60;
        let countdownElement = $('#countdown-timer');
        const resendButton = $('#resend-code-btn');
        if(countdownElement.length === 0) {
            countdownElement = $('<span id="countdown-timer"></span>');
            resendButton.before(countdownElement);
        }

        resendButton.css('display', 'none');
        countdownElement.css('display', 'inline');

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

        timerInterval = setInterval(countdown, 1000);

        function resendCode() {
            $('.error-message').hide().text('');

            if (counter === 0) {
                $.ajax({
                    type: "POST",
                    url: '/user/registration',
                    data: $('#registration-form').serialize(),
                    success: function (data) {
                        if (!data.validation) {
                            $.each(data.errors, function (key, value) {
                                $('#error-' + key).text(value[0]).show();
                            });
                        }
                    }
                });
                $('#verification-code-1').focus();
                counter = 60;
                countdownElement.css('display', 'inline');
                resendButton.css('display', 'none');
                clearInterval(timerInterval);
                timerInterval = setInterval(countdown, 1000);
                $('#code-inputs .code-input').val('');

            } else if (counter < 60 && counter > 0) {
                $('#verification-code-1').focus();
                countdownElement.css('display', 'inline');
                resendButton.css('display', 'none');
            }
        }
        resendButton.off('click').on('click', function (e) {
            e.preventDefault();
            resendCode();
        });
    });
});
