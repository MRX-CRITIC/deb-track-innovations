$('#bank-select').change(function () {
    const selected = $(this).val();
    if (selected === 'new') {
        window.location.href = "/product/add-bank";
    }
});


$(document).ready(function () {
    const input = $('#credit-limit');

    function formatInput(value) {
        return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    input.on('input', function () {
        $(this).val(formatInput($(this).val()));
    });
    if (input.val()) {
        input.val(formatInput(input.val()));
    }
});


$(document).ready(function () {
    const input = $('#interest-free-period');

    function formatInput(value) {
        value = value.replace(' дней', '').trim();
        return value.replace(/\D/g, '') + ' дней';
    }

    function setCursorBeforeSymbol(inputElement) {
        const valueWithoutFormat = inputElement.value.replace(/\s+/g, '').replace(' дней', '');
        const newPosition = valueWithoutFormat.length - 4;
        inputElement.setSelectionRange(newPosition, newPosition);
    }

    input.on('input', function () {
        const originalValue = $(this).val();
        const formattedValue = formatInput(originalValue);
        $(this).val(formattedValue);

        setCursorBeforeSymbol(this);
    });

    if (input.val()) {
        input.val(formatInput(input.val()));
    }
});


$(document).ready(function () {
    if ($("#model-payment-partial-repayment").find('input[type="radio"]:checked').val() === "1") {
        $(".conditional-fields-payment").show();
    } else {
        $(".conditional-fields-payment").hide();
    }
});
$("#model-payment-partial-repayment input[type='radio']").change(function () {
    if ($(this).val() === "1") {
        $(".conditional-fields-payment").show();
    } else {
        $(".conditional-fields-payment").hide();
    }
});


$(document).ready(function () {
    if ($("#model-payment-date-purchase-partial-repayment").find('input[type="radio"]:checked').val() === "0") {
        $(".conditional-fields-terms-payment").show();
    } else {
        $(".conditional-fields-terms-payment").hide();
    }
});
$("#model-payment-date-purchase-partial-repayment input[type='radio']").change(function () {
    if ($(this).val() === "0") {
        $(".conditional-fields-terms-payment").show();
    } else {
        $(".conditional-fields-terms-payment").hide();
    }
});


$(document).ready(function () {
    if ($("#model-refund-cash-calculation").find('input[type="radio"]:checked').val() === "1") {
        $(".billing-period").show();
    } else {
        $(".billing-period").hide();
    }
});
$("#model-refund-cash-calculation input[type='radio']").change(function () {
    if ($(this).val() === "1") {
        $(".billing-period").show();
    } else {
        $(".billing-period").hide();
    }
});
