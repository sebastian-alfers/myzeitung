<div class="settings index">
	<h2><?php __('Settings');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('key');?></th>
			<th><?php echo $this->Paginator->sort('value');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($settings as $setting):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $setting['Setting']['key']; ?>&nbsp;</td>
		<td>
        <?php
   		switch($setting['Setting']['value_data_type']){
            case 'yes_no':
                echo $this->element('settings/view/value/yes_no', array('value' => $setting['Setting']['value']));
                break;
            case 'enable_disable':
                echo $this->element('settings/view/value/enable_disable', array('value' => $setting['Setting']['value']));
                break;
            default:
                echo $setting['Setting']['value'];
            }
        ?>
   		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $setting['Setting']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>