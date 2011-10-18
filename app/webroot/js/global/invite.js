function validateAndSubmitInvitation(){
    if(isValidEmails()){
        $('#InviteAddForm').submit();
    }
    else{
        alert('Please enter a valid email');
    }
}

function isValidEmails(){

    if($('#invite-fields input').length < 2) return false;

    var is_valid = true;
    $('#invite-fields input').each(function(){
        if($(this).val() != ''){
            if(!isEmailValid($(this).val())){
                is_valid = false;
            }
        }

    });
    return is_valid;
}

$(document).ready(function(){

    $('#invite-fields input').live('focus keyup', function(){
        if($(this).val() != '' && $('#invite-fields li:last-child input').val() != ''){
            $('#invite-fields').append('<li><input name="data[Invitation][email][]" type="text" class="textinput" /><span></span></li>');
            
            //hack for IE to make the div grow
            var height = $('#dialog-invitation .modal-content').height();
            alert('edit');
            $('#dialog-invitation .modal-content').css('height', height + 50 +'px');
        }

    });

    $('#invite-fields input').live('blur', function(){
        var element = this;
        var email = $(element).val();

        if(email == '' && $('#invite-fields input').length > 1){
            $(element).next("span").html('');
            $(element).remove();
            
            //hack for IE to make the div shrink
            var height = $('#dialog-invitation .modal-content').height();
            $('#dialog-invitation .modal-content').css('height', height - 50 +'px');            
            
            return;
        }

        var req = $.post(base_url + '/ajax/validateEmail.json', {email: email})
           .success(function( string ){
                if(string.status == 'success'){
                    //valid email
                    $(element).next("span").html('');
                }
                else{

                    $(element).next("span").html(string.data.msg);
                }
           })
           .error(function(){
               ////alert('error');
        });

    });

    $('.invitation-btn').click(function(e){
        e.preventDefault();
		$( "#dialog-invitation" ).dialog('open');
	});

	$( "#dialog-invitation" ).dialog({
        resizable: false,
        height:500,
        width:900,
        left:358,
        draggable:false,
        modal: true,
        autoOpen: false
    });


});
