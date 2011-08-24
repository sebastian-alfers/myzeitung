
<script type="text/javascript">

	var  base_url = '<?php echo FULL_BASE_URL; ?>';
</script>
<?php
echo $this->Html->meta('icon');

/*
 * all CSS/JS will be compressed and combined
 * by the plugin "asset"
 *
 * set options in app_controller
 *
 */
$this->MzJavascript->link('jquery-1.5.1.min');
$this->MzJavascript->link('jquery-ui-1.8.11.min');
$this->MzJavascript->link('jquery.jqtransform');
$this->MzJavascript->link('global/myzeitung');
$this->MzJavascript->link('jquery.pop.js');
$this->MzJavascript->link('jquery.tipsy.js');
$this->MzJavascript->link('json2.js');
//$this->MzJavascript->link('jquery.spinner.js');
if($session->read('Auth.User.id')){
    //build new js if logged in
    $this->MzJavascript->link('user/subscribe');
    $this->MzJavascript->link('jquery.fileupload');
    $this->MzJavascript->link('jquery.fileupload-ui');

}

//the order of the scrips is important!
$this->MzHtml->css('style');
$this->MzHtml->css('jquery.fileupload-ui');
$this->MzHtml->css('jquery-ui-1.8.11');
$this->MzHtml->css('tiny_mce/themes/advanced/skins/default/ui.css');
//$html->css('/js/tiny_mce/themes/advanced/skins/default/ui.css', false, array('inline' => false));

echo $asset->scripts_for_layout();
?>

<script type="text/javascript" language="javascript">
		$(function(){
		    $('form.jqtransform').jqTransform({imgPath:'jqtransformplugin/img/'});

		});
</script>

<?php if($this->params['controller'] == 'posts' && $this->params['action'] == 'add'): ?>
    <?php //the init of tinymce has to be in the header ?>
    <?php echo $this->element('posts/add_edit/tiny_mce_js'); ?>
<?php endif; ?>
