<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#show-subscribers').click(function(element){

		$('#content-activity').html('');
		$( "#dialog-activity" ).dialog('open');
		var url = $(this).attr('title');

		var req = $.post(base_url + "/"+ url)
   		.success(function( string ){
	   		$('#content-activity').html(string);
   		})		   
   		.error(function(){
	   		alert('error');
		});		
		
		
		
	});

	$( "#dialog-activity" ).dialog({
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
<div id="dialog-activity" title="Activity" style="display:none;">
    <h4><?php __('Activity');?></h4>
	<div id="content-activity"></div>
</div>
