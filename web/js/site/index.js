$(document).ready(function() {
    $('.card-info').on('click', function(e) {
        e.preventDefault();
        let cardId = $(this).data('card-id');

        $.ajax({
            url: '/product/card-info',
            type: 'GET',
            data: {
                card_id: cardId
            },
            success: function(data) {
                $('#modalCardInfo .modal-body').html(data);
            }
        });
    });
});

$(document).ready(function() {
    const myModal = $('#modalCardInfo').modal({
        backdrop: 'static', // Запрет закрытия модального окна при клике по фону
        // keyboard: false // Запрет закрытия модального окна при нажатии клавиши Esc
    });
    // Для открытия модального окна можно использовать следующий код:
    // myModal.modal('show');
});