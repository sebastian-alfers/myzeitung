$(document).ready(function() {
    $('.mzslides').click(function(){
        $('#modal-slides-content').html('<iframe src="'+base_url+'/deck.js/introduction/" height="600" width="920"></iframe>');
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