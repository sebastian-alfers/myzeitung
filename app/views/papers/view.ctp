<?php echo $this->element('papers/sidebar'); ?>



<?php echo $this->element('posts/navigator'); ?>
<?php /*
<div>
	<ul>
	<?php foreach($contentReferences as $contentPaper):?>
		<li>
		<?php if($contentPaper['Topic']['id']): ?>
			<ul>
			<li>Reference to topic (<?php echo $contentPaper['Topic']['name']; ?>) from user (<?php echo $contentPaper['Topic']['User']['username']; ?>)</li>
			<li>Found Posts in this Topic: <?php echo count($contentPaper['Topic']['Post']); ?></li>
			<li>found post in index for this topic???</li>
			</ul> 
		<?php endif; ?>
		<?php if($contentPaper['User']['id']): ?>
			<ul>
			<li>Reference to whole user(<?php echo $contentPaper['User']['username']; ?>)<br /></li>
			<li>Found Posts from this user: <?php echo count($contentPaper['User']['Post']); ?></li>
			<li>found post in index for this user???</li>
			</ul> 		
			
		<?php endif; ?>		
		</li>
		<hr />
	<?php endforeach ?>
	</ul>
</div>   */?>