<?php if(count($references) > 0): ?>
<ul>
	<?php foreach($references as $reference): ?>
		<?php
		$link_data = array();
		$link_data['url'] = array('controller' => 'users', 'action' => 'view', $reference['User']['id']);
		$link_data['additional'] = array('class' => 'user-image');
		echo $image->render($reference['User'], 185, 185, array("alt" => $reference['User']['username']), $link_data); ?>		
		<?php /* if(!empty($reference['User']['id'])): ?>
		 <li><?php echo $reference['User']['name'] ?> (whole user)</li>
		<?php endif; ?>
		
		<?php if(!empty($reference['Topic']['id'])): ?>
		
		<li><?php echo $reference['Topic']['name']; ?> (topic from user <?php echo  $reference['Topic']['User']['name'] ?> )</li>
		<?php endif; */ ?>		
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php //echo $this->Html->link('< ' . __('Back to Paper', true), array('controller' => 'papers', 'action' => 'view', $paper_id)); ?>