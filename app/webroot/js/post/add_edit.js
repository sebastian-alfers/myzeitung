var pageType = 'post';
$(document).ready(function() {
    $('#fileupload').fileupload().bind('fileuploadstop', function(e, data){

        setTimeout(function(){
            scrollTo('#media-content');
            $('#dialog-upload').dialog( "close" );
        }, 900);

    });


    $('#links-content .link').live('mouseenter', function(){
        $(this).find('.link-delete-icon').css('visibility', 'visible');
    });
    $('#links-content .link').live('mouseleave', function(){
        $(this).find('.link-delete-icon').css('visibility', 'hidden');
    });

    $('.link-delete-icon').live('click', function(){
        $(this).parent().remove();
        if($('#links li').length == 0){
            $('#links-content').toggle('slow');
        }
        //$( "#dialog-url" ).dialog('open');
        //$('#url').val($(this).parent().find('a').attr('href'));
        //$('#orig-url').val($(this).parent().find('a').attr('href'));
    });

    var is_paste = false;
    $( "#dialog:ui-dialog" ).dialog( "destroy" );

    $( "#dialog-topic" ).dialog({
        resizable: false,
        height:240,
        width:500,
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
			autoOpen: false
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
			            var url = $('#video-url').val();                    
                        parseAndAddVideo(url);
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
	            var url = $('#video-url').val();
                parseAndAddVideo(url);
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
			   		//alert('error');
			});					
			//remove image from list
		    $(this).closest('li').remove();
            if($('#sortable li').length == 0){
                $('#media-content').toggle('slow');
            }
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

		var max = 110;
		var length = url.length;
		var text = url;
		if(length > max){
			text = url.substring(0, 110);
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

        if($('#orig-url').val() != ''){
            /*
            var orig_id = $('#orig-url').val();
            alert(orig_id);
            $('#'+orig_id).attr('id', url);
            alert('#'+orig_id);
            console.log($('#'+orig_id));
            alert($('#'+orig_id).attr('id'));
            */
        }
        else{
            //$('#links').append('<li id="' +url+ '"><a href="' +url+ '" title="' +url+ '" target="blank">' +text+ '</a><a class="remove_li_item"> - remove</a></li>');
            $('#links').append('<li id="' +url+ '" class="link"><a href="' +url+ '" title="' +url+ '" target="blank">' +text+ '</a><span class="link-delete-icon" style="visibility: hidden; "></span></li>');

            scrollTo('#files');
            if(!$('#links').is(":visible")){
                $('#links-content').toggle('slow');
            }

        }
        $('#orig-url').val('');
        $('#url').val('');


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
    	
       	url = jQuery.trim(url);

        var hash = $('#hash').val();
        if(url == '') url = 'empty';//for validation purpose
        var req = $.post(base_url + '/ajax/getVideoPreview.json', {url:url, hash:hash})
		   .success(function( response ){

               if(response.status == 'failure'){
                   if(response.data.msg != ''){
                       //alert(response.data.msg);
                   }
               }
               else if(response.status == 'valid_url_no_video'){
                   prcoessUrl(url);
                   $( "#dialog-video-url" ).dialog( "close" );
               }
               else{
                    if(!$('#media-content').is(":visible")){
                        $('#media-content').toggle('slow');
                    }
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
        scrollTo('#sortable');

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
			   //alert('error');
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
			   //alert('error');
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


$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:

    //$('#fileupload')
    //    .bind('fileuploaddone', function (e, data) {
            //alert('jipp');
    //    });



    /*
    // Load existing files:
    $.getJSON($('#fileupload form').prop('action'), function (files) {
        var fu = $('#fileupload').data('fileupload');
        fu._adjustMaxNumberOfFiles(-files.length);
        fu._renderDownload(files)
            .appendTo($('#fileupload .files'))
            .fadeIn(function () {
                // Fix for IE7 and lower:
                $(this).show();
            });
    });
    */

    // Open download dialogs via iframes,
    // to prevent aborting current uploads:
    /*
    $('#fileupload .files a:not([target^=_blank])').live('click', function (e) {
        e.preventDefault();
        $('<iframe style="display:none;"></iframe>')
            .prop('src', this.href)
            .appendTo('body');
    });
    */

});

//prevent form to submit
$(function()
    {
       var input = $('#PostTitle');
        var code =null;
        input.keypress(function(e)
        {
            code= (e.keyCode ? e.keyCode : e.which);
            if (code == 13) e.preventDefault();

        });

});



