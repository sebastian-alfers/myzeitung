$(document).ready(function() {
    var up = false;
    var upload = '';
    $( "#dialog-upload" ).dialog({
        resizable: false,
        height:450,
        width:500,
        draggable:false,
        modal: true,
        autoOpen: false
    });

	$("#submit_new_image_btn").click(function(){
		//submit sortet images
        var img = $('#new_img_preview li:first').attr('id');//only one <li> is possible
		$('#new_image').val(img);
		$('#NewImageForm').submit();
	});

    //link in upload popup to delete user profile picture
    $(".delete-profile-picture").click(function(){
		document.location = base_url + '/users/deleteProfilePicture';
	});

    //link in upload popup to delete paper profile picture
    $(".delete-paper-picture").click(function(){
        var id = $(this).attr('id');
		document.location = base_url + '/paper/deleteImage/'+id;
	});




	$("#add_image").click(function(){
        //$('#submit_new_image').hide();


        $('#dialog-upload').dialog('open');
        return false;
	});
    initUpload();
	
	var maxFiles = 1,
    filesCounter = 0;
	
	//.live() is needed for new elemenets
	$('.remove_li_item').live('click', function() {
        $('#submit_new_image').hide();

		//remove image from list
	    $(this).closest('li').remove();	    
	    //decrease counter
	    filesCounter = filesCounter - 1;
	    //hide form submit
        //$('#submit_new_image').hide();
	    //$('#submit_profile_img').hide();
	    
	});		


        function initUpload(){
        up = true;

	    $('#file_upload').fileUploadUI({
	        uploadTable: $('#files'),
	        downloadTable: $('#new_img_preview'),
	        buildUploadRow: function (files, index) {
	        	if (filesCounter + index + 1 > maxFiles) {
	                return null;
	            }
	            return $('<tr><td class="file_upload_preview"><\/td>' +
	                    '<td class=""><img src="'+base_url +'/img/assets/loader.gif" /><div><\/div><\/td>' +
	                    '<td class="file_upload_cancel">' +
	                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
	                    '<span class="ui-icon ui-icon-cancel"><\/span>' +
	                    '<\/button><\/td><\/tr>');
	        },
	        buildDownloadRow: function (file) {
	            $('#submit_new_image').show();
                //$('#file_upload').hide();

                if(file.status == 'success'){
                    return $('<li id="' + file.data.name + '" class="ui-state-default"><a class="remove_li_item" name="'+file.data.path+'" style="cursor:pointer;vertical-align:top;color:#000">entfernen</a> <img src="' + base_url + '/' +file.data.path + '" width=100 \/></li>');
                }
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
				
				if (filesCounter + index + 1 > maxFiles) {
		            alert('Only one image allowed! Please remove selected image first');
		            return;
		        }
				filesCounter = filesCounter + 1;
				
				
				
    			//check file types				
				var regexp = /\.(png)|(jpg)|(jpeg)|(gif)$/i;
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
                    index
		            handler.removeNode(handler.uploadRow);
                    alert('File is to big. Max 1 MB allowed');
                    filesCounter--;
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
            }

	
});



