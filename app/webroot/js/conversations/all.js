$(document).ready(function(){
	$('#btm-new-conversation').click(function(e){
		e.preventDefault();
		validateAndSubmit();
	});        
        
});

function validateAndSubmit(){

	var title = $('#ConversationTitle').val();
	var msg = $('#ConversationMessage').val();	
    var req = $.post(base_url + '/ajax/validateNewMessage.json', {title: title , message: msg})
       .success(function( string ){
       		console.log(string);
            if(string.status == 'success'){
            	$('#ConversationAddForm').submit();
            }
            else{
				if(string.data.msg != ''){
					alert(string.data.msg);
				}
            }
       })
       .error(function(){
           //alert('error');
    });

}