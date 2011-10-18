//validation = new AjaxRequest(base_url+'/ajax/validation/register');
$(document).ready(function() {
    $('.tt-title').tipsy({live:true, fade: false, opacity: 1, gravity: 'sw'});
    $('.tt-title-north').tipsy({live:true, fade: false, opacity: 1, gravity: 'n'});
});



function isEmailValid(email){

    var result = '';

    $.ajaxSetup({async:false});

    var req = $.post(base_url + '/ajax/validateEmail.json', {email: email})
       .success(function( string ){
            if(string.status == 'success'){
                result = true;
            }
            else{
                //alert(string.data.msg);
                result = false;
            }
       })
       .error(function(){
           ////alert('error');
    });

    return result;

}

function isValidUrl(url){
    var result = '';

    $.ajaxSetup({async:false});

    var req = $.post(base_url + '/ajax/validateUrl.json', {url: url})
       .success(function( json ){

            if(json.status == 'success'){
                result = true;
            }
            else{
                alert(json.data.msg);
                result = false;
            }
       })
       .error(function(){
           ////alert('error');
    });

    return result;
}

function scrollTo(trgt){
    //get the full url - like mysitecom/index.htm#home
    var full_url = this.href;

    //get the top offset of the target anchor
    var target_offset = $(trgt).offset();
    var target_top = target_offset.top - 350;

    //goto that anchor by setting the body scroll top to anchor top
    $('html, body').animate({scrollTop:target_top}, 500);
}

$(document).ready(function() {
    $('.articlewrapper').click(function(e){
        goTo($(this).find('a:first').attr('href'));
    });


    var subscribe_box = $('#subscribe-box');
    var timeout = '';
    var mouse_over = '';

    //button to submit a form to a users profile to send msg
    $('.user-new-conversation').click(function(e){
        $(this).parent('form').submit();
    });

    $('.user-image').live('mouseenter', function(e){
        if($(this).hasClass('nosubscribe')) return;
        if(timeout != '') {
            clearTimeout(timeout);
            if( subscribe_box.is(':visible') ) {
                subscribe_box.hide();
            }
        }

        var subscribe_link = $(this).attr('rel');
        var subscribe_user_id = $(this).attr('id');
        var subscribe_user_name = $(this).attr('alt');
        var link = $(this).attr('link');

        var user_img = $(this);
        timeout = setTimeout(function() {
                showSubscribeDialog(user_img, e, subscribe_link, subscribe_user_id, subscribe_user_name, link);
                //timeout = '';
        }, 700);

    });


    $('.user-image').bind('mouseleave', function(e){
        clearTimeout(timeout);
    });


    subscribe_box.bind('mouseenter', function(e){
        //$('.user-image').unbind('mouseleave');
    });

    subscribe_box.bind('mouseleave', function(e){//do NOT user mouseout here!!!
        $(this).hide();
    });




    function showSubscribeDialog(element, event, subscribe_link, subscribe_user_id, subscribe_user_name, link){

        var eng_label = "Subscribe Author";
        var deu_label = "Autor abonnieren";
        if($(element).hasClass('me')){
            eng_label = "Subscribe me";
            deu_label = "Mich abonnieren";
        }
        var label = deu_label;
        if($('body').hasClass('eng')) label = eng_label;

        var box = subscribe_box;

        box.html('<a href="'+link+'">'+subscribe_user_name+'</a><br /><br /><a href="' + subscribe_link + '" class="btn subscribe-user" id="' + subscribe_user_id + '"><span>+</span>' + label + '</a>');

        box.css('top', mouse_top-5);
        box.css('left', mouse_left-5);

        box.toggle();

    }

    var mouse_top = '';
    var mouse_left = '';
    $(document).mousemove(function(e){
        mouse_top = e.pageY;
        mouse_left = e.pageX;
   });


    $(function(){
        $('form.jqtransform').jqTransform({imgPath:'jqtransformplugin/img/'});

    });

      $(".signin").click(function(e) {
          e.preventDefault();
          $("div#signin_menu").toggle();
          $(".signin").toggleClass("menu-open");
      });

      $("div#signin_menu").mouseup(function() {
          return false
      });
      $(document).mouseup(function(e) {
          if($(e.target).parent("a.signin").length==0) {
              $(".signin").removeClass("menu-open");
              $("div#signin_menu").hide();
          }
      });

});/* end onload ready*/


