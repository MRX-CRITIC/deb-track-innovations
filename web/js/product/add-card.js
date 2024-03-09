$(document).ready(function() {
    $('#bank-select').change(function() {
        if ($(this).val() === 'add-bank') {
            window.location.href = '/product/add-bank';
            $('#bank-select').val('');
        }
    });
});

// $(document).ready(function () {
//     const input = $('#credit-limit');
//     console.log(input)
//     function formatInput(value) {
//         return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
//     }
//
//     function unFormatInput(value) {
//         // Убедитесь, что уже не добавлено ".00". Если добавлено, возвращаем без изменений.
//         if (/^\d+\.00$/.test(value)) {
//             return value;
//         }
//         let creditLimit = parseFloat(value.replace(/\s/g, ''));
//         return creditLimit.toFixed(2);
//     }
//
//
//     input.on('input', function () {
//         $(this).val(formatInput($(this).val()));
//     });
//
//     $('#add-card-form').submit(function (event) {
//         // Проверка, была ли форма уже валидирована и готова к отправке
//         if (!$(this).data('ready-to-submit')) {
//             event.preventDefault(); // предотвращаем отправку формы
//
//             // Переформатирование значения в input перед отправкой
//             const unFormatInputValue = unFormatInput(input.val());
//             input.val(unFormatInputValue);
//             console.log(unFormatInputValue)
//             $(this).data('ready-to-submit', true); // Устанавливаем флаг, что форма готова к отправке
//             $(this).submit(); // повторно отправляем форму
//         }
//     });
//
//     $('#btn-add-card').click(function () {
//         $('#add-card-form').submit(); // Убедитесь, что форма отправляется при клике на кнопку
//     });
// });




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
    const inputPercentage = $('#percentage_partial_repayment');

    function formatInputPercentage(value) {
        value = value.replace(' %', '').trim();
        return value.replace(/\D/g, '') + ' %';
    }

    function setCursorBeforeSymbolPercentage(inputElement) {
        const valueWithoutFormat = inputElement.value.replace(/\s+/g, '').replace(' %', '');
        const newPosition = valueWithoutFormat.length - 1;
        inputElement.setSelectionRange(newPosition, newPosition);
    }

    inputPercentage.on('input', function () {
        const originalValue = $(this).val();
        const formattedValue = formatInputPercentage(originalValue);
        $(this).val(formattedValue);

        setCursorBeforeSymbolPercentage(this);
    });

    if (inputPercentage.val()) {
        inputPercentage.val(formatInputPercentage(inputPercentage.val()));
    }
});

$(document).ready(function () {
    if ($("#model-payment-partial-repayment").find('input[type="radio"]:checked').val() === "1") {
        $(".conditional-fields-payment").show();
    } else {
        $(".conditional-fields-payment").hide();
        $("#percentage-partial-repayment").val('');
        $("#conditions-partial-repayment").val('');

    }
});
$("#model-payment-partial-repayment input[type='radio']").change(function () {
    if ($(this).val() === "1") {
        $(".conditional-fields-payment").show();
    } else {
        $(".conditional-fields-payment").hide();
        $("#percentage-partial-repayment").val('');
        $("#conditions-partial-repayment").val('');
    }
});


$(document).ready(function () {
    if ($("#model-payment-date-purchase-partial-repayment").find('input[type="radio"]:checked').val() === "0") {
        $(".conditional-fields-terms-payment").show();
    } else {
        $(".conditional-fields-terms-payment").hide();
        $("#conditions-partial-repayment").val('');
    }
});
$("#model-payment-date-purchase-partial-repayment input[type='radio']").change(function () {
    if ($(this).val() === "0") {
        $(".conditional-fields-terms-payment").show();
    } else {
        $(".conditional-fields-terms-payment").hide();
        $("#conditions-partial-repayment").val('');
    }
});


$(document).ready(function () {
    if ($("#model-refund-cash-calculation").find('input[type="radio"]:checked').val() === "1") {
        $(".billing-period").show();
    } else {
        $(".billing-period").hide();
        $("#start-date").val('');
        $("#end-date").val('');
    }
});
$("#model-refund-cash-calculation input[type='radio']").change(function () {
    if ($(this).val() === "1") {
        $(".billing-period").show();
    } else {
        $(".billing-period").hide();
        $("#start-date").val('');
        $("#end-date").val('');
    }
});



$(document).ready(function () {
    if ($("#model-service-period").find('input[type="radio"]:checked').val() === "1") {
        $(".date_annual_service").show();
    } else {
        $(".date_annual_service").hide();
        $("#date-annual-service").val('');
    }
});
$("#model-service-period input[type='radio']").change(function () {
    if ($(this).val() === "1") {
        $(".date_annual_service").show();
    } else {
        $(".date_annual_service").hide();
        $("#date-annual-service").val('');
    }
});




// $(document).ready(function(){
//     $('#filter-form select[name="OperationSearchForm[name_card]"]').change(function(){
//         $(this).closest('form').submit(); // Отправка формы при изменении значения в dropdown
//     });
// });

// $(document).on('submit', '#filter-form', function (event) {
//     $.pjax.submit(event, '#pjax-container-id');
// });



