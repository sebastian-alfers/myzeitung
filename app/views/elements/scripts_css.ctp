
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
e($cf->script('tiny_mce/tiny_mce.js'));


e($cf->css('jquery.fileupload-ui'));
e($cf->css('jquery-ui-1.8.11'));


echo $cf->css('style');


//	echo $scripts_for_layout;
		
?>

<link rel="stylesheet" href="/js/tiny_mce/themes/advanced/skins/default/ui.css">



<script type="text/javascript" language="javascript">
		$(function(){
		    $('form.jqtransform').jqTransform({imgPath:'jqtransformplugin/img/'});
			
		});
	</script>


<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "exact",
        elements : "PostContent",
        theme : "advanced",
        width : 728,
        theme_advanced_resizing_max_width: 728,
        theme_advanced_resizing_min_width: 728,
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        entity_encoding : "raw",
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect, bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,forecolor,backcolor",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "mz_skin",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        content_css : "css/example.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
});
</script>
