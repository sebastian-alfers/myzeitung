<div class="paper form">
<?php echo $this->Form->create('Paper');?>
	<fieldset>
		<legend><?php __('Admin Edit User'); ?></legend>
	<?php
		//echo $this->Form->input('id');
		echo $this->data['Paper']['title'];
        if(isset($this->data['User']['username'])){
            echo "(".$this->data['User']['username'].")";
         }
        echo $this->Form->input('id');
        echo $this->Form->hidden('title');
        echo $this->Form->input('premium_route');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>