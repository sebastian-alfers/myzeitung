
<script type="text/javascript">
	
	var base_url = '<?php echo FULL_BASE_URL; ?>'
</script>
<?php
echo $this->Html->meta('icon');


e($cf->script('jquery-1.5.1.min'));
e($cf->script('jquery.fileupload'));
e($cf->script('jquery.fileupload-ui'));
e($cf->script('jquery-ui-1.8.11.min'));
e($cf->script('jquery.jqtransform'));
e($cf->script('global/myzeitung'));
e($cf->script('jquery.pop.js'));
e($cf->script('jquery.tipsy.js'));

e($cf->css('jquery.fileupload-ui'));
e($cf->css('jquery-ui-1.8.11'));


echo $cf->css('style');


//	echo $scripts_for_layout;
		
?>
<script type="text/javascript" language="javascript">
		$(function(){
			$('form').jqTransform({imgPath:'jqtransformplugin/img/'});
		});
	</script>
