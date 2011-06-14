<script type="text/javascript">

var reply = false;

function ajxSubmitComment(){
	var parent_post_id = 0;
	var text = '';
	var validation_error = '';
	
	if(reply){
		//get reply value to validate
		validation_error = '<?php __('Please add reply text or press cancel'); ?>';
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
		validation_error = '<?php __('Please add comment text'); ?>';	
	}
	
	if(text == ''){
		alert('please fill the field');
		return false;
	}
	var req = $.post('<?php echo FULL_BASE_URL.'/comments/ajxAdd'?>', {text:text, post_id:<?php echo $post_id; ?>, parent_id:parent_post_id})
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
	$('#add_comment').live('click', function(){ajxSubmitComment();});


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
	
		
});



	
</script>

<?php echo $this->element('comments/form'); ?>