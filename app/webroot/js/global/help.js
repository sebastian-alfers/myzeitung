$(document).ready(function() {

    $(".help-link").live('click', function (e) {

        $(".start-help").slideToggle("slow");
        e.preventDefault();

        var margin = '50px';
        if($('#main-wrapper').css('margin-top') != '0px'){
            margin = '0px';
        }

        $('#main-wrapper').animate({
            marginTop: margin
        }, 'slow');

        $("#help").slideToggle("slow");

        for (var i = 0; i < length; i++) {
            $(helper_elements[i]).tipsy("hide");
        }

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

    $('.icon-close-help-white').live('hover', function(){
        $('.close-text').toggle();
    });

    var helper_elements = new Array();
    var helper_values = new Array();
    $.each(helpcenter, function(i, item) {
        helper_elements.push(item.key);
        helper_values.push(item.value);
    });

    var current_element = 0;
    var length = helper_elements.length;
    var last_element = length-1;

    for (var i = 0; i < length; i++) {
        $(helper_elements[i]).tipsy({gravity: 'w', fade: true,opacity: 1, html: true, trigger: 'manual', title: 'help-text'});

        $(helper_elements[i]).attr('help-text', helper_values[i] + '<br /><hr /><ul class="help-nav"><li><a class="helpnav prev"><span class="arrow-left-white"></span></a></li><li><a class="helpnav next"><span class="arrow-right-white"></span></a></li><li class="last"><a class="help-link icon icon-close-help-white">close</a></li><li class="last close-text">Close Help Center</li></ul><br /><br />');
    }
});