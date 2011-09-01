$(document).ready(function(){

    $('#topics-content .topic').bind('mouseenter', function(){
        $(this).find('.edit-icon').css('visibility', 'visible');
    });
    $('#topics-content .topic').bind('mouseleave', function(){
        $(this).find('.edit-icon').css('visibility', 'hidden');
    });

    $('.edit-icon').bind('click', function(e){
        $( "#dialog-topic" ).dialog('open');
        var topic_id = $(this).attr('topic-id');
        $('#TopicId').val(topic_id);
        var req = $.post(base_url + '/topics/view_topic_name/'+topic_id+'.json')
		   .success(function( response ){

               if(response.status == 'failure'){
                   $( "#dialog-topic" ).dialog('close');
               }
               else if(response.status == 'success'){
                   var topic_name = response.data.topic_name
                   $('#topic').val(topic_name);
               }
		   })
		   .error(function(){
			   alert('request error');
		});

        //bind the event do delte box
        $('#deletebox').unbind('click');//remove old clicks
        $('#deletebox').bind('click', function(event){
            event.preventDefault();
            //redir to controller
            goTo(base_url+'/topics/delete/'+topic_id);
        });

    });

    $('.new-conversation').bind('click', function(event){
        event.preventDefault();
        $( "#dialog-new-conversation" ).dialog('open');
    });

    $( "#dialog-new-conversation" ).dialog({
            resizable: false,
            height:400,
            width:500,
            draggable:false,
            modal: true,
            autoOpen: false
    });

	$( "#dialog-topic" ).dialog({
        resizable: false,
        height:200,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false
    });


});


$(document).ready(function() {
	$('#show-subscribers').click(function(element){
        element.preventDefault();

		$('#content-activity').html('');
		$( "#dialog-activity" ).dialog('open');
		var url = $(this).attr('title');

		var req = $.post(base_url + "/"+ url)
   		.success(function( string ){
	   		$('#content-activity').html(string);
   		})
   		.error(function(){
	   		alert('error');
		});



	});

	$( "#dialog-activity" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false
    });
});
