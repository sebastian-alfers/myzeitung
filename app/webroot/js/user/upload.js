$(document).ready(function() {	

	$("#add_profile_img_btn").click(function(){
		//submit sortet images
		var img = $('#profile_img_preview li:first').attr('id');//only one <li> is possible
		
		$('#UsersImages').val(img);
		$('#UsersAccImageForm').submit();
	});	
	
	var maxFiles = 1,
    filesCounter = 0;
	
	//.live() is needed for new elemenets
	$('.remove_li_item').live('click', function() {

		//remove image from list
	    $(this).closest('li').remove();	    
	    //decrease counter
	    filesCounter = filesCounter - 1;
	    //hide form submit
	    $('#submit_profile_img').hide();
	    
	});		

/*
	$(function () {

	    $('#file_upload').fileUploadUI({
	        uploadTable: $('#files'),
	        downloadTable: $('#profile_img_preview'),
	        buildUploadRow: function (files, index) {
	        	if (filesCounter + index + 1 > maxFiles) {
	                return null;
	            }
	        		        	
	            return $('<tr><td class="file_upload_preview"><\/td>' +
			            '<td>' + files[index].name + '<\/td>' +
	                    '<td class="file_upload_progress"><img src="'+base_url +'/img/assets/loader.gif" /><div><\/div><\/td>' +
	                    '<td class="file_upload_cancel">' +
	                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
	                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
	                    '<\/button><\/td><\/tr>');
	        },
	        buildDownloadRow: function (file) {
                alert(file);
                alert(file.path);
	            $('#submit_profile_img').show();
	        	return $('<li id="' + file.name + '" class="ui-state-default"><img src="' + base_url + '/' +file.path + '" width=100 \/><a class="remove_li_item" name="'+file.path+'" style="cursor:pointer;vertical-align:top;">remove</a> </li>');
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
	*/
	
});



