<div id="complain">
    <a class="btn complain-btn" href="#" id="/<?php echo $class ?>/<?php echo $complain_target_id ?>"><span>!</span><?php __('Complain'); ?></a>
</div>

<div id="dialog-complain" title="title" style="display:none;">

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.complain-btn').click(function(){
        //load form
        loadForm();

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

    function loadForm(){
        var req = $.post(base_url + '/complaints/add')
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
            alert('Please fill in all fields');
        }


    }

    function validateForm(){
        if(($("#ComplaintReporterFirstname").length > 0) && ($('#ComplaintReporterFirstname').val() == '')){
            return false;
        }

        if(($("#ComplaintReporterName").length > 0) && ($('#ComplaintReporterFirstname').val() == '')){
            return false;
        }

        if(($("#ComplaintReporterEmail").length > 0)){
            alert('found mail');
            if($('#ComplaintReporterEmail').val() == ''){
                return false;
            }

            //validate emial - locatet in js/global/myzeitung.js
            if(!isEmailValid($('#ComplaintReporterEmail').val())){
                return false;
            }

        }

        if($('#ComplaintComments').val() == ''){ return false; }

        return true;
    }

});

</script>