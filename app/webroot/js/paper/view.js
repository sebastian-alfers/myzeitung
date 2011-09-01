$(document).ready(function() {


    $('#category-content .category').bind('mouseenter', function(){
        $(this).find('.edit-icon').css('visibility', 'visible');
    });
    $('#category-content .category').bind('mouseleave', function(){
        $(this).find('.edit-icon').css('visibility', 'hidden');
    });

    $('#add_category').bind('click', function(){
        $( "#dialog-category" ).dialog('open');
    });

    $('.edit-icon').bind('click', function(){
        $( "#dialog-category" ).dialog('open');
        $('#category').val($(this).parent().find('a').html());
        $('#CategoryId').val($(this).attr('category-id'));

    });



    $( "#dialog-category" ).dialog({
			resizable: false,
			height:240,
			width:400,
			draggable:false,
			modal: true,
			autoOpen: false
	});


    var edit_icon = $('#category-content .edit-icon');
    var category_content = $('#category-content .category');

    category_content.bind('mouseenter', function(){
        $(this).find('.edit-icon').css('visibility', 'visible');
    });

    category_content.bind('mouseleave', function(){
        $(this).find('.edit-icon').css('visibility', 'hidden');
    });


});

$(document).ready(function() {

    var current_element = '';

    function loadAssociations(element, content_paper_id){

        $('#contente-show-references').html('');
        $( "#dialog-show-references" ).dialog('open');
        var url = $(element).attr('id');

        //we use paper instead of papers for controller name
        //as defined in routes
        var req = $.post(base_url + '/paper/references/' + url, {content_paper_id: ""+content_paper_id+""})
           .success(function( string ){
               current_element = element;
               $('#contente-show-references').html(string);
           })
           .error(function(){
               alert('error');
        });
    }

	$('.show-associations').click(function(){
		loadAssociations(this, '');
	});

	$( "#dialog-show-references" ).dialog({
        resizable: false,
        height:340,
        width:400,
        draggable:false,
        modal: true,
        autoOpen: false,
        beforeClose: function(event, ui) {
            //reload if associations have been deleted
            window.location.reload();
        }
    });

    //link hover the user img - from popup
    $('.link-delete').live('click', function(){

        //extract id for image from id from link
        link_id = $(this).attr('id');
        len = "link-del";
        content_paper_id = link_id.substring(len.length, link_id.length);

        loadAssociations(current_element, content_paper_id);
    });


});


