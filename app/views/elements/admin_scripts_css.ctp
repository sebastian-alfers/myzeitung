<script type="text/javascript">
	
	var base_url = '<?php echo FULL_BASE_URL; ?>'
</script>
<?php
echo $this->Html->meta('icon');

$this->MzJavascript->link('jquery-1.5.1.min');
$this->MzJavascript->link('admin/hoverIntent');
$this->MzJavascript->link('admin/superfish');


$this->MzHtml->css('style');
$this->MzHtml->css('admin');
$this->MzHtml->css('admin/superfish');
echo $asset->scripts_for_layout();


?>
<script type="text/javascript">

// initialise plugins
jQuery(function(){
    jQuery('ul.sf-menu').superfish();
});

</script>