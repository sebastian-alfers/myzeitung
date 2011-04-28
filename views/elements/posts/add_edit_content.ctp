<script type="text/javascript">




	$(function() {
//		$( "#dialog" ).dialog({
//			autoOpen: true,
//			hide: "explode"
//		});
//
//		$( "#opener" ).click(function() {
//			$( "#dialog" ).dialog( "open" );
//			return false;
//		});
	//});
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-topic" ).dialog({
			resizable: false,
			height:240,
			width:400,
			draggable:false,
			modal: true,
			autoOpen: false,
			buttons: {
				"<?php __('Add and select'); ?>": function() {
					saveTopic($('#new_topic').val());
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		
	});

		$( "#dialog-url" ).dialog({
			resizable: false,
			height:240,
			width:400,
			draggable:false,
			modal: true,
			autoOpen: false,
			buttons: {
				"<?php __('Add new URL'); ?>": function() {
					prcessUrl($('#url').val());
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		
	});
		
	});


	$(document).ready(function() {

		$('.remove_li_item').live('click', function() {
		    $(this).closest('li').remove();
		});		
		
		//now show sidebar
		$('#user_sidebar_content').show();

		//$('#').bind('click', alert('submit form'));
		$("#submit_post_btn").click(function(){

			//submit sortet images
			var imgs = $('#sortable').sortable('toArray');
			
			$('#PostImages').val(imgs);

			$('#PostAddForm').submit();
		});
		
		
		$('#add_topick_link').bind('click', function(){topicDialog();});
		$('#add_url_link').bind('click', function(){urlDialog();});
//
//		$('#url').keyup(function() {
//			prcessUrl($('#url').val());
//			});		

		function removeUrl(){
			alert('remove');
		}
		
		function topicDialog(){
			$('#dialog-topic').dialog('open');	
			return false;
		}

		function urlDialog(){
			$('#dialog-url').dialog('open');	
			return false;
		}		
		
	});

	//
	function prcessUrl(url){
		var max = 30;
		var length = url.length;
		var text = url;
		if(length > max){
			text = url.substring(0, 28);
			text += '...';
		}
		$('#links').after('<li><a href="' +url+ '" title="' +url+ '" target="blank">' +text+ '</a> - <a class="remove_li_item"><?php __('remove'); ?></a></li>');
		
/*
		var urlregex = new RegExp(
            "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
      	if(urlregex.test(url)){
      		loadContentForUrl(url);
      	}
      	else if(urlregex.test('http://'+url)){
      		$('#url').val('http://'+url);
			loadContentForUrl('http://'+url);
      	}
      	else{
      		alert('geht nix');
      	}
      	*/
	}
	/**
	 * gets a valid url
	 */
	function loadContentForUrl(url){
		alert('get content for ' + url);
		var req = $.post('<?php echo DS.APP_DIR.'/posts/url_content_extract/'?>', {url:url})
		   .success(function( string ){
			   alert(string);
				$('#url_content').html(string);
		   })		   
		   .error(function(){
			   alert('error');
		});		
	}

	function saveTopic(val){
		var req = $.get('<?php echo DS.APP_DIR.'/topics/ajax_add/'?>'+val)
		   .success(function( new_topic_id ){
			   $('#PostTopicId').append($('<option></option>').val(new_topic_id).html(val));
			   $('#dialog-topic').dialog('close');
			   $('#PostTopicId').val(new_topic_id).text();
		   })
		   .error(function(){
			   alert('error');
		});		
	}
		

		
	
</script>


<?php echo $this->element('topics/modal_add_topic'); ?>
<?php echo $this->element('posts/modal_add_url'); ?>



<?php //debug($this->data); ?>
<div class="posts form"><?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>

<p><?php echo $this->Form->input('id'); ?> <?php echo $this->Form->input('topic_id');

echo $this->Form->input('title');
//echo $this->Form->input('content');
echo $cksource->ckeditor('content', array('escape' => false));
//echo $form->input('image',array("type" => "file", 'label' => ''));
echo $this->Form->hidden('user_id',array('value' => $user_id));
echo $this->Form->hidden('hash',array('value' => $hash));
echo $this->Form->hidden('images',array('value' => ''));


?></p>

<div id="files" style="float: left"></div>

<a class="btn" id="submit_post_btn"><span class="reply-icon"></span><?php __('Add Post'); ?></a>
</form>
</div>

<script>
	$(document).ready(function() {
		$('#PostImage').hide();	
	

		<?php /*
		//counter for image names
		var i = 0;
		$('#file_upload').fileUploadUI({
		    uploadTable: $('#files'),
		    downloadTable: $('#files'),
		    buildUploadRow: function (files, index) {
		        return $('<tr><td class="file_upload_preview"><\/td>' +
		                '<td>' + files[index].name + '<input  name="data[Post][images][img_'+ (i++) + ']" type="file" name="'+ files[index].name +'" /><\/td>' +
		                '<td class="file_upload_progress"><div><\/div><\/td>' +
		                '<td class="file_upload_start">' +
		                '<button class="ui-state-default ui-corner-all" title="Start Upload">' +
		                '<span class="ui-icon ui-icon-circle-arrow-e">Start Upload<\/span>' +
		                '<\/button><\/td>' +
		                '<td class="file_upload_cancel">' +
		                '<button class="ui-state-default ui-corner-all" title="Cancel">' +
		                '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
		                '<\/button><\/td><\/tr>');
		    },
		    buildDownloadRow: function (file) {
		        return $('<tr><td>' + file.name + '<\/td><\/tr>');
		    },
		    beforeSend: function (event, files, index, xhr, handler, callBack) {
		        handler.uploadRow.find('.file_upload_start button').click(function () {
		            callBack();
		            return false;
		        });
		    }
		});
		*/ ?>
		/*global $ */
				
		$(function () {
		    $('#file_upload').fileUploadUI({
		        uploadTable: $('#files'),
		        downloadTable: $('#sortable'),
		        buildUploadRow: function (files, index) {
		        		        
		            return $('<tr><td class="file_upload_preview"><\/td>' +
				            '<td>' + files[index].name + '<\/td>' +
		                    '<td class="file_upload_progress"><?php  echo $this->Html->image('loader.gif'); ?><div><\/div><\/td>' +
		                    '<td class="file_upload_cancel">' +
		                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
		                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
		                    '<\/button><\/td><\/tr>');
		        },
		        buildDownloadRow: function (file) {
		            return $('<li id="' + file.name + '" class="ui-state-default" style="cursor: move;"><img src="/myzeitung/' + file.path + '" width=100 \/><a class="remove_li_item" style="cursor:pointer;vertical-align:top;"><?php __('remove'); ?></a></li>');
		        },
		        beforeSend: function (event, files, index, xhr, handler, callBack) {
					//check for empty files or folders
					if (files[index].size === 0) {
	            			handler.uploadRow.find('.file_upload_progress').html('FILE IS EMPTY!');
	            			setTimeout(function () {
	                			handler.removeNode(handler.uploadRow);
            				}, 10000);
            			return;
        			}	
        			//check file types				
					var regexp = /\.(png)|(jpg)|(gif)$/i;
			        // Using the filename extension for our test,
			        // as legacy browsers don't report the mime type
			        if (!regexp.test(files[index].name)) {
			            handler.uploadRow.find('.file_upload_progress').html('ONLY IMAGES ALLOWED!');
			            setTimeout(function () {
			                handler.removeNode(handler.uploadRow);
			            }, 10000);
			            return;
			        }

			        if (files[index].size > 1000000) {
			            handler.uploadRow.find('.file_upload_progress').html('FILE TOO BIG!');
			            setTimeout(function () {
			                handler.removeNode(handler.uploadRow);
			            }, 10000);
			            return;
			        }
			        
		            if (index === 0) {
		                // The files array is a shared object between the instances of an upload selection.
		                // We extend it with a custom array to coordinate the upload sequence:
		                files.uploadSequence = [];
		                files.uploadSequence.start = function (index) {
		                    var next = this[index];
		                    if (next) {
		                        // Call the callback with any given additional arguments:
		                        next.apply(null, Array.prototype.slice.call(arguments, 1));
		                        this[index] = null;
		                    }
		                };
		            }
		            files.uploadSequence.push(callBack);
		            if (index + 1 === files.length) {
		                files.uploadSequence.start(0);
		            }
		        },
		        onComplete: function (event, files, index, xhr, handler) {
		            files.uploadSequence.start(index + 1);
		        },
		        onAbort: function (event, files, index, xhr, handler) {
		            handler.removeNode(handler.uploadRow);
		            files.uploadSequence[index] = null;
		            files.uploadSequence.start(index + 1);
		        }
		    });
		});
		
	});

	//var url_match = /https?:\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?/;

			//alert(url_match.test("http://go4expert.com/"));
	
</script>


