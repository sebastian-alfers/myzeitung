<script type="text/javascript">


$(function(){


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
                paste_preprocessing : true,
                //this extends the "valid_elements" which we use currently with default value. this specific added entry forces every link within the editor to have rel - nofollow and target: _blank
                extended_valid_elements : "a[href|target:_blank|rel:nofollow]",


                //CLEANUP / OUTPUT params
                convert_newlines_to_brs : true,
                verify_html : true,

                // Skin options
                skin : "mz_skin",
                //skin_variant : "silver",

                // Example content CSS (should be your site CSS)
                //content_css : "css/example.css",

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