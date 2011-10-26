
<script type="text/javascript">

	var  base_url = '<?php echo FULL_BASE_URL; ?>';
    var home=  <?php echo ($this->params['controller'] == 'home')? 'true' : 'false';?>;
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
$this->MzJavascript->link('jquery.1.6.4.min');
$this->MzJavascript->link('jquery-ui-1.8.16.min');
$this->MzJavascript->link('jquery.jqtransform');
$this->MzJavascript->link('global/myzeitung');
$this->MzJavascript->link('jquery.pop.js');
$this->MzJavascript->link('jquery.tipsy.js');
$this->MzJavascript->link('json2.js');
$this->MzJavascript->link('global/help.js');
$this->MzJavascript->link('global/invite.js');
$this->MzJavascript->link('global/mzslides.js');
$this->MzJavascript->link('jquery.spinner.js');

if($session->read('Auth.User.id')){
    //build new js if logged in
    $this->MzJavascript->link('user/subscribe');

    $this->MzJavascript->link('jquery.iframe-transport');
    $this->MzJavascript->link('jquery.fileupload');
    $this->MzJavascript->link('jquery.fileupload-ui');
    $this->MzJavascript->link('jquery.tmpl.min');
}

//the order of the scrips is important!
$this->MzHtml->css('style');
$this->MzHtml->css('jquery-ui-1.8.11');
$this->MzHtml->css('tiny_mce/themes/advanced/skins/default/ui.css');
//$html->css('/js/tiny_mce/themes/advanced/skins/default/ui.css', false, array('inline' => false));

echo $asset->scripts_for_layout();
?>


<!--[if IE 9 ]>
<?php echo $this->Cf->css('/css/fixes/ie/ie8-fix.css'); ?>
<![endif]-->


<?php if($this->params['controller'] == 'posts' && in_array($this->params['action'], array('add', 'edit'))): ?>
    <?php //the init of tinymce has to be in the header ?>
    <?php echo $this->element('posts/add_edit/tiny_mce_js'); ?>
<?php endif; ?>
