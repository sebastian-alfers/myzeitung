<div class="papers addcontent">

<?php echo $this->Form->create('Paper');?>
		
	
	<fieldset>
 		<legend><?php __('Add Content to your Paper'); ?></legend>
	<?php
		echo $this->Form->input('content_data', $content_data);
		echo $this->Form->hidden('target_type',array('value' => $target_type));
		echo $this->Form->hidden('target_id',array('value' => $target_id));
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>