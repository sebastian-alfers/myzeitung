var pageType = 'user';
$(document).ready(function() {
    $('.delete-profile-picture').show();
    $('#fileupload').fileupload({maxNumberOfFiles: 1});

    $('.delete-rss').click(function(e){
        e.preventDefault();

        var url = $(this).parent().parent().find('.rss-url').attr('href');

        $('#UserFeedId').val($(this).attr('id'));

        $( "#delete-feed-topic" ).dialog('open');
    });



    $('#submit-delete-form').click(function(e){
        e.preventDefault();
        $('#UserAccRssImportForm').submit();
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
