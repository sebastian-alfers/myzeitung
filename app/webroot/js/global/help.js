$(document).ready(function() {

    if(helpcenter != ''){

    var helper_elements = new Array();
    var helper_values = new Array();

    

    $.each(helpcenter, function(i, item) {
        if($(item.key).length){
            helper_elements.push(item.key);
            helper_values.push(item.value);
        }
    });
    $('#help #content').prepend(default_helptext);

    var current_element = 0;
    var length = helper_elements.length;
    var last_element = length-1;

    for (var i = 0; i < length; i++) {
        $(helper_elements[i]).tipsy({gravity: 'w', fade: true,opacity: 1, html: true, trigger: 'manual', title: 'help-text'});

        $(helper_elements[i]).attr('help-text', helper_values[i] + '<br /><hr /><ul class="help-nav"><li><a class="helpnav prev"><span class="icon icon-arrow-left-white"></span></a></li><li><a class="helpnav next"><span class="icon icon-arrow-right-white"></span></a></li><li class="last"><span class="help-link icon icon-close-help-white"></span></li><li class="last close-text">Close Help Center</li></ul><br /><br />');
    }

    $('.icon-close-help-white').live('hover', function(){
        $('.close-text').toggle();
    });
    
    $('.helpnav').live('click', function(e){
        e.preventDefault();
        if(current_element > length-1){
            current_element = 0;
        }
        scrollTo(helper_elements[current_element]);
        $(helper_elements[last_element]).tipsy("hide");
        $(helper_elements[current_element]).tipsy("show");
        last_element = current_element;

        current_element++;

    });    
    
    $(".help-link").live('click', function (e) {

        $(".start-help").slideToggle("slow");
        e.preventDefault();

        var margin = '70px';
        if($('#main-wrapper').css('margin-top') != '0px'){
            margin = '0px';
        }

        $('#main-wrapper').animate({
            marginTop: margin
        }, 'slow');
        
        //ie fix
        if($('html').hasClass('ie')){
			//ie needs also padding...
	        $('#main-wrapper').css('paddingTop', margin);        
        }


        $("#help").slideToggle("slow");

        for (var i = 0; i < length; i++) {
            $(helper_elements[i]).tipsy("hide");
        }

    });
    
    }else{
    	$('a.start-help').hide();
    	$('a.help-link').hide();    	
    	
    }//end if helpcenter is available
    
});