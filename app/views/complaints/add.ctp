<div class="complaints form">
<?php echo $this->Form->create('Complaint');?>

    <?php
    if($user_id != null){
        echo __('Your Name: ', true) . $user_name;
    }
    else{
        echo $this->Form->input('reporter_firstname', array('id' => 'ComplaintReporterFirstname', 'value' => $mzform->value($this, 'Complaint', 'reporter_firstname'), 'label' => __('Firstname', true)));
        echo $this->Form->input('reporter_name', array('id' => 'ComplaintReporterName','value' =>   $mzform->value($this, 'Complaint', 'reporter_name'), 'label' => __('Lastname', true)));
        echo $this->Form->input('reporter_email', array('id' => 'ComplaintReporterEmail', 'value' =>  $mzform->value($this, 'Complaint', 'reporter_email'), 'label' => __('Email', true)));
    }
    ?>





	<?php
		echo $this->Form->input('reason_id');
		echo $this->Form->input('comments');
		echo $this->Form->hidden('reporter_id', array('value' =>  123));

		echo $this->Form->hidden('complaintstatus_id', array('value' =>  1));
	?>
<ul class="big-btn">
                <li class="big-btn" onclick="preSubmitActions();"><a class="btn" style="color:#fff;"><span class="icon icon-tick"></span>Save Post</a></li>
</ul>
</form>
</div>