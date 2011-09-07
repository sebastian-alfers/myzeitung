<form enctype="multipart/form-data" id="PostAddForm" method="post" action="/test/invite" accept-charset="utf-8">


    <ul id="invite-fields">
        <li><input name="data[Post][email][]" type="text"/><span>asdf</span></li>
        <li><input name="data[Post][email][]" type="text"/><span>ff</span></li>

    </ul>



    <input type="submit" value="submit" style="display: none;" />
    <a href="#" class="btn" onclick="validateAndSubmit()"><span>+</span>Beschwerde absenden</a>
    </form>


<script type="text/javascript">
function validateAndSubmit(){
    if(isValidEmails()){
        $('PostAddForm').submit();
    }
    else{
        alert('no not');
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

        if(email == ''){
            $(element).next("span").html('');
            $(element).remove();
            return;
        }

        var req = $.post(base_url + '/ajax/validateEmail.json', {email: email})
           .success(function( string ){
                if(string.status == 'success'){
                    console.log(string);
                    $(element).next("span").html('success');
                }
                else{

                    $(element).next("span").html(string.data.msg);
                }
           })
           .error(function(){
               alert('error');
        });

    });

});
</script>