
<script type="text/javascript">
	
	var base_url = '<?php echo FULL_BASE_URL; ?>'
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
$disable_combine = true;
$this->MzJavascript->link('jquery-1.5.1.min');
$this->MzJavascript->link('jquery.fileupload');
$this->MzJavascript->link('jquery.fileupload-ui');
$this->MzJavascript->link('jquery-ui-1.8.11.min');
$this->MzJavascript->link('jquery.jqtransform');
$this->MzJavascript->link('global/myzeitung');
$this->MzJavascript->link('jquery.pop.js');
$this->MzJavascript->link('jquery.tipsy.js');
$this->MzJavascript->link('tiny_mce/tiny_mce.js');
$this->MzJavascript->link('pirobox_extended_min.js');
$this->MzJavascript->link('json2.js');
$this->MzJavascript->link('jquery.spinner.js');

//tiny mce
$this->MzJavascript->link('tiny_mce/plugins/autolink/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/lists/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/spellchecker/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/pagebreak/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/style/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/layer/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/table/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/save/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/advhr/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/advimage/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/emotions/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/advlink/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/iespell/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/inlinepopups/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/insertdatetime/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/media/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/preview/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/print/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/searchreplace/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/paste/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/directionality/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/fullscreen/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/noneditable/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/visualchars/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/nonbreaking/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/xhtmlxtras/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/plugins/template/editor_plugin.js');
$this->MzJavascript->link('tiny_mce/themes/advanced/editor_template.js');
$this->MzJavascript->link('tiny_mce/langs/en.js');



//the order of the scrips is important!
$this->MzHtml->css('style');
$this->MzHtml->css('jquery.fileupload-ui');
$this->MzHtml->css('jquery-ui-1.8.11');
//$this->MzHtml->css('tiny_mce/themes/advanced/skins/default/ui.css');
$html->css('/js/tiny_mce/themes/advanced/skins/default/ui.css', false, array('inline' => false));

echo $asset->scripts_for_layout();

?>

<script src="http://platform.twitter.com/anywhere.js?id=nhTFk7CxUqd64YDjlE9Tg&v=1" type="text/javascript"></script>






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

        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
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
        oninit : "setPlainText",
        paste_auto_cleanup_on_paste : true,
        paste_convert_headers_to_strong : false,
        paste_strip_class_attributes : "all",
        paste_remove_spans : true,
        paste_remove_styles : true,


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

function setPlainText() {
        var ed = tinyMCE.activeEditor;

        ed.pasteAsPlainText = true;

        //adding handlers crossbrowser
        if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
            ed.onKeyDown.add(function (ed, e) {
                if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
                    ed.pasteAsPlainText = true;
            });
        } else {
            ed.onPaste.addToTop(function (ed, e) {
                ed.pasteAsPlainText = true;
            });
        }
    }
</script>
