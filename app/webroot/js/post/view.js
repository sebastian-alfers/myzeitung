var reply = false;
function ajxSubmitComment(pid){
	var parent_post_id = 0;
	var text = '';
	var validation_error = '';

	if(reply){
		//get reply value to validate
		validation_error = 'Please enter your reply or press cancel';
		$('.reply_text').each(function(index) {
			if($(this).val() != ''){
				text = $(this).val();
			}

		    //alert(index + ': ' + $(this).val());
		});
	}
	else{
		//get post comment to validate
		text = $('#comment_text').val();
		validation_error = 'Please enter your comment';
	}

	if(text == ''){
		alert('please fill the field');
		return false;
	}
	var req = $.post(base_url +'/comments/ajxAdd', {text:text, post_id:pid, parent_id:parent_post_id})
	   .success(function( comment ){
		   $("#comment_list").prepend('<li class="comment" style="display:none" id="replywrapper">' +comment+ '</li>');
		   var options = {};
		   $("#replywrapper").toggle( 'blind', options, 500 );
		   $('#comment_text').val('');

		   var old_count = $('#comment_counter').attr('name');
		   new_count = parseInt(old_count) + 1;
		   $('#comment_counter').attr('name', new_count)
		   $('#comment_counter').html(new_count+'<span></span>');


			//$('#url_content').html(string);
	   })
	   .error(function(){
		   alert('error');
	});

	return false;
}


$(document).ready(function() {
	$('#add_comment').live('click', function(event, element){ajxSubmitComment($(this).attr('pid'));});


	//bind does not work...
	$(".reply").click(function(){
		//activate reply modus
		reply = true;

		//get form from ajax
		var reply_btn = this;

		var req = $.post('<?php echo DS.APP_DIR.'/comments/ajxGetForm/'?>', {level:'first'})
		   .success(function( form ){
			   	$(reply_btn).parent().append('<div class="vspacer"></div>');
				$(reply_btn).parent().append('<ul id="reply_first" style="display:none"><li>' +form+ '</li></ul>');

				//animate
				var options = {};
				$('#reply_first').toggle( 'blind', options, 500 );
		   })
		   .error(function(){
			   alert('error');
		   });

	 });


    //show / hide button
    $('.comment').live('hover', function(){
        var id = $(this).attr('id');
        $("#btn_comment_complain_"+id).toggle();
    });



});
