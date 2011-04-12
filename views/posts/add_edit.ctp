<?php
e($html->script('jquery-1.5.1.min'));
e($html->script('jquery.fileupload'));
e($html->script('jquery.fileupload-ui'));
e($html->script('jquery-ui-1.8.11.min'));
e($html->css('jquery.fileupload-ui'));
e($html->css('jquery-ui-1.8.11'));

?>




<?php debug($this->data); ?>
<div class="posts form"><?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>
<fieldset><legend><?php __('Add Post'); ?></legend> <?php
echo $this->Form->input('id');
echo $this->Html->link(__('New Topic', true), array('controller' => 'topics',  'action' => 'add'));
echo $this->Form->input('topic_id');

echo $this->Form->input('title');
//echo $this->Form->input('content');
echo $cksource->ckeditor('content', array('escape' => false));
echo $form->input('image',array("type" => "file", 'label' => ''));
echo $this->Form->hidden('user_id',array('value' => $user_id));
echo $this->Form->hidden('hash',array('value' => $hash));

?></fieldset>
<input name="data[Post][hidden]" type="hidden" value="adsf" />
<table id="files"></table>

<?php echo $this->Form->end(__('Submit', true));?></div>
<div class="actions"><?php echo $this->element('navigation'); ?>
<h3><?php __('Options'); ?></h3>
<ul>
	<li><?php echo $this->Html->link(__('Back', true), array('controller' => 'users',  'action' => 'view', $session->read('Auth.User.id'))); ?>
	</li>
</ul>
</div>


<form id="file_upload"
	action="http://localhost/myzeitung/posts/ajxImageProcess"
	method="POST" enctype="multipart/form-data"><input
	type="file" name="file" multiple>
<button>Upload</button>
<div>Upload files</div>
<input type="hidden" value="<?php echo $hash; ?>" />
</form>
aa
<div class="file_upload_progress">
<div id="adf" class="ui-progressbar-value"></div>
</div>
bb
<hr />
cc
<table>
<tr style="display: none;"><td>Bildschirmfoto 2011-02-14 um 16.42.50.png</td><td class="file_upload_progress"><div><progress max="100" value="0"></progress></div></td><td class="file_upload_cancel"><button title="Cancel" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-cancel">Cancel</span></button></td></tr>
</table>
aa

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
		        downloadTable: $('#files'),
		        buildUploadRow: function (files, index) {
		            return $('<tr><td class="file_upload_preview"><\/td>' +
				            '<td>' + files[index].name + '<\/td>' +
		                    '<td class="file_upload_progress"><div><\/div><\/td>' +
		                    '<td class="file_upload_cancel">' +
		                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
		                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
		                    '<\/button><\/td><\/tr>');
		        },
		        buildDownloadRow: function (file) {
		            return $('<tr><td><img src="/myzeitung/img/post/' + file.path + '" \/><\/td><\/tr>');
		        },
		        beforeSend: function (event, files, index, xhr, handler, callBack) {
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

</script>
