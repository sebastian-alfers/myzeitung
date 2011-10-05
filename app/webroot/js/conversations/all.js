$(document).ready(
        function(){
            $('.message').click(function(){
                var id = $(this).attr('id');
                goTo($('#conv'+id).attr('href'));
            });
        }
);