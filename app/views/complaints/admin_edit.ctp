<div class="complaints form">
<?php echo $this->Form->create('Complaint');?>
	<fieldset>
		<legend><?php __('Admin Edit Complaint'); ?></legend>
	<?php

        if(!empty($this->data['Paper']['id'])){
            echo $this->element('complaints/models/paper');
        }

        if(!empty($this->data['Post']['id'])){
            echo $this->element('complaints/models/post');
        }

        if(!empty($this->data['User']['id'])){
            echo $this->element('complaints/models/user');
        }

        if(!empty($this->data['Comment']['id'])){
            echo $this->element('complaints/models/comment');
        }

		echo $this->Form->input('id');

		echo $this->Form->input('reason_id');
		echo $this->Form->input('comments');
		//echo $this->Form->input('reporter_id');
		echo $this->Form->input('reporter_email');
		echo $this->Form->input('complaintstatus_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>