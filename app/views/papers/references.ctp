<?php if(count($references) > 0): ?>
	<?php foreach($references as $reference): ?>
		<div style="float:left;margin:0 5px 5px 0;"><?php
		$link_data = array();
		$link_data['url'] = array('controller' => 'users', 'action' => 'view', $reference['User']['id']);
		$link_data['additional'] = array('class' => 'user-image');
		$name = $reference['User']['username'];
		if(isset($reference['User']['name']) && !empty($reference['User']['name'])) $name.= " (".$reference['User']['name'].")";
		echo $image->render($reference['User'], 50, 50, array("alt" => $reference['User']['username'], 'title' => $name, 'class' => 'tt-title'), $link_data); ?></div>		
		<?php /* if(!empty($reference['User']['id'])): ?>
		 <li><?php echo $reference['User']['name'] ?> (whole user)</li>
		<?php endif; ?>
		
		<?php if(!empty($reference['Topic']['id'])): ?>
		
		<li><?php echo $reference['Topic']['name']; ?> (topic from user <?php echo  $reference['Topic']['User']['name'] ?> )</li>
		<?php endif; */ ?>		
	<?php endforeach; ?>

<?php endif; ?>

<?php //echo $this->Html->link('< ' . __('Back to Paper', true), array('controller' => 'papers', 'action' => 'view', $paper_id)); ?>

<script>
$('.tt-title').tipsy({ fade: false, opacity: 1, gravity: 'sw',});
</script>