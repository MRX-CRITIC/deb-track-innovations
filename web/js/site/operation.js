$(document).ready(function(){
    $('.operation').hover(
        function(){
            $(this).find('.delete-link').css('display', 'inline');
        },
        function(){
            $(this).find('.delete-link').css('display', 'none');
        }
    );
});

$(document).ready(function() {
    $('.reset-btn').on('click', function() {
        $('#filter-form')[0].reset();
        window.location.href = '/site/operations';
    });
});

$(document).ready(function() {
    const $input = $('#idDateInput');

    $input.on('blur', function() {
        $(this).closest('form').submit();
    });

    $input.on('keydown', function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            $(this).closest('form').submit();
        }
    });
});
