<div id="dialog-complain" title="title" style="display:none;">

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.complain-btn').click(function(){
        var target_id = $(this).attr('id');
        var target_type = $(this).attr('title');
        //load form
        loadForm(target_id, target_type);

		$( "#dialog-complain" ).dialog('open');
	});

	$( "#dialog-complain" ).dialog({
        resizable: false,
        height:440,
        width:740,
        left:358,
        draggable:false,
        modal: true,
        autoOpen: false,
        buttons: {
            "Submit complaint": function() {
                submitComplaintForm();
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });

    function loadForm(target_id, target_type){
        $('#dialog-complain').html("");
        var req = $.post(base_url + '/complaints/add', {type:target_type , id:target_id})
           .success(function( string ){
               $('#dialog-complain').html(string);
           })
           .error(function(){
               alert('error');
        });
    }

    function submitComplaintForm(){
        if(validateForm()){
            $("#ComplaintAddForm").submit();
        }
        else{
            alert('Please fill in all fields correctly');
        }
    }

    function validateForm(){
        if(($("#ComplaintReporterFirstname").length > 0) && ($('#ComplaintReporterFirstname').val() == '')){
            return false;
        }

        if(($("#ComplaintReporterName").length > 0) && ($('#ComplaintReporterName').val() == '')){
            return false;
        }

        if(($("#ComplaintReporterEmail").length > 0)){
            if($('#ComplaintReporterEmail').val() == ''){
                return false;
            }

            //validate emial - located in js/global/myzeitung.js
            if(!isEmailValid($('#ComplaintReporterEmail').val())){
                return false;
            }
        }

        if($('#ComplaintComments').val() == ''){ return false; }

        return true;
    }

});

</script>