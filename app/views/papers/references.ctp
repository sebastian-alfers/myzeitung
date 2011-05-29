<?php if(count($paperReferences) > 0): ?>
<ul>
	<?php foreach($paperReferences as $reference): ?>
		<?php if(!empty($reference['User']['id'])): ?>
		<li><?php echo $reference['User']['name'] ?> (whole user)</li>
		<?php endif; ?>
		
		<?php if(!empty($reference['Topic']['id'])): ?>
		
		<li><?php echo $reference['Topic']['name']; ?> (topic from user <?php echo  $reference['Topic']['User']['name'] ?> )</li>
		<?php endif; ?>		
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php echo $this->Html->link('< ' . __('Back to Paper', true), array('controller' => 'papers', 'action' => 'view', $paper_id)); ?>