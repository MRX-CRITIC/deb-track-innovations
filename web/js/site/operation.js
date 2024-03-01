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