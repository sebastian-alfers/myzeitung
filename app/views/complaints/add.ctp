<div class="complaints form">
<?php echo $this->Form->create('Complaint');?>

    <?php
    if($user_id != null){
        echo __('Your Name: ', true) . $user_name."<br />";
        echo __('Your Email: ', true) . $user_email;

		echo $this->Form->hidden('reporter_id', array('value' =>  $user_id));
    }
    else{
        echo $this->Form->input('reporter_firstname', array('id' => 'ComplaintReporterFirstname', 'value' => $mzform->value($this, 'Complaint', 'reporter_firstname'), 'label' => __('Firstname', true)));
        echo $this->Form->input('reporter_name', array('id' => 'ComplaintReporterName','value' =>   $mzform->value($this, 'Complaint', 'reporter_name'), 'label' => __('Lastname', true)));
        echo $this->Form->input('reporter_email', array('id' => 'ComplaintReporterEmail', 'value' =>  $mzform->value($this, 'Complaint', 'reporter_email'), 'label' => __('Email', true)));
    }
    ?>





	<?php
		echo $this->Form->input('reason_id');
        if($type == 'user'){
            __('What is wrong with this User? Please describe the issue below.');
        }
        if($type == 'post'){
            __('What is wrong with this Post? Please describe the issue below.');
        }
        if($type == 'paper'){
            __('What is wrong with this Paper? Please describe the issue below.');
        }
        if($type == 'comment'){
            __('What is wrong with this Comment? Please describe the issue below.');
        }

        echo $this->Form->hidden('type', array('value' => $type));
        echo $this->Form->hidden('id', array('value' => $id, 'name' => 'data[Complaint][type_id]', 'id' => 'type_id'));


		echo $this->Form->input('comments', array('label' => false));


		echo $this->Form->hidden('complaintstatus_id', array('value' =>  1));
	?>
</form>
</div>