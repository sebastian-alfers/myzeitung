<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php __('Admin Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->data['User']['username'];
        if(isset($this->data['User']['username'])){
            echo "(".$this->data['User']['username'].")";
         }
        echo "<br />";
        echo $this->data['User']['email'];
        echo "<br />";
        echo $this->Form->input('group_id');
		echo $this->Form->input('enabled');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>