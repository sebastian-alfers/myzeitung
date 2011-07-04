<script type="text/javascript">
<!--
$(document).ready(function() {
	$('.show-associations').click(function(element){
		$('#contente-show-references').html('');
		$( "#dialog-show-references" ).dialog('open');
		var url = $(this).attr('id');
		var req = $.post(base_url + '/papers/references/' + url)
   		.success(function( string ){
	   		$('#contente-show-references').html(string);
   		})		   
   		.error(function(){
	   		alert('error');
		});		
		
		
		
	});

	$( "#dialog-show-references" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false,
    });	
});
//-->
</script>
<div id="dialog-show-references" title="title" style="display:none;">
    <h4><?php __('References');?></h4>
	<div id="contente-show-references"></div>
</div>
