<div class="settings form">
<?php echo $this->Form->create('Setting');?>
	<fieldset>
		<legend><?php __('Edit Setting'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->data['Setting']['key'];

        echo $this->element('settings/edit/value/'.$this->data['Setting']['value_data_type']);

        ?>

        <?php if(isset($this->data['Setting']['note']) && !empty($this->data['Setting']['note'])): ?>
            <?php echo nl2br($this->data['Setting']['note']); ?>
        <?php endif; ?>

	</fieldset>

<?php echo $this->Form->end(__('Save', true));?>
</div>