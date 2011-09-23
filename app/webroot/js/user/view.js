$(document).ready(function(){

    $('#topics-content .topic').bind('mouseenter', function(){
        $(this).find('.edit-icon').css('visibility', 'visible');
    });
    $('#topics-content .topic').bind('mouseleave', function(){
        $(this).find('.edit-icon').css('visibility', 'hidden');
    });
    var view = '';
    $('.edit-icon').bind('click', function(e){
        if(view != ''){
            $('#dialog-topic').html(view);
        }

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

        $('.deletebox').live('click', function(event){
            event.preventDefault();
            //load conform and review dialog
            var req = $.post(base_url + '/topics/delete/'+topic_id+'.json')
               .success(function( response ){

                   if(response.status == 'failure'){
                       $( "#dialog-topic" ).dialog('close');
                   }
                   else if(response.status == 'success'){
                       //save old view
                       view = $('#dialog-topic').html();
                       $('#dialog-topic').html(response.view);
                   }
               })
               .error(function(){
                   alert('request error');
            });
            //goTo(base_url+'/topics/delete/'+topic_id);
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
        height:300,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false,
        beforeClose: function(event, ui) {
            //reload if associations have been deleted
            window.location.reload();
        }
    });


});


$(document).ready(function() {
	$('#show-subscribers').click(function(element){
        element.preventDefault();

		$('#content-activity').html('');
		$( "#dialog-activity" ).dialog('open');
		var url = $(this).attr('rel');

		var req = $.post(base_url + "/"+ url)
   		.success(function( string ){
	   		$('#content-activity').html(string);
   		})
   		.error(function(){
	   		//alert('error');
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
