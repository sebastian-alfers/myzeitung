var pageType = 'user';
$(document).ready(function() {
    $('.delete-profile-picture').show();
    $('#fileupload').fileupload({maxNumberOfFiles: 1});

    $('.delete-rss').click(function(e){
        e.preventDefault();

        var url = $(this).parent().parent().find('.rss-url').attr('href');

        $('#UserFeedId').val($(this).attr('id'));

        $( "#delete-feed" ).dialog('open');
    });

    $('#submit-add-form').click(function(e){
        e.preventDefault();

        var url = $('#UserFeedUrl').val();

        //validate url
        if(isValidUrl(url)){
            $('#UserAccRssImportForm').submit();
        }


    });



    $('#submit-delete-form').click(function(e){
        e.preventDefault();
        $('#UserAccRssImportForm').submit();
    });

    $('#ImportNewRss').click(function(e){
        e.preventDefault();
        $( "#add-feed" ).dialog('open');
    });



});

$( "#add-feed" ).dialog({
    resizable: false,
    height:400,
    width:600,
    draggable:false,
    modal: true,
    autoOpen: false
});//end button .dialog

$( "#delete-feed" ).dialog({
    resizable: false,
    height:400,
    width:600,
    draggable:false,
    modal: true,
    autoOpen: false
});//end button .dialog
