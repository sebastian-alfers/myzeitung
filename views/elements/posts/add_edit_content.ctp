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
		
		$( "#dialog-confirm" ).dialog({
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
		
	});


	$(document).ready(function() {	
		
		$('#add_topick_link').bind('click', function(){addTopic(33, 'test');});
		
		function addTopic(val, text){
			$('#dialog-confirm').dialog('open');	
			return false;
		}
	
	});

	function saveTopic(val){

		var req = $.get('<?php echo DS.APP_DIR.'/topics/ajax_add/'?>'+val)
		   .success(function( new_topic_id ){
			   $('#PostTopicId').append($('<option></option>').val(new_topic_id).html(val));
			   $('#dialog-confirm').dialog('close');
			   $('#PostTopicId').val(new_topic_id).text();
		   })
		   .error(function(){
			   alert('error');
		});		

	}
		

		
	
</script>


<?php echo $this->element('topics/modal_add'); ?>



<?php //debug($this->data); ?>
<div class="posts form"><?php echo $this->Form->create('Post', array("enctype" => "multipart/form-data"));?>

<p>
 <?php echo $this->Form->input('id'); ?>

 
	
<?php echo $this->Form->input('topic_id');

echo $this->Form->input('title');
//echo $this->Form->input('content');
echo $cksource->ckeditor('content', array('escape' => false));
//echo $form->input('image',array("type" => "file", 'label' => ''));
echo $this->Form->hidden('user_id',array('value' => $user_id));
echo $this->Form->hidden('hash',array('value' => $hash));

?>
</p>

<div id="files" style="float:left"></div>

<?php echo $this->Form->end(__('Submit', true));?></div>

<?php if(isset($images)): ?>
	<?php foreach($images as $img): ?>
		<?php  echo $this->Html->image($image->resize($img, 150, 150, true, 'tmp')); ?> 
	<?php endforeach; ?>
<?php endif;?>


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
		                    '<td class="file_upload_progress"><?php  echo $this->Html->image('loader.gif'); ?><div><\/div><\/td>' +
		                    '<td class="file_upload_cancel">' +
		                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
		                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
		                    '<\/button><\/td><\/tr>');
		        },
		        buildDownloadRow: function (file) {
		            return $('<div float="left"><img src="/myzeitung/' + file.path + '" width=100 \/><\/div>');
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

	//var url_match = /https?:\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?/;

			//alert(url_match.test("http://go4expert.com/"));
	
</script>


