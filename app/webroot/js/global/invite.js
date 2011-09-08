function validateAndSubmitInvitation(){
    if(isValidEmails()){
        $('PostAddForm').submit();
    }
    else{
        alert('Please enter a valid email');
    }
}

function isValidEmails(){
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
            $('#invite-fields').append('<li><input name="data[Post][email][]" type="text" /><span></span></li>');
        }

    });

    $('#invite-fields input').live('blur', function(){
        var element = this;
        var email = $(element).val();

        if(email == '' && $('#invite-fields input').length > 1){
            $(element).next("span").html('');
            $(element).remove();
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
               alert('error');
        });

    });

    $('.invitation-btn').click(function(e){
        e.preventDefault();
		$( "#dialog-invitation" ).dialog('open');
	});

	$( "#dialog-invitation" ).dialog({
        resizable: false,
        height:440,
        width:740,
        left:358,
        draggable:false,
        modal: true,
        autoOpen: false
    });


});
