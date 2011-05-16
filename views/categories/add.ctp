<div class="categories form">
<?php echo $this->Form->create('Category');?>
	<fieldset>
 		<legend><?php __('Add Category'); ?></legend>
	<?php
		echo $this->Form->input('name');
		if(isset($paper_id)){
			echo $this->Form->hidden('paper_id',array('value' => $paper_id));
		}
		if(isset($category_id)){
			echo $this->Form->hidden('parent_id',array('value' => $category_id));
		}		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
