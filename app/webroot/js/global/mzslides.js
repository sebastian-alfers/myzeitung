$(document).ready(function() {
    $('.mzslides').click(function(){
        $('#modal-slides').dialog('open');
    });

    $( "#modal-slides" ).dialog({
        resizable: false,
        height:650,
        width:950,
        left:358,
        draggable:false,
        modal: true,
        autoOpen: false
    });

});