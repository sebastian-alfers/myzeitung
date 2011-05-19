	
	<script type="text/javascript">
	
	var base_url = '<?php echo DS.APP_DIR; ?>'
	
	</script>	
	
	<?php
		echo $this->Html->meta('icon');
		
				
		e($html->script('jquery-1.5.1.min'));
		e($html->script('jquery.fileupload'));
		e($html->script('jquery.fileupload-ui'));
		e($html->script('jquery-ui-1.8.11.min'));
		e($html->script('jquery.jqtransform'));
		
		e($html->css('jquery.fileupload-ui'));
		e($html->css('jquery-ui-1.8.11'));		
		
		
		
		//echo $this->Html->css('mz.custom');		
		
		echo $this->Html->css('style');
	

	//	echo $scripts_for_layout;
			
	?>
	<script type="text/javascript" language="javascript">
		$(function(){
			$('form').jqTransform({imgPath:'jqtransformplugin/img/'});
		});
	</script>