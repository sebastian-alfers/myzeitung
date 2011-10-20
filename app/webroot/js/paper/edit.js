var pageType = 'paper';
$(document).ready(function() {

    $('.delete-paper-picture').show();
    $('#fileupload').fileupload({maxNumberOfFiles: 1});
    
    $('#btn-submit-new-category').click(function(e){
    	e.preventDefault();
    	validateAndSubmitCategory();
    });

    
});


function validateAndSubmitCategory(){

	var category = $('#category').val();
    var req = $.post(base_url + '/ajax/validateNewCategory.json', {name: category})
       .success(function( string ){
       		console.log(string);
            if(string.status == 'success'){
			    $('#CategoryFormAdd').submit();
            }
            else{
				if(string.data.msg != ''){
					alert(string.data.msg);
				}
            }
       })
       .error(function(){
           //alert('error');
    });

}