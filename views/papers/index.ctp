<?php 


//$code = file_get_contents('http://localhost:8983/solr/select?q=video');
//eval("\$result = " . $code . ";");
//var_dump(($code));
//die();
?>


<div class="papers index">
	<h2><?php __('Papers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('owner_id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo __('Categories'); ?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php

	$i = 0;	
	foreach ($papers as $paper):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $paper['Paper']['id']; ?>&nbsp;</td>
		<td><?php echo $paper['User']['firstname'] . ' ' .$paper['User']['name']; ?>&nbsp;</td>
		<td><?php echo $paper['Paper']['title']; ?>&nbsp;</td>
		<td>
		
			<?php  if(isset($paper['Category']) && !empty($paper['Category']) && is_array($paper['Category'])): ?>
				<ul>
					<?php foreach($paper['Category'] as $category): ?>
					<li><?php echo $category['name']; ?> ( <?php echo $this->Html->link(__('Add Content', true), array('action' => 'addcontent', ContentPaper::CATEGORY, $category['id'])); ?> )
					<?php if(isset($category['Children']) && !empty($category['Children']) && is_array($category['Children'])): ?>
						<ul>
						<?php foreach($category['Children'] as $child): ?>
							<li><?php echo $child['name']; ?> ( <?php echo $this->Html->link(__('Add Content', true), array('action' => 'addcontent', ContentPaper::CATEGORY, $child['id'])); ?> ) </li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					</li>
					<?php endforeach; ?> 
				</ul>
				
			<?php endif; ?>
		
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Add Content', true), array('action' => 'addcontent', ContentPaper::PAPER, $paper['Paper']['id'])); ?>
			<?php echo $this->Html->link(__('Show Content', true), array('action' => 'references', ContentPaper::PAPER, $paper['Paper']['id'])); ?>
			<br /><br /><?php echo $this->Html->link(__('Add Category', true), array('controller' => 'categories', 'action' => 'add', 'paper', $paper['Paper']['id'])); ?>		
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $paper['Paper']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $paper['Paper']['id'])); ?>
			<br /><br /><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $paper['Paper']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $paper['Paper']['id'])); ?>
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
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Paper', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Routes', true), array('controller' => 'routes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Route', true), array('controller' => 'routes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories', true), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category', true), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subscriptions', true), array('controller' => 'subscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subscription', true), array('controller' => 'subscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>