$(function() {
$('#forgot_username_link').tipsy({gravity: 'w'});
});



$(function() {
	function lookup(inputString) {
		if(inputString.length == 0) { // esc btn) {
			// Hide the suggestion box.
			$('#search-suggest').hide();
		} else {
			inputString = $.trim(inputString);
			$.post(base_url+"/search/ajxSearch/", {query: ""+inputString+"", home: home}, function(data){
				$('#search-suggest').show();
				$('#search-suggest').html(data);
			});
		}
	} // lookup

	$(document).bind('click', function(){
		if($('#search-suggest').is(":visible")){
			hideSuggestion();
		}

	});

	$('#inputString').focus(function(e){
		if($('#inputString').val() != 'Find') {
			lookup($('#inputString').val());
		}
	});

	$('#inputString').keyup(function(e){
		if (e.keyCode == 27) { // esc btn
			hideSuggestion('');
			$('#inputString').val('');
		}
		else{
			lookup($('#inputString').val());
		}
	});

	$(document).bind('keyup', function(e){
		  if (e.keyCode == 27) { // esc btn
			  hideSuggestion();
			  $('#inputString').val('');
		   }
		});

	function hideSuggestion(value){
		$('#search-suggest').hide();
		lookup('');
		$('#search-suggest').html('');
	}
});


$(document).ready(function() {
    //auto suggest
    $('.autoresult').live('click', function(e){
        goTo($(this).find('h6 a').attr('href'));
    });
    //normal serach
    $('.search-result li').live('click', function(){
        goTo($(this).find('h3 a').attr('href'));
    });


    $('.complain-btn').click(function(e){
        e.preventDefault();
        var target_id = $(this).attr('id');
        var target_type = $(this).attr('title');
        //load form
        loadForm(target_id, target_type);

		$( "#dialog-complain" ).dialog('open');
	});

	$( "#dialog-complain" ).dialog({
        resizable: false,
        height:440,
        width:740,
        left:358,
        draggable:false,
        modal: true,
        autoOpen: false
    });

    function loadForm(target_id, target_type){
        $('#dialog-complain').html("");
        var req = $.post(base_url + '/complaints/add', {type:target_type , id:target_id})
           .success(function( string ){
               $('#dialog-complain').html(string);
           })
           .error(function(){
               ////alert('error');
        });
    }



});

function goTo(url){
    window.location = url;
}


$(function(){
    $('form.jqtransform').jqTransform({imgPath:'jqtransformplugin/img/'});

});

    function validateForm(){
        if(($("#ComplaintReporterFirstname").length > 0) && ($('#ComplaintReporterFirstname').val() == '')){
            return false;
        }

        if(($("#ComplaintReporterName").length > 0) && ($('#ComplaintReporterName').val() == '')){
            return false;
        }

        if(($("#ComplaintReporterEmail").length > 0)){
            if($('#ComplaintReporterEmail').val() == ''){
                return false;
            }

            //validate emial - located in js/global/myzeitung.js
            if(!isEmailValid($('#ComplaintReporterEmail').val())){
                return false;
            }
        }

        if($('#ComplaintComments').val() == ''){ return false; }

        return true;
    }

function submitComplaintForm(){
    if(validateForm()){
        $("#ComplaintAddForm").submit();
    }
    else{
        alert('Please fill in all fields correctly');
    }
}



$(document).ready(function() {

    $( "#dialog-repost-chosse-topic" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false
    });

    observeRepost();

});


    function observeRepost(){
        $(".repost").click(function(e){
            e.preventDefault();

                var post_id = $(this).attr('id');
                var req = $.post(base_url + '/topics/getTopics.json', {post_id: post_id})
                   .success(function( string ){
                        if(string.status == 'success'){
                            $( '#dialog-repost-chosse-topic-content').html(string.data);
                        }
                        else{
                            //alert(string.status);
                        }
                   })
                   .error(function(){
                       ////alert('error');
                });

                $( "#dialog-repost-chosse-topic").dialog('open');

                //stop browser of performing default action
                return false;
            });
        }