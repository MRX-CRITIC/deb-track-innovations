$(document).ready(function () {
    $('#buttonReg').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: '/user/registration',
            data: $('#registration-form').serialize(),
            success: function (data) {
                if (data.validation) {
                    $('#overlay-modal').css('display', 'flex');
                } else {
                    alert(data.errors.email);
                    alert(data.errors.password);
                    alert(data.errors.repeatPassword);
                }
            }
        });
    });
});

