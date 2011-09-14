<div class="helpelements form">
<?php echo $this->Form->create('Helpelement');?>
	<fieldset>
		<legend><?php __('Admin Add Helpelement'); ?></legend>

	<?php
        echo "<h2>".$helppage['Helppage']['description']."</h2>";
        echo " (".$helppage['Helppage']['controller']."/".$helppage['Helppage']['action'] . ")";
        echo $this->Form->input('page_id', array('type' => 'hidden', 'value' => $page_id));
		echo $this->Form->input('description');
		echo $this->Form->input('accessor');
		echo $this->Form->input('deu');
		echo $this->Form->input('eng');
        echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>