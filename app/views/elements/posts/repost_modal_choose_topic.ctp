<div id="dialog-repost-chosse-topic" title="<?php __('Choose you topic'); ?>" style="display:none;">
	<div id="dialog-repost-chosse-topic-content"></div>
</div>


<script type="text/javascript">
$(document).ready(function() {

    $( "#dialog-repost-chosse-topic" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false,
        buttons: {
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }

    });

	$(".repost").click(function(e){
        e.preventDefault();
        alert('jo');

        var post_id = $(this).attr('id');
        var req = $.post(base_url + '/topics/getTopics.json', {post_id: post_id})
           .success(function( string ){
                if(string.status == 'success'){
                    $( '#dialog-repost-chosse-topic-content').html(string.data);
                }
                else{
                    alert(string.status);
                }
           })
           .error(function(){
               alert('error');
        });


        $( "#dialog-repost-chosse-topic").dialog('open');



        //stop browser of performing default action
        return false;
	});

});
</script>