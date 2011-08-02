<script type="text/javascript">
<!--
$(document).ready(function() {

    var current_element = '';

    function loadAssociations(element, content_paper_id){
        $('#contente-show-references').html('');
        $( "#dialog-show-references" ).dialog('open');
        var url = $(element).attr('id');

        var req = $.post(base_url + '/papers/references/' + url, {content_paper_id: ""+content_paper_id+""})
           .success(function( string ){
               current_element = element;
               $('#contente-show-references').html(string);
           })
           .error(function(){
               alert('error');
        });
    }

	$('.show-associations').click(function(){
		loadAssociations(this, '');
	});

	$( "#dialog-show-references" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false
    });

    //link hover the user img - from popup
    $('.link-delete').live('click', function(){

        //extract id for image from id from link
        link_id = $(this).attr('id');
        len = "link-del";
        content_paper_id = link_id.substring(len.length, link_id.length);

        loadAssociations(current_element, content_paper_id);
    });


});
//-->
</script>
<div id="dialog-show-references" title="<?php __('Authors of this paper'); ?>" style="display:none;">
    <div id="content-refs-chooser"></div>
	<div id="contente-show-references"></div>
</div>
