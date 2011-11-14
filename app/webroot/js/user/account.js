var pageType = 'user';
$(document).ready(function() {
    $('.delete-profile-picture').show();
    $('#fileupload').fileupload({maxNumberOfFiles: 1});

    $('.delete-rss').click(function(e){
        e.preventDefault();

        var url = $(this).parent().parent().find('.rss-url').attr('href');
        alert($(this).attr('id'));
        $( "#delete-feed-topic" ).dialog('open');
    });

});


$( "#delete-feed-topic" ).dialog({
    resizable: false,
    height:400,
    width:600,
    draggable:false,
    modal: true,
    autoOpen: false
});//end button .dialog
