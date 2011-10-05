$(document).ready(
        function(){
            $('.message').click(function(){
                var id = $(this).attr('id');

                $('#conv'+id).trigger('click');
            });
        }
);