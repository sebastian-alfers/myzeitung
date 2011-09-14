<div class="helpelements form">
<?php echo $this->Form->create('Helpelement');?>
	<fieldset>
		<legend><?php __('Admin Edit Helpelement'); ?></legend>
	<?php
        echo "<h2>".$helppage['Helppage']['description']."</h2>";
        echo " (".$helppage['Helppage']['controller']."/".$helppage['Helppage']['action'] . ")";
		echo $this->Form->input('id');
        echo $this->Form->input('page_id', array('type' => 'hidden', 'value' => $page_id));
		echo $this->Form->input('description');
		echo $this->Form->input('accessor');
		echo $this->Form->input('deu');
		echo $this->Form->input('eng');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Save Data', true));?>
<?php if($is_superadmin): ?>
    <?php     echo $this->Html->link(__('Delte from this page', true) , array('controller' => 'helpelements', 'action' => 'delete', $this->data['Helpelement']['id']), null, sprintf(__('Are you sure you want to delete this element?', true))); ?>

<?php endif; ?>
</div>
