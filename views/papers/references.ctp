<?php if(count($paperReferences) > 0): ?>
<ul>
	<?php foreach($paperReferences as $reference): ?>
		<?php if(!empty($reference['User']['id'])): ?>
		<li><?php echo $reference['User']['firstname'].' '. $reference['User']['name'] ?> (whole user)</li>
		<?php endif; ?>
		
		<?php if(!empty($reference['Topic']['id'])): ?>
		
		<li><?php echo $reference['Topic']['name']; ?> (topic from user <?php echo $reference['Topic']['User']['firstname'].' '. $reference['Topic']['User']['name'] ?> )</li>
		<?php endif; ?>		
	<?php endforeach; ?>
</ul>
<?php endif; ?>