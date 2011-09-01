$(document).ready(function() {
        var is_paste = false;
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-topic" ).dialog({
			resizable: false,
			height:240,
			width:400,
			draggable:false,
			modal: true,
			autoOpen: false
	});

		$( "#dialog-url" ).dialog({
			resizable: false,
			height:240,
			width:400,
			draggable:false,
			modal: true,
			autoOpen: false,
			buttons: {
				"Add new URL": function() {
                        prcoessUrl($('#url').val());
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		
		//dialod to add video url
		$( "#dialog-video-url" ).dialog({
			resizable: false,
			height:160,
			width:550,
			draggable:false,
			modal: true,
			autoOpen: false,
			buttons: {
				"Add new Video": function() {
                    if(!is_paste){
                        parseAndAddVideo($('#video-url').val());
                    }
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});		
		


        /**
         * bind the paste event (ctrl-v)
         */

        $("#video-url").live('paste',function(e){
            is_paste = true;
            //timeout since val has to be populated
            setTimeout(function() {
                parseAndAddVideo($('#video-url').val());
            }, 100);


        });

			
		//.live() is needed for new elemenets
		$('.remove_li_item').live('click', function() {
			//remove image
			var img_path = $(this).attr('name');
			var post_id = $(this).attr('id');
			var req = $.post(base_url + '/posts/ajxRemoveImage/', {id:post_id, path:img_path})
		   		.success(function( string ){
			   		//alert(string);
					//$('#url_content').html(string);
		   		})		   
		   		.error(function(){
			   		alert('error');
			});					
			//remove image from list
		    $(this).closest('li').remove();
		});		
		
		//now show sidebar
		$('#user_sidebar_content').show();

		//$('#').bind('click', alert('submit form'));
        /*
		$("#add_post_btn").click(function(){			
			//submit sortet images
			var imgs = $('#sortable').sortable('toArray');
			$('#PostImages').val(imgs);
			$('#PostAddForm').submit();
		});
		$("#edit_post_btn").click(function(){			
			//submit sortet images
			var imgs = $('#sortable').sortable('toArray');
			$('#PostImages').val(imgs);
			$('#PostEditForm').submit();
		});
		*/

		
		
		$('#add_topick_link').bind('click', function(){topicDialog();});
		$('#btn-add-link').bind('click', function(){urlDialog();});
		$('#add_url_video_link').bind('click', function(){$('#video-url').val('');videoDialog();});
//
//		$('#url').keyup(function() {
//			prcoessUrl($('#url').val());
//			});		

		function removeUrl(){
			alert('remove');
		}

	});

	//
	function prcoessUrl(url){

		var max = 30;
		var length = url.length;
		var text = url;
		if(length > max){
			text = url.substring(0, 28);
			text += '...';
		}
        //add http:// to url if needed


        if(url.substring(0,8) != 'https://' && url.substring(0,7) != 'http://'){
             url = 'http://'+url;
        }

        //url validation -
            if(!isValidUrl(url)){
                return false;
            }
		$('#links').append('<li id="' +url+ '"><a href="' +url+ '" title="' +url+ '" target="blank">' +text+ '</a><a class="remove_li_item"> - remove</a></li>');
        $('#url').val('');
		//$('#links').after('<li><a>Bild.de/Atompolitik/xyz<span class="icon icon-delete"></span></a></li>');
		
		
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
		$( "#dialog-url" ).dialog( "close" );
	}

    function parseAndAddVideo(url){

        var hash = $('#hash').val();

        if(url == '') url = 'empty';//for validation purpose

        var req = $.post(base_url + '/ajax/getVideoPreview.json', {url:url, hash:hash})
		   .success(function( response ){

               if(response.status == 'failure'){
                   if(response.data.msg != ''){
                        alert(response.data.msg);
                   }
               }
               else if(response.status == 'valid_url_no_video'){
                   prcoessUrl(url);
                   $( "#dialog-video-url" ).dialog( "close" );
               }
               else{
                   addVideoItem(response.data.video_item_html);
               }
               is_paste = false;
				//$('#url_content').html(string);
		   })
		   .error(function(){
			   alert('request error');
		});
    }


    function addVideoItem(video_item_html){
        $('#sortable').append(video_item_html);
        $( "#dialog-video-url" ).dialog( "close" );
        scrollTo('sortable');

    }
	
	/**
	 * gets a valid url
	 */
	function loadContentForUrl(url){
		alert('get content for ' + url);
		var req = $.post(base_url + '/posts/url_content_extract/', {url:url})
		   .success(function( string ){
			   alert(string);
				$('#url_content').html(string);
		   })		   
		   .error(function(){
			   alert('error');
		});		
	}

	function saveTopic(val){
		var req = $.post(base_url + '/topics/ajax_add.json', {topic_name: val})
		   .success(function( response ){
               if(response.status == 'success'){
                   var new_topic_id = response.data;
                   $('#SelectPostTopicId').append($('<option></option>').val(new_topic_id).html(val));
                   $('#dialog-topic').dialog('close');
                   $('#SelectPostTopicId').val(new_topic_id).text();
               }
               else{
                   alert('error while adding topic');
               }


		   })
		   .error(function(){
			   alert('error');
		});		
	}

    function preSubmitActions(form){
        var len = $('#tmp_upload_stuff').children().length;

        if(len > 0){
            alert('wait');
            $('.create-actions').prepend('<li>loading</li>');
            waitForSubmit(form);
        }
        else{
            var links = '';
            $('#links').children().each(function(index, element) {
                links += $(element).attr('id') + ',';
              });


            $('#PostLinks').val(links);

            getTopicId();
            getAllowCommentsSetting();

            $('#PostImages').val($('#sortable').sortable('toArray'));
            //alert($('#PostImages').val());
            //sear;

            var media_data = new Array();
            var j = 0;
            $('#sortable li').each(function(index, element){
                //all needed data are stored within hidden-element in the div with class idem_data
                if($(this).find('.item_data').children().length > 0){
                    //alert($(this).find('.item_data').children().toArray().serializeArray());
                    var item_data = new Array();
                    var i = 0;
                    $(this).find('.item_data').children().each(function(index, attribute){

                        var tmp = new Array();
                        tmp[0] = $(this).attr('name');
                        tmp[1] = $(this).attr('value');

                        item_data[i] = tmp;
                        i++;
                    });

                    media_data[j] = item_data;
                    j++;

                }
            });
            $('#PostMedia').val(JSON.stringify(media_data));

            $(form).submit();
        }



    }

    function waitForSubmit(form){
        var len = $('#tmp_upload_stuff').children().length;


        if(len == 0){
            prewaitForSubmits(form);
        }else{
            setTimeout(function () {
	            waitForSubmit(form);
            }, 100);
        }
    }


    function getTopicId(){
        $('#PostTopicId').val($('#SelectPostTopicId').val());
        return true;
    }

    function getAllowCommentsSetting(){
        $('#PostAllowComments').val($('#SelectPostAllowComments').val());
        return true;
    }

    function topicDialog(){
        $('#dialog-topic').dialog('open');
        return false;
    }

    function urlDialog(){
        $('#dialog-url').dialog('open');
        return false;
    }
    
    function videoDialog(){
        $('#dialog-video-url').dialog('open');
        return false;
    }    


$(document).ready(function() {
    var scroll = false;
		$('#PostImage').hide();	

		$(function () {
		    $('#file_upload').fileUploadUI({
		        uploadTable: $('#sortable'),
		        downloadTable: $('#sortable'),
		        buildUploadRow: function (files, index) {
		        		        
		            return $('<li id="tmp_upload_stuff"><table><tr><td class="file_upload_preview"><img style="position:relative; top:0px; left:10px;height:16px;width:16px;" src="'+base_url +'/img/assets/loader.gif" /><\/td>' +
		                    '<\/tr></table></li>');
		        },
		        buildDownloadRow: function (file) {
		            return $('<li class="ui-state-default teaser-sort"><a class="remove_li_item" name="'+file.path+'" style="cursor:pointer;vertical-align:top;">remove</a><img src="/' + file.path + '" width="100" \/><div class="item_data" style="display: none;"><input type="hidden" name="item_type" value="image" /><input type="hidden" name="img_name" value="'+file.name+'"></div></li>');
		        },
		        beforeSend: function (event, files, index, xhr, handler, callBack) {
                    if(!scroll){
                        scrollTo('sortable');
                        scroll = true;
                    }

					//check for empty files or folders
					if (files[index].size === 0) {
	            			handler.uploadRow.find('.file_upload_progress').html('FILE IS EMPTY!');
	            			setTimeout(function () {
	                			handler.removeNode(handler.uploadRow);
            				}, 10000);
            			return;
        			}	
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






});//end domready




