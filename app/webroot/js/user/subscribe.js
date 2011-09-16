$(document).ready(function() {


	$('.subscribe-user').live('click', function(e){
        e.preventDefault();
        $('#subscribe-box').hide();
        subscribeDialog(this);
    });


    function subscribeDialog(element){
        var user_subscribe_id = $(element).attr('id');
        loadForm(user_subscribe_id);

        return false;
    }

    function loadForm(user_subscribe_id){
        $('#dialog-subscribe-content').html("");
        var req = $.post(base_url + '/users/subscribe/'+user_subscribe_id+'.json')
           .success(function( response ){

               if(response.status == 'success'){
                   $('#dialog-subscribe-content').html(response.view);
                   $('#dialog-subscribe').dialog('open');
               }
               if(response.status == 'reload'){
                   location.reload();
               }
           })
           .error(function(){
               alert('error');
        });
    }

    $(function() {

        $( "#dialog:ui-dialog" ).dialog( "destroy" );
        $( "#dialog-subscribe" ).dialog({
            resizable: false,
            height:400,
            width:500,
            draggable:false,
            modal: true,
            autoOpen: false
        });//end button .dialog
    });
});

//observe the select box for papers in subscribe modal dialog

$('#UserPaperContentData').live('change', function() {
    $('.category-choose-content').hide();
    $('.choose-paper-image').hide();

    var id = $(this).val();
    $(" #choose-category-"+id).toggle("slow");
    $(" #choose-paper-image-"+id).toggle("slow");
});